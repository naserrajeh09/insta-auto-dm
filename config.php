<?php
// config.php

return [

    // إعدادات التطبيق
    'app' => [
        'name'   => 'Instagram Auto DM',
        'env'    => 'local', // local | production
        'debug'  => true,    // true أثناء التطوير - false في الإصدار النهائي
        'url'    => 'http://localhost:8000',
        'timezone' => 'UTC',
    ],

    // إعدادات قاعدة البيانات
    'db' => [
        'host'     => '127.0.0.1',       // أو localhost
        'database' => 'instagram_bot', // غيّرها لاسم قاعدة بياناتك
        'username' => 'root',              // اسم مستخدم MySQL
        'password' => '',                  // كلمة مرور MySQL (افتراضي فارغ في XAMPP)
        'charset'  => 'utf8mb4',           // مهم لتفادي Unknown character set
        'collation'=> 'utf8mb4_unicode_ci',
    ],

    // خيارات أخرى مستقبلية (API, Mail, ... إلخ)
    'services' => [
        'instagram' => [
            'app_id'     => '',
            'app_secret' => '',
            'redirect'   => '',
        ],
    ],
];
