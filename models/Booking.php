    <?php
    class Booking
    {
        protected $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', '', 'da1_nhom10');
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

        public function getLastInsertId()
        {
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
        $sql = "SELECT tour_id, guide_id, full_name, phone, status, assigned_date FROM tour_guides";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

  public function getAllBookings()
{
    $sql = "
    SELECT 
        b.*,
        t.name AS tour_name,
        GROUP_CONCAT(c.name SEPARATOR ', ') AS customers
    FROM bookings b
    LEFT JOIN tours t ON b.tour_id = t.id
    LEFT JOIN booking_customers bc ON b.id = bc.booking_id
    LEFT JOIN customers c ON bc.customer_id = c.id
    GROUP BY b.id
    ORDER BY b.created_at DESC
    ";

    return $this->conn->query($sql);
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
public function addCustomerToBooking($booking_id, $customer_id)
{
    $booking_id  = (int)$booking_id;
    $customer_id = (int)$customer_id;

    if ($booking_id <= 0 || $customer_id <= 0) {
        return false;
    }

    $stmt = $this->conn->prepare("
        INSERT INTO booking_customers (booking_id, customer_id)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $booking_id, $customer_id);
    return $stmt->execute();
}


    // ========== create booking ==========
   public function createBooking($data)
{
    $stmt = $this->conn->prepare("
        INSERT INTO bookings (tour_id, guide_name, start_date, end_date, contact_name)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iisss",
        $data['tour_id'],
        $data['guide_id'],
        $data['start_date'],
        $data['end_date'],
        $data['contact_name']
    );

    if ($stmt->execute()) {
        return [
            'success' => true,
            'booking_id' => $this->conn->insert_id
        ];
    }

    return [
        'success' => false,
        'message' => 'Lỗi khi tạo booking'
    ];
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

public function updateGuide($booking_id, $guide_id)
{
    $stmt0 = $this->conn->prepare(
        "SELECT full_name FROM tour_guides WHERE guide_id = ?"
    );
    $stmt0->bind_param("i", $guide_id);
    $stmt0->execute();
    $guide = $stmt0->get_result()->fetch_assoc();

    if (!$guide) return false;

    $guide_name = $guide['full_name'];

    $stmt = $this->conn->prepare(
        "UPDATE bookings 
         SET tour_guide_id = ?, guide_name = ?
         WHERE id = ?"
    );
    $stmt->bind_param("isi", $guide_id, $guide_name, $booking_id);

    return $stmt->execute();
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
    // Returns ['success'=>bool, 'message'=>string]
    public function assignCustomers($booking_id, $customer_ids)
    {
        // get booking dates
        $booking = $this->getBookingById($booking_id);
        if (!$booking) {
            return ['success' => false, 'message' => 'Booking không tồn tại.'];
        }
        $start = $booking['start_date'];
        $end   = $booking['end_date'];

            // Validate and check overlaps first (so it's atomic in logic)
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

            // Now insert
            $stmt = $this->conn->prepare("INSERT INTO booking_customers (booking_id, customer_id) VALUES (?, ?)");
            if (!$stmt) {
                return ['success' => false, 'message' => $this->conn->error];
            }

            foreach ($customer_ids as $cid) {
                $cid = (int)$cid;
                $stmt->bind_param("ii", $booking_id, $cid);
                if (!$stmt->execute()) {
                    // If any insert fails, return error (you could rollback multiple inserts here if needed)
                    return ['success' => false, 'message' => $stmt->error];
                }
            }

        return ['success' => true, 'message' => 'Gán khách thành công.'];
    }
    // ===============================
// Lấy danh sách customer_id theo booking
// ===============================
public function getCustomerIdsByBooking($booking_id)
{
    $stmt = $this->conn->prepare("SELECT customer_id FROM booking_customers WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $ids = [];
    foreach ($result as $row) {
        $ids[] = $row['customer_id'];
    }
    return $ids;
}
// ========== update booking ==========
public function updateBooking($id, $data)
{
    $start_date  = $this->formatDate($data['start_date'] ?? null);
    $end_date    = $this->formatDate($data['end_date'] ?? null);
    $finish_date = $this->formatDate($data['finish_date'] ?? null);
    $num_people  = (int)($data['num_people'] ?? 0);
    $total_price = (float)($data['total_price'] ?? 0);
    $guide_id    = !empty($data['guide_id']) ? (int)$data['guide_id'] : null;
    $note        = $data['note'] ?? null;

    // ✅ FIX: LẤY guide_name TỪ DB (KHÔNG LẤY TỪ FORM)
    $guide_name = null;
    if (!empty($guide_id)) {
        $stmtG = $this->conn->prepare("SELECT full_name FROM tour_guides WHERE guide_id = ?");
        $stmtG->bind_param("i", $guide_id);
        $stmtG->execute();
        $g = $stmtG->get_result()->fetch_assoc();
        $guide_name = $g['full_name'] ?? null;
    }

    // Validate required
    if (empty($data['tour_id']) || empty($data['contact_name']) || empty($data['phone']) || empty($data['email'])) {
        return ["success" => false, "message" => "Các trường bắt buộc chưa được điền."];
    }

    // Check guide busy but ignore current booking
    if ($guide_id && $this->isGuideBusy($guide_id, $start_date, $end_date, $id)) {
        return ["success" => false, "message" => "Hướng dẫn viên đã bận trong khoảng thời gian này."];
    }

    // Validate customer_ids
    $customer_ids = [];
    if (!empty($data['customer_ids']) && is_array($data['customer_ids'])) {
        foreach ($data['customer_ids'] as $cid) {
            $cid = (int)$cid;

            if (!$this->isValidCustomer($cid)) {
                return ["success" => false, "message" => "Khách hàng ID $cid không tồn tại."];
            }

            if ($this->isCustomerBusy($cid, $start_date, $end_date, $id)) {
                return ["success" => false, "message" => "Khách ID $cid đang bận trong thời gian này."];
            }

            if ($this->isCustomerBookedForTour($cid, $data['tour_id'], $id)) {
                return ["success" => false, "message" => "Khách ID $cid đã đăng ký tour này."];
            }

            $customer_ids[] = $cid;
        }
    }

    // ✅ Update booking
    $stmt = $this->conn->prepare("
        UPDATE bookings SET
            tour_id = ?, 
            contact_name = ?, 
            phone = ?, 
            email = ?, 
            num_people = ?, 
            total_price = ?, 
            start_date = ?, 
            end_date = ?, 
            finish_date = ?, 
            tour_guide_id = ?, 
            guide_name = ?, 
            note = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "isssidsssissi",
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
        $note,
        $id
    );

    if (!$stmt->execute()) {
        return ["success" => false, "message" => $stmt->error];
    }

    // Xóa khách cũ
    $this->conn->query("DELETE FROM booking_customers WHERE booking_id = " . (int)$id);

    // Gán lại khách
    if (!empty($customer_ids)) {
        $assign = $this->assignCustomers($id, $customer_ids);
        if (!$assign['success']) {
            return $assign;
        }
    }

    return ["success" => true, "message" => "Cập nhật booking thành công."];
}

// ===============================
// Lấy chi tiết booking đầy đủ
// ===============================
public function getBookingDetail($id)
{
    // Lấy booking + tour + guide
    $stmt = $this->conn->prepare("
        SELECT 
            b.*, 
            t.name AS tour_name,
            t.type AS tour_type,
            tg.full_name AS guide_full_name,
            tg.phone AS guide_phone
        FROM bookings b
        LEFT JOIN tours t ON b.tour_id = t.id
        LEFT JOIN tour_guides tg ON b.tour_guide_id = tg.guide_id
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) return null;

    // Lấy danh sách khách hàng
    $stmt2 = $this->conn->prepare("
        SELECT c.*
        FROM booking_customers bc
        JOIN customers c ON bc.customer_id = c.id
        WHERE bc.booking_id = ?
    ");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $customers = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

    $booking['customers'] = $customers;

    return $booking;
}


}
