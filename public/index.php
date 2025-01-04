<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$kernel = require_once __DIR__.'/../bootstrap/app.php'; // Tạo đối tượng ứng dụng Laravel
$response = $kernel->handle(
    $request = Request::capture() // Lấy yêu cầu HTTP
);

$response->send(); // Gửi phản hồi

$kernel->terminate($request, $response); // Kết thúc và dọn dẹp
