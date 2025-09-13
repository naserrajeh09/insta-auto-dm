<?php
// app/Helpers/ResponseHelper.php

namespace App\Helpers;

class ResponseHelper
{
    /**
     * إرسال استجابة نجاح
     * @param mixed $data البيانات المراد إرسالها
     * @param string $message رسالة اختيارية
     */
    public static function success($data = null, string $message = '')
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * إرسال استجابة خطأ
     * @param string $message رسالة الخطأ
     * @param int $code كود HTTP (default: 400)
     */
    public static function error(string $message = '', int $code = 400)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
