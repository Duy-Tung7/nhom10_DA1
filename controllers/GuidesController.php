<?php
require_once 'models/Guide.php';

class GuidesController {
    private $guideModel;

    public function __construct() {
        $this->guideModel = new Guide();
    }

    public function index() {
        $keyword = $_GET['keyword'] ?? '';
        $page    = $_GET['page'] ?? 1;
        $guides  = $this->guideModel->getAllGuides($keyword, $page);
        
        // Tạo biến ảo để Sidebar không lỗi
        $categories = []; 

        include 'views/admin/header.php';
        include 'views/admin/sidebar.php';
        
        // QUAN TRỌNG: Thêm thẻ này để đẩy nội dung sang phải
        echo '<div class="content-wrapper">'; 
            // Gọi nội dung chính
            $viewPath = dirname(__DIR__) . '/views/admin/guide-list.php';
            if (file_exists($viewPath)) include $viewPath;
        echo '</div>'; // Đóng thẻ content-wrapper

        include 'views/admin/footer.php';
    }

    // Các hàm khác (create, edit, detail) bạn cũng nhớ thêm content-wrapper tương tự
    public function create() {
        $categories = [];
        include 'views/admin/header.php';
        include 'views/admin/sidebar.php';
        echo '<div class="content-wrapper">';
            include dirname(__DIR__) . '/views/admin/guide-form.php';
        echo '</div>';
        include 'views/admin/footer.php';
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $guide = $this->guideModel->getGuideById($id);
            $categories = [];

            include 'views/admin/header.php';
            include 'views/admin/sidebar.php';
            echo '<div class="content-wrapper">';
                include dirname(__DIR__) . '/views/admin/guide-form.php';
            echo '</div>';
            include 'views/admin/footer.php';
        }
    }

    public function detail() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $guide = $this->guideModel->getGuideById($id);
            $categories = [];

            include 'views/admin/header.php';
            include 'views/admin/sidebar.php';
            echo '<div class="content-wrapper">';
                include dirname(__DIR__) . '/views/admin/guide-detail.php';
            echo '</div>';
            include 'views/admin/footer.php';
        }
    }

    // --- Các hàm xử lý logic (store, update, delete) giữ nguyên ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->getFormData();
            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            } else { $data['image'] = ''; }
            $this->guideModel->insertGuide($data);
            header("Location: index.php?action=admin-list-guides&msg=success");
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_GET['id'];
            $data = $this->getFormData();
            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            } else { $data['image'] = $_POST['current_image']; }
            $this->guideModel->updateGuide($id, $data);
            header("Location: index.php?action=admin-list-guides&msg=updated");
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->guideModel->deleteGuide($_GET['id']);
            header("Location: index.php?action=admin-list-guides&msg=deleted");
        }
    }

    // Các hàm hỗ trợ (getFormData, uploadImage) giữ nguyên...
    private function getFormData() {
        return [
            'name' => $_POST['name'],
            'dob' => $_POST['dob'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'type' => $_POST['type'],
            'languages' => $_POST['languages'],
            'certificate' => $_POST['certificate'],
            'experience' => $_POST['experience'],
            'rating' => $_POST['rating'],
            'health_status' => $_POST['health_status']
        ];
    }
    private function uploadImage($file) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $targetDir . $fileName);
        return $fileName;
    }
}
?>