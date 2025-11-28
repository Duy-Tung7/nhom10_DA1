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

    // Lấy tất cả tour
    public function getAllTours()
    {
        $sql = "SELECT * FROM tours ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Lấy tất cả booking kèm loại tour
    public function getAllBookings()
    {
        $sql = "SELECT b.*, t.name AS tour_name, t.type AS tour_type
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    private function formatDate($date)
    {
        if (!$date || trim($date) === "") return null;
        $timestamp = strtotime($date);
        return $timestamp ? date("Y-m-d", $timestamp) : null;
    }

    // Tạo booking mới
    public function createBooking($data)
    {
        $start_date  = $this->formatDate($data['start_date']);
        $end_date    = $this->formatDate($data['end_date']);
        $finish_date = $this->formatDate($data['finish_date']);
        $num_people  = (int)($data['num_people'] ?? 1);

        if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone']) || empty($data['email'])) {
            return ["success" => false, "message" => "Các trường bắt buộc chưa được điền."];
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO bookings
            (tour_id, contact_name, phone, email, num_people, total_price, start_date, end_date, finish_date, guide_name, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        $tour_id    = (int)$data['tour_id'];
        $contact    = $data['contact_name'];
        $phone      = $data['phone'];
        $email      = $data['email'];
        $total      = (float)($data['total_price'] ?? 0);
        $guide_name = $data['guide_name'] ?? null;

        $stmt->bind_param(
            "isssidssss",
            $tour_id,
            $contact,
            $phone,
            $email,
            $num_people,
            $total,
            $start_date,
            $end_date,
            $finish_date,
            $guide_name
        );

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Booking đã được lưu thành công."];
        }

        return ["success" => false, "message" => "Lỗi DB: " . $stmt->error];
    }
}
