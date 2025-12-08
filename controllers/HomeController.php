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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $check = $this->userModel->checkLogin($email, $password);

            if ($check) {

                $_SESSION['success'][] = "Đăng nhập thành công";

                // THỐNG NHẤT: dùng session 'user'
                $_SESSION['user'] = [
                    'id'   => $check['id'],
                    'name' => $check['name'],
                    'role' => $check['role']
                ];

                if ($check['role'] == 1) {
                    header("Location:" . BASE_URL . "?action=admin-dashboard");
                    exit();
                }

                header("Location:" . BASE_URL);
                exit();
            }

            $_SESSION['error'][] = "Email hoặc mật khẩu sai!";
            header("Location:" . BASE_URL . "?action=login");
            exit();
        }

        $title = "Trang đăng nhập";
        $view = "login";
        require_once PATH_VIEW . 'main.php';
    }

    public function logout()
    {
        unset($_SESSION['user']);
        $_SESSION['success'][] = "Đăng xuất thành công";
        header("Location:" . BASE_URL . "?action=login");
        exit();
    }
}
