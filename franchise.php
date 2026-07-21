<?php
// franchise.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $location_preference = trim($_POST['location_preference'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($full_name) || empty($phone) || empty($email) || empty($location_preference)) {
        $error_msg = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນ' : 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = $current_lang === 'lo' ? 'ອີເມລບໍ່ຖືກຕ້ອງ' : 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO franchise_applications (full_name, phone, email, location_preference, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$full_name, $phone, $email, $location_preference, $message]);
            $success_msg = t('fran_success');
        } catch (\Exception $e) {
            $error_msg = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດ: ' : 'Database error: ') . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Title Header Section -->
<section class="py-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('fran_title'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo t('fran_sub'); ?></p>
    </div>
</section>

<!-- Content Section with details and Form -->
<section class="py-20 bg-gray-50 px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
        
        <!-- Left: Details and Franchise Benefits -->
        <div class="space-y-8">
            <div class="space-y-4">
                <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider">Business Opportunity</span>
                <h2 class="text-3xl font-bold text-gray-900 font-serif-lao">ເປັນຫຍັງຕ້ອງເລືອກ LaoFe & Beer?</h2>
                <p class="text-gray-600 font-light leading-relaxed">
                    LaoFe & Beer ແມ່ນແບຣນກາເຟ ແລະ ບາ ຍຸກໃໝ່ທີ່ໄດ້ຮັບຄວາມນິຍົມສູງ ແລະ ມີການເຕີບໂຕຢ່າງຕໍ່ເນື່ອງ. ພວກເຮົາສະເໜີໂອກາດທາງທຸລະກິດທີ່ໝັ້ນຄົງ ແລະ ລະບົບສະໜັບສະໜູນທີ່ຄົບວົງຈອນ.
                </p>
            </div>

            <!-- Benefits Checklist -->
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 rounded-full bg-burgundy-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">ແນວຄິດຮ້ານແບບ 2-in-1 (ກາເຟ & ບາ)</h4>
                        <p class="text-sm text-gray-500 font-light mt-1">ສ້າງລາຍຮັບໄດ້ທັງກາງເວັນ (ກາເຟ/ເຄື່ອງດື່ມ) ແລະ ກາງຄືນ (ບາ/ອາຫານປະຍຸກ).</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 rounded-full bg-burgundy-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">ວັດຖຸດິບ ແລະ ສູດສະເພາະຕົວ</h4>
                        <p class="text-sm text-gray-500 font-light mt-1">ການສະໜອງແກ່ນກາເຟ ແລະ ວັດຖຸດິບຄຸນນະພາບສູງ ພ້ອມສູດເຄື່ອງດື່ມທີ່ເປັນເອກະລັກ.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 rounded-full bg-burgundy-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">ລະບົບຫຼັງບ້ານ ແລະ ການຕະຫຼາດ</h4>
                        <p class="text-sm text-gray-500 font-light mt-1">ມີລະບົບຈັດການຮ້ານທີ່ທັນສະໄໝ ແລະ ທີມງານການຕະຫຼາດມືອາຊີບຄອຍຊ່ວຍເຫຼືອ.</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-white border border-burgundy-700/10 rounded-2xl">
                <p class="text-sm text-burgundy-700 font-medium">
                    <?php echo t('fran_desc'); ?>
                </p>
            </div>
        </div>

        <!-- Right: Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 font-serif-lao"><?php echo t('fran_form_title'); ?></h3>
            
            <?php if (!empty($success_msg)): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                    <p class="text-green-700 text-sm font-medium"><?php echo $success_msg; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <p class="text-red-700 text-sm font-medium"><?php echo $error_msg; ?></p>
                </div>
            <?php endif; ?>

            <form action="franchise.php" method="POST" class="space-y-5">
                <div>
                    <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('fran_name'); ?> *</label>
                    <input type="text" name="full_name" id="full_name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('fran_phone'); ?> *</label>
                        <input type="text" name="phone" id="phone" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('fran_email'); ?> *</label>
                        <input type="email" name="email" id="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                    </div>
                </div>

                <div>
                    <label for="location_preference" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('fran_loc'); ?> *</label>
                    <input type="text" name="location_preference" id="location_preference" required placeholder="ເຊັ່ນ: ແຂວງສະຫວັນນະເຂດ, ວຽງຈັນ, ..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                </div>

                <div>
                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('fran_msg'); ?></label>
                    <textarea name="message" id="message" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700"></textarea>
                </div>

                <button type="submit" class="w-full btn-premium py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-lg transition-colors duration-300 shadow-md">
                    <?php echo t('fran_submit'); ?>
                </button>
            </form>
        </div>

    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
