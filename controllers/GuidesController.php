<?php
// File: controllers/GuideController.php
include_once 'models/db_connect.php'; // Giả sử bạn có file kết nối DB chung

class GuideController {
    public function index() {
        global $conn; // Sử dụng biến kết nối $conn từ file config
        
        // 1. Lấy dữ liệu từ bảng guides
        $sql = "SELECT * FROM guides";
        $result = $conn->query($sql);
        
        $guides = []; // Mảng chứa danh sách
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $guides[] = $row;
            }
        }

        // 2. Gọi View để hiển thị (Lưu ý đường dẫn thư mục view của bạn)
        // Đường dẫn dựa trên hình ảnh: views/admin/quanly&dieuhanh...
        include 'views/admin/quanly&dieuhanh/list-tourguide.php';
    }

    public function create() {
        // Logic hiển thị form thêm mới
        // include 'views/admin/quanly&dieuhanh/create-guide.php';
    }
    
    public function profile(){
        
    }
    // Bạn có thể thêm các hàm store(), edit(), update(), delete() tại đây
}
?>