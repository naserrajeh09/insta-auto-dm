<?php
// routes/web.php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CommentController;
use App\Controllers\MessageController;

// نفترض أن autoload محمّل من public/index.php
// هنا نستخدم المتغير $router الذي أنشأناه في public/index.php

// تسجيل المسارات الأساسية
$router->get('/', [AuthController::class, 'showLoginForm']);
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);

// Comments / Keywords
$router->get('/comments', [CommentController::class, 'index']);
$router->post('/comments/store', [CommentController::class, 'store']);
$router->post('/comments/delete', [CommentController::class, 'delete']);
$router->post('/comments/update', [CommentController::class, 'updateKeyword']);
$router->post('/comments/respond', [CommentController::class, 'respondComment']);

// Messages
$router->get('/messages', [MessageController::class, 'index']);

// إعادة إرسال رسالة (نستخدم closure لالتقاط POST id وتمريره)
$router->post('/messages/resend', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    \App\Middleware\AuthMiddleware::check();

    $message_id = (int)($_POST['id'] ?? 0);
    if ($message_id > 0) {
        $ctrl = new \App\Controllers\MessageController();
        $ctrl->resend($message_id);
    } else {
        header('Location: /messages');
        exit;
    }
});

// حذف رسالة (closure ليستخدم POST data)
$router->post('/messages/delete', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    \App\Middleware\AuthMiddleware::check();

    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $ctrl = new \App\Controllers\MessageController();
        // لدينا دالة delete() تقرأ $_POST['id'] داخلياً حسب النسخ السابقة؛
        // لكن للحفاظ على وضوح الاستدعاء، نعيد تغليفها:
        $_POST['id'] = $id;
        $ctrl->delete();
    }
    header('Location: /messages');
    exit;
});
