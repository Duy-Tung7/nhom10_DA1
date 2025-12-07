<?php
require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../models/User.php';

class HomeController
{
    protected $userModel;

    public function __construct()
    {
        
        $this->userModel = new User();  
    }

    public function index()
    {
        include __DIR__ . '/../views/home.php';
    }

    public function login()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $user = new User();
            $check = $user->checkLogin($_POST['email'], $_POST['password']);
            if ($check) {
                $_SESSION['success'][] = "Đăng nhập thành công";
                // Thông tin user đã đăng nhập
                $_SESSION['userLogin'] = [
                    'id' => $check['id'],
                    'name' => $check['name'],
                    'role' => $check['role'],
                ];
                if ($check['role'] == 1) {
                    header("Location:" . BASE_URL . "?action=admin-dashboard");
                    exit();
                }
                header("Location:" . BASE_URL);
                exit();
            } else {
                $_SESSION['error'][] = "Đăng nhập thất bại";
                header("Location:" . BASE_URL . "?action=login");
                exit();
            }
        }
        $title = "Trang đăng nhập";
        $view = "login";
        require_once PATH_VIEW . 'main.php';
    }


    public function logout()
    {
        unset($_SESSION['userLogin']);
        $_SESSION['success'][] = "Đăng xuất thành công";
        header("Location:" . BASE_URL . "?action=login");
        exit();
    }
}