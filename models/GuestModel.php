<?php
class GuestModel extends BaseModel {

    // Lấy danh sách khách theo tour_id
    public function getGuestsByTour($tour_id) {
        try {
            $sql = "SELECT 
                        g.id,
                        g.name,
                        g.phone,
                        g.email,
                        g.status,
                        g.note
                    FROM guests g
                    WHERE g.tour_id = :tour_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':tour_id', $tour_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
}
