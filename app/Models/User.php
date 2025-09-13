<?php
// app/Models/User.php

namespace App\Models;

use PDO;

class User
{
    protected static PDO $db;

    /**
     * تهيئة الاتصال بقاعدة البيانات
     */
    protected static function initDB(): void
    {
        if (!isset(self::$db)) {
            $config = require __DIR__ . '/../../config.php';
            $dbConf = $config['db'];

            $dsn = "mysql:host={$dbConf['host']};dbname={$dbConf['database']};charset={$dbConf['charset']}";
            self::$db = new PDO($dsn, $dbConf['username'], $dbConf['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
    }

    /**
     * جلب المستخدم حسب البريد الإلكتروني
     */
    public static function findByEmail(string $email): ?array
    {
        self::initDB();

        $stmt = self::$db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    /**
     * جلب المستخدم حسب الـ ID
     */
    public static function findById(int $id): ?array
    {
        self::initDB();

        $stmt = self::$db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
