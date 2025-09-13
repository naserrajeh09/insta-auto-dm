<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Models\User;

class DashboardController
{
    /**
     * عرض لوحة التحكم
     */
    public function index(): void
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }


        // التحقق من تسجيل الدخول
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // جلب بيانات المستخدم
        $user = User::findById((int)$_SESSION['user_id']);

        require __DIR__ . '/../../resources/dashboard/index.php';
    }
}
