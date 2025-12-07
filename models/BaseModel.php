<?php

class BaseModel
{
    protected $table;
    protected $pdo;

    // Kết nối CSDL
    public function __construct()
    {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);

        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $e) {
            // Xử lý lỗi kết nối
            die("Kết nối cơ sở dữ liệu thất bại: {$e->getMessage()}. Vui lòng thử lại sau.");
        }
    }

    // Hủy kết nối CSDL
    public function __destruct()
    {
        $this->pdo = null;
    }// --- BẮT ĐẦU ĐOẠN CẦN THÊM (Dòng 25) ---

    // Hàm thực thi câu lệnh SQL và lấy về danh sách (dùng cho hàm getAllGuides)
    public function query($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Hàm lấy về 1 dòng dữ liệu (dùng cho hàm countTotalGuides ở dòng 34 bên kia)
    public function queryOne($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- KẾT THÚC ĐOẠN CẦN THÊM ---
    
    public function getConnection()
    {
        return $this->pdo;
    }
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function checkLogin($email, $password)
{
    $email = strtolower(trim($email));
    $hashedPassword = md5($password);

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$email, $hashedPassword]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user; 
}
}
