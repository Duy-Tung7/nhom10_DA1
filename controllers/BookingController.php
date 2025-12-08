    <?php


    class BookingController
    {
        protected $bookingModel;

        public function __construct()
        {
            $this->bookingModel = new Booking();
        }

        // ===============================
        // Danh sách booking
        // ===============================
        public function index()
        {
            $bookings = $this->bookingModel->getAllBookings();

            $title = "Danh sách Booking";
            $view = "book/booking_list"; // giống cấu trúc category
            require_once PATH_VIEW . 'main.php';
        }

        // ===============================
        // Form thêm booking
        // ===============================
        public function create()
        {
            $tours = $this->bookingModel->getAllTours();
            $guides = $this->bookingModel->getAllGuides();
            $customers = $this->bookingModel->getAllCustomers();

            $title = "Thêm Booking mới";
            $view = "book/booking_create";
            require_once PATH_VIEW . 'main.php';
        }

        // ===============================
        // Lưu booking
        // ===============================
        public function store()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') 
                return;

            $data = $_POST;
            $data['customer_ids'] = $_POST['customer_ids'] ?? [];

            $result = $this->bookingModel->createBooking($data);

            if ($result['success']) {
                header("Location: " . BASE_URL . "?action=book-booking_list");
                exit;
            }

            // FAILED → load lại view
            $message = $result['message'];
            $tours = $this->bookingModel->getAllTours();
            $guides = $this->bookingModel->getAllGuides();
            $customers = $this->bookingModel->getAllCustomers();

            $title = "Thêm Booking mới";
            $view = "book/booking_create";
            require_once PATH_VIEW . 'main.php';
        }

        

        // ===============================
        // Gán hướng dẫn viên
        // ===============================
        public function assignGuide()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

            $booking_id = $_POST['booking_id'] ?? null;
            $guide_id   = $_POST['guide_id'] ?? null;

            if (!$booking_id || !$guide_id) {
                echo json_encode(['success'=>false, 'message'=>'Thiếu dữ liệu']);
                return;
            }

            $booking = $this->bookingModel->getBookingById($booking_id);
            if (!$booking) {
                echo json_encode(['success'=>false, 'message'=>'Booking không tồn tại']);
                return;
            }

            $start = $booking['start_date'];
            $end   = $booking['end_date'];

            if ($this->bookingModel->isGuideBusy($guide_id, $start, $end, $booking_id)) {
                echo json_encode(['success'=>false, 'message'=>'HDV đang bận thời gian này']);
                return;
            }

            $ok = $this->bookingModel->updateGuide($booking_id, $guide_id);
            echo json_encode(['success'=>$ok, 'message'=>$ok?'Đã gán HDV':'Lỗi khi gán HDV']);
        }

        // ===============================
        // Gán khách hàng
        // ===============================
        public function assignCustomers()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

            $booking_id = $_POST['booking_id'] ?? null;
            $customer_ids = $_POST['customer_ids'] ?? [];

            if (!$booking_id || empty($customer_ids)) {
                echo json_encode(['success'=>false,'message'=>'Thiếu dữ liệu']);
                return;
            }

            $result = $this->bookingModel->assignCustomers($booking_id, $customer_ids);

            echo json_encode($result);
        }
    }


}

