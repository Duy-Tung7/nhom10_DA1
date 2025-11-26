<?php
require_once 'models/Tour.php';
require_once 'models/GuestModel.php';

class GuideController {
    
    private $tourModel;
    private $guestModel;

    public function __construct() {
        $this->tourModel = new Tour();
        $this->guestModel = new GuestModel();
    }

    // 1. Xem lịch làm việc của chính mình
    public function mySchedule() {
        // Giả lập ID của HDV đang đăng nhập (Sau này lấy từ Session)
        $guide_id = 1; 

        $tours = $this->tourModel->getToursByGuide($guide_id);

        $title = "Lịch dẫn tour";
        // View này nằm ở thư mục riêng cho client/guide, không dùng view admin
        $view = "guide/my-schedule"; 
        
        // Gọi Layout dành cho phía Client (Header/Footer đơn giản hơn Admin)
        require_once PATH_VIEW . 'main.php'; 
    }

    // 2. Xem danh sách khách của một tour cụ thể
    public function viewGuests() {
        if (!isset($_GET['tour_id'])) die("Thiếu ID Tour");
        
        $tour_id = $_GET['tour_id'];
        
        // (Tùy chọn) Nên kiểm tra xem Tour này có đúng là của HDV này dẫn không để bảo mật
        
        $guests = $this->guestModel->getGuestsByTour($tour_id);

        $title = "Danh sách khách hàng";
        $view = "guide/guest-list";
        
        require_once PATH_VIEW . 'main.php';
    }
}
?>