<?php
class TourGuide
{
    protected $pdo;

    public function __construct()
    {
        // Kết nối database
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=da1_nhom10;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    public function getToursByGuide($guide_id)
    {
        $sql = "SELECT tg.id as tour_guide_id, t.id as tour_id, t.name as tour_name, t.start_date, t.end_date
                FROM tour_guides tg
                JOIN tours t ON tg.tour_id = t.id
                WHERE tg.guide_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItinerary($tour_id)
    {
        $sql = "SELECT day_number, activities FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogs($tour_id, $guide_id)
    {
        $sql = "SELECT content, created_at FROM tour_logs WHERE tour_id = ? AND guide_id = ? ORDER BY created_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tour_id, $guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLog($tour_id, $guide_id, $content)
    {
        $sql = "INSERT INTO tour_logs (tour_id, guide_id, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tour_id, $guide_id, $content]);
    }
}
