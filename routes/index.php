<?php

$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController)->index(),

    // ==================== Dashboard & Sidebar ====================
    'admin-sidebar' => (new DashboardController)->index(),
    'guide-sidebar' => (new GuideController)->index(),
    
    // ==================== Category ====================
    'admin-list-categories'   => (new CategoryController)->index(), // Danh mục chính
    'admin-list-category'     => (new CategoryController)->index(), // Danh mục con → show tour
    'admin-create-categories' => (new CategoryController)->create(),
    'admin-update-categories' => (new CategoryController)->update(),
    'admin-delete-categories' => (new CategoryController)->delete(),

    // ==================== HDV (Hướng Dẫn Viên) ====================
    // 1. Dashboard chung của HDV
    'guide-sidebar', 'guide-dashboard' => (new GuideController)->index(),
    
    // 2. Lịch tour của tôi (Action trong sidebar: guide-my-tours)
    'guide-my-tours' => (new GuideController)->listTours(), 
    
    // 3. Danh sách khách hàng (Action trong sidebar: guide-guests)
    'guide-guests'   => (new GuideController)->listGuests(), 
    
    // 4. Hồ sơ cá nhân (Action trong sidebar: guide-profile)
    'guide-profile'  => (new GuideController)->profile(),
    'check-in'       => (new GuideController)->checkIn(),

    // ==================== Tour (Admin) ====================
    'admin-list-tour'   => (new TourController)->index(),
    'admin-create-tour' => (new TourController)->create(),
    'admin-update-tour' => (new TourController)->update(),
    'admin-delete-tour' => (new TourController)->delete(),
    'admin-tour-detail'  => (new TourController)->detail(),



    // ==================== User & Auth ====================
    'home'   => (new HomeController)->index(),
    'login'  => (new HomeController)->login(),
    'logout' => (new HomeController)->logout(),

    default => (new HomeController)->index(),
};