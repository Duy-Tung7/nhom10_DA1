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
    // Form tạo booking mới
    // ===============================
    public function create()
    {
        $tours = $this->bookingModel->getAllTours();
        $guides = $this->bookingModel->getAllGuides(); // Lấy danh sách HDV
        $message = "";
        include __DIR__ . '/../views/book/booking_create.php';
    }

    // ===============================
    // Lưu booking
    // ===============================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getFormData();

            // Validate ngày
            foreach (['start_date', 'end_date', 'finish_date'] as $key) {
                if (!empty($data[$key])) {
                    $d = DateTime::createFromFormat('Y-m-d', $data[$key]);
                    if (!$d || $d->format('Y-m-d') !== $data[$key]) {
                        $message = "Ngày không hợp lệ: $key = " . htmlspecialchars($data[$key]);
                        $tours = $this->bookingModel->getAllTours();
                        $guides = $this->bookingModel->getAllGuides();
                        include __DIR__ . '/../views/book/booking_create.php';
                        return;
                    }
                }
            }

            $result = $this->bookingModel->createBooking($data);

            if ($result['success']) {
                header("Location: index.php?action=booking-list");
                exit;
            } else {
                $message = $result['message'];
                $tours = $this->bookingModel->getAllTours();
                $guides = $this->bookingModel->getAllGuides();
                include __DIR__ . '/../views/book/booking_create.php';
            }
        }
    }

    // ===============================
    // Gán hướng dẫn viên
    // ===============================
    public function assignGuide()
    {
        $booking_id = $_POST['booking_id'] ?? null;
        $guide_id   = $_POST['guide_id'] ?? null;

        if (!$booking_id || !$guide_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu.']);
            return;
        }

        // Lấy thông tin booking
        $booking = $this->bookingModel->getBookingById($booking_id);
        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking không tồn tại.']);
            return;
        }

        // Kiểm tra HDV bận
        if ($this->bookingModel->isGuideBusy($guide_id, $booking['start_date'], $booking['end_date'], $booking_id)) {
            echo json_encode(['success' => false, 'message' => 'HDV đang bận thời gian này!']);
            return;
        }

        // Gán HDV
        $ok = $this->bookingModel->updateGuide($booking_id, $guide_id);

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Đã gán hướng dẫn viên!' : 'Lỗi khi gán HDV.'
        ]);
    }

    // ===============================
    // Lấy dữ liệu từ form
    // ===============================
    private function getFormData()
    {
        return [
            'contact_name' => $_POST['contact_name'] ?? '',
            'phone'        => $_POST['phone'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'tour_id'      => $_POST['tour_id'] ?? '',
            'num_people'   => $_POST['num_people'] ?? 1,
            'total_price'  => $_POST['total_price'] ?? 0,
            'start_date'   => $_POST['start_date'] ?? '',
            'end_date'     => $_POST['end_date'] ?? '',
            'finish_date'  => $_POST['finish_date'] ?? '',
            'tour_guide_id'=> $_POST['tour_guide_id'] ?? null, 
            'note'         => $_POST['note'] ?? ''
        ];
    }
}
