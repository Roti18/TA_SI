<?php  include "config/helpers.php"?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- CSS Tailwind hasil build -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex justify-center items-center">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">

        <!-- Title -->
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">
            Login
        </h2>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?= route('proses-login'); ?>" method="POST" class="space-y-5">

            <!-- Email -->
            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="yourmail@example.com">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="*******">
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                Login
            </button>

        </form>
    </div>

</body>

</html>