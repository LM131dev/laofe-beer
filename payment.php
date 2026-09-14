<?php
// payment.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$order_id = intval($_GET['order_id'] ?? 0);

// ດຶງຂໍ້ມູນອໍເດີ້
$order = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
} catch (\Exception $e) {
    // error
}

if (!$order) {
    die(t('payment_invalid_order'));
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simulate_payment'])) {
    if ($order['status'] !== 'Paid' && $order['status'] !== 'Completed') {
        try {
            $pdo->beginTransaction();

            // 1. ອັບເດດສະຖານະອໍເດີ້ເປັນ Paid
            $stmt_update = $pdo->prepare("UPDATE orders SET status = 'Paid' WHERE id = ?");
            $stmt_update->execute([$order_id]);

            // 2. ເພີ່ມຄະແນນສະສົມໃຫ້ລູກຄ້າຫາກເຂົ້າສູ່ລະບົບ
            if ($order['user_id']) {
                $points_earned = intval($order['points_earned']);
                
                // ດຶງຄະແນນລູກຄ້າປະຈຸບັນ
                $stmt_user = $pdo->prepare("SELECT points FROM users WHERE id = ?");
                $stmt_user->execute([$order['user_id']]);
                $current_points = intval($stmt_user->fetchColumn());

                $new_points = $current_points + $points_earned;

                // ຄຳນວນ Tier
                $new_tier = 'Member';
                if ($new_points >= 1000) {
                    $new_tier = 'Platinum';
                } elseif ($new_points >= 500) {
                    $new_tier = 'Gold';
                } elseif ($new_points >= 100) {
                    $new_tier = 'Silver';
                }

                // ອັບເດດຄະແນນ ແລະ Tier ໃສ່ DB
                $stmt_user_update = $pdo->prepare("UPDATE users SET points = ?, tier = ? WHERE id = ?");
                $stmt_user_update->execute([$new_points, $new_tier, $order['user_id']]);

                // ອັບເດດໃນ Session ຫາກແມ່ນຜູ້ໃຊ້ປະຈຸບັນ
                if ($_SESSION['user_id'] == $order['user_id']) {
                    $_SESSION['user_points'] = $new_points;
                    $_SESSION['user_tier'] = $new_tier;
                }
            }

            $pdo->commit();
            $success = true;
            
            // ດຶງຂໍ້ມູນອໍເດີ້ຫຼ້າສຸດຄືນໃໝ່
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch();

        } catch (\Exception $e) {
            $pdo->rollBack();
            $error = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດໃນການຈ່າຍເງິນ: ' : 'Payment simulation failed: ') . $e->getMessage();
        }
    } else {
        $success = true; // ຖືວ່າຈ່າຍແລ້ວ
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="py-16 bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 p-8 space-y-6">
        
        <?php if ($success): ?>
            <!-- ຜົນການຊຳລະເງິນສຳເລັດ (Successful simulation) -->
            <div class="text-center space-y-4 py-6">
                <div class="w-20 h-20 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 font-serif-lao"><?php echo t('order_placed_success'); ?></h2>
                <p class="text-sm text-gray-500 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo' 
                        ? 'ລະບົບໄດ້ຮັບຍອດຊຳລະເງິນ ແລະ ສົ່ງອໍເດີ້ຂອງທ່ານໄປຫ້ອງຄົວແລ້ວ. ກະລຸນາຮັບເຄື່ອງດື່ມຕາມເລກບິນດ້ານລຸ່ມ:' 
                        : 'We have received your payment. Your order is sent to the kitchen. Please pick up using the order ID:'; ?>
                </p>
                <div class="p-4 bg-burgundy-50 border border-burgundy-100 rounded-xl">
                    <span class="text-xs text-burgundy-700 font-bold block uppercase tracking-wider"><?php echo $current_lang === 'lo' ? 'ເລກບິນອໍເດີ້' : 'Order ID'; ?></span>
                    <span class="text-2xl font-extrabold text-burgundy-700 font-mono mt-1 block">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="pt-4 flex flex-col gap-2">
                    <a href="menu.php" class="w-full btn-premium py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                        <?php echo $current_lang === 'lo' ? 'ໄປໜ້າເມນູ' : 'Back to Menu'; ?>
                    </a>
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                        <a href="account.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline">
                            <?php echo t('nav_account'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- ຟອມສະແກນ QR Code -->
            <div class="text-center space-y-4">
                <!-- BCEL Banner Mockup -->
                <div class="flex items-center justify-center space-x-2 bg-[#E2E8F0] p-3 rounded-xl border border-gray-200">
                    <!-- Red/Blue BCEL simulation representation -->
                    <span class="w-6 h-6 rounded bg-red-650 flex items-center justify-center text-[10px] font-extrabold text-white">B</span>
                    <span class="text-xs font-bold text-gray-800 tracking-wide uppercase">BCEL OnePay Simulator</span>
                </div>
                
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Amount to Pay</p>
                    <h3 class="text-3xl font-extrabold text-burgundy-700 font-mono mt-1"><?php echo number_format($order['total_amount']); ?> LAK</h3>
                </div>

                <!-- Generated QR Code Container -->
                <div class="relative w-64 h-64 border-4 border-burgundy-700/10 rounded-2xl mx-auto p-4 flex flex-col items-center justify-center bg-white shadow-inner">
                    <!-- Visual representation of QR Code with LaoFe logo in center -->
                    <div class="grid grid-cols-5 gap-1 w-full h-full opacity-90 p-2">
                        <!-- Simulated QR Pattern -->
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-white"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-white"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-white"></div>
                        <div class="bg-white"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <!-- Logo Overlay in the center -->
                        <div class="bg-white rounded-lg flex items-center justify-center p-0.5 shadow-md border col-span-2 row-span-2 transform scale-110">
                            <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-8 h-8 rounded-full object-contain">
                        </div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-white"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-white"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                        <div class="bg-gray-900 rounded-sm"></div>
                    </div>
                    <span class="absolute bottom-2 text-[9px] font-bold text-burgundy-700/50 uppercase tracking-widest">Scan to Pay</span>
                </div>

                <p class="text-xs text-gray-500 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ກະລຸນາສະແກນ QR ດ້ານເທິງຜ່ານແອັບ BCEL One ເພື່ອທົດລອງຊຳລະເງິນ.'
                        : 'Please scan the QR code above with BCEL One App to pay.'; ?>
                </p>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-red-700 text-xs font-semibold">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="payment.php?order_id=<?php echo $order_id; ?>" method="POST" class="pt-4 border-t border-gray-100">
                    <button type="submit" name="simulate_payment" class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md text-sm transition-all duration-300 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0114 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                        <span><?php echo $current_lang === 'lo' ? 'ທົດລອງຈ່າຍເງິນສຳເລັດ (Simulate Success)' : 'Simulate Successful Payment'; ?></span>
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
