<?php 
session_start();

// 1. Định nghĩa đường dẫn gốc của dự án
define('PATH_ROOT', __DIR__ . '/');

// 2. Gọi file cấu hình ĐẦU TIÊN để lấy các hằng số (PATH_MODEL, DB_HOST...)
require_once PATH_ROOT . 'configs/env.php';
require_once PATH_ROOT . 'configs/helper.php';

// 3. Khởi tạo Autoload (Tự động nạp file Model và Controller)
spl_autoload_register(function ($class) {
    // Dòng này là dòng số 8 gây lỗi cũ của bạn
    // Bây giờ PATH_MODEL đã được load từ bước 2 nên sẽ không lỗi nữa
    $modelFile = PATH_MODEL . $class . '.php';
    $controllerFile = PATH_CONTROLLER . $class . '.php';

    if (file_exists($modelFile)) {
        require_once $modelFile;
    } elseif (file_exists($controllerFile)) {
        require_once $controllerFile;
    }
});

// 4. Điều hướng (Router)
require_once PATH_ROOT . 'routes/index.php';
?>