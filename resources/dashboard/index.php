<?php
// resources/dashboard/index.php
// session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// جلب بيانات المستخدم
$user = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Instagram Auto DM Bot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-600 p-4 text-white flex justify-between">
    <div class="font-bold">Instagram Auto DM Bot</div>
    <div>
        <a href="/logout" class="hover:underline">Logout</a>
    </div>
</nav>

<div class="p-6">
    <h1 class="text-3xl font-bold mb-4">Welcome, User #<?= htmlspecialchars($user) ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-bold mb-2">Manage Keywords</h2>
            <p>Add or remove keywords to trigger auto DMs.</p>
            <a href="/comments" class="text-blue-600 hover:underline">Go</a>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-bold mb-2">Messages Log</h2>
            <p>View sent messages and logs.</p>
            <a href="/messages" class="text-blue-600 hover:underline">Go</a>
        </div>
    </div>
</div>

</body>
</html>
