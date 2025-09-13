<?php
// app/Services/SchedulerService.php

namespace App\Services;

class SchedulerService
{
    /**
     * تشغيل سكريبت بشكل مستمر
     * @param string $script_path مسار السكريبت المراد تشغيله
     * @param int $interval تأخير بين كل تشغيل بالثواني
     */
    public function runContinuous(string $script_path, int $interval = 30): void
    {
        while (true) {
            echo "[" . date('Y-m-d H:i:s') . "] Running script: {$script_path}\n";
            exec("php " . escapeshellarg($script_path));
            sleep($interval);
        }
    }

    /**
     * تشغيل مهمة واحدة مرة واحدة
     * @param string $script_path مسار السكريبت
     */
    public function runOnce(string $script_path): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] Running script once: {$script_path}\n";
        exec("php " . escapeshellarg($script_path));
    }
}
