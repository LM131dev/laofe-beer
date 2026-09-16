<?php
// admin/login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ຫາກເຂົ້າສູ່ລະບົບແລ້ວ ໃຫ້ໄປຫາ dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Rate Limiting (5 failed attempts max per 15 minutes)
    $max_attempts = 5;
    $lockout_time = 900; // 15 mins
    if (!isset($_SESSION['admin_login_attempts'])) {
        $_SESSION['admin_login_attempts'] = 0;
        $_SESSION['admin_last_attempt'] = time();
    }

    if ($_SESSION['admin_login_attempts'] >= $max_attempts) {
        $time_left = $lockout_time - (time() - $_SESSION['admin_last_attempt']);
        if ($time_left > 0) {
            $mins = ceil($time_left / 60);
            $error = "ທ່ານລອງເຂົ້າສູ່ລະບົບຜິດຫຼາຍເກີນໄປ ($max_attempts ຄັ້ງ). ກະລຸນາລໍຖ້າ $mins ນາທີ ແລ້ວລອງໃໝ່.";
        } else {
            $_SESSION['admin_login_attempts'] = 0;
        }
    }

    if (empty($error)) {
        if (empty($username) || empty($password)) {
            $error = 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນ';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password']) && in_array($user['role'], ['admin', 'staff'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_user'] = $user['username'];
                    $_SESSION['admin_role'] = $user['role'];
                    $_SESSION['admin_login_attempts'] = 0; // Reset counter
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $_SESSION['admin_login_attempts']++;
                    $_SESSION['admin_last_attempt'] = time();
                    $error = 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ!';
                }
            } catch (\Exception $e) {
                error_log("Admin login error: " . $e->getMessage());
                $error = 'ເກີດຂໍ້ຜິດພາດໃນການເຊື່ອມຕໍ່ລະບົບ. ກະລຸນາລອງໃໝ່ອີກຄັ້ງ.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LaoFe & Beer Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <!-- Production Static Tailwind CSS -->
    <link rel="stylesheet" href="../assets/css/tailwind.min.css?v=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=1.0">
</head>
<body class="bg-burgundy-50 min-h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 p-8 md:p-10 space-y-6">
        <!-- Logo Header -->
        <div class="text-center space-y-2 flex flex-col items-center">
            <img src="../assets/images/logo.png" alt="LaoFe Logo" class="w-16 h-16 object-contain rounded-full shadow-md bg-cream-100 border border-burgundy-700/10 mb-2">
            <span class="text-3xl font-bold tracking-wider font-serif-lao text-burgundy-700">LaoFe</span>
            <p class="text-sm text-gray-500 font-light">Admin Management System Portal</p>
            <div class="w-16 h-1 bg-burgundy-700 mx-auto mt-2"></div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="login.php" method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">ຊື່ຜູ້ໃຊ້ (Username)</label>
                <input type="text" name="username" id="username" required placeholder="admin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">ລະຫັດຜ່ານ (Password)</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
            </div>

            <button type="submit" class="w-full btn-premium py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-lg transition-all duration-300 shadow-md">
                ເຂົ້າສູ່ລະບົບ (Login)
            </button>
        </form>

        <div class="text-center pt-2">
            <a href="../index.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-medium underline">
                ກັບຄືນໜ້າຫຼັກເວັບໄຊ
            </a>
        </div>
    </div>

</body>
</html>
