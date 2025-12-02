<?php
// 1. Phải nhúng file Model vào thì mới dùng được "new GuestModel()"
require_once 'models/GuestModel.php';

class GuestController {

    private $model;

    public function __construct() {
        $this->model = new GuestModel();
    }

    // Hiển thị danh sách khách của 1 tour
    public function list() {
        // Kiểm tra xem trên URL có tour_id chưa
        if (!isset($_GET['tour_id'])) {
            die("Lỗi: Vui lòng cung cấp tour_id trên URL (Ví dụ: &tour_id=1)");
        }

        $tour_id = $_GET['tour_id'];

        // Lấy dữ liệu từ Model
        $guests = $this->model->getGuestsByTour($tour_id);

        // 2. Dùng __DIR__ để đường dẫn tuyệt đối an toàn, không bị lỗi "failed to open stream"
        include __DIR__ . '/../views/guests/list.php';
    }
}
?>