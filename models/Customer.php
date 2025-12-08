    <?php
    class Customer {
        protected $conn;

        public function __construct() {
            $this->conn = new mysqli('localhost', 'root', '', 'da1_nhom10');
            if ($this->conn->connect_error) {
                die("Database connection failed: " . $this->conn->connect_error);
            }
        }

        // Lấy danh sách khách hàng kèm thông tin booking
        public function getAll() {
            $sql = "SELECT c.*, b.contact_name, b.phone as booking_phone, b.email, b.start_date, b.end_date, b.total_price 
                    FROM customers c
                    INNER  JOIN bookings b ON c.booking_id = b.id
                    ORDER BY c.id DESC";
            $result = $this->conn->query($sql);
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        }

        // Lấy chi tiết khách hàng theo ID
        public function getById($id) {
            $stmt = $this->conn->prepare("SELECT c.*, b.contact_name, b.phone as booking_phone, b.email, b.start_date, b.end_date, b.total_price 
                                        FROM customers c
                                        LEFT JOIN bookings b ON c.booking_id = b.id
                                        WHERE c.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        // Thêm khách hàng mới
        public function create($booking_id, $name, $gender, $passport, $phone, $request) {
            $stmt = $this->conn->prepare("INSERT INTO customers (booking_id, name, gender, passport, phone, request) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $booking_id, $name, $gender, $passport, $phone, $request);
            return $stmt->execute();
        }

        // Cập nhật khách hàng
        public function update($id, $booking_id, $name, $gender, $passport, $phone, $request) {
            $stmt = $this->conn->prepare("UPDATE customers SET booking_id=?, name=?, gender=?, passport=?, phone=?, request=? WHERE id=?");
            $stmt->bind_param("isssssi", $booking_id, $name, $gender, $passport, $phone, $request, $id);
            return $stmt->execute();
        }

        // Xóa khách hàng
        public function delete($id) {
            $stmt = $this->conn->prepare("DELETE FROM customers WHERE id=?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        //
    }
    ?>
