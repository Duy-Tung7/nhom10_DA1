<?php
class Booking
{
    protected $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', '', 'da1');
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
    $sql = "SELECT tours_id, guide_id, full_name,phone, status, assigned_date
            FROM tour_guides";
            
    $result = $this->conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}



    public function getAllBookings()
    {
        $sql = "SELECT b.*, 
                       t.name AS tour_name, 
                       t.type AS tour_type,
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

    // ============================
    //  Tạo booking
    // ============================
public function createBooking($data)
{
    $start_date  = $this->formatDate($data['start_date']);
    $end_date    = $this->formatDate($data['end_date']);
    $finish_date = $this->formatDate($data['finish_date']);
    $num_people  = (int)$data['num_people'];
    $note        = $data['note'] ?? null;
    $total_price = (float)$data['total_price'];
    $guide_name  = $data['guide_name'] ?? null;
    $guide_id    = $data['tour_guide_id'] ?? null;

    // Bắt buộc
    if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone']) || empty($data['email'])) {
        return ["success" => false, "message" => "Các trường bắt buộc chưa được điền."];
    }

    // Kiểm tra HDV bận
    if ($guide_id && $this->isGuideBusy($guide_id, $start_date, $end_date)) {
        return ["success" => false, "message" => "Hướng dẫn viên đã bận trong khoảng thời gian này."];
    }

    // Nếu có danh sách khách, kiểm tra trùng lịch
    if (!empty($data['customer_ids'])) {
        foreach ($data['customer_ids'] as $cid) {
            if ($this->isCustomerBusy($cid, $start_date, $end_date)) {
                return ["success" => false, "message" => "Khách hàng ID $cid đã trùng lịch."];
            }
            if ($this->isCustomerBookedForTour($cid, $data['tour_id'])) {
                return ["success" => false, "message" => "Khách hàng ID $cid đã đăng ký tour này."];
            }
        }
    }

    // Thêm booking
    $stmt = $this->conn->prepare(
        "INSERT INTO bookings
        (tour_id, contact_name, phone, email, num_people, total_price, start_date, end_date, finish_date, tour_guide_id, guide_name, note, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
    );

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

        // Gán khách nếu có
        if (!empty($data['customer_ids'])) {
            $this->assignCustomers($booking_id, $data['customer_ids']);
        }

        return ["success" => true, "booking_id" => $booking_id];
    }

    return ["success" => false, "message" => $stmt->error];
}

    // ============================
    // Check HDV bận
    // ============================
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
        return $stmt->get_result()->num_rows > 0;
    }

    public function updateGuide($booking_id, $guide_id)
    {
        $stmt = $this->conn->prepare("UPDATE bookings SET tour_guide_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $guide_id, $booking_id);
        return $stmt->execute();
    }

    // ============================
    // Khách đã gán
    // ============================
   // Kiểm tra khách hàng trùng lịch
public function isCustomerBusy($customer_id, $start_date, $end_date, $exclude_booking_id = null)
{
    $sql = "SELECT COUNT(*) AS cnt
            FROM booking_customers bc
            JOIN bookings b ON bc.booking_id = b.id
            WHERE bc.customer_id = ?
              AND NOT (b.end_date < ? OR b.start_date > ?)";
    
    if ($exclude_booking_id) {
        $sql .= " AND bc.booking_id != ?";
    }

    $stmt = $this->conn->prepare($sql);

    if ($exclude_booking_id) {
        $stmt->bind_param("isss", $customer_id, $end_date, $start_date, $exclude_booking_id);
    } else {
        $stmt->bind_param("iss", $customer_id, $end_date, $start_date);
    }

    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['cnt'] > 0;
}


// Kiểm tra khách hàng đã đăng ký tour
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

    return $result['cnt'] > 0;
}

// Gán khách hàng vào booking
public function assignCustomers($booking_id, $customer_ids)
{
    $booking = $this->getBookingById($booking_id);

    $start = $booking['start_date'];
    $end   = $booking['end_date'];

    foreach ($customer_ids as $cid) {

        // Kiểm tra trùng lịch
        if ($this->isCustomerBusy($cid, $start, $end, $booking_id)) {
            return [
                'success' => false,
                'message' => "Khách ID $cid đang bận trong thời gian này."
            ];
        }

        // Kiểm tra trùng tour
        if ($this->isCustomerBookedForTour($cid, $booking['tour_id'], $booking_id)) {
            return [
                'success' => false,
                'message' => "Khách ID $cid đã đăng ký tour này."
            ];
        }
    }

    // ===============================
    // INSERT KHÁCH HÀNG VÀO BOOKING
    // ===============================
    $stmt = $this->conn->prepare(
        "INSERT INTO booking_customers (booking_id, customer_id)
         VALUES (?, ?)"
    );

    foreach ($customer_ids as $cid) {
        $stmt->bind_param("ii", $booking_id, $cid);
        $stmt->execute();
    }

    return ['success' => true];
}



}
