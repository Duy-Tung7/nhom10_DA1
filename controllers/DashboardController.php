<?php
 class DashboardController{
    public function index(){
        
        $title = "Admin";
        $view = "admin/dashboard";
        require_once PATH_VIEW . 'main.php';
    }
 }