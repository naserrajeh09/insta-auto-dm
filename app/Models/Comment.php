<?php
// app/Models/Comment.php

namespace App\Models;

use PDO;

class Comment
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
     * إضافة تعليق جديد
     */
    public static function create(int $keyword_id, string $user_instagram_id, string $comment_text): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("
            INSERT INTO comments (keyword_id, user_instagram_id, comment_text, sent_dm, created_at)
            VALUES (:keyword_id, :user_instagram_id, :comment_text, 0, NOW())
        ");
        return $stmt->execute([
            'keyword_id' => $keyword_id,
            'user_instagram_id' => $user_instagram_id,
            'comment_text' => $comment_text
        ]);
    }

    /**
     * جلب كل التعليقات مع الكلمة المرتبطة
     */
    public static function all(): array
    {
        self::initDB();
        $stmt = self::$db->query("
            SELECT c.*, k.keyword 
            FROM comments c
            LEFT JOIN keywords k ON c.keyword_id = k.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * تحديث حالة الرسالة المرسلة في الخاص
     */
    public static function markAsSent(int $comment_id): bool
    {
        self::initDB();
        $stmt = self::$db->prepare("UPDATE comments SET sent_dm = 1 WHERE id = :id");
        return $stmt->execute(['id' => $comment_id]);
    }

    /**
     * تحديث الردود على تعليق (عام + خاص)
     */
    public static function updateResponse(int $comment_id, ?string $response_public = null, ?string $response_dm = null): bool
    {
        self::initDB();

        $fields = [];
        $params = [':id' => $comment_id];

        if ($response_public !== null) {
            $fields[] = 'response_public = :pub';
            $params[':pub'] = $response_public;
        }

        if ($response_dm !== null) {
            $fields[] = 'response_dm = :dm';
            $params[':dm'] = $response_dm;
        }

        if (empty($fields)) return false;

        $sql = "UPDATE comments SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * جلب تعليق واحد حسب ID
     */
    public static function find(int $id): ?array
    {
        self::initDB();
        $stmt = self::$db->prepare("SELECT * FROM comments WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
