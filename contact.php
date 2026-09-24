<?php
// contact.php - Warm Luxury & Bright Premium Contact Page
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/captcha.php';
require_once __DIR__ . '/includes/mailer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success_msg = $_SESSION['flash_success'] ?? '';
$error_msg   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $captcha_error = '';
    if (!verify_captcha_response($captcha_error, $current_lang)) {
        $_SESSION['flash_error'] = $captcha_error;
        header("Location: contact.php#contact-form");
        exit;
    }

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['flash_error'] = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃນຊ່ອງທີ່ມີເຄື່ອງໝາຍ * ໃຫ້ຄົບຖ້ວນ' : 'Please fill in all required fields marked with *';
        header("Location: contact.php#contact-form");
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = $current_lang === 'lo' ? 'ອີເມລບໍ່ຖືກຕ້ອງ' : 'Please enter a valid email address.';
        header("Location: contact.php#contact-form");
        exit;
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $email, $subject, $message]);
            
            // ແຈ້ງເຕືອນເຂົ້າອີເມລ Admin
            $mail_subject = "📥 [LaoFe Website] ຂໍ້ຄວາມຕິດຕໍ່ໃໝ່ຈາກ: " . $name;
            $mail_body = "
                <h3 style='color: #531321; margin-top: 0;'>ລາຍລະອຽດຂໍ້ຄວາມຕິດຕໍ່ (Contact Inquiry)</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr><td style='padding: 8px; font-weight: bold; width: 120px;'>ຊື່ຜູ້ຕິດຕໍ່:</td><td style='padding: 8px;'>" . htmlspecialchars($name) . "</td></tr>
                    <tr><td style='padding: 8px; font-weight: bold;'>ເບີໂທລະສັບ:</td><td style='padding: 8px;'>" . htmlspecialchars($phone ?: '-') . "</td></tr>
                    <tr><td style='padding: 8px; font-weight: bold;'>ອີເມລ:</td><td style='padding: 8px;'>" . htmlspecialchars($email) . "</td></tr>
                    <tr><td style='padding: 8px; font-weight: bold;'>ຫົວຂໍ້:</td><td style='padding: 8px;'>" . htmlspecialchars($subject ?: 'ສອບຖາມທົ່ວໄປ') . "</td></tr>
                    <tr><td style='padding: 8px; font-weight: bold; vertical-align: top;'>ເນື້ອຫາ:</td><td style='padding: 8px; background: #FAF7F2; border-radius: 8px;'>" . nl2br(htmlspecialchars($message)) . "</td></tr>
                </table>
            ";
            send_admin_notification($mail_subject, $mail_body);

            $_SESSION['flash_success'] = t('contact_success');
            header("Location: contact.php?status=success#contact-form");
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = ($current_lang === 'lo' ? 'ເກີດຂໍ້ຜິດພາດ: ' : 'Database error: ') . $e->getMessage();
            header("Location: contact.php#contact-form");
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Title Hero Header Section (Rich Burgundy & Gold Luxury) -->
<section class="relative pt-32 pb-16 bg-gradient-to-r from-[#1C050B] via-[#531321] to-[#1C050B] text-white text-center overflow-hidden border-b border-amber-400/20 shadow-2xl">
    <!-- Subtle Golden Radial Glow Effect -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-amber-500/15 via-transparent to-transparent pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10 space-y-3 font-sans-lao">
        <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-amber-300 text-xs font-bold tracking-widest uppercase font-sans-lao shadow-inner">
            ✦ LAOFE & BEER CONTACT ✦
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold font-sans-lao tracking-wide text-white leading-tight drop-shadow-md">
            <?php echo t('contact_title'); ?>
        </h1>
        <p class="text-amber-100/90 font-light text-sm md:text-base max-w-2xl mx-auto font-sans-lao leading-relaxed">
            <?php echo t('contact_sub'); ?>
        </p>
        <div class="w-16 h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent mx-auto pt-2 rounded-full opacity-90"></div>
    </div>
</section>

