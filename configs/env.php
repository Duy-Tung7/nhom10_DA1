<?php
// ... các dòng trên giữ nguyên

define('DB_HOST',     'localhost');
define('DB_PORT',     '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');


// CHỌN 1 TRONG 2 DÒNG DƯỚI ĐÂY (dựa trên tên database thật trong Laragon của bạn):

define('DB_NAME',     'da1_nhom10'); 
// Hoặc nếu tên DB là da1_f25 thì dùng: define('DB_NAME', 'da1_f25');

define('PATH_MODEL', PATH_ROOT . 'models/');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);