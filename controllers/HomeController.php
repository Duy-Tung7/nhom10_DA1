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
            $name     = $_POST['name'] ?? '';
            $email    = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // ---- SỬA Ở ĐÂY ----
            $user = $this->userModel->checkLogin($email, $password);

            if ($user) {
                $_SESSION['user'] = $user;

                // Kiểm tra nếu user là admin
                if (isset($user['role']) && $user['role'] === 'admin') {
                    header("Location: index.php?action=admin");
                } else {
                    header("Location: index.php?action=home");
                }
                exit;
            } else {
                $error = "Email hoặc mật khẩu sai!";
                include __DIR__ . '/../views/login.php';
            }
        } else {
            include __DIR__ . '/../views/login.php';
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