<!-- Content Section (Warm Cream & Pure White Premium Cards) -->
<section class="py-16 md:py-20 bg-[#EFEADF] px-6 md:px-12 font-sans-lao">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 md:gap-12 items-start">
        
        <!-- Left Side (5 Cols): Contact Information & Direct Channels -->
        <div class="lg:col-span-5 space-y-6">
            <div class="space-y-3">
                <span class="text-xs font-extrabold text-burgundy-700 uppercase tracking-wider font-sans-lao">
                    <?php echo t('contact_badge'); ?>
                </span>
                <h2 class="text-3xl font-bold text-[#2C1810] font-sans-lao">
                    <?php echo t('contact_info'); ?>
                </h2>
                <p class="text-sm text-[#6E584E] font-light leading-relaxed font-sans-lao">
                    <?php echo t('contact_desc'); ?>
                </p>
            </div>

            <!-- Contact Detail Cards -->
            <div class="space-y-4 pt-2 font-sans-lao">
                
                <!-- Phone Card -->
                <div class="bg-[#FAF7F2] border border-[#EBE4D8] rounded-2xl p-5 shadow-md shadow-[#2C1810]/5 flex items-center space-x-4 group hover:border-[#D97706]/50 hover:bg-white hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-burgundy-50 border border-burgundy-100 text-burgundy-700 flex items-center justify-center shrink-0 shadow-sm group-hover:bg-amber-400 group-hover:text-gray-950 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-[#9E8C82] font-semibold uppercase tracking-wider font-sans-lao">Hotline Phone Number</p>
                        <a href="tel:+8562091111104" class="text-lg font-bold text-burgundy-900 font-sans-lao hover:text-[#D97706] transition-colors">
                            +856 20 91 111 104
                        </a>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="bg-[#FAF7F2] border border-[#EBE4D8] rounded-2xl p-5 shadow-md shadow-[#2C1810]/5 flex items-center space-x-4 group hover:border-[#D97706]/50 hover:bg-white hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-burgundy-50 border border-burgundy-100 text-burgundy-700 flex items-center justify-center shrink-0 shadow-sm group-hover:bg-amber-400 group-hover:text-gray-950 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-[#9E8C82] font-semibold uppercase tracking-wider font-sans-lao">Support Email Address</p>
                        <a href="mailto:info@laofecafe.com" class="text-lg font-bold text-[#2C1810] hover:text-[#D97706] transition-colors font-sans-lao">
                            info@laofecafe.com
                        </a>
                    </div>
                </div>

                <!-- Working Hours & Main Branch Info -->
                <div class="bg-[#FAF7F2] border border-[#EBE4D8] rounded-2xl p-5 shadow-md shadow-[#2C1810]/5 space-y-2.5 font-sans-lao">
                    <div class="flex items-center gap-2 text-burgundy-800 font-bold text-xs font-sans-lao">
                        <span>🕒</span>
                        <span><?php echo $current_lang === 'lo' ? 'ເວລາເປີດ-ປິດບໍລິການ:' : 'Opening Hours:'; ?></span>
                        <span class="text-[#2C1810] font-sans-lao">07:00 - 23:00 ໂມງ (ທຸກໆວັນ)</span>
                    </div>
                    <div class="flex items-start gap-2 text-xs text-[#6E584E] font-sans-lao font-light leading-relaxed border-t border-[#EBE4D8] pt-2">
                        <span class="text-[#D97706] mt-0.5">📍</span>
                        <span><?php echo $current_lang === 'lo' ? 'ສາຂາຫຼັກ: ຖະໜົນສີຫອມ / ນ້ຳພຸ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ' : 'Main Branch: Sihom / Namphou Rd, Chanthabouly District, Vientiane Capital'; ?></span>
                    </div>
                </div>

                <!-- WhatsApp Direct Action Button -->
                <div class="pt-2">
                    <a href="https://wa.me/8562091111104" target="_blank" class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-md shadow-emerald-600/20 hover:scale-[1.02] font-sans-lao text-xs sm:text-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.488 1.459 5.416 1.46 5.72 0 10.375-4.65 10.379-10.366.002-2.77-1.077-5.373-3.037-7.338-1.958-1.965-4.563-3.048-7.34-3.049-5.73 0-10.38 4.651-10.383 10.37-.001 1.93.504 3.812 1.461 5.418l-.96 3.502 3.584-.94z"/></svg>
                        <span><?php echo $current_lang === 'lo' ? 'ແຊັດຕິດຕໍ່ຜ່ານ WhatsApp ທັນທີ' : 'Chat via WhatsApp Hotline'; ?></span>
                    </a>
                </div>

            </div>

            <!-- Official Social Media Channels -->
            <div class="pt-4 border-t border-[#EBE4D8] space-y-3 font-sans-lao">
                <p class="text-xs font-bold text-[#6E584E] uppercase tracking-wider font-sans-lao">
                    <?php echo $current_lang === 'lo' ? 'ຕິດຕາມ LaoFe & Beer ຜ່ານ Social Media' : 'Follow Us on Social Media'; ?>
                </p>
                
                <div class="flex items-center space-x-3 pt-1">
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/laofe_beer?stkn=OG4wNGh0YmoxNWsw&utm_source=qr" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white flex items-center justify-center transition-all duration-300 shadow-md hover:scale-110" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/profile.php?id=61577332485813" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-[#1877F2] text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@laofecafe_sihorm11?_r=1&_t=ZS-99m4pEa9dQd" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-black text-white hover:scale-110 flex items-center justify-center transition-all duration-300 shadow-md" title="TikTok">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#00f2fe" transform="translate(-0.8, -0.8)" />
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#ff0050" transform="translate(0.8, 0.8)" />
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.63 4.18 1.09 1.15 2.58 1.83 4.15 1.94v3.83c-1.74-.07-3.41-.75-4.69-1.92-.12-.11-.23-.23-.34-.35v6.52c0 1.94-.56 3.84-1.61 5.43-1.46 2.08-3.9 3.32-6.43 3.36-2.58.07-5.11-1.07-6.72-3.13-1.68-2.22-2.12-5.26-1.12-7.87 1.01-2.55 3.52-4.26 6.27-4.39 1.48-.07 2.97.35 4.19 1.2V4.9c-.83-.43-1.75-.68-2.7-.73-2.02-.12-4.04.77-5.27 2.37C2.9 8.23 2.69 10.5 3.31 12.59c.64 2.1 2.35 3.73 4.5 4.3 2.15.54 4.5-.04 6.13-1.54 1.56-1.54 2.21-3.87 1.68-6.01l-.01-9.32z" fill="#ffffff" />
                        </svg>
                    </a>

                    <!-- YouTube -->
                    <a href="https://youtube.com/channel/UChlinl63V_DeLIwNb0Kho6g?si=zzYYlPINBv9Ii7wM" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-[#FF0000] text-white flex items-center justify-center transition-all duration-300 shadow-md hover:scale-110" title="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>

                    <!-- Telegram -->
                    <a href="https://t.me/laofe_beer" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-[#26A5E4] text-white flex items-center justify-center transition-all duration-300 shadow-md hover:scale-110" title="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm5.221 8.358c-.161.713-1.636 7.636-2.38 11.23-.316 1.521-.874 1.802-1.411 1.85-.929.083-1.635-.494-2.535-1.085-1.408-.924-2.203-1.498-3.569-2.399-1.579-1.042-.556-1.615.344-2.55.235-.245 4.316-3.957 4.394-4.293.01-.042.018-.2-.075-.282-.093-.082-.228-.054-.326-.032-.139.031-2.355 1.498-6.649 4.4-.628.432-1.197.644-1.707.633-.563-.013-1.644-.319-2.448-.58-.987-.32-1.773-.49-1.704-1.034.036-.283.428-.574 1.176-.873 4.606-2.007 7.676-3.332 9.21-3.974 4.385-1.833 5.295-2.152 5.889-2.162.131-.002.423.031.613.185.16.13.204.307.225.431.021.124.038.384.021.597z"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Side (7 Cols): Bright, Clean, Premium Pure-White Form Card -->
        <div id="contact-form" class="lg:col-span-7 bg-white border border-[#EBE4D8] rounded-3xl p-8 md:p-10 shadow-xl shadow-[#2C1810]/5 space-y-6 font-sans-lao">
            
            <div>
                <h3 class="text-2xl md:text-3xl font-bold text-[#2C1810] font-sans-lao flex items-center gap-2">
                    <span>💬</span>
                    <span><?php echo t('contact_form_title'); ?></span>
                </h3>
                <div class="w-14 h-1 bg-[#D97706] mt-2 rounded-full"></div>
            </div>
            
            <?php if (!empty($success_msg)): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-800 text-xs md:text-sm font-sans-lao">
                    <p class="font-bold flex items-center gap-2">
                        <span>✅</span>
                        <span><?php echo $success_msg; ?></span>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-800 text-xs md:text-sm font-sans-lao">
                    <p class="font-bold flex items-center gap-2">
                        <span>⚠️</span>
                        <span><?php echo $error_msg; ?></span>
                    </p>
                </div>
            <?php endif; ?>

            <form action="contact.php#contact-form" method="POST" class="space-y-5 font-sans-lao">
                <div>
                    <label for="name" class="block text-xs font-bold text-[#4A3B34] uppercase font-sans-lao tracking-wider mb-2">
                        <?php echo t('contact_name'); ?> *
                    </label>
                    <input type="text" name="name" id="name" required placeholder="<?php echo $current_lang === 'lo' ? 'ປ້ອນຊື່ ແລະ ນາມສະກຸນຂອງທ່ານ' : 'Enter your full name'; ?>" class="w-full bg-[#FAF7F2] border border-[#E2D9CC] text-[#2C1810] placeholder-[#9E8C82] rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm transition-all shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-xs font-bold text-[#4A3B34] uppercase font-sans-lao tracking-wider mb-2">
                            <?php echo t('contact_phone'); ?>
                        </label>
                        <input type="text" name="phone" id="phone" placeholder="<?php echo $current_lang === 'lo' ? 'ເບີໂທລະສັບຕິດຕໍ່ (ຖ້າມີ)' : 'Phone number'; ?>" class="w-full bg-[#FAF7F2] border border-[#E2D9CC] text-[#2C1810] placeholder-[#9E8C82] rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm transition-all shadow-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#4A3B34] uppercase font-sans-lao tracking-wider mb-2">
                            <?php echo t('contact_email'); ?> *
                        </label>
                        <input type="email" name="email" id="email" required placeholder="your.email@domain.com" class="w-full bg-[#FAF7F2] border border-[#E2D9CC] text-[#2C1810] placeholder-[#9E8C82] rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm transition-all shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-xs font-bold text-[#4A3B34] uppercase font-sans-lao tracking-wider mb-2">
                        <?php echo t('contact_subject'); ?>
                    </label>
                    <input type="text" name="subject" id="subject" placeholder="<?php echo $current_lang === 'lo' ? 'ຫົວຂໍ້ / Subject' : 'Subject of inquiry'; ?>" class="w-full bg-[#FAF7F2] border border-[#E2D9CC] text-[#2C1810] placeholder-[#9E8C82] rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm transition-all shadow-sm">
                </div>

                <div>
                    <label for="message" class="block text-xs font-bold text-[#4A3B34] uppercase font-sans-lao tracking-wider mb-2">
                        <?php echo t('contact_msg'); ?> *
                    </label>
                    <textarea name="message" id="message" rows="4" required placeholder="<?php echo $current_lang === 'lo' ? 'ພິມຂໍ້ຄວາມຂອງທ່ານຢູ່ບ່ອນນີ້...' : 'Write your message details here...'; ?>" class="w-full bg-[#FAF7F2] border border-[#E2D9CC] text-[#2C1810] placeholder-[#9E8C82] rounded-xl px-4 py-3.5 focus:outline-none focus:bg-white focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm transition-all shadow-sm"></textarea>
                </div>

                <!-- Anti-Spam Captcha Protection -->
                <?php render_captcha_widget($current_lang); ?>

                <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-[#D97706] via-[#F59E0B] to-[#D97706] text-[#1F100A] font-extrabold text-sm tracking-wide shadow-[0_8px_20px_rgba(217,119,6,0.3)] hover:shadow-[0_12px_28px_rgba(217,119,6,0.45)] hover:scale-[1.01] transition-all duration-300 font-sans-lao flex items-center justify-center gap-2">
                    <span><?php echo t('contact_submit'); ?></span>
                    <span>➔</span>
                </button>
            </form>
        </div>

    </div>
</section>

<!-- Interactive Google Maps Section (Warm & Clean Style) -->
<section class="py-16 bg-white px-6 md:px-12 border-t border-[#EBE4D8] font-sans-lao">
    <div class="max-w-6xl mx-auto space-y-8">
        <div class="text-center space-y-2">
            <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider font-sans-lao">
                LOCATION & NAVIGATION
            </span>
            <h2 class="text-2xl md:text-3xl font-bold text-[#2C1810] font-sans-lao flex items-center justify-center gap-2">
                <span>📍</span>
                <span><?php echo $current_lang === 'lo' ? 'ແຜນທີ່ທີ່ຕັ້ງ LaoFe & Beer (ສາຂາສີຫອມ/ນ້ຳພຸ)' : 'LaoFe & Beer Location Map (Sihom)'; ?></span>
            </h2>
            <div class="w-16 h-1 bg-burgundy-700 mx-auto rounded-full"></div>
        </div>

        <!-- Map Frame Container -->
        <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-200 h-[450px] relative">
            <iframe 
                src="https://maps.google.com/maps?q=17.966801,102.606311&hl=<?php echo $current_lang; ?>&z=17&output=embed" 
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
            <a href="https://www.google.com/maps?q=17.966801,102.606311" target="_blank" class="px-8 py-3.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-sm shadow-lg hover:scale-105 transition-all duration-300 font-sans-lao inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span><?php echo $current_lang === 'lo' ? 'ນຳທາງຜ່ານ Google Maps App' : 'Open Navigation in Google Maps'; ?></span>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
