<?php
// login.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

// ຈັດການການອອກຈາກລະບົບ (Logout)
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['user_logged_in']);
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_fullname']);
    unset($_SESSION['user_points']);
    unset($_SESSION['user_tier']);
    header("Location: index.php");
    exit;
}

// ຫາກເຂົ້າສູ່ລະບົບແລ້ວ ໃຫ້ໄປຫາ account.php
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: account.php");
    exit;
}

require_once __DIR__ . '/includes/auth_lockout.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = $current_lang === 'lo' ? 'ກະລຸນາກອກ ຊື່ຜູ້ໃຊ້ ແລະ ລະຫັດຜ່ານ ໃຫ້ຄົບຖ້ວນ' : 'Please enter username and password.';
    } else {
        // 1. Check if account is locked out (Tier 1: 15m, Tier 2: 1h, Tier 3: 24h/Admin unlock)
        $lock_check = check_user_lockout($pdo, $username, $current_lang);
        if ($lock_check['locked']) {
            $error = $lock_check['message'];
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_fullname'] = $user['fullname'];
                    $_SESSION['user_points'] = $user['points'];
                    $_SESSION['user_tier'] = $user['tier'];
                    
                    // Reset lockout tracking
                    reset_user_lockout($pdo, $user['id']);

                    header("Location: account.php");
                    exit;
                } else {
                    // Record failed attempt and trigger lockout if reached 3 attempts
                    $error = record_failed_login_attempt($pdo, $username, $current_lang);
                }
            } catch (\Exception $e) {
                error_log("User login error: " . $e->getMessage());
                $error = $current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດໃນການເຊື່ອມຕໍ່ລະບົບ. ກະລຸນາລອງໃໝ່.' : 'Connection error. Please try again.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="py-16 bg-gray-50 min-h-[80vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 p-8 space-y-6">
        <div class="text-center space-y-2">
            <span class="text-3xl font-bold tracking-wider font-serif-lao text-burgundy-700">LaoFe</span>
            <span class="text-gray-400">/</span>
            <span class="text-xl font-semibold text-gray-800 tracking-wider">LOGIN</span>
            <p class="text-xs text-gray-500 font-light mt-1">
                <?php echo $current_lang === 'lo' ? 'ເຂົ້າສູ່ລະບົບເພື່ອສະສົມຄະແນນ ແລະ ສັ່ງເຄື່ອງດື່ມ' : 'Login to earn loyalty rewards and order beverages'; ?>
            </p>
            <div class="w-16 h-0.5 bg-burgundy-700 mx-auto mt-2"></div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    <?php echo $current_lang === 'lo' ? 'ຊື່ຜູ້ໃຊ້ (Username)' : 'Username'; ?> *
                </label>
                <input type="text" name="username" id="username" required placeholder="ສະມາຍ" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    <?php echo $current_lang === 'lo' ? 'ລະຫັດຜ່ານ (Password)' : 'Password'; ?> *
                </label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
            </div>

            <button type="submit" class="w-full btn-premium py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl transition-all duration-300 shadow-md text-sm">
                <?php echo t('nav_login'); ?>
            </button>
        </form>

        <div class="text-center text-xs text-gray-500 pt-2 flex flex-col items-center gap-3">
            <div>
                <?php echo $current_lang === 'lo' ? 'ຍັງບໍ່ມີບັນຊີສະມາຊິກ?' : 'Don\'t have an account yet?'; ?>
                <a href="register.php" class="text-burgundy-700 font-bold hover:underline ml-1">
                    <?php echo t('nav_register'); ?>
                </a>
            </div>

            <div class="pt-2 border-t border-gray-100 w-full text-center">
                <a href="admin/" class="inline-flex items-center gap-1.5 text-xs text-burgundy-700 hover:text-burgundy-900 font-bold hover:underline py-1.5 px-4 bg-burgundy-50 hover:bg-burgundy-100 rounded-full border border-burgundy-700/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span><?php echo $current_lang === 'lo' ? 'ເຂົ້າສູ່ລະບົບຜູ້ດູແລ (Admin Portal)' : 'Switch to Admin Portal'; ?></span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
