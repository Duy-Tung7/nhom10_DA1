<?php
 class GuideController{
    public function index(){
        
        $title = "Trang dashboard";
        $view = "guide/dashboard";
        require_once PATH_VIEW . 'main.php';
    }
    public function listTours() {
        // 1. Giả định ID của hướng dẫn viên đang đăng nhập là 1 (Sau này bạn thay bằng $_SESSION['user_id'])
        $guide_id = 1; 
        $model = new GuideModel();
        $tours = $model->getToursByGuide($guide_id);

        // 3. Hiển thị ra giao diện
        // Bạn cần tạo file này ở Bước 3
        include 'views/guide/my-tours.php'; 
    }
  public function listGuests() {
    // 1. Link bạn gửi là: ?action=guide-guests&tour_id=...
    // Nên ta dùng $_GET['tour_id'] để hứng lấy cái số đó.
    
    $tour_id = isset($_GET['tour_id']) ? $_GET['tour_id'] : null;

    // Kiểm tra xem đã lấy được chưa
    if ($tour_id) {
        // Gọi Model
        $guestModel = new GuestModel();
        $guests = $guestModel->getGuestsByTour($tour_id);

        // Gọi View (Sửa lại đường dẫn file này cho đúng thư mục của bạn)
        // Dựa theo ảnh trước đó thì là:
        include 'views/guide/Guest-list.php'; 
    } else {
        // Đây là dòng thông báo lỗi bạn đang gặp
        echo "Lỗi: Không tìm thấy ID của Tour. (tour_id trên URL bị rỗng)";
    }

}
// File: app/controllers/GuideController.php

public function checkIn() {
    // 1. Lấy dữ liệu từ URL
    $guest_id = isset($_GET['id']) ? $_GET['id'] : null;
    $tour_id = isset($_GET['tour_id']) ? $_GET['tour_id'] : null;

    if ($guest_id && $tour_id) {
        // 2. Gọi Model để update trạng thái thành 2 (Đã Check-in)
        $model = new GuestModel();
        $model->updateStatus($guest_id, 2);

        // 3. Quay lại trang danh sách (Reload)
        header("Location: index.php?action=guide-guests&tour_id=" . $tour_id);
        exit();
    } else {
        echo "Lỗi thiếu thông tin ID.";
    }
}
}
    
 