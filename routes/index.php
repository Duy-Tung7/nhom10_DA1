<?php

$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController)->index(),

    // Dashboard admin
    'admin-dashboard' => (new DashboardController)->index(),

    // ==================== Category ====================
    'admin-list-categories'   => (new CategoryController)->index(), // Danh mục chính
    'admin-list-category'     => (new CategoryController)->index(), // Danh mục con → show tour
    'admin-create-categories' => (new CategoryController)->create(),
    'admin-update-categories' => (new CategoryController)->update(),
    'admin-delete-categories' => (new CategoryController)->delete(),

    // ==================== Tour ====================
    'admin-list-tour'   => (new TourController)->index(),
    'admin-create-tour' => (new TourController)->create(),
    'admin-update-tour' => (new TourController)->update(),
    'admin-delete-tour' => (new TourController)->delete(),

    default => (new HomeController)->index(),
};
