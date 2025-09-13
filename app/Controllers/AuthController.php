<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm(): void
    {
        require __DIR__ . '/../../resources/auth/login.php';
    }

    /**
     * تنفيذ تسجيل الدخول
     */
    public function login(): void
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        // حماية CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token mismatch.');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /login');
            exit;
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}
