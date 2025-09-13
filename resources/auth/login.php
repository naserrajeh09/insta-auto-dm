<?php
// resources/auth/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// توليد CSRF token جديد إذا لم يكن موجود
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تسجيل الدخول - Instagram Auto DM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md p-6 bg-white rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">تسجيل الدخول</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div>
                <label class="block mb-1 font-medium">البريد الإلكتروني</label>
                <input type="email" name="email" required
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
            </div>

            <div>
                <label class="block mb-1 font-medium">كلمة المرور</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" />
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                دخول
            </button>
        </form>
    </div>
</body>
</html>
