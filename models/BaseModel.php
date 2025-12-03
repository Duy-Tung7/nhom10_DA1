<?php
class BaseModel {
    // Biến lưu kết nối
    protected $conn;

    public function __construct() {
        try {
            // Tên Database chuẩn theo lỗi cũ của bạn là: da1_nhom10
            $this->conn = new PDO("mysql:host=localhost;dbname=da1_nhom10;charset=utf8", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    // --- ĐÂY LÀ HÀM QUAN TRỌNG ĐỂ SỬA LỖI CỦA BẠN ---
    // Giúp Category.php lấy được kết nối mà không bị lỗi "undefined method"
    public function getConnection() {
        return $this->conn;
    }

    // Hàm query cơ bản
    public function query($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
            return [];
        }
    }
}
?>