<?php
require_once __DIR__ . '/../models/BaseModel.php';

class HomeController
{
    protected $baseModel;
    public function __construct()
    {
        $this->baseModel = new BaseModel();
    }

    public function index()
    {
        include __DIR__ . '/../views/home.php';
    }

 

   public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = $_POST['email'] ?? '';
        $password = md5($_POST['password'] ?? ''); 

        $user = $this->baseModel->checkLogin($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header("Location: index.php?action=home");
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
