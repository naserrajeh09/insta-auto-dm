<?php
// app/Models/Message.php

namespace App\Models;

use PDO;

class Message
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
     * تسجيل رسالة جديدة
     */
    public static function create(int $comment_id, string $message_text, string $status = 'pending'): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("
            INSERT INTO messages (comment_id, message_text, status)
            VALUES (:comment_id, :message_text, :status)
        ");
        return $stmt->execute([
            'comment_id' => $comment_id,
            'message_text' => $message_text,
            'status' => $status
        ]);
    }

    /**
     * جلب كل الرسائل (اختياري مع التعليقات والكلمات)
     */
    public static function all(): array
    {
        self::initDB();
        $stmt = self::$db->query("
            SELECT m.*, c.comment_text, k.keyword
            FROM messages m
            JOIN comments c ON m.comment_id = c.id
            JOIN keywords k ON c.keyword_id = k.id
            ORDER BY m.sent_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * تحديث حالة الرسالة
     */
    public static function updateStatus(int $message_id, string $status): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("UPDATE messages SET status = :status WHERE id = :id");
        return $stmt->execute([
            'status' => $status,
            'id' => $message_id
        ]);
    }
}
