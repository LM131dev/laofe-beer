<?php
// contact.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error_msg = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃນຊ່ອງທີ່ມີເຄື່ອງໝາຍ * ໃຫ້ຄົບຖ້ວນ' : 'Please fill in all required fields marked with *';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = $current_lang === 'lo' ? 'ອີເມລບໍ່ຖືກຕ້ອງ' : 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $email, $subject, $message]);
            $success_msg = t('contact_success');
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
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('contact_title'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo t('contact_sub'); ?></p>
    </div>
</section>

<!-- Content Section -->
<section class="py-20 bg-gray-50 px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
        
        <!-- Left Side: Contact Information & Direct Buttons -->
        <div class="space-y-8">
            <div class="space-y-4">
                <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider">ຕິດຕໍ່ພວກເຮົາ</span>
                <h2 class="text-3xl font-bold text-gray-900 font-serif-lao"><?php echo t('contact_info'); ?></h2>
                <p class="text-gray-600 font-light leading-relaxed">
                    ພວກເຮົາຍິນດີຮັບຟັງທຸກຄຳຄິດເຫັນ, ຂໍ້ສະເໜີແນະ ຫຼື ຄຳຖາມຈາກທ່ານ. ທ່ານສາມາດຕິດຕໍ່ຫາພວກເຮົາໂດຍກົງຜ່ານຊ່ອງທາງຕິດຕໍ່ຫຼັກ ຫຼື ສົ່ງຂໍ້ຄວາມຜ່ານຟອມທາງຂ້າງໄດ້.
                </p>
            </div>

            <!-- Contact detail list -->
            <div class="space-y-6 pt-4">
                <!-- Phone -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Hotline Phone Number</p>
                        <p class="text-lg font-bold text-gray-900">+856 20 95 555 094</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Support Email</p>
                        <p class="text-lg font-bold text-gray-900">info@laofecafe.com</p>
                    </div>
                </div>

                <!-- WhatsApp Direct Action -->
                <div class="pt-6">
                    <a href="https://wa.me/8562095555094" target="_blank" class="inline-flex items-center justify-center px-8 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-full transition-colors duration-300 shadow-md">
                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.488 1.459 5.416 1.46 5.72 0 10.375-4.65 10.379-10.366.002-2.77-1.077-5.373-3.037-7.338-1.958-1.965-4.563-3.048-7.34-3.049-5.73 0-10.38 4.651-10.383 10.37-.001 1.93.504 3.812 1.461 5.418l-.96 3.502 3.584-.94z"/></svg>
                        <span>Chat via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side: Contact Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 font-serif-lao"><?php echo t('contact_form_title'); ?></h3>
            
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

            <form action="contact.php" method="POST" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('contact_name'); ?> *</label>
                    <input type="text" name="name" id="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('contact_phone'); ?></label>
                        <input type="text" name="phone" id="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('contact_email'); ?> *</label>
                        <input type="email" name="email" id="email" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('contact_subject'); ?></label>
                    <input type="text" name="subject" id="subject" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700">
                </div>

                <div>
                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-1"><?php echo t('contact_msg'); ?> *</label>
                    <textarea name="message" id="message" rows="4" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:border-burgundy-700"></textarea>
                </div>

                <button type="submit" class="w-full btn-premium py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-lg transition-colors duration-300 shadow-md">
                    <?php echo t('contact_submit'); ?>
                </button>
            </form>
        </div>

    </div>
</section>

<!-- Interactive Google Maps Section -->
<section class="py-16 bg-white px-6 md:px-12 border-t border-gray-100">
    <div class="max-w-6xl mx-auto space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider">Location Map</span>
            <h2 class="text-3xl font-bold text-gray-900 font-serif-lao">
                <?php echo $current_lang === 'lo' ? 'ແຜນທີ່ທີ່ຕັ້ງ LaoFe & Beer (ສີຫອມ)' : 'LaoFe & Beer Location Map (Sihom)'; ?>
            </h2>
            <div class="w-16 h-1 bg-burgundy-700 mx-auto"></div>
        </div>

        <div class="rounded-3xl overflow-hidden shadow-2xl border border-gray-200 h-[450px] relative">
            <iframe 
                src="https://maps.google.com/maps?q=17.96681162156752,102.60627919999999&hl=<?php echo $current_lang; ?>&z=17&output=embed" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="LaoFe & Beer Location Map">
            </iframe>
        </div>
        
        <div class="text-center pt-2">
            <a href="https://www.google.com/maps?q=17.96681162156752,102.60627919999999" target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 bg-burgundy-700 text-white rounded-full font-bold text-sm hover:bg-burgundy-800 transition-all duration-300 shadow-lg hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span><?php echo $current_lang === 'lo' ? 'ນຳທາງຜ່ານ Google Maps' : 'Open in Google Maps App'; ?></span>
            </a>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
