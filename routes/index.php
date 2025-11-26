<?php

// Nhúng các file Controller
require_once 'controllers/HomeController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/CategoryController.php';
require_once 'controllers/TourController.php';
require_once 'controllers/GuestController.php';

// 1. Nhúng thêm Controller mới dành cho HDV
require_once 'controllers/GuideController.php'; 

$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController)->index(),

    // Dashboard admin
    'admin-sidebar' => (new DashboardController)->index(),

    // ==================== Category ====================
    'admin-list-categories'   => (new CategoryController)->index(),
    'admin-list-category'     => (new CategoryController)->index(), // Có thể bỏ dòng này nếu trùng
    'admin-create-categories' => (new CategoryController)->create(),
    'admin-update-categories' => (new CategoryController)->update(),
    'admin-delete-categories' => (new CategoryController)->delete(),

    // ==================== Tour (Admin) ====================
    'admin-list-tour'   => (new TourController)->index(),
    'admin-create-tour' => (new TourController)->create(),
    'admin-update-tour' => (new TourController)->update(),
    'admin-delete-tour' => (new TourController)->delete(),
    'admin-tour-detail' => (new TourController)->detail(),

    // ==================== Guest (Admin) ====================
    'guest-list' => (new GuestController)->list(),

    // ==================== Guide (Dành cho HDV) - MỚI THÊM ====================
    'hdv-lich'      => (new GuideController)->mySchedule(), // Xem lịch làm việc
    'hdv-xem-khach' => (new GuideController)->viewGuests(), // Xem danh sách khách của tour

    // ==================== User ====================
    'home'   => (new HomeController)->index(),
    'login'  => (new HomeController)->login(),
    'logout' => (new HomeController)->logout(),

    default => (new HomeController)->index(),
};