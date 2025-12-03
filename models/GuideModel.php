<?php
class GuideModel extends BaseModel {

    /**
     * Lấy danh sách các Tour được phân công cho HDV
     * @param int $guide_id ID của tài khoản HDV
     */
    public function getToursByGuide($guide_id) {
        try {
            // Giả sử bảng tours có cột 'guide_id' để lưu ID người hướng dẫn
            // Bạn hãy đổi 'guide_id' thành tên cột thực tế trong DB của bạn (ví dụ: id_hdv, user_id...)
            $sql = "SELECT * FROM tours 
                    WHERE guide_id = :guide_id 
                    ORDER BY start_date DESC"; // Sắp xếp tour mới nhất lên đầu

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':guide_id' => $guide_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi lấy danh sách tour: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Lấy thông tin chi tiết của HDV (cho trang Profile)
     */
    public function getGuideDetail($user_id) {
        try {
            // Lấy thông tin từ bảng users (hoặc bảng guides tùy thiết kế DB)
            $sql = "SELECT * FROM users WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $user_id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi lấy thông tin HDV: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Đếm tổng số tour sắp tới (Để hiện lên Dashboard cho đẹp)
     */
    public function countUpcomingTours($guide_id) {
        try {
            // Đếm các tour có ngày khởi hành lớn hơn ngày hiện tại
            $sql = "SELECT COUNT(*) as total FROM tours 
                    WHERE guide_id = :guide_id 
                    AND start_date >= CURDATE()";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':guide_id' => $guide_id]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>