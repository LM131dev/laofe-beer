<?php
// register.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

// ຫາກເຂົ້າສູ່ລະບົບແລ້ວ ໃຫ້ໄປຫາ account.php
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: account.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($fullname) || empty($phone) || empty($email) || empty($username) || empty($password)) {
        $error = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນທຸກຊ່ອງ' : 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = $current_lang === 'lo' ? 'ຮູບແບບອີເມລບໍ່ຖືກຕ້ອງ' : 'Invalid email address.';
    } else {
        try {
            // ກວດສອບຊື່ຜູ້ໃຊ້ຊ້ຳ
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt_check->execute([$username]);
            if ($stmt_check->fetchColumn() > 0) {
                $error = $current_lang === 'lo' ? 'ຊື່ຜູ້ໃຊ້ນີ້ຖືກນຳໃຊ້ແລ້ວ!' : 'Username is already taken.';
            } else {
                // ເພີ່ມຜູ້ໃຊ້ໃໝ່
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                $stmt_insert = $pdo->prepare("INSERT INTO users (username, password, fullname, phone, email, role) VALUES (?, ?, ?, ?, ?, 'customer')");
                $stmt_insert->execute([$username, $hashed_pass, $fullname, $phone, $email]);

                $user_id = $pdo->lastInsertId();

                // ເຂົ້າສູ່ລະບົບອັດຕະໂນມັດ
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_username'] = $username;
                $_SESSION['user_fullname'] = $fullname;
                $_SESSION['user_points'] = 0;
                $_SESSION['user_tier'] = 'Member';

                header("Location: account.php");
                exit;
            }
        } catch (\Exception $e) {
            $error = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດ: ' : 'Database error: ') . $e->getMessage();
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
            <span class="text-xl font-semibold text-gray-800 tracking-wider">REGISTER</span>
            <p class="text-xs text-gray-500 font-light mt-1">
                <?php echo $current_lang === 'lo' ? 'ລົງທະບຽນສະມາຊິກເພື່ອສະສົມຄະແນນ' : 'Register a member account to earn points'; ?>
            </p>
            <div class="w-16 h-0.5 bg-burgundy-700 mx-auto mt-2"></div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label for="fullname" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    <?php echo $current_lang === 'lo' ? 'ຊື່ ແລະ ນາມສະກຸນ' : 'Full Name'; ?> *
                </label>
                <input type="text" name="fullname" id="fullname" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                        <?php echo $current_lang === 'lo' ? 'ເບີໂທລະສັບ' : 'Phone'; ?> *
                    </label>
                    <input type="text" name="phone" id="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                </div>
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                        <?php echo $current_lang === 'lo' ? 'ອີເມລ' : 'Email'; ?> *
                    </label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <div>
                    <label for="username" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                        <?php echo $current_lang === 'lo' ? 'ຊື່ຜູ້ໃຊ້ (Username)' : 'Username'; ?> *
                    </label>
                    <input type="text" name="username" id="username" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    <?php echo $current_lang === 'lo' ? 'ລະຫັດຜ່ານ (Password)' : 'Password'; ?> *
                </label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
            </div>

            <button type="submit" class="w-full btn-premium py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl transition-all duration-300 shadow-md text-sm">
                <?php echo $current_lang === 'lo' ? 'ລົງທະບຽນສະມາຊິກ' : 'Register Account'; ?>
            </button>
        </form>

        <div class="text-center text-xs text-gray-500 pt-2 flex flex-col items-center gap-3">
            <div>
                <?php echo $current_lang === 'lo' ? 'ມີບັນຊີສະມາຊິກແລ້ວ?' : 'Already have an account?'; ?>
                <a href="login.php" class="text-burgundy-700 font-bold hover:underline ml-1">
                    <?php echo t('nav_login'); ?>
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
