<?php
// app/Controllers/MessageController.php

namespace App\Controllers;

use App\Models\Message;
use App\Models\Comment;
use App\Services\InstagramService;

class MessageController
{
    protected InstagramService $instagram;

    public function __construct()
    {
        $this->instagram = new InstagramService();
    }
    public function send()
{
    $recipientId = $_POST['recipient_id'] ?? null;
    $message = $_POST['message'] ?? null;

    if (!$recipientId || !$message) {
        return ["success" => false, "error" => "Recipient and message are required."];
    }

    $service = new InstagramService($_SESSION['access_token']);
    $result = $service->sendMessage($recipientId, $message);

    return $result;
}
    // عرض جميع الرسائل
    public function index(): void
    {
        \App\Middleware\AuthMiddleware::check();
        $messages = Message::all();
        require __DIR__ . '/../../resources/messages/index.php';
    }

    // إعادة إرسال رسالة فاشلة
    public function resend(int $message_id): void
    {
        \App\Middleware\AuthMiddleware::check();
        $msg = Message::findById($message_id);
        if ($msg) {
            $comment_id = $msg['comment_id'];
            $comment = Comment::findById($comment_id);
            if ($comment) {
                $sent = $this->instagram->sendDM($comment['user_instagram_id'], $msg['message_text']);
                $status = $sent ? 'sent' : 'failed';
                Message::updateStatus($message_id, $status);
            }
        }
        header('Location: /messages');
        exit;
    }

    // حذف رسالة
    public function delete(): void
    {
        \App\Middleware\AuthMiddleware::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            Message::delete($id);
        }
        header('Location: /messages');
        exit;
    }
}
