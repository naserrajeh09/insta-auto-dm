<?php
// database/seeds/UserSeeder.php

$config = require __DIR__ . '/../../bootstrap.php';

// الآن نجيب بيانات قاعدة البيانات من المصفوفة
$dbConfig = $config['db'];

// اتصال PDO
$pdo = new PDO(
    $dbConfig['dsn'],
    $dbConfig['user'],
    $dbConfig['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// كلمة مرور مشفرة
$passwordHash = password_hash('admin123', PASSWORD_BCRYPT);

// إدخال مستخدم
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->execute(['Admin', 'admin@example.com', $passwordHash]);

echo "✅ User seeded successfully.\n";
