<?php
// app/Middleware/AuthMiddleware.php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * التحقق من تسجيل الدخول
     * إذا لم يكن المستخدم مسجلاً الدخول، يعيد التوجيه لصفحة login
     */
    public static function check(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
}
