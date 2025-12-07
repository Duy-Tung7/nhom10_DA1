<?php
class Booking
{
    protected $conn;

    public function __construct()
    {
        // Đảm bảo tên DB đúng như trong ảnh: da1_nhom10
        $this->conn = new mysqli('localhost', 'root', '', 'da1_nhom10');
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function getAllTours()
    {
        // Code này đúng vì SELECT * sẽ lấy toàn bộ các trường: 
        // id, category_id, name, base_price, duration, max_people...
        $sql = "SELECT * FROM tours ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCustomers()
    {
        $result = $this->conn->query("SELECT * FROM customers ORDER BY id DESC");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllGuides()
    {
        // Lưu ý: Đảm bảo bảng tour_guides của bạn có các trường này
        $sql = "SELECT tours_id, guide_id, full_name, phone, status, assigned_date FROM tour_guides";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllBookings()
    {
        // === SỬA ĐỔI QUAN TRỌNG TẠI ĐÂY ===
        // Trong ảnh bảng tours dùng 'category_id', code cũ dùng 'category' -> Đã sửa thành t.category_id
        // Đã thêm t.base_price và t.duration để lấy thêm thông tin nếu cần hiển thị
        $sql = "SELECT b.*, 
                       t.name AS tour_name, 
                       t.category_id AS tour_type, 
                       t.base_price,
                       t.duration,
                       b.guide_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getBookingById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function formatDate($date)
    {
        if (!$date || trim($date) === "") return null;
        $timestamp = strtotime($date);
        return $timestamp ? date("Y-m-d", $timestamp) : null;
    }

    // validate customer exists
    public function isValidCustomer($customer_id)
    {
        $stmt = $this->conn->prepare("SELECT id FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res && $res->num_rows > 0;
    }

    // ========== create booking ==========
    public function createBooking($data)
    {
        $start_date  = $this->formatDate($data['start_date'] ?? null);
        $end_date    = $this->formatDate($data['end_date'] ?? null);
        // Lưu ý: Kiểm tra xem bảng bookings của bạn có cột finish_date không.
        // Nếu không có hãy xóa dòng dưới đây.
        $finish_date = $this->formatDate($data['finish_date'] ?? null); 
        
        $num_people  = (int)($data['num_people'] ?? 0);
        $note        = $data['note'] ?? null;
        $total_price = (float)($data['total_price'] ?? 0);
        
        // NOTE: form uses guide_id; DB column is tour_guide_id
        $guide_id    = !empty($data['guide_id']) ? (int)$data['guide_id'] : null;
        $guide_name  = $data['guide_name'] ?? null;

        // Required fields check
        if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone']) || empty($data['email'])) {
            return ["success" => false, "message" => "Các trường bắt buộc chưa được điền."];
        }

        // Check guide busy
        if ($guide_id && $this->isGuideBusy($guide_id, $start_date, $end_date)) {
            return ["success" => false, "message" => "Hướng dẫn viên đã bận trong khoảng thời gian này."];
        }

        // Customer handling
        $customer_ids = [];
        if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
            foreach ($data['customer_ids'] as $cid) {
                $cid = (int)$cid;
                if ($cid <= 0) {
                    return ["success" => false, "message" => "ID khách hàng không hợp lệ."];
                }
                if (!$this->isValidCustomer($cid)) {
                    return ["success" => false, "message" => "Khách hàng ID $cid không tồn tại."];
                }
                $customer_ids[] = $cid;
            }
        }

        // Insert booking
        // Giả sử bảng bookings có cấu trúc như code cũ. 
        // Nếu bảng bookings KHÔNG có cột finish_date, hãy xóa 'finish_date' khỏi câu SQL dưới.
        $stmt = $this->conn->prepare(
            "INSERT INTO bookings
            (tour_id, contact_name, phone, email, num_people, total_price, start_date, end_date, finish_date, tour_guide_id, guide_name, note, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        // types: i s s s i d s s s i s s
        $stmt->bind_param(
            "isssidsssiss",
            $data['tour_id'],
            $data['contact_name'],
            $data['phone'],
            $data['email'],
            $num_people,
            $total_price,
            $start_date,
            $end_date,
            $finish_date,
            $guide_id,
            $guide_name,
            $note
        );

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;

            // assign customers
            if (!empty($customer_ids)) {
                $assignResult = $this->assignCustomers($booking_id, $customer_ids);
                if (!$assignResult['success']) {
                    // rollback: delete booking
                    $this->conn->query("DELETE FROM bookings WHERE id = " . (int)$booking_id);
                    return ["success" => false, "message" => $assignResult['message']];
                }
            }

            return ["success" => true, "booking_id" => $booking_id];
        }

        return ["success" => false, "message" => $stmt->error];
    }

    // ========== guide busy ==========
    public function isGuideBusy($guide_id, $start_date, $end_date, $ignore = null)
    {
        $sql = "SELECT id FROM bookings 
                WHERE tour_guide_id = ?
                AND start_date <= ?
                AND end_date >= ?";

        if ($ignore) $sql .= " AND id != ?";

        $stmt = $this->conn->prepare($sql);

        if ($ignore) {
            $stmt->bind_param("issi", $guide_id, $end_date, $start_date, $ignore);
        } else {
            $stmt->bind_param("iss", $guide_id, $end_date, $start_date);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        return $res && $res->num_rows > 0;
    }

    public function updateGuide($booking_id, $guide_id)
    {
        $stmt = $this->conn->prepare("UPDATE bookings SET tour_guide_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $guide_id, $booking_id);
        return $stmt->execute();
    }

    // ========== isCustomerBusy ==========
    public function isCustomerBusy($customer_id, $start_date, $end_date, $exclude_booking_id = null)
    {
        $sql = "SELECT COUNT(*) AS cnt
                FROM booking_customers bc
                JOIN bookings b ON bc.booking_id = b.id
                WHERE bc.customer_id = ?
                  AND b.start_date <= ?
                  AND b.end_date >= ?";

        if ($exclude_booking_id) {
            $sql .= " AND bc.booking_id != ?";
        }

        $stmt = $this->conn->prepare($sql);

        if ($exclude_booking_id) {
            $stmt->bind_param("issi", $customer_id, $end_date, $start_date, $exclude_booking_id);
        } else {
            $stmt->bind_param("iss", $customer_id, $end_date, $start_date);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return (int)$result['cnt'] > 0;
    }

    // ========== isCustomerBookedForTour ==========
    public function isCustomerBookedForTour($customer_id, $tour_id, $exclude_booking_id = null)
    {
        $sql = "SELECT COUNT(*) AS cnt
                FROM booking_customers bc
                JOIN bookings b ON bc.booking_id = b.id
                WHERE bc.customer_id = ? AND b.tour_id = ?";

        if ($exclude_booking_id) {
            $sql .= " AND bc.booking_id != ?";
        }

        $stmt = $this->conn->prepare($sql);

        if ($exclude_booking_id) {
            $stmt->bind_param("iii", $customer_id, $tour_id, $exclude_booking_id);
        } else {
            $stmt->bind_param("ii", $customer_id, $tour_id);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return (int)$result['cnt'] > 0;
    }

    // ========== assignCustomers ==========
    public function assignCustomers($booking_id, $customer_ids)
    {
        $booking = $this->getBookingById($booking_id);
        if (!$booking) {
            return ['success' => false, 'message' => 'Booking không tồn tại.'];
        }

        $start = $booking['start_date'];
        $end   = $booking['end_date'];

        foreach ($customer_ids as $cid) {
            $cid = (int)$cid;
            if ($cid <= 0) {
                return ['success' => false, 'message' => "ID khách không hợp lệ: $cid"];
            }
            if (!$this->isValidCustomer($cid)) {
                return ['success' => false, 'message' => "Khách hàng ID $cid không tồn tại."];
            }
            if ($this->isCustomerBusy($cid, $start, $end, $booking_id)) {
                return ['success' => false, 'message' => "Khách ID $cid đang bận trong thời gian này."];
            }
            if ($this->isCustomerBookedForTour($cid, $booking['tour_id'], $booking_id)) {
                return ['success' => false, 'message' => "Khách ID $cid đã đăng ký tour này."];
            }
        }

        $stmt = $this->conn->prepare("INSERT INTO booking_customers (booking_id, customer_id) VALUES (?, ?)");
        if (!$stmt) {
            return ['success' => false, 'message' => $this->conn->error];
        }

        foreach ($customer_ids as $cid) {
            $cid = (int)$cid;
            $stmt->bind_param("ii", $booking_id, $cid);
            if (!$stmt->execute()) {
                return ['success' => false, 'message' => $stmt->error];
            }
        }

        return ['success' => true, 'message' => 'Gán khách thành công.'];
    }
}
?>