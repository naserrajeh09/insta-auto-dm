<?php
// public/index.php

declare(strict_types=1);

// تحميل autoload أولاً
require __DIR__ . '/../vendor/autoload.php';

// تحميل الإعدادات (config.php قد يستخدم dotenv داخله)
$config = require __DIR__ . '/../config.php';

// أخطاء العرض حسب الـ debug
if (!empty($config['app']['debug'])) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// بدء الجلسة لو لم تبدأ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استخدام الراوتر
use App\Helpers\Router;

// إنشاء الراوتر
$router = new Router();

// تحميل ملف المسارات (routes/index.php) الذي بدوره سيُسجّل المسارات على $router
require __DIR__ . '/../routes/index.php';

// تنفيذ المسار الحالي
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
