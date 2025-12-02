<?php
// models/Guide.php
require_once 'BaseModel.php';

class Guide extends BaseModel {
    
    // Lấy danh sách HDV (có tìm kiếm và phân trang)
    public function getAllGuides($keyword = '', $filterStatus = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM guides WHERE 1=1";
        
        // Chức năng tìm kiếm (Tên, SĐT, Email)
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR email LIKE '%$keyword%')";
        }
        
        // Chức năng lọc (Ví dụ lọc theo phân loại Quốc tế/Nội địa)
        if (!empty($filterStatus)) {
            $sql .= " AND type = '$filterStatus'";
        }

        $sql .= " ORDER BY id DESC LIMIT $offset, $limit";
        
        // Thực thi query (giả sử BaseModel có hàm query)
        return $this->query($sql); 
    }

    // Đếm tổng số bản ghi để làm phân trang
    public function countTotalGuides($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM guides WHERE 1=1";
        if (!empty($keyword)) {
            $sql .= " AND (name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR email LIKE '%$keyword%')";
        }
        $result = $this->queryOne($sql);
        return $result['total'];
    }

    // Xóa HDV
    public function deleteGuide($id) {
        $sql = "DELETE FROM guides WHERE id = $id";
        return $this->execute($sql);
    }
    
    // Lấy chi tiết 1 HDV (cho chức năng Sửa/Chi tiết)
    public function getGuideById($id) {
        $sql = "SELECT * FROM guides WHERE id = $id";
        return $this->queryOne($sql);
    }
}
?>