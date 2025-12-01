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

    // ==========================================================
    //  LẤY DANH SÁCH TOUR
    // ==========================================================
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

    // ==========================================================
    //  LẤY DANH SÁCH HƯỚNG DẪN VIÊN
    // ==========================================================
 public function getAllGuides()
{
    $sql = "SELECT g.id, u.name 
            FROM guides g
            JOIN users u ON u.id = g.user_id";
    
    $result = $this->conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    // ==========================================================
    //  LẤY TOÀN BỘ BOOKING (kèm tên tour + tên HDV)
    // ==========================================================
public function getAllBookings()
{
    $sql = "SELECT b.*, 
                   t.name AS tour_name, 
                   t.type AS tour_type,
                   b.guide_name AS guide_name
            FROM bookings b
            LEFT JOIN tours t ON t.id = b.tour_id
            ORDER BY b.id DESC";

    $result = $this->conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    // ==========================================================
    //  LẤY 1 BOOKING THEO ID
    // ==========================================================
    public function getBookingById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ==========================================================
    //  FORMAT NGÀY
    // ==========================================================
    private function formatDate($date)
    {
        if (!$date || trim($date) === "") return null;
        $timestamp = strtotime($date);
        return $timestamp ? date("Y-m-d", $timestamp) : null;
    }

    // ==========================================================
    //  TẠO BOOKING (đã thêm note)
    // ==========================================================
    public function createBooking($data)
    {
        $start_date  = $this->formatDate($data['start_date']);
        $end_date    = $this->formatDate($data['end_date']);
        $finish_date = $this->formatDate($data['finish_date']);
        $num_people  = (int)($data['num_people'] ?? 1);
        $note        = $data['note'] ?? null;

        if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone']) || empty($data['email'])) {
            return ["success" => false, "message" => "Các trường bắt buộc chưa được điền."];
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO bookings
            (tour_id, contact_name, phone, email, num_people, total_price, start_date, end_date, finish_date, guide_name, note, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        $tour_id    = (int)$data['tour_id'];
        $contact    = $data['contact_name'];
        $phone      = $data['phone'];
        $email      = $data['email'];
        $total      = (float)($data['total_price'] ?? 0);
        $guide_name = $data['guide_name'] ?? null;

        $stmt->bind_param(
            "isssidsssss",
            $tour_id,
            $contact,
            $phone,
            $email,
            $num_people,
            $total,
            $start_date,
            $end_date,
            $finish_date,
            $guide_name,
            $note
        );

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Booking đã được lưu thành công."];
        }

        return ["success" => false, "message" => "Lỗi DB: " . $stmt->error];
    }

    // ==========================================================
    //  KIỂM TRA HƯỚNG DẪN VIÊN BẬN
    // ==========================================================
    public function isGuideBusy($guide_id, $start_date, $end_date, $ignore_booking_id = null)
    {
        $sql = "SELECT id FROM bookings 
                WHERE tour_guide_id = ?
                AND start_date <= ?
                AND end_date >= ?";

        if ($ignore_booking_id) {
            $sql .= " AND id != ?";
        }

        $stmt = $this->conn->prepare($sql);

        if ($ignore_booking_id) {
            $stmt->bind_param("issi", $guide_id, $end_date, $start_date, $ignore_booking_id);
        } else {
            $stmt->bind_param("iss", $guide_id, $end_date, $start_date);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // ==========================================================
    //  GÁN HƯỚNG DẪN VIÊN CHO BOOKING
    // ==========================================================
    public function updateGuide($booking_id, $guide_id)
    {
        $stmt = $this->conn->prepare("UPDATE bookings SET tour_guide_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $guide_id, $booking_id);
        return $stmt->execute();
    }
    


}
