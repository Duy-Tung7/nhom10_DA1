<?php
require_once 'BaseModel.php';

class Guide extends BaseModel {

    // Lấy tất cả HDV (có tìm kiếm và phân trang)
    public function getAllGuides($keyword = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM guides WHERE 1=1";

        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR email LIKE '%$keyword%')";
        }

        $sql .= " ORDER BY id DESC LIMIT $offset, $limit";
        return $this->query($sql);
    }

    // Đếm tổng số bản ghi (để phân trang)
    public function countTotalGuides($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM guides WHERE 1=1";
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%')";
        }
        $result = $this->queryOne($sql);
        return $result['total'];
    }

    // Lấy chi tiết 1 HDV theo ID
    public function getGuideById($id) {
        $sql = "SELECT * FROM guides WHERE id = $id";
        return $this->queryOne($sql);
    }

    // Thêm mới HDV
    public function insert($data) {
        $sql = "INSERT INTO guides (name, dob, image, phone, email, bio, certificates, languages, type, health_status) 
                VALUES (:name, :dob, :image, :phone, :email, :bio, :certificates, :languages, :type, :health_status)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    // Cập nhật HDV
    public function update($id, $data) {
        $sql = "UPDATE guides SET 
                name = :name, dob = :dob, image = :image, phone = :phone, email = :email, 
                bio = :bio, certificates = :certificates, languages = :languages, 
                type = :type, health_status = :health_status
                WHERE id = :id";
        
        $data['id'] = $id; // Thêm ID vào mảng dữ liệu để bind param
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    // Xóa HDV
    public function delete($id) {
        $sql = "DELETE FROM guides WHERE id = $id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}