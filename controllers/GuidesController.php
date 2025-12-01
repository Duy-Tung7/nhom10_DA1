<?php
require_once 'models/Guide.php';

class GuidesController {
    private $guideModel;

    public function __construct() {
        $this->guideModel = new Guide();
    }

    // Hiển thị danh sách
    public function index() {
        $keyword = $_GET['keyword'] ?? '';
        $page = $_GET['page'] ?? 1;
        
        $guides = $this->guideModel->getAllGuides($keyword, $page);
        
        // Gọi view hiển thị
        include 'views/admin/guide/list.php'; 
    }

    // Hiển thị form thêm mới
    public function create() {
        include 'views/admin/guide/create.php';
    }

    // Xử lý lưu dữ liệu thêm mới
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý upload ảnh
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "assets/uploads/";
                $image = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            }

            $data = [
                'name' => $_POST['name'],
                'dob' => $_POST['dob'],
                'image' => $image,
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'bio' => $_POST['bio'],
                'certificates' => $_POST['certificates'],
                'languages' => $_POST['languages'],
                'type' => $_POST['type'],
                'health_status' => $_POST['health_status']
            ];

            $this->guideModel->insert($data);
            header("Location: index.php?url=guides"); // Chuyển hướng về trang danh sách
        }
    }

    // Xóa HDV
    public function delete() {
        $id = $_GET['id'];
        $this->guideModel->delete($id);
        header("Location: index.php?url=guides");
    }
}