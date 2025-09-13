<?php
// database/seeds/KeywordSeeder.php

$config = require __DIR__ . '/../../bootstrap.php';

// جلب إعدادات قاعدة البيانات
$dbConfig = $config['db'];

// إنشاء اتصال PDO
$pdo = new PDO(
    $dbConfig['dsn'],
    $dbConfig['user'],
    $dbConfig['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// مصفوفة بالكلمات المفتاحية (عدلها حسب حاجتك)
$keywords = [
    ['keyword' => 'hello', 'response' => 'Hi there! 👋'],
    ['keyword' => 'offer', 'response' => 'Check out our latest offers! 🔥'],
    ['keyword' => 'support', 'response' => 'Please contact our support team.'],
];

// إدخال البيانات في جدول keywords
$stmt = $pdo->prepare("INSERT INTO keywords (keyword, response) VALUES (?, ?)");

foreach ($keywords as $k) {
    $stmt->execute([$k['keyword'], $k['response']]);
}

echo "✅ Keywords seeded successfully.\n";
