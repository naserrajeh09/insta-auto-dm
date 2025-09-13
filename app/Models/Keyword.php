<?php
// app/Models/Keyword.php

namespace App\Models;

use PDO;

class Keyword
{
    protected static PDO $db;

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
     * جلب كل الكلمات
     */
    public static function all(): array
    {
        self::initDB();
        $stmt = self::$db->query("SELECT * FROM keywords ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * إضافة كلمة جديدة مع الردود الافتراضية
     */
    public static function create(string $keyword, string $default_response_public = '', string $default_response_dm = ''): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("
            INSERT INTO keywords (keyword, default_response_public, default_response_dm)
            VALUES (:keyword, :pub, :dm)
        ");
        return $stmt->execute([
            'keyword' => $keyword,
            'pub' => $default_response_public,
            'dm' => $default_response_dm,
        ]);
    }

    /**
     * حذف كلمة حسب ID
     */
    public static function delete(int $id): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("DELETE FROM keywords WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * جلب كلمة حسب النص
     */
    public static function findByKeyword(string $keyword): ?array
    {
        self::initDB();
        $stmt = self::$db->prepare("SELECT * FROM keywords WHERE keyword = :keyword LIMIT 1");
        $stmt->execute(['keyword' => $keyword]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * تحديث كلمة مفتاحية مع الردود الافتراضية
     */
    public static function updateKeyword(int $id, string $keyword, string $default_response_public = '', string $default_response_dm = ''): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("
            UPDATE keywords
            SET keyword = :keyword,
                default_response_public = :pub,
                default_response_dm = :dm
            WHERE id = :id
        ");
        return $stmt->execute([
            'keyword' => $keyword,
            'pub' => $default_response_public,
            'dm' => $default_response_dm,
            'id' => $id,
        ]);
    }
}
