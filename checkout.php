<?php
// checkout.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart = $_SESSION['cart'] ?? [];
$cart_total = 0;
foreach ($cart as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart)) {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'Cash';
    $notes = trim($_POST['notes'] ?? '');

    if (empty($fullname) || empty($phone)) {
        $error_msg = $current_lang === 'lo' ? 'ກະລຸນາກອກຊື່ ແລະ ເບີໂທລະສັບຕິດຕໍ່.' : 'Please enter your name and phone number.';
    } else {
        try {
            $pdo->beginTransaction();

            $user_id = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true ? $_SESSION['user_id'] : null;
            $points_earned = $user_id ? floor($cart_total / 10000) : 0;
            
            $table_number = $_POST['table_number'] ?? ($_SESSION['table_number'] ?? '01');
            $order_type = $_POST['order_type'] ?? 'dine_in_qr';
            
            // ບັນທຶກອໍເດີ້ (ລວມເບີໂຕະ Table Number ແລະ Order Type)
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, table_number, order_type, guest_name, total_amount, points_earned, status, payment_method, notes) VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?, ?)");
            $stmt->execute([$user_id, $table_number, $order_type, $fullname, $cart_total, $points_earned, $payment_method, $notes]);
            $order_id = $pdo->lastInsertId();

            // ບັນທຶກລາຍການອໍເດີ້
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price, notes) VALUES (?, ?, ?, ?, ?)");
            foreach ($cart as $item) {
                $item_note = $item['notes'] ?? '';
                $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price'], $item_note]);
            }

            $pdo->commit();

            // ລຶບຕະກ້າ
            $_SESSION['cart'] = [];

            // ຫາກຈ່າຍດ້ວຍ BCEL OnePay ໃຫ້ໄປໜ້າຊຳລະເງິນຈຳລອງ
            if ($payment_method === 'BCEL OnePay') {
                header("Location: payment.php?order_id=" . $order_id);
                exit;
            } else {
                // ຈ່າຍເງິນສົດໜ້າເຄົາເຕີ້
                $success_msg = $current_lang === 'lo' 
                    ? "ສົ່ງອໍເດີ້ສຳເລັດແລ້ວ! ກະລຸນາຊຳລະເງິນສົດທີ່ເຄົາເຕີ້. ລະຫັດອໍເດີ້ຂອງທ່ານແມ່ນ: #" . str_pad($order_id, 6, '0', STR_PAD_LEFT)
                    : "Order placed successfully! Please pay cash at the counter. Order ID: #" . str_pad($order_id, 6, '0', STR_PAD_LEFT);
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            $error_msg = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດ: ' : 'Database error: ') . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="py-12 bg-gray-50 min-h-screen px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao mb-8"><?php echo t('nav_checkout'); ?></h1>

        <?php if (!empty($success_msg)): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-2xl mb-8 space-y-4">
                <h3 class="text-green-800 text-lg font-bold"><?php echo t('order_placed_success'); ?></h3>
                <p class="text-green-700 text-sm"><?php echo $success_msg; ?></p>
                <div>
                    <a href="menu.php" class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition-all">
                        <?php echo $current_lang === 'lo' ? 'ກັບໄປໜ້າເມນູ' : 'Back to Menu'; ?>
                    </a>
                </div>
            </div>
        <?php else: ?>

            <?php if (!empty($error_msg)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 text-red-700 text-sm font-semibold">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($cart)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center space-y-4">
                    <p class="text-gray-400 font-light"><?php echo t('cart_empty'); ?></p>
                    <a href="menu.php" class="inline-flex items-center px-6 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-xl text-sm font-bold transition-all shadow-md">
                        <?php echo t('hero_cta'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left: Cart Items Details (7 cols) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-7 space-y-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-3 font-serif-lao"><?php echo t('checkout_cart_items'); ?></h3>
                        <div class="divide-y divide-gray-100">
                            <?php foreach ($cart as $id => $item): ?>
                                <div class="flex items-center justify-between py-4 space-x-4">
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="item image" class="w-16 h-16 object-cover rounded-xl border border-gray-100 shrink-0">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 text-sm"><?php echo htmlspecialchars($current_lang === 'lo' ? $item['name_lo'] : $item['name_en']); ?></h4>
                                        <p class="text-xs text-gray-400 font-mono mt-0.5"><?php echo number_format($item['price']); ?> LAK</p>
                                    </div>
                                    
                                    <!-- Qty controls -->
                                    <div class="flex items-center space-x-2.5">
                                        <a href="cart_action.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $item['quantity'] - 1; ?>" class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs font-bold font-mono">-</a>
                                        <span class="text-sm font-bold font-mono text-gray-800 w-4 text-center"><?php echo $item['quantity']; ?></span>
                                        <a href="cart_action.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $item['quantity'] + 1; ?>" class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs font-bold font-mono">+</a>
                                        <a href="cart_action.php?action=remove&id=<?php echo $id; ?>" class="text-red-500 hover:text-red-700 ml-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-lg font-bold text-gray-900">
                            <span><?php echo t('cart_total'); ?></span>
                            <span class="text-burgundy-700 font-mono"><?php echo number_format($cart_total); ?> LAK</span>
                        </div>
                    </div>

                    <!-- Right: Checkout Details and form (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Membership banner -->
                        <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                            <div class="bg-burgundy-700 text-white rounded-2xl p-5 shadow-md flex items-center justify-between border border-burgundy-800">
                                <div>
                                    <h4 class="font-bold text-sm">Member Status: <?php echo htmlspecialchars($user['fullname'] ?? $_SESSION['user_fullname']); ?></h4>
                                    <p class="text-xs text-white/80 mt-1"><?php echo t('checkout_points_earn'); ?>: <strong class="text-yellow-400 font-mono">+<?php echo floor($cart_total / 10000); ?> Pt</strong></p>
                                </div>
                                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-bold text-yellow-400"><?php echo htmlspecialchars($_SESSION['user_tier']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 flex flex-col space-y-3">
                                <div>
                                    <h4 class="font-bold text-yellow-800 text-sm"><?php echo $current_lang === 'lo' ? 'ສະສົມຄະແນນຫຼັກຈ່າຍ!' : 'Earn Loyalty Points!'; ?></h4>
                                    <p class="text-xs text-yellow-700 mt-1">
                                        <?php echo $current_lang === 'lo' ? 'ເຂົ້າສູ່ລະບົບ ຫຼື ລົງທະບຽນກ່ອນສັ່ງຊື້ ເພື່ອສະສົມຄະແນນຮັບເຄື່ອງດື່ມຟຣີ!' : 'Login or register before checking out to earn points for free beverages!'; ?>
                                    </p>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="login.php" class="px-4 py-1.5 bg-burgundy-700 text-white rounded-lg text-xs font-bold hover:bg-burgundy-800"><?php echo t('nav_login'); ?></a>
                                    <a href="register.php" class="px-4 py-1.5 bg-white text-burgundy-700 border border-burgundy-700 rounded-lg text-xs font-bold hover:bg-burgundy-50"><?php echo t('nav_register'); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Checkout Form -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-3 font-serif-lao mb-4"><?php echo t('checkout_receiver_info'); ?></h3>
                            <form action="checkout.php" method="POST" class="space-y-4">
                                <div>
                                    <label for="fullname" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                        <?php echo $current_lang === 'lo' ? 'ຊື່ຜູ້ຮັບ' : 'Receiver Name'; ?> *
                                    </label>
                                    <input type="text" name="fullname" id="fullname" required value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="phone" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                            <?php echo $current_lang === 'lo' ? 'ເບີໂທລະສັບ' : 'Phone Number'; ?> *
                                        </label>
                                        <input type="text" name="phone" id="phone" required value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                            <?php echo $current_lang === 'lo' ? 'ອີເມລ' : 'Email'; ?>
                                        </label>
                                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                        <?php echo $current_lang === 'lo' ? 'ວິທີການຊຳລະເງິນ' : 'Payment Method'; ?> *
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-burgundy-700">
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" name="payment_method" value="Cash" checked class="text-burgundy-700 focus:ring-burgundy-700">
                                                <span class="text-xs font-bold text-gray-700"><?php echo $current_lang === 'lo' ? 'ເງິນສົດ (Cash)' : 'Cash'; ?></span>
                                            </div>
                                        </label>
                                        <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-burgundy-700">
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" name="payment_method" value="BCEL OnePay" class="text-burgundy-700 focus:ring-burgundy-700">
                                                <span class="text-xs font-bold text-gray-700">BCEL One QR</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                                        <?php echo $current_lang === 'lo' ? 'ໝາຍເຫດເຖິງຫ້ອງຄົວ (ເຊັ່ນ: ຫວານໜ້ອຍ, ບໍ່ໃສ່ນ້ຳກ້ອນ...)' : 'Kitchen Notes (e.g., less sweet, no ice...)'; ?>
                                    </label>
                                    <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700"></textarea>
                                </div>

                                <button type="submit" class="w-full btn-premium py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl transition-all duration-300 shadow-md text-sm">
                                    <?php echo t('btn_checkout'); ?>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
