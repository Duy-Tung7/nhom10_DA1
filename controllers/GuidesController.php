<?php
require_once 'models/Guide.php';

class GuidesController {
    
    public function index() {
        $guideModel = new Guide();
        $keyword = $_GET['keyword'] ?? '';
        $guides = $guideModel->getList($keyword);
        include 'views/admin/guide-list.php';
    }

    public function create() {
        $guide = [];
        include 'views/admin/guide-form.php';
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $guideModel = new Guide();
            $guide = $guideModel->getGuideById($_GET['id']);
            include 'views/admin/guide-form.php';
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id'               => $_POST['id'] ?? null,
                'user_id'          => $_POST['user_id'], // ID tài khoản User
                'phone'            => $_POST['phone'],
                'birthday'         => $_POST['birthday'],
                'languages'        => $_POST['languages'],
                'experience_years' => $_POST['experience_years'], // Số năm kinh nghiệm
                'health_status'    => $_POST['health_status'],
                'certifications'   => $_POST['certifications']
            ];

            // Xử lý Upload Ảnh (Avatar)
            $data['avatar'] = $_POST['current_avatar'] ?? '';
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $target_dir = "assets/uploads/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                
                $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_dir . $fileName)) {
                    $data['avatar'] = $fileName;
                }
            }

            $guideModel = new Guide();
            $guideModel->save($data);
            
            header("Location: index.php?action=admin-list-guides");
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $guideModel = new Guide();
            $result = $guideModel->delete($_GET['id']);
            if (!$result) {
                echo "<script>alert('Không thể xóa vì dữ liệu ràng buộc!'); window.location='index.php?action=admin-list-guides';</script>";
                exit;
            }
        }
        header("Location: index.php?action=admin-list-guides");
    }
}