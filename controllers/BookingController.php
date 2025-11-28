<?php
require_once __DIR__ . '/../models/Booking.php';

class BookingController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
    }

    // Danh sách booking
    public function index()
    {
        $bookings = $this->bookingModel->getAllBookings();
        include __DIR__ . '/../views/book/booking_list.php';
    }

    // Form tạo booking mới
    public function create()
    {
        $tours = $this->bookingModel->getAllTours();
        $message = "";
        include __DIR__ . '/../views/book/booking_create.php';
    }

    // Xử lý lưu booking
public function store()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = $this->getFormData();

        // LẤY LOẠI TOUR TỪ URL: ?tour_type=domestic | international | custom
        $data['tour_type'] = $_GET['tour_type'] ?? null;

        // Validate ngày
        foreach (['start_date', 'end_date', 'finish_date'] as $key) {
            if (!empty($data[$key])) {
                $d = DateTime::createFromFormat('Y-m-d', $data[$key]);
                if (!$d || $d->format('Y-m-d') !== $data[$key]) {
                    $message = "Ngày không hợp lệ: $key = " . htmlspecialchars($data[$key]);
                    $tours = $this->bookingModel->getAllTours();
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
            include __DIR__ . '/../views/book/booking_create.php';
        }
    }
}

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
            'guide_name'   => $_POST['guide_name'] ?? ''
        ];
    }
}
