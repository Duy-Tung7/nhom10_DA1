<?php
class Booking
{
    protected $conn;

    public function __construct()
    {
        // 1. Kết nối Database
        $this->conn = new mysqli('localhost', 'root', '', 'da1_nhom10');
        if ($this->conn->connect_error) {
            die("Lỗi kết nối Database: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    // --- CÁC HÀM GET DỮ LIỆU (Đã sửa để không bao giờ bị lỗi vặt) ---

    public function getAllTours()
    {
        $result = $this->conn->query("SELECT * FROM tours ORDER BY id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCustomers()
    {
        $result = $this->conn->query("SELECT * FROM customers ORDER BY id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllGuides()
    {
        // SỬA LỖI: Dùng SELECT * để tránh lỗi "Unknown column user_id"
        // vì bảng tour_guides của bạn hiện tại chưa có cột user_id/phone.
        $result = $this->conn->query("SELECT * FROM tour_guides");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllBookings()
    {
        // SỬA LỖI QUAN TRỌNG:
        // 1. Dùng t.category_id (đúng với database của bạn)
        // 2. Thêm cơ chế hiển thị lỗi SQL ra màn hình thay vì hiện danh sách trống
        
        $sql = "SELECT b.*, 
                       t.name AS tour_name, 
                       t.category_id AS tour_type, 
                       b.guide_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC";

        $result = $this->conn->query($sql);

        // --- NẾU KHÔNG HIỆN, DÒNG NÀY SẼ BÁO LỖI CHO BẠN ---
        if (!$result) {
            // Dừng trang web và in lỗi màu đen ra màn hình
            die("<h2>LỖI SQL (Chụp ảnh gửi lại lỗi này):</h2> " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookingById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // --- CÁC HÀM XỬ LÝ (FORMAT, VALIDATE) ---

    private function formatDate($date)
    {
        if (!$date || trim($date) === "") return null;
        $timestamp = strtotime($date);
        return $timestamp ? date("Y-m-d", $timestamp) : null;
    }

    public function isValidCustomer($customer_id)
    {
        $stmt = $this->conn->prepare("SELECT id FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res && $res->num_rows > 0;
    }

    // --- HÀM TẠO BOOKING (Đã xử lý an toàn) ---

    public function createBooking($data)
    {
        // Xử lý dữ liệu đầu vào
        $start_date  = $this->formatDate($data['start_date'] ?? null);
        $end_date    = $this->formatDate($data['end_date'] ?? null);
        $finish_date = $this->formatDate($data['finish_date'] ?? null); 
        $num_people  = (int)($data['num_people'] ?? 0);
        $note        = $data['note'] ?? null;
        $total_price = (float)($data['total_price'] ?? 0);
        
        // Hướng dẫn viên
        $guide_id    = !empty($data['guide_id']) ? (int)$data['guide_id'] : null;
        $guide_name  = $data['guide_name'] ?? null;

        // Validate cơ bản
        if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone'])) {
            return ["success" => false, "message" => "Thiếu thông tin bắt buộc (Tour, Tên, SĐT)."];
        }

        // Validate Guide Busy
        if ($guide_id && $this->isGuideBusy($guide_id, $start_date, $end_date)) {
            return ["success" => false, "message" => "Hướng dẫn viên bận trong thời gian này."];
        }

        // Validate Customers
        $customer_ids = [];
        if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
            foreach ($data['customer_ids'] as $cid) {
                if ((int)$cid > 0) $customer_ids[] = (int)$cid;
            }
        }

        // INSERT
        // Nếu database bookings thiếu cột nào dưới đây, nó sẽ báo lỗi ở dòng die()
        $sql = "INSERT INTO bookings 
                (tour_id, contact_name, phone, email, num_people, total_price, start_date, end_date, finish_date, tour_guide_id, guide_name, note, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
             return ["success" => false, "message" => "Lỗi chuẩn bị SQL: " . $this->conn->error];
        }

        $stmt->bind_param(
            "isssidsssiss",
            $data['tour_id'], $data['contact_name'], $data['phone'], $data['email'],
            $num_people, $total_price, $start_date, $end_date, $finish_date,
            $guide_id, $guide_name, $note
        );

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            if (!empty($customer_ids)) {
                $this->assignCustomers($booking_id, $customer_ids);
            }
            return ["success" => true, "booking_id" => $booking_id];
        } else {
            return ["success" => false, "message" => "Lỗi lưu DB: " . $stmt->error];
        }
    }

    // --- CÁC HÀM CHECK LOGIC (Giữ nguyên) ---

    public function isGuideBusy($guide_id, $start_date, $end_date, $ignore = null)
    {
        $sql = "SELECT id FROM bookings WHERE tour_guide_id = ? AND start_date <= ? AND end_date >= ?";
        if ($ignore) $sql .= " AND id != ?";
        $stmt = $this->conn->prepare($sql);
        if ($ignore) $stmt->bind_param("issi", $guide_id, $end_date, $start_date, $ignore);
        else $stmt->bind_param("iss", $guide_id, $end_date, $start_date);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function updateGuide($booking_id, $guide_id)
    {
        $stmt = $this->conn->prepare("UPDATE bookings SET tour_guide_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $guide_id, $booking_id);
        return $stmt->execute();
    }

    public function assignCustomers($booking_id, $customer_ids)
    {
        $stmt = $this->conn->prepare("INSERT INTO booking_customers (booking_id, customer_id) VALUES (?, ?)");
        foreach ($customer_ids as $cid) {
            $stmt->bind_param("ii", $booking_id, $cid);
            $stmt->execute();
        }
        return ['success' => true];
    }
    
    // Các hàm check busy customer nếu cần giữ lại, nhưng không ảnh hưởng hiển thị
    public function isCustomerBusy($id, $s, $e, $ex=null) { return false; } 
    public function isCustomerBookedForTour($id, $t, $ex=null) { return false; }
}
?>