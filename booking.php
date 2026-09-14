<?php
// booking.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$branches = [];
try {
    $branches = $pdo->query("SELECT * FROM branches ORDER BY id ASC")->fetchAll();
} catch (\Exception $e) {
    // error
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = intval($_POST['branch_id'] ?? 0);
    $booking_name = trim($_POST['booking_name'] ?? '');
    $booking_phone = trim($_POST['booking_phone'] ?? '');
    $booking_email = trim($_POST['booking_email'] ?? '');
    $booking_date = trim($_POST['booking_date'] ?? '');
    $booking_time = trim($_POST['booking_time'] ?? '');
    $guest_count = intval($_POST['guest_count'] ?? 1);
    $table_zone = $_POST['table_zone'] ?? 'Standard';

    // ຄຳນວນຄ່າມັດຈຳ
    $deposit_amount = 50000;
    if ($table_zone === 'Lounge') {
        $deposit_amount = 100000;
    } elseif ($table_zone === 'VIP') {
        $deposit_amount = 200000;
    }

    if ($branch_id <= 0 || empty($booking_name) || empty($booking_phone) || empty($booking_date) || empty($booking_time) || $guest_count <= 0) {
        $error_msg = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນທຸກຊ່ອງທີ່ມີເຄື່ອງໝາຍ *' : 'Please fill in all required fields marked with *';
    } else {
        try {
            $user_id = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true ? $_SESSION['user_id'] : null;

            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, branch_id, booking_name, booking_phone, booking_email, booking_date, booking_time, guest_count, table_zone, deposit_amount, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Unpaid', 'Pending')");
            $stmt->execute([$user_id, $branch_id, $booking_name, $booking_phone, $booking_email, $booking_date, $booking_time, $guest_count, $table_zone, $deposit_amount]);
            
            $booking_id = $pdo->lastInsertId();

            // Redirect ໄປຫາໜ້າຊຳລະເງິນມັດຈຳ
            header("Location: booking_payment.php?booking_id=" . $booking_id);
            exit;
        } catch (\Exception $e) {
            $error_msg = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດ: ' : 'Database error: ') . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Title Header Section -->
<section class="pt-32 pb-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('nav_booking'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo $current_lang === 'lo' ? 'ຈອງພື້ນທີ່ພັກຜ່ອນສຸດພິເສດຂອງທ່ານລ່ວງໜ້າ' : 'Reserve your premium table or lounge spot in advance'; ?></p>
    </div>
</section>

<!-- Booking Form & Info -->
<section class="py-20 bg-gray-50 px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Zones Description (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <h3 class="text-xl font-bold text-gray-950 font-serif-lao border-b pb-3 flex items-center space-x-2">
                <span class="w-2.5 h-6 bg-burgundy-700 rounded-full"></span>
                <span>ໂຊນພື້ນທີ່ບໍລິການ & ຄ່າມັດຈຳ</span>
            </h3>

            <!-- Standard -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                <div class="flex justify-between items-start">
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-[10px] font-bold uppercase rounded-full tracking-wider">Standard Zone</span>
                    <span class="text-xs font-bold text-burgundy-700 font-mono">ມັດຈຳ: 50,000 LAK</span>
                </div>
                <h4 class="font-bold text-gray-900 text-base mt-2"><?php echo $current_lang === 'lo' ? 'ໂຊນທົ່ວໄປ (Indoor / Outdoor)' : 'Standard General Zone'; ?></h4>
                <p class="text-xs text-gray-500 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ພື້ນທີ່ນັ່ງສະບາຍໆເໝາະສຳລັບການດື່ມກາເຟຍາມເວັນ ຫຼື ພົບປະທົ່ວໄປ, ມີທັງໂຊນຫ້ອງແອ ແລະ ໂຊນຮັບລົມທຳມະຊາດ.'
                        : 'Perfect spot for quick coffee catchups, featuring both air-conditioned indoor seating and open-air outdoor options.'; ?>
                </p>
            </div>

            <!-- Lounge -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                <div class="flex justify-between items-start">
                    <span class="px-3 py-1 bg-burgundy-50 text-burgundy-700 text-[10px] font-bold uppercase rounded-full tracking-wider">Lounge Zone</span>
                    <span class="text-xs font-bold text-burgundy-700 font-mono">ມັດຈຳ: 100,000 LAK</span>
                </div>
                <h4 class="font-bold text-gray-900 text-base mt-2"><?php echo $current_lang === 'lo' ? 'ໂຊນເລົາຈ໌ (Premium Lounge)' : 'Premium Lounge Zone'; ?></h4>
                <p class="text-xs text-gray-500 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ໂຊຟານຸ້ມລະດັບພຣີມ່ຽມ ບັນຍາກາດອົບອຸ່ນ ພ້ອມດົນຕີເບົາໆ ເໝາະສຳລັບການຜ່ອນຄາຍກັບເຄື່ອງດື່ມໃນຍາມຄ່ຳຄືນ.'
                        : 'Cozy premium sofas with warm lighting and chill background music, ideal for evening relaxation with cocktails or craft beer.'; ?>
                </p>
            </div>

            <!-- VIP Room -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                <div class="flex justify-between items-start">
                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-600 text-[10px] font-bold uppercase rounded-full tracking-wider">VIP Zone</span>
                    <span class="text-xs font-bold text-burgundy-700 font-mono">ມັດຈຳ: 200,000 LAK</span>
                </div>
                <h4 class="font-bold text-gray-900 text-base mt-2"><?php echo $current_lang === 'lo' ? 'ຫ້ອງ VIP ສ່ວນຕົວ' : 'Private VIP Room'; ?></h4>
                <p class="text-xs text-gray-500 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ຫ້ອງສ່ວນຕົວທີ່ເປັນເອກະລາດ ພ້ອມອຸປະກອນອຳນວຍຄວາມສະດວກ ເໝາະສຳລັບການຈັດປະຊຸມ, ງານລ້ຽງສັງສັນ ຫຼື ຄວາມເປັນສ່ວນຕົວສູງ.'
                        : 'Fully private enclosed rooms, equipped for business meetings, birthday parties, or exclusive small group gatherings.'; ?>
                </p>
            </div>
        </div>

        <!-- Right: Booking Form (7 cols) -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 lg:col-span-7">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 font-serif-lao">ຂໍ້ມູນການຈອງໂຕະ</h3>

            <?php if (!empty($error_msg)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 text-red-700 text-sm font-semibold">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form action="booking.php" method="POST" class="space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Branch -->
                    <div>
                        <label for="branch_id" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ເລືອກສາຂາ *</label>
                        <select name="branch_id" id="branch_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                            <option value=""><?php echo $current_lang === 'lo' ? '-- ເລືອກສາຂາ --' : '-- Select Branch --'; ?></option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?php echo $branch['id']; ?>"><?php echo htmlspecialchars(td($branch, 'name')); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Zone -->
                    <div>
                        <label for="table_zone" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ເລືອກໂຊນໂຕະ *</label>
                        <select name="table_zone" id="table_zone" onchange="updateDepositLabel()" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                            <option value="Standard">Standard (ມັດຈຳ 50,000 LAK)</option>
                            <option value="Lounge">Lounge (ມັດຈຳ 100,000 LAK)</option>
                            <option value="VIP">VIP Room (ມັດຈຳ 200,000 LAK)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Date -->
                    <div>
                        <label for="booking_date" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ວັນທີຈອງ *</label>
                        <input type="date" name="booking_date" id="booking_date" min="<?php echo date('Y-m-d'); ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <!-- Time (Open from 06:30 - 00:00, booking slots adjusted) -->
                    <div>
                        <label for="booking_time" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ເວລາ *</label>
                        <select name="booking_time" id="booking_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                            <option value="07:00">07:00</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>
                            <option value="21:00">21:00</option>
                            <option value="22:00">22:00</option>
                            <option value="23:00">23:00</option>
                        </select>
                    </div>

                    <!-- Guests -->
                    <div>
                        <label for="guest_count" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ຈຳນວນຄົນ *</label>
                        <input type="number" name="guest_count" id="guest_count" min="1" value="2" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <h4 class="text-sm font-bold text-gray-800">ຂໍ້ມູນຜູ້ຕິດຕໍ່</h4>
                    
                    <div>
                        <label for="booking_name" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ຊື່ ແລະ ນາມສະກຸນ *</label>
                        <input type="text" name="booking_name" id="booking_name" required value="<?php echo htmlspecialchars($_SESSION['user_fullname'] ?? ''); ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="booking_phone" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ເບີໂທລະສັບຕິດຕໍ່ *</label>
                            <input type="text" name="booking_phone" id="booking_phone" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                        </div>
                        <div>
                            <label for="booking_email" class="block text-xs font-semibold text-gray-700 uppercase mb-1">ອີເມລ *</label>
                            <input type="email" name="booking_email" id="booking_email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                        </div>
                    </div>
                </div>

                <!-- Required Deposit visual label -->
                <div class="p-4 bg-burgundy-50 border border-burgundy-100 rounded-xl flex justify-between items-center shadow-inner">
                    <span class="text-xs text-burgundy-800 font-bold uppercase"><?php echo $current_lang === 'lo' ? 'ຄ່າມັດຈຳທີ່ຕ້ອງຊຳລະ' : 'Required Deposit'; ?>:</span>
                    <span id="deposit-display" class="text-base font-extrabold text-burgundy-700 font-mono">50,000 LAK</span>
                </div>

                <button type="submit" class="w-full btn-premium py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md">
                    ຢືນຢັນ ແລະ ໄປໜ້າຊຳລະມັດຈຳ (Confirm & Pay Deposit)
                </button>
            </form>
        </div>

    </div>
</section>

<script>
function updateDepositLabel() {
    var zone = document.getElementById('table_zone').value;
    var display = document.getElementById('deposit-display');
    if (zone === 'Standard') {
        display.innerText = '50,000 LAK';
    } else if (zone === 'Lounge') {
        display.innerText = '100,000 LAK';
    } else if (zone === 'VIP') {
        display.innerText = '200,000 LAK';
    }
}
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
