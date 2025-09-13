<?php
// worker/auto_dm.php

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Comment;
use App\Models\Message;

echo "[" . date('Y-m-d H:i:s') . "] Auto DM Worker started...\n";

// دالة لتشغيل السكريبت باستمرار كل 30 ثانية
while (true) {
    $comments = Comment::all();

    foreach ($comments as $comment) {
        // تحقق إذا لم يتم إرسال الرسالة بعد
        if (!$comment['sent_dm']) {
            $message_text = "Hello! Thanks for commenting '{$comment['keyword']}' on our post. Check your inbox!";

            // تسجيل الرسالة في قاعدة البيانات
            $sent = Message::create($comment['id'], $message_text);

            if ($sent) {
                Comment::markAsSent($comment['id']);
                echo "[" . date('Y-m-d H:i:s') . "] Sent DM to Instagram User: {$comment['user_instagram_id']} for Comment ID: {$comment['id']}\n";
            } else {
                echo "[" . date('Y-m-d H:i:s') . "] Failed to send DM for Comment ID: {$comment['id']}\n";
            }
        }
    }

    // تأخير 30 ثانية قبل التحقق التالي
    sleep(30);
}
