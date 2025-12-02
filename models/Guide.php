<?php
require_once 'BaseModel.php';

class Guide extends BaseModel {
    protected $table = 'guides';

    // Hàm kết nối
    public function getDb() {
        if (isset($this->conn)) return $this->conn;
        if (isset($this->db)) return $this->db;
        if (isset($this->pdo)) return $this->pdo;
        return new PDO("mysql:host=localhost;dbname=nhom10_da1;charset=utf8", "root", "");
    }

    public function getList($keyword = '') {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        if (!empty($keyword)) {
            $sql .= " AND (phone LIKE :kw OR languages LIKE :kw)";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute(['kw' => "%$keyword%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGuideById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- HÀM SAVE ĐÃ SỬA LỖI ---
    public function save($data) {
        $db = $this->getDb();
        
        if (isset($data['id']) && !empty($data['id'])) {
            // --- TRƯỜNG HỢP 1: CẬP NHẬT (UPDATE) ---
            // SQL này CÓ tham số :id, nên ta giữ nguyên $data['id']
            $sql = "UPDATE {$this->table} SET 
                    user_id = :user_id,
                    phone = :phone,
                    birthday = :birthday,
                    avatar = :avatar,
                    languages = :languages,
                    experience_years = :experience_years,
                    health_status = :health_status,
                    certifications = :certifications
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            return $stmt->execute($data);

        } else {
            // --- TRƯỜNG HỢP 2: THÊM MỚI (INSERT) ---
            // SQL này KHÔNG CÓ tham số :id (vì id tự tăng)
            $sql = "INSERT INTO {$this->table} 
                    (user_id, phone, birthday, avatar, languages, experience_years, health_status, certifications) 
                    VALUES 
                    (:user_id, :phone, :birthday, :avatar, :languages, :experience_years, :health_status, :certifications)";
            
            // QUAN TRỌNG: Xóa 'id' khỏi mảng data trước khi chạy lệnh Insert
            // Nếu không xóa, máy sẽ báo lỗi "Invalid parameter number" vì thừa id
            unset($data['id']); 

            $stmt = $db->prepare($sql);
            return $stmt->execute($data);
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->getDb()->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}