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

    if (empty($username) || empty($password)) {
        $error = 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນ';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!$user || !in_array($user['role'], ['admin', 'staff'])) {
                $error = 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ!';
            } else {
                // Check Lockout Status
                $now = time();
                $lockout_until = !empty($user['lockout_until']) ? strtotime($user['lockout_until']) : 0;
                $is_locked = intval($user['is_locked'] ?? 0);
                $stage = intval($user['lockout_stage'] ?? 0);

                if ($is_locked == 1 || $stage >= 3) {
                    if ($lockout_until > 0 && $now < $lockout_until) {
                        $time_left = $lockout_until - $now;
                        $hours = ceil($time_left / 3600);
                        $error = "ບັນຊີຂອງທ່ານຖືກລັອກ ຍ້ອນປ້ອນລະຫັດຜິດຫຼາຍຄັ້ງ (3 ຂັ້ນ). ກະລຸນາລໍຖ້າ $hours ຊົ່ວໂມງ ຫຼື ຕິດຕໍ່ Super Admin ເພື່ອປົດລັອກ.";
                    } elseif ($is_locked == 1 && $lockout_until == 0) {
                        $error = "ບັນຊີ Admin ຂອງທ່ານຖືກລັອກໂດຍລະບົບ. ກະລຸນາຕິດຕໍ່ Super Admin ເພື່ອປົດລັອກ.";
                    }
                } elseif ($lockout_until > $now) {
                    $time_left = $lockout_until - $now;
                    $mins = ceil($time_left / 60);
                    if ($stage == 2) {
                        $error = "ທ່ານປ້ອນລະຫັດຜິດ 3 ຄັ້ງໃນຂັ້ນທີ 2. ບັນຊີຖືກລັອກຊົ່ວຄາວ 1 ຊົ່ວໂມງ (ລໍຖ້າອີກ $mins ນາທີ).";
                    } else {
                        $error = "ທ່ານປ້ອນລະຫັດຜິດ 3 ຄັ້ງ. ບັນຊີຖືກລັອກຊົ່ວຄາວ 15 ນາທີ (ລໍຖ້າອີກ $mins ນາທີ).";
                    }
                }

                if (empty($error)) {
                    if (password_verify($password, $user['password'])) {
                        // Success: Reset lockout status & counter
                        $stmt_reset = $pdo->prepare("UPDATE users SET failed_attempts = 0, lockout_stage = 0, lockout_until = NULL, is_locked = 0 WHERE id = ?");
                        $stmt_reset->execute([$user['id']]);

                        session_regenerate_id(true);
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_user'] = $user['username'];
                        $_SESSION['admin_role'] = $user['role'];

                        header("Location: dashboard.php");
                        exit;
                    } else {
                        // Password Incorrect -> Update Failed Attempts & Lockout Stages
                        $attempts = intval($user['failed_attempts'] ?? 0) + 1;
                        $stage = intval($user['lockout_stage'] ?? 0);
                        $new_lockout_until = null;
                        $new_is_locked = 0;

                        if ($attempts >= 3) {
                            $stage = min(3, $stage + 1);
                            $attempts = 0; // Reset attempt count for current stage

                            if ($stage == 1) {
                                $new_lockout_until = date('Y-m-d H:i:s', time() + 900); // 15 mins
                                $error = "ທ່ານປ້ອນລະຫັດຜິດ 3 ຄັ້ງ! ບັນຊີຖືກລັອກຊົ່ວຄາວ 15 ນາທີ.";
                            } elseif ($stage == 2) {
                                $new_lockout_until = date('Y-m-d H:i:s', time() + 3600); // 1 hour
                                $error = "ທ່ານປ້ອນລະຫັດຜິດອີກ 3 ຄັ້ງ (ຂັ້ນທີ 2)! ບັນຊີຖືກລັອກຊົ່ວຄາວ 1 ຊົ່ວໂມງ.";
                            } elseif ($stage >= 3) {
                                $new_lockout_until = date('Y-m-d H:i:s', time() + 86400); // 24 hours (next day)
                                $new_is_locked = 1;
                                $error = "ທ່ານປ້ອນລະຫັດຜິດກວ່ານີ້ 3 ຄັ້ງ (ຂັ້ນທີ 3)! ບັນຊີຖືກລັອກຈົນຮອດມື້ອື່ນ ຫຼື ຈົນກວ່າ Admin ຈະປົດລັອກ.";
                            }
                        } else {
                            $left = 3 - $attempts;
                            $error = "ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ! (ປ້ອນຜິດໄດ້ອີກ $left ຄັ້ງ ກ່ອນຖືກລັອກ)";
                        }

                        $stmt_up = $pdo->prepare("UPDATE users SET failed_attempts = ?, lockout_stage = ?, lockout_until = ?, is_locked = ? WHERE id = ?");
                        $stmt_up->execute([$attempts, $stage, $new_lockout_until, $new_is_locked, $user['id']]);
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("Admin login error: " . $e->getMessage());
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການເຊື່ອມຕໍ່ລະບົບ. ກະລຸນາລອງໃໝ່ອີກຄັ້ງ.';
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
