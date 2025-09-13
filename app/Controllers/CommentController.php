<?php
// app/Controllers/CommentController.php

namespace App\Controllers;

use App\Models\Keyword;
use App\Models\Comment;
use App\Models\Message;

class CommentController
{
    /**
     * عرض جميع الكلمات والتعليقات
     */
    public function index(): void
    {
        // if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $keywords = Keyword::all();
        $comments = Comment::all();

        require __DIR__ . '/../../resources/comments/index.php';
    }

    /**
     * إضافة كلمة مفتاحية جديدة
     */
    public function store(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $keyword = trim($_POST['keyword'] ?? '');
        $default_public = trim($_POST['default_response_public'] ?? '');
        $default_dm = trim($_POST['default_response_dm'] ?? '');

        if ($keyword && !Keyword::findByKeyword($keyword)) {
            Keyword::create($keyword, $default_public, $default_dm);
        }

        header('Location: /comments');
        exit;
    }

    /**
     * حذف كلمة مفتاحية
     */
    public function delete(): void
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            Keyword::delete($id);
        }

        header('Location: /comments');
        exit;
    }

    /**
     * تحديث كلمة مفتاحية (keyword + default replies)
     */
    public function updateKeyword(): void
    {
        // if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $keyword = trim($_POST['keyword'] ?? '');
        $default_public = trim($_POST['default_response_public'] ?? '');
        $default_dm = trim($_POST['default_response_dm'] ?? '');

        if ($id && $keyword) {
            Keyword::updateKeyword($id, $keyword, $default_public, $default_dm);
        }

        header('Location: /comments');
        exit;
    }

    /**
     * تحديث الردود على تعليق (Public + DM)
     */
    public function respondComment(): void
    {
        // if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $comment_id = (int)($_POST['comment_id'] ?? 0);
        $response_public = trim($_POST['response_public'] ?? null);
        $response_dm = trim($_POST['response_dm'] ?? null);

        if ($comment_id) {
            Comment::updateResponse($comment_id, $response_public, $response_dm);
        }

        header('Location: /comments');
        exit;
    }

    /**
     * إرسال رسالة خاصة بناءً على تعليق
     */
    public function sendDM(int $comment_id, string $message_text): bool
    {
        // هنا ستربط مع API الانستاجرام لاحقًا
        // مؤقتًا: تسجيل الرسالة في DB
        $created = Message::create($comment_id, $message_text);
        if ($created) {
            Comment::markAsSent($comment_id);
            return true;
        }
        return false;
    }
}
