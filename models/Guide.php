<?php
require_once 'BaseModel.php';

class Guide extends BaseModel {
    
    // Chỉ nhận đúng 2 tham số: Từ khóa và Trang
    public function getAllGuides($keyword = '', $page = 1) {
        $limit = 5; // Số dòng trên 1 trang
        
        // 1. Ép kiểu số (Chống lỗi string - int)
        $page = (int)$page; 
        if ($page < 1) $page = 1;

        // 2. Tính toán offset
        $offset = ($page - 1) * $limit;

        // 3. Viết câu SQL
        $sql = "SELECT * FROM guides WHERE 1=1";
        
        // Tìm kiếm
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR email LIKE '%$keyword%')";
        }

        $sql .= " ORDER BY id DESC LIMIT $offset, $limit";
        
        return $this->query($sql); 
    }

    public function countTotalGuides($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM guides WHERE 1=1";
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR email LIKE '%$keyword%')";
        }
        $result = $this->queryOne($sql);
        return $result ? $result['total'] : 0;
    }

    public function deleteGuide($id) {
        $sql = "DELETE FROM guides WHERE id = $id";
        return $this->execute($sql);
    }
    // --- THÊM CÁC HÀM NÀY VÀO DƯỚI CÙNG CLASS Guide ---

    // 1. Thêm mới
    public function insertGuide($data) {
        $sql = "INSERT INTO guides (name, dob, phone, email, type, languages, certificate, experience, rating, health_status, image) 
                VALUES (:name, :dob, :phone, :email, :type, :languages, :certificate, :experience, :rating, :health_status, :image)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // 2. Cập nhật
    public function updateGuide($id, $data) {
        // Biến $data cần chứa cả :id
        $data['id'] = $id; 

        $sql = "UPDATE guides SET 
                name = :name, dob = :dob, phone = :phone, email = :email, 
                type = :type, languages = :languages, certificate = :certificate, 
                experience = :experience, rating = :rating, health_status = :health_status, 
                image = :image 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}
?>