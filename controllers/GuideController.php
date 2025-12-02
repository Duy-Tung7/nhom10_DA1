<?php
 class GuideController{
    public function index(){
        
        $title = "Hướng dẫn viên";
        $view = "guide/dashboard";
        require_once PATH_VIEW . 'main.php';
    }
 }