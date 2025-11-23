<?php

class TourGuideController {
    protected $tourGuide;

    public function __construct()
    {
        $this->tourGuide = new TourGuide();
        // Bỏ qua session để test giao diện
        // Nếu muốn tích hợp session thật sau, uncomment dòng dưới
        // if(session_status() == PHP_SESSION_NONE) session_start();
    }

    // Xem danh sách tour được phân công
    public function index()
    {
        $guide_id = 1; // giả lập ID guide để test giao diện
        $tours = $this->tourGuide->getToursByGuide($guide_id);

        $view = 'admin/guide-list-tours';
        require_once __DIR__ . '/../views/main.php';
    }

    // Xem chi tiết tour
    public function detail()
    {
        $guide_id = 1; // giả lập ID guide
        $tour_id = $_GET['tour_id'] ?? 0;

        $itinerary = $this->tourGuide->getItinerary($tour_id);
        $logs = $this->tourGuide->getLogs($tour_id, $guide_id);

        $view = 'admin/guide-detail-tour';
        require_once __DIR__ . '/../views/main.php';
    }

    // Thêm ghi chú
    public function addLog()
    {
        $guide_id = 1; // giả lập ID guide
        $tour_id = $_POST['tour_id'] ?? 0;
        $content = $_POST['content'] ?? '';

        if($tour_id && $content){
            $this->tourGuide->addLog($tour_id, $guide_id, $content);
        }

        header("Location: index.php?action=guide-tour-detail&tour_id=".$tour_id);
        exit();
    }
}
