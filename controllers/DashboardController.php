<?php
 class DashboardController{
    public function index(){
        
        $title = "Trang dashboard";
        $view = "admin/dashboard";
        require_once PATH_VIEW . 'main.php';
    }
 }