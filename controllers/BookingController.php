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

        $data = $this->getFormData();
        $customer_ids = $_POST['customer_ids'] ?? [];

        // 1. Validate ngày
        foreach (['start_date', 'end_date', 'finish_date'] as $key) {
            if (!empty($data[$key])) {
                $d = DateTime::createFromFormat('Y-m-d', $data[$key]);
                if (!$d || $d->format('Y-m-d') !== $data[$key]) {
                    $message = "Ngày không hợp lệ: $key = " . htmlspecialchars($data[$key]);
                    $this->reloadCreateView($message);
                    return;
                }
            }
        }

        // 2. Kiểm tra khách trùng lịch / đã đăng ký tour
        foreach ($customer_ids as $cid) {
            if ($this->bookingModel->isCustomerBusy($cid, $data['start_date'], $data['end_date'])) {
                $message = "Khách hàng ID $cid trùng lịch!";
                $this->reloadCreateView($message);
                return;
            }

            if ($this->bookingModel->isCustomerBookedForTour($cid, $data['tour_id'])) {
                $message = "Khách hàng ID $cid đã đăng ký tour này!";
                $this->reloadCreateView($message);
                return;
            }
        }

        // 3. Kiểm tra HDV trùng lịch
        if (!empty($data['tour_guide_id'])) {
            if ($this->bookingModel->isGuideBusy($data['tour_guide_id'], $data['start_date'], $data['end_date'])) {
                $message = "Hướng dẫn viên đã bận trong thời gian này!";
                $this->reloadCreateView($message);
                return;
            }
        }

        // 4. Tạo booking
        $result = $this->bookingModel->createBooking($data);

        if ($result['success']) {
            $bookingId = $result['booking_id'];

            // Gán khách hàng
            if (!empty($customer_ids)) {
                $assign = $this->bookingModel->assignCustomers($bookingId, $customer_ids);
                if (!$assign['success']) {
                    $message = $assign['message'];
                    $this->reloadCreateView($message);
                    return;
                }
            }

            header("Location: index.php?action=booking-list");
            exit;
        } else {
            $message = $result['message'];
            $this->reloadCreateView($message);
        }
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
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu.']);
            return;
        }

        $booking = $this->bookingModel->getBookingById($booking_id);
        if (!is_array($booking)) {
            echo json_encode(['success' => false, 'message' => 'Booking không tồn tại.']);
            return;
        }

        // Tránh lỗi array offset on null
        $start_date = $booking['start_date'] ?? null;
        $end_date   = $booking['end_date'] ?? null;

        if ($this->bookingModel->isGuideBusy($guide_id, $start_date, $end_date, $booking_id)) {
            echo json_encode(['success' => false, 'message' => 'HDV đang bận thời gian này!']);
            return;
        }

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
            'contact_name'  => $_POST['contact_name'] ?? '',
            'phone'         => $_POST['phone'] ?? '',
            'email'         => $_POST['email'] ?? '',
            'tour_id'       => $_POST['tour_id'] ?? '',
            'num_people'    => (int)($_POST['num_people'] ?? 1),
            'total_price'   => (float)($_POST['total_price'] ?? 0),
            'start_date'    => $_POST['start_date'] ?? '',
            'end_date'      => $_POST['end_date'] ?? '',
            'finish_date'   => $_POST['finish_date'] ?? '',
            'tour_guide_id' => $_POST['guide_id'] ?? null,
            'guide_name'    => $this->getGuideNameById($_POST['guide_id'] ?? null),
            'note'          => $_POST['note'] ?? ''
        ];
    }

    private function getGuideNameById($guide_id)
    {
        if (!$guide_id) return null;
        $guides = $this->bookingModel->getAllGuides();
        foreach ($guides as $g) {
            if ($g['guide_id'] == $guide_id) return $g['full_name'];
        }
        return null;
    }

    // ===============================
    // Reload create booking view với message
    // ===============================
    private function reloadCreateView($message)
    {
        $tours = $this->bookingModel->getAllTours();
        $guides = $this->bookingModel->getAllGuides();
        $customers = $this->bookingModel->getAllCustomers();
        include __DIR__ . '/../views/book/booking_create.php';
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
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu.']);
            return;
        }

        $booking = $this->bookingModel->getBookingById($booking_id);
        if (!is_array($booking)) {
            echo json_encode(['success' => false, 'message' => 'Booking không tồn tại.']);
            return;
        }

        // Tránh lỗi array offset on null
        $start_date = $booking['start_date'] ?? null;
        $end_date   = $booking['end_date'] ?? null;
        $tour_id    = $booking['tour_id'] ?? null;

        foreach ($customer_ids as $cid) {
            if ($this->bookingModel->isCustomerBusy($cid, $start_date, $end_date, $booking_id)) {
                echo json_encode(['success' => false, 'message' => "Khách hàng ID $cid trùng lịch!"]);
                return;
            }

            if ($this->bookingModel->isCustomerBookedForTour($cid, $tour_id, $booking_id)) {
                echo json_encode(['success' => false, 'message' => "Khách hàng ID $cid đã đăng ký tour này!"]);
                return;
            }
        }

        $ok = $this->bookingModel->assignCustomers($booking_id, $customer_ids);

        echo json_encode([
            'success' => $ok['success'],
            'message' => $ok['success'] ? 'Đã gán khách hàng!' : $ok['message']
        ]);
    }
}
