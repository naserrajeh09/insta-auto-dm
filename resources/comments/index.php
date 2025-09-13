<?php
// resources/comments/index.php
// session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// جلب البيانات من الكنترولر
// $keywords و $comments متوفرة عند استدعاء index() من CommentController
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Keywords & Comments - Instagram Auto DM Bot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-600 p-4 text-white flex justify-between">
    <div class="font-bold">Instagram Auto DM Bot</div>
    <div>
        <a href="/dashboard" class="mr-4 hover:underline">Dashboard</a>
        <a href="/logout" class="hover:underline">Logout</a>
    </div>
</nav>

<div class="p-6 max-w-6xl mx-auto space-y-6">

    <!-- إضافة كلمة مفتاحية جديدة -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-bold mb-2">Add New Keyword</h2>
        <form action="/comments/store" method="POST" class="flex gap-2">
            <input type="text" name="keyword" placeholder="Enter keyword" required
                   class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="default_response_public" placeholder="Default reply in comments"
                   class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <input type="text" name="default_response_dm" placeholder="Default reply in DM"
                   class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add
            </button>
        </form>
    </div>

    <!-- قائمة الكلمات المفتاحية -->
    <div class="bg-white p-4 rounded shadow overflow-x-auto">
        <h2 class="text-xl font-bold mb-2">Keywords List</h2>
        <table class="w-full text-left border-collapse">
            <thead>
            <tr>
                <th class="border-b p-2">ID</th>
                <th class="border-b p-2">Keyword</th>
                <th class="border-b p-2">Default Reply (Comments)</th>
                <th class="border-b p-2">Default Reply (DM)</th>
                <th class="border-b p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($keywords as $kw): ?>
                <tr>
                    <td class="border-b p-2"><?= htmlspecialchars($kw['id'] ?? '') ?></td>
                    <td class="border-b p-2">
                        <form action="/comments/update" method="POST" class="flex gap-2 items-center">
                            <input type="hidden" name="id" value="<?= $kw['id'] ?>">
                            <input type="text" name="keyword" value="<?= htmlspecialchars($kw['keyword'] ?? '') ?>"
                                   class="px-2 py-1 border rounded w-full">
                    </td>
                    <td class="border-b p-2">
                        <input type="text" name="default_response_public"
                               value="<?= htmlspecialchars($kw['default_response_public'] ?? '') ?>"
                               class="px-2 py-1 border rounded w-full">
                    </td>
                    <td class="border-b p-2">
                        <input type="text" name="default_response_dm"
                               value="<?= htmlspecialchars($kw['default_response_dm'] ?? '') ?>"
                               class="px-2 py-1 border rounded w-full">
                    </td>
                    <td class="border-b p-2 flex gap-2">
                        <button type="submit"
                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition">
                            Save
                        </button>
                        </form>
                        <form action="/comments/delete" method="POST" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?= $kw['id'] ?>">
                            <button type="submit"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- قائمة التعليقات -->
    <div class="bg-white p-4 rounded shadow overflow-x-auto">
        <h2 class="text-xl font-bold mb-2">Comments</h2>
        <table class="w-full text-left border-collapse">
            <thead>
            <tr>
                <th class="border-b p-2">ID</th>
                <th class="border-b p-2">Keyword</th>
                <th class="border-b p-2">Comment</th>
                <th class="border-b p-2">Instagram User</th>
                <th class="border-b p-2">Sent DM</th>
                <th class="border-b p-2">Reply (Comments)</th>
                <th class="border-b p-2">Reply (DM)</th>
                <th class="border-b p-2">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($comments as $cm): ?>
                <tr>
                    <td class="border-b p-2"><?= htmlspecialchars($cm['id'] ?? '') ?></td>
                    <td class="border-b p-2"><?= htmlspecialchars($cm['keyword'] ?? '') ?></td>
                    <td class="border-b p-2"><?= htmlspecialchars($cm['comment_text'] ?? '') ?></td>
                    <td class="border-b p-2"><?= htmlspecialchars($cm['user_instagram_id'] ?? '') ?></td>
                    <td class="border-b p-2"><?= $cm['sent_dm'] ? '✅ Sent' : '❌ Pending' ?></td>
                    <td class="border-b p-2">
                        <form action="/comments/respond" method="POST" class="flex gap-2 items-center">
                            <input type="hidden" name="comment_id" value="<?= $cm['id'] ?>">
                            <input type="text" name="response_public"
                                   value="<?= htmlspecialchars($cm['response_public'] ?? '') ?>"
                                   class="px-2 py-1 border rounded w-full">
                    </td>
                    <td class="border-b p-2">
                            <input type="text" name="response_dm"
                                   value="<?= htmlspecialchars($cm['response_dm'] ?? '') ?>"
                                   class="px-2 py-1 border rounded w-full">
                    </td>
                    <td class="border-b p-2">
                        <button type="submit"
                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition">
                            Save Reply
                        </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
