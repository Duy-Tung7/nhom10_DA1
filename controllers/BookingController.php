<?php
require_once __DIR__ . '/../models/Booking.php';

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
        include __DIR__ . '/../views/book/booking_list.php';
    }

    // ===============================
    // Form tạo booking
    // ===============================
    public function create()
    {
        $tours = $this->bookingModel->getAllTours();
        $guides = $this->bookingModel->getAllGuides();
        $customers = $this->bookingModel->getAllCustomers();
        $message = "";
        include __DIR__ . '/../views/book/booking_create.php';
    }

    // ===============================
    // Lưu booking
    // ===============================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $data = $_POST;

        // Lấy customer_ids từ form nếu có
        $data['customer_ids'] = $_POST['customer_ids'] ?? [];

        $result = $this->bookingModel->createBooking($data);

        if ($result['success']) {
            header("Location: index.php?action=booking-list");
            exit;
        } else {
            // Nếu lỗi, load lại view create
            $message = $result['message'];
            $tours = $this->bookingModel->getAllTours();
            $guides = $this->bookingModel->getAllGuides();
            $customers = $this->bookingModel->getAllCustomers();
            include __DIR__ . '/../views/book/booking_create.php';
        }
        
    }
        public function edit()
    {
        $id = $_GET['id'] ?? 0;

        $booking = $this->bookingModel->getBookingById($id);
        if (!$booking) {
            echo "Booking không tồn tại!";
            return;
        }

        $tours = $this->bookingModel->getAllTours();
        $guides = $this->bookingModel->getAllGuides();
        $customers = $this->bookingModel->getAllCustomers();
        $selected_customers = $this->bookingModel->getCustomerIdsByBooking($id);

        include __DIR__ . '/../views/book/booking_edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id'];
        $data = $_POST;
        $data['customer_ids'] = $_POST['customer_ids'] ?? [];

        $result = $this->bookingModel->updateBooking($id, $data);

        if ($result['success']) {
            header("Location: index.php?action=booking-list");
            exit;
        }

        $message = $result['message'];
        $booking = $this->bookingModel->getBookingById($id);
        $tours = $this->bookingModel->getAllTours();
        $guides = $this->bookingModel->getAllGuides();
        $customers = $this->bookingModel->getAllCustomers();
        $selected_customers = $this->bookingModel->getCustomerIdsByBooking($id);

        include __DIR__ . '/../views/book/booking_edit.php';
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
            echo json_encode(['success'=>false,'message'=>'Thiếu dữ liệu']);
            return;
        }

        $booking = $this->bookingModel->getBookingById($booking_id);
        if (!$booking) {
            echo json_encode(['success'=>false,'message'=>'Booking không tồn tại']);
            return;
        }

        $start = $booking['start_date'];
        $end   = $booking['end_date'];

        if ($this->bookingModel->isGuideBusy($guide_id, $start, $end, $booking_id)) {
            echo json_encode(['success'=>false,'message'=>'HDV đang bận thời gian này']);
            return;
        }

        $ok = $this->bookingModel->updateGuide($booking_id, $guide_id);
        echo json_encode(['success'=>$ok,'message'=>$ok?'Đã gán HDV':'Lỗi khi gán HDV']);
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

        echo json_encode([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }
}
