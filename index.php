<?php
// index.php - Luckin Coffee Singapore 1:1 Homepage Architecture (Fully Bilingual Lao & English)
if (isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
}

require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນໂປຣໂມຊັນຫຼ້າສຸດ 1 ລາຍການ
$promo = null;
try {
    $stmt_promo = $pdo->query("SELECT * FROM news WHERE is_promo = 1 ORDER BY publish_date DESC LIMIT 1");
    $promo = $stmt_promo->fetch();
} catch (\Exception $e) {}

// ດຶງເມນູຍອດນິຍົມ 6 ລາຍການ
$popular_items = [];
try {
    $stmt_pop = $pdo->query("SELECT * FROM menus WHERE is_popular = 1 ORDER BY id DESC LIMIT 6");
    $popular_items = $stmt_pop->fetchAll();
} catch (\Exception $e) {}

// ດຶງຂໍ້ມູນ Banner ຈາກຖານຂໍ້ມູນ (ສະເພາະ Home ຫຼື All)
$banners = [];
try {
    $stmt_banner = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND (target_page = 'home' OR target_page = 'all' OR target_page IS NULL OR target_page = '') ORDER BY sort_order ASC, id DESC");
    $banners = $stmt_banner->fetchAll();
} catch (\Exception $e) {}
?>

<!-- =========================================================
     1. LUCKIN HERO BANNER SWIPER (Full Bleed Luckin Carousel)
     ========================================================= -->
<section class="relative w-full h-[75vh] md:h-[85vh] bg-burgundy-900 overflow-hidden">
    <div class="swiper main-hero-swiper w-full h-full">
        <div class="swiper-wrapper">
            <?php if (!empty($banners)): ?>
                <?php foreach ($banners as $b): ?>
                    <div class="swiper-slide relative w-full h-full">
                        <img src="<?php echo htmlspecialchars($b['image_path']); ?>" alt="<?php echo htmlspecialchars(td($b, 'title')); ?>" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-burgundy-950/90 via-black/40 to-black/30"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="max-w-6xl mx-auto px-6 text-center text-white space-y-6">
                                <?php if (!empty(td($b, 'badge'))): ?>
                                    <span class="inline-block px-4 py-1.5 bg-white/90 text-burgundy-900 rounded-full text-xs font-extrabold uppercase tracking-widest shadow-lg font-serif-lao">
                                        <?php echo htmlspecialchars(td($b, 'badge')); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight font-serif-lao text-white drop-shadow-md sm:whitespace-nowrap">
                                    <?php echo htmlspecialchars(str_replace([' ໆ', 'ເບຍສົດ'], ['ໆ', 'ເບຍ' . "\xC2\xA0" . 'ສົດ'], td($b, 'title'))); ?>
                                </h1>
                                
                                <?php if (!empty(td($b, 'subtitle'))): ?>
                                    <p class="text-lg md:text-2xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed font-serif-lao drop-shadow">
                                        <?php echo nl2br(htmlspecialchars(td($b, 'subtitle'))); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                                    <?php if (!empty(td($b, 'btn1_text')) && !empty($b['btn1_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($b['btn1_link']); ?>" class="px-8 py-4 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full font-bold shadow-xl transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                            <?php echo htmlspecialchars(td($b, 'btn1_text')); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty(td($b, 'btn2_text')) && !empty($b['btn2_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($b['btn2_link']); ?>" class="px-8 py-4 border-2 border-white hover:bg-white hover:text-burgundy-900 text-white rounded-full font-bold transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                            <?php echo htmlspecialchars(td($b, 'btn2_text')); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Slide 1: Coffee & Bar Focus -->
                <div class="swiper-slide relative w-full h-full">
                    <img src="assets/images/hero_banner.png" alt="LaoFe & Beer Coffee" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-burgundy-950/90 via-black/40 to-black/30"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="max-w-6xl mx-auto px-6 text-center text-white space-y-6">
                            <span class="inline-block px-4 py-1.5 bg-white/90 text-burgundy-900 rounded-full text-xs font-extrabold uppercase tracking-widest shadow-lg font-serif-lao">
                                <?php echo $current_lang === 'lo' ? 'ກາເຟ ແລະ ບາ ແບບລາວປະຍຸກ' : 'Lao-Adapted Coffee & Bar'; ?>
                            </span>
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-snug font-serif-lao text-white drop-shadow-md">
                                <?php echo t('hero_title'); ?>
                            </h1>
                            <p class="text-lg md:text-2xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed font-serif-lao drop-shadow">
                                <?php echo t('hero_subtitle'); ?>
                            </p>
                            <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                                <a href="menu.php" class="px-9 py-4 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full font-bold shadow-xl transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                    <?php echo t('hero_cta'); ?>
                                </a>
                                <a href="locations.php" class="px-8 py-4 border-2 border-white hover:bg-white hover:text-burgundy-900 text-white rounded-full font-bold transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                    <?php echo t('nav_locations'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2: Evening Lounge & Draft Beer Focus -->
                <div class="swiper-slide relative w-full h-full">
                    <img src="assets/images/beer_drink.png" alt="LaoFe & Beer Bar" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-burgundy-950/90 via-black/40 to-black/30"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="max-w-6xl mx-auto px-6 text-center text-white space-y-6">
                            <span class="inline-block px-4 py-1.5 bg-white/90 text-burgundy-900 rounded-full text-xs font-extrabold uppercase tracking-widest shadow-lg font-serif-lao">
                                <?php echo $current_lang === 'lo' ? 'ແນວຄິດການພັກຜ່ອນຍາມຄ່ຳຄືນ' : 'Premium Nightlife Concept'; ?>
                            </span>
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-snug font-serif-lao text-white drop-shadow-md">
                                <?php echo $current_lang === 'lo' ? 'ກາງເວັນກາເຟ · ກາງຄືນເບຍສົດ' : 'Coffee By Day · Beer By Night'; ?>
                            </h1>
                            <p class="text-lg md:text-2xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed font-serif-lao drop-shadow">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ດື່ມດ່ຳກັບເບຍຄຣາບລາວ ແລະ ຄັອກເທວສະໝຸນໄພສູດພິເສດ ໃນບັນຍາກາດຊິລໆຍາມຄ່ຳຄືນ' 
                                    : 'Savor craft Lao beer and herbal signature cocktails in a relaxed premium lounge evening.'; ?>
                            </p>
                            <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                                <a href="menu.php" class="px-9 py-4 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full font-bold shadow-xl transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                    <?php echo t('hero_cta'); ?>
                                </a>
                                <a href="locations.php" class="px-8 py-4 border-2 border-white hover:bg-white hover:text-burgundy-900 text-white rounded-full font-bold transition-all duration-300 text-base font-serif-lao hover:scale-105">
                                    <?php echo t('nav_locations'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- Swiper Controls -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next hidden md:flex"></div>
        <div class="swiper-button-prev hidden md:flex"></div>
    </div>
</section>

<!-- =========================================================
     2. LUCKIN "EXPLORE OUR COFFEE & BAR WORLD" (3 Core Pillars)
     ========================================================= -->
<section class="py-24 bg-white px-6 md:px-12 border-b border-gray-100">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-4">
            <span class="inline-block px-4 py-1.5 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-extrabold uppercase tracking-widest border border-burgundy-700/10 font-serif-lao">
                <?php echo $current_lang === 'lo' ? '3 ອົງປະກອບຫຼັກຂອງເຮົາ' : 'OUR 3 CORE PILLARS'; ?>
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 font-serif-lao tracking-tight">
                <?php echo $current_lang === 'lo' ? 'ຄົ້ນພົບໂລກຂອງກາເຟ ແລະ ບາ ຂອງ LaoFe' : 'EXPLORE OUR COFFEE & BAR WORLD'; ?>
            </h2>
            <p class="text-gray-500 font-light max-w-3xl mx-auto text-base leading-relaxed font-serif-lao">
                <?php echo $current_lang === 'lo' 
                    ? 'ການປະສົມປະສານ 3 ອົງປະກອບທຳມະຊາດຢ່າງລົງຕົວ ທີ່ສ້າງເອກະລັກອັນໂດດເດັ່ນໃຫ້ກັບ <span class="inline-block">LaoFe & Beer</span>' 
                    : 'A harmonious blend of 3 natural elements defining the iconic identity of <span class="inline-block">LaoFe & Beer</span>'; ?>
            </p>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Pillar 1: Wood (ໄມ້) -->
            <div class="wood-card p-8 rounded-3xl space-y-6 hover:scale-[1.02] transition-all duration-300 relative overflow-hidden group border border-gray-100 shadow-xl hover:shadow-2xl bg-white">
                <div class="flex items-center justify-between">
                    <span class="pillar-badge-wood px-3.5 py-1 rounded-full text-xs font-extrabold tracking-wide flex items-center gap-1.5 font-serif-lao">
                        🪵 <?php echo $current_lang === 'lo' ? 'ໄມ້ & ບັນຍາກາດ' : 'WOOD & LOUNGE'; ?>
                    </span>
                    <span class="text-3xl font-serif-lao font-bold text-burgundy-700 opacity-20 group-hover:opacity-100 transition-opacity">01</span>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-burgundy-700 to-burgundy-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-burgundy-900/25 group-hover:scale-105 transition-transform">
                    <svg class="w-8 h-8 text-gold-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-18v18M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18" />
                    </svg>
                </div>
                <div class="space-y-3">
                    <h3 class="text-2xl font-bold text-gray-900 font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ບັນຍາກາດໄມ້ເນື້ອແຂງ<br/>ຄຸນນະພາບສູງ' : 'HIGH QUALITY<br/>SOLID TIMBER LOUNGE'; ?>
                    </h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? 'ດີຊາຍຮ້ານທີ່ເນັ້ນງານໄມ້ເນື້ອແຂງ ປະສານຄວາມອົບອຸ່ນຂອງທຳມະຊາດ ເຮັດໃຫ້ທຸກໆມຸມເປັນບ່ອນພັກຜ່ອນທີ່ຜ່ອນຄາຍ ແລະ ທັນສະໄໝ.'
                            : 'Crafted with warm solid timber and natural wood textures, creating an inviting lounge ambiance day and night.'; ?>
                    </p>
                </div>
            </div>

            <!-- Pillar 2: Coffee (ກາເຟ) -->
            <div class="wood-card p-8 rounded-3xl space-y-6 hover:scale-[1.02] transition-all duration-300 relative overflow-hidden group border border-gray-100 shadow-xl hover:shadow-2xl bg-white">
                <div class="flex items-center justify-between">
                    <span class="pillar-badge-coffee px-3.5 py-1 rounded-full text-xs font-extrabold tracking-wide flex items-center gap-1.5 font-serif-lao">
                        ☕ <?php echo $current_lang === 'lo' ? 'ກາເຟບໍລະເວນ' : 'BOLAVEN COFFEE'; ?>
                    </span>
                    <span class="text-3xl font-serif-lao font-bold text-amber-700 opacity-20 group-hover:opacity-100 transition-opacity">02</span>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-amber-700 to-amber-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-900/25 group-hover:scale-105 transition-transform">
                    <svg class="w-8 h-8 text-amber-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v8.25A6 6 0 009.75 20.25h.75a6 6 0 006-6V12.75M16.5 8.25H18A3.75 3.75 0 0121.75 12v0A3.75 3.75 0 0118 15.75h-1.5M16.5 8.25v4.5" />
                    </svg>
                </div>
                <div class="space-y-3">
                    <h3 class="text-2xl font-bold text-gray-900 font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ແກ່ນກາເຟອາຣາບິກ້າ<br/>ຄຸນນະພາບສູງ 100%' : 'HIGH QUALITY<br/>ARABICA BEANS'; ?>
                    </h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? 'ຄັດສັນແກ່ນກາເຟອາຣາບິກ້າແທ້ຈາກພູພຽງບໍລະເວນ ຂົ້ວບົດສົດໃໝ່ ປຸງແຕ່ງໂດຍທີມງານບາຣິສຕ້າ ແລະ ມິກໂຊໂລຈິສມືອາຊີບ.'
                            : 'Directly sourced from the Bolaven Plateau, roasted to perfection and brewed by champion baristas.'; ?>
                    </p>
                </div>
            </div>

            <!-- Pillar 3: Beer (ເບຍ) -->
            <div class="wood-card p-8 rounded-3xl space-y-6 hover:scale-[1.02] transition-all duration-300 relative overflow-hidden group border border-gray-100 shadow-xl hover:shadow-2xl bg-white">
                <div class="flex items-center justify-between">
                    <span class="pillar-badge-beer px-3.5 py-1 rounded-full text-xs font-extrabold tracking-wide flex items-center gap-1.5 font-serif-lao">
                        🍺 <?php echo $current_lang === 'lo' ? 'ເບຍສົົດ & ຄັອກເທວ' : 'CRAFT DRAFT BEER'; ?>
                    </span>
                    <span class="text-3xl font-serif-lao font-bold text-amber-600 opacity-20 group-hover:opacity-100 transition-opacity">03</span>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-600/30 group-hover:scale-105 transition-transform">
                    <svg class="w-8 h-8 text-amber-100" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v11.25A2.25 2.25 0 0114.25 19.5h-4.5A2.25 2.25 0 017.5 17.25V6m9 0H7.5m9 0h1.5A2.25 2.25 0 0120.25 8.25v2.25A2.25 2.25 0 0118 12.75h-1.5M7.5 6H6A2.25 2.25 0 003.75 8.25v2.25A2.25 2.25 0 006 12.75h1.5" />
                    </svg>
                </div>
                <div class="space-y-3">
                    <h3 class="text-2xl font-bold text-gray-900 font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ເບຍສົດຄຣາບດຶງສົດ<br/>ຈາກແທັບເຢັນໆ' : 'FRESHLY POURED<br/>CRAFT DRAFT BEER'; ?>
                    </h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? 'ດື່ມດ່ຳກັບເບຍຄຣາບລາວ ດຶງສົດເຢັນໆ ຈາກແທັບ ພ້ອມຄັອກເທວສະໝຸນໄພສູດພິເສດ ປ່ຽນບັນຍາກາດຍາມຄ່ຳຄືນໃຫ້ຊິລໆ.'
                            : 'Enjoy cold craft draft beer straight from the tap alongside signature Lao herbal cocktails in our evening lounge.'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     4. LUCKIN "HIGH QUALITY ARABICA BEANS" (Parallax Story)
     ========================================================= -->

<!-- =========================================================
     4. LUCKIN "HIGH QUALITY ARABICA BEANS" (Parallax Story)
     ========================================================= -->
<section class="relative py-32 parallax-bg overflow-hidden text-white" style="background-image: url('assets/images/Caf.png');">
    <div class="absolute inset-0 bg-gradient-to-r from-burgundy-950/95 via-burgundy-900/80 to-black/60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 items-center gap-12">
        <div class="space-y-6 max-w-xl">
            <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                <?php echo $current_lang === 'lo' ? 'ກາເຟອາຣາບິກ້າ ຄັດສັນພິເສດຈາກບໍລະເວນ' : 'BOLAVEN ARABICA SELECTION'; ?>
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold font-serif-lao leading-tight text-white">
                <?php echo $current_lang === 'lo' ? 'ພວກເຮົາມີຄວາມພູມໃຈສະເໜີ ເມັດກາເຟອາຣາບິກ້າ ຄຸນນະພາບສູງ' : 'We are proud to present you high-quality Arabica beans'; ?>
            </h2>
            <div class="w-20 h-1.5 bg-gold-400 rounded-full"></div>
            <p class="text-gray-200 font-light leading-relaxed text-base md:text-lg font-serif-lao">
                <?php echo $current_lang === 'lo'
                    ? 'ກາເຟຂອງພວກເຮົາໄດ້ຮັບການຄັດເລືອກ ແລະ ປັບປຸງສູດຢ່າງພິຖີພິຖັນ ໃຫ້ເຂົ້າກັບລົດຊາດຂອງຜູ້ບໍລິໂພກທັງພາຍໃນ ແລະ ຕ່າງປະເທດ ຢ່າງລົງຕົວ.'
                    : 'Our coffee is carefully selected and blended to perfectly match the taste and preferences of modern coffee lovers.'; ?>
            </p>
        </div>
    </div>
</section>

<!-- =========================================================
     5. LUCKIN "BLENDED BY THE MASTER TEAM" (Barista Showcase)
     ========================================================= -->
<section class="relative py-24 bg-gradient-to-r from-[#1C050B] via-[#4D0F1E] to-[#1C050B] text-white px-6 md:px-12 border-t border-amber-400/20 overflow-hidden shadow-2xl">
    <!-- Subtle Golden Ambient Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent pointer-events-none"></div>
    <div class="max-w-7xl mx-auto space-y-16 relative z-10">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-5xl font-extrabold font-serif-lao text-white tracking-tight">
                <?php echo $current_lang === 'lo' ? 'ຄັດສັນ ແລະ ປຸງແຕ່ງໂດຍທີມງານຜູ້ຊ່ຽວຊານ LaoFe' : 'Blended by LaoFe Master Team'; ?>
            </h2>
            <div class="w-24 h-1.5 bg-gold-400 mx-auto rounded-full"></div>
            <p class="text-gray-300 font-light max-w-2xl mx-auto text-base font-serif-lao">
                <?php echo $current_lang === 'lo' 
                    ? 'ທຸກໆ Batch ຂອງກາເຟ ແລະ ເຄື່ອງດື່ມ ໄດ້ຮັບການທົດສອບ ແລະ ປຸງແຕ່ງຢ່າງພິຖີພິຖັນ ໂດຍທີມງານບາຣິສຕ້າ ແລະ ມິກໂຊໂລຈິສມືອາຊີບ.'
                    : 'Every batch of our coffee is carefully tested and blended by our professional team of baristas and mixologists.'; ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left: Barista Content Panel (8 Cols) -->
            <div class="lg:col-span-8 bg-white/10 p-8 md:p-12 rounded-3xl border border-white/20 space-y-8 relative overflow-hidden min-h-[360px] backdrop-blur-md">
                
                <!-- Barista 1 content -->
                <div id="barista-content-0" class="barista-content-fade space-y-6">
                    <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ບາຣິສຕ້າ ຜູ້ຊ່ຽວຊານ LaoFe' : 'LaoFe Master Barista'; ?>
                    </span>
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">
                        <?php echo $current_lang === 'lo' ? 'ທີມງານບາຣິສຕ້າ LaoFe' : 'LaoFe Barista Team'; ?>
                    </h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? '"ກາເຟ ແລະ ເຄື່ອງດື່ມທຸກແກ້ວ ຖືກສ້າງສັນດ້ວຍຄວາມຕັ້ງໃຈ ເພື່ອມອບລົດຊາດ ແລະ ປະສົບການທີ່ດີທີ່ສຸດໃຫ້ລູກຄ້າ."'
                            : '"Every cup of coffee and beverage is crafted with care to deliver the finest taste and experience to our customers."'; ?>
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">
                            <?php echo $current_lang === 'lo' ? 'ທີມງານບາຣິສຕ້າ LaoFe' : 'LaoFe Barista Team'; ?>
                        </strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ບາຣິສຕ້າ ມືອາຊີບ' : 'Professional Barista Team'; ?>
                        </span>
                    </div>
                </div>

                <!-- Barista 2 content (Hidden initially) -->
                <div id="barista-content-1" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ມິກໂຊໂລຈິສ ຜູ້ຊ່ຽວຊານ LaoFe' : 'LaoFe Master Mixologist'; ?>
                    </span>
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">
                        <?php echo $current_lang === 'lo' ? 'ທີມງານມິກໂຊໂລຈິສ LaoFe' : 'LaoFe Mixologist Team'; ?>
                    </h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? '"ການຜະສົມຜະສານເຄື່ອງດື່ມ ແລະ ບັນຍາກາດຍາມຄ່ຳຄືນ ໃຫ້ທ່ານໄດ້ຜ່ອນຄາຍ ແລະ ມີຄວາມສຸກ."'
                            : '"Blending craft beverages with evening ambiance for your ultimate relaxation and enjoyment."'; ?>
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">
                            <?php echo $current_lang === 'lo' ? 'ທີມງານມິກໂຊໂລຈິສ LaoFe' : 'LaoFe Mixologist Team'; ?>
                        </strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ມິກໂຊໂລຈິສ ມືອາຊີບ' : 'Professional Mixologist Team'; ?>
                        </span>
                    </div>
                </div>

                <!-- Barista 3 content (Hidden initially) -->
                <div id="barista-content-2" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ນັກຂົ້ວກາເຟ ຜູ້ຊ່ຽວຊານ LaoFe' : 'LaoFe Master Roaster'; ?>
                    </span>
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">
                        <?php echo $current_lang === 'lo' ? 'ທີມງານນັກຂົ້ວກາເຟ LaoFe' : 'LaoFe Roaster Team'; ?>
                    </h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        <?php echo $current_lang === 'lo'
                            ? '"ຄັດສັນເມັດກາເຟຄຸນນະພາບ ແລະ ຄວບຄຸມການຂົ້ວຢ່າງພິຖີພິຖັນ ເພື່ອກິ່ນຫອມ ແລະ ລົດຊາດທີ່ເປັນເອກະລັກ."'
                            : '"Selecting quality coffee beans and carefully roasting to extract rich, signature aromas."'; ?>
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">
                            <?php echo $current_lang === 'lo' ? 'ທີມງານນັກຂົ້ວກາເຟ LaoFe' : 'LaoFe Roaster Team'; ?>
                        </strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ນັກຂົ້ວກາເຟ ມືອາຊີບ' : 'Professional Coffee Roaster Team'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Avatar Selectors (4 Cols) -->
            <div class="lg:col-span-4 flex lg:flex-col justify-center lg:justify-start gap-6">
                <button onclick="switchBarista(0)" class="barista-tab-btn active w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-gold-400 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/our_story.png" alt="LaoFe Barista" class="w-full h-full object-cover">
                </button>
                <button onclick="switchBarista(1)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-transparent hover:border-gold-400/50 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/beer_drink.png" alt="LaoFe Mixologist" class="w-full h-full object-cover">
                </button>
                <button onclick="switchBarista(2)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-transparent hover:border-gold-400/50 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/coffee.png" alt="LaoFe Roaster" class="w-full h-full object-cover">
                </button>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     6. LUCKIN "FRESHLY ROASTED, FRESHLY GROUNDED" (Swiss Equipment)
     ========================================================= -->
<section class="py-24 bg-white px-6 md:px-12 border-b border-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <span class="text-xs font-extrabold text-burgundy-700 uppercase tracking-widest font-serif-lao">
                <?php echo $current_lang === 'lo' ? 'ເຄື່ອງມື ແລະ ເທັກໂນໂລຢີທີ່ທັນສະໄໝ' : 'STATE-OF-THE-ART EQUIPMENT'; ?>
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 font-serif-lao leading-tight">
                <?php echo $current_lang === 'lo' ? 'ຂົ້ວບົດສົດໃໝ່,<br/>ແລະ ດຶງເບຍສົດຈາກແທັບ' : 'Freshly Roasted,<br/>Freshly Grounded & Draft Poured'; ?>
            </h2>
            <div class="w-20 h-1.5 bg-burgundy-700 rounded-full"></div>
            <p class="text-gray-600 font-light leading-relaxed text-base md:text-lg font-serif-lao">
                <?php echo $current_lang === 'lo'
                    ? 'ພວກເຮົາໃຊ້ເຄື່ອງຊົງກາເຟທີ່ທັນສະໄໝລະດັບໂລກຈາກສະວິດເຊີແລນ (SCHAERER) ໃນທຸກສາຂາ. ແລະ ລະບົບດຶງເບຍສົດທີ່ຮັກສາອຸນຫະພູມ ແລະ ຄວາມກົດດັນຢ່າງເໝາະສົມ ເພື່ອໃຫ້ເຄື່ອງດື່ມທຸກຈອກມີຄວາມສົດໃໝ່ ແລະ ມີລົດຊາດທີ່ດີທີ່ສຸດ.'
                    : 'We use state-of-the-art SCHAERER coffee machines from Switzerland in all of our outlets alongside cold craft draft taps, ensuring that every cup and pint is served fresh for you.'; ?>
            </p>
        </div>
        <div class="relative rounded-3xl overflow-hidden shadow-2xl h-96 border border-gray-100">
            <img src="assets/images/our_story.png" alt="LaoFe Swiss Equipment" class="absolute inset-0 w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- =========================================================
     7. LUCKIN BRAND AUTOPLAY VIDEO SHOWCASE
     ========================================================= -->
<section class="w-full bg-burgundy-900 overflow-hidden">
    <div class="w-full h-[400px] md:h-[550px] relative overflow-hidden bg-burgundy-900">
        <video autoplay muted loop playsinline poster="assets/images/hero_banner.png" class="w-full h-full object-cover">
            <source src="https://res.cloudinary.com/wycebg9u/video/upload/v1789444483/Laofe.mp4" type="video/mp4"/>
            <source src="assets/videos/Laofe.mp4" type="video/mp4"/>
            <img src="assets/images/hero_banner.png" alt="LaoFe & Beer Showcase" class="w-full h-full object-cover">
        </video>
        <div class="absolute inset-0 bg-gradient-to-t from-burgundy-950/80 via-transparent to-burgundy-950/40"></div>
    </div>
</section>

<!-- =========================================================
     8. LUCKIN POPULAR MENU CMS SHOWCASE
     ========================================================= -->
<section class="py-24 px-6 md:px-12 bg-[#EFEADF] border-b border-[#E5DCD0]">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-2">
            <span class="inline-block px-4 py-1.5 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-extrabold uppercase tracking-widest border border-burgundy-700/10 font-serif-lao">
                ⭐ <?php echo $current_lang === 'lo' ? 'ເມນູແນະນຳພິເສດ' : 'FEATURED SELECTIONS'; ?>
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-burgundy-700 font-serif-lao tracking-tight"><?php echo t('popular_title'); ?></h2>
            <p class="text-gray-600 font-light max-w-md mx-auto font-serif-lao"><?php echo t('popular_sub'); ?></p>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <?php if (count($popular_items) > 0): ?>
                <?php foreach ($popular_items as $item): ?>
                    <div class="reference-menu-card overflow-hidden flex flex-col justify-between group">
                        <div class="reference-card-image-wrap relative">
                            <span class="bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 absolute top-3 left-3 z-10 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest font-serif-lao flex items-center gap-1">
                                ★ <?php echo htmlspecialchars(t('menu_' . $item['category'])); ?>
                            </span>
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars(td($item, 'name')); ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-4 bg-[#FAF7F2]">
                            <div class="space-y-1.5">
                                <h3 class="reference-card-title text-base sm:text-lg line-clamp-1 group-hover:text-burgundy-700 transition-colors duration-300"><?php echo htmlspecialchars(td($item, 'name')); ?></h3>
                                <p class="text-xs text-[#6E584E] font-light leading-relaxed line-clamp-2 font-serif-lao min-h-[2.25rem]"><?php echo htmlspecialchars(td($item, 'description')); ?></p>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-[#EBE4D8]">
                                <div class="flex flex-col">
                                    <span class="reference-card-price text-lg sm:text-xl leading-none">
                                        <?php echo number_format($item['price']); ?> <span class="text-xs font-normal text-amber-900/70 font-serif-lao">₭</span>
                                    </span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-amber-900/60 mt-0.5 font-serif-lao">
                                        <?php echo htmlspecialchars(t('menu_' . $item['category'])); ?>
                                    </span>
                                </div>
                                <a href="menu.php" class="px-3.5 py-1.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs shadow-md transition-all hover:scale-105 active:scale-95 font-serif-lao">
                                    <span><?php echo $current_lang === 'lo' ? 'ເບິ່ງເມນູ' : 'View Item'; ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- 1. Coconut Latte -->
                <div class="reference-menu-card overflow-hidden flex flex-col justify-between group">
                    <div class="reference-card-image-wrap relative">
                        <span class="bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 absolute top-3 left-3 z-10 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest font-serif-lao">
                            ★ <?php echo t('menu_coffee'); ?>
                        </span>
                        <img src="assets/images/coffee.png" alt="Coconut Latte" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4 bg-[#FAF7F2]">
                        <div class="space-y-1.5">
                            <h3 class="reference-card-title text-base sm:text-lg">
                                <?php echo $current_lang === 'lo' ? 'ລາວເຟ ໂຄໂຄນັດ ລາເຕ້' : 'LaoFe Coconut Latte'; ?>
                            </h3>
                            <p class="text-xs text-[#6E584E] font-light leading-relaxed line-clamp-2 font-serif-lao min-h-[2.25rem]">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ກາເຟເອສເປຣສໂຊທີ່ເຂັ້ມຂຸ້ນ ຜສົມຜະສານກັບນ້ຳໝາກພ້າວສົດ ແລະ ນ້ຳນົມໝາກພ້າວສູດພິເສດ ໃຫ້ລົດຊາດທີ່ຫວານມັນ ຫອມລະມຸນ.'
                                    : 'A rich espresso shot layered with fresh coconut water and our signature coconut cream, delivering a smooth, refreshing, and tropical taste.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-[#EBE4D8]">
                            <div class="flex flex-col">
                                <span class="reference-card-price text-lg sm:text-xl leading-none">35,000 <span class="text-xs font-normal text-amber-900/70 font-serif-lao">₭</span></span>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-amber-900/60 mt-0.5 font-serif-lao"><?php echo t('menu_coffee'); ?></span>
                            </div>
                            <a href="menu.php" class="px-3.5 py-1.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs shadow-md transition-all hover:scale-105 active:scale-95 font-serif-lao">
                                <span><?php echo $current_lang === 'lo' ? 'ເບິ່ງເມນູ' : 'View Item'; ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Craft Lao Beer -->
                <div class="reference-menu-card overflow-hidden flex flex-col justify-between group">
                    <div class="reference-card-image-wrap relative">
                        <span class="bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 absolute top-3 left-3 z-10 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest font-serif-lao">
                            ★ <?php echo t('menu_bar'); ?>
                        </span>
                        <img src="assets/images/beer_drink.png" alt="Lao Craft Beer" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4 bg-[#FAF7F2]">
                        <div class="space-y-1.5">
                            <h3 class="reference-card-title text-base sm:text-lg">
                                <?php echo $current_lang === 'lo' ? 'ເບຍລາວຄຣາບພຣີມ່ຽມ' : 'Premium Lao Craft Beer'; ?>
                            </h3>
                            <p class="text-xs text-[#6E584E] font-light leading-relaxed line-clamp-2 font-serif-lao min-h-[2.25rem]">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ເບຍສົດຄຣາບຄຸນນະພາບສູງ ໝັກຈາກເຂົ້າຫອມລາວແທ້ໆ ໃຫ້ລົດຊາດທີ່ນຸ້ມນວນ ແລະ ກິ່ນຫອມອັນເປັນເອກະລັກ.'
                                    : 'High-quality draft craft beer brewed locally with authentic Lao jasmine rice, offering a smooth finish and a unique aroma.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-[#EBE4D8]">
                            <div class="flex flex-col">
                                <span class="reference-card-price text-lg sm:text-xl leading-none">45,000 <span class="text-xs font-normal text-amber-900/70 font-serif-lao">₭</span></span>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-amber-900/60 mt-0.5 font-serif-lao"><?php echo t('menu_bar'); ?></span>
                            </div>
                            <a href="menu.php" class="px-3.5 py-1.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs shadow-md transition-all hover:scale-105 active:scale-95 font-serif-lao">
                                <span><?php echo $current_lang === 'lo' ? 'ເບິ່ງເມນູ' : 'View Item'; ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Signature Lao Cocktail -->
                <div class="reference-menu-card overflow-hidden flex flex-col justify-between group">
                    <div class="reference-card-image-wrap relative">
                        <span class="bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 absolute top-3 left-3 z-10 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest font-serif-lao">
                            ★ <?php echo t('menu_bar'); ?>
                        </span>
                        <img src="assets/images/our_story.png" alt="Lao Herbal Cocktail" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4 bg-[#FAF7F2]">
                        <div class="space-y-1.5">
                            <h3 class="reference-card-title text-base sm:text-lg">
                                <?php echo $current_lang === 'lo' ? 'ຄັອກເທວສະໝຸນໄພລາວ' : 'Lao Herbal Signature Cocktail'; ?>
                            </h3>
                            <p class="text-xs text-[#6E584E] font-light leading-relaxed line-clamp-2 font-serif-lao min-h-[2.25rem]">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ຄັອກເທວສູດພິເສດ ທີ່ປະສົມປະສານສະໝຸນໄພທ້ອງຖິ່ນຂອງລາວ ເຂົ້າກັບເຫຼົ້າຊັ້ນດີ ໃຫ້ລົດຊາດທີ່ສົດຊື່ນ ແລະ ມີເອກະລັກ.'
                                    : 'A signature cocktail blending local Lao botanicals with fine spirits for a uniquely crisp and memorable flavor.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-[#EBE4D8]">
                            <div class="flex flex-col">
                                <span class="reference-card-price text-lg sm:text-xl leading-none">55,000 <span class="text-xs font-normal text-amber-900/70 font-serif-lao">₭</span></span>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-amber-900/60 mt-0.5 font-serif-lao"><?php echo t('menu_bar'); ?></span>
                            </div>
                            <a href="menu.php" class="px-3.5 py-1.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs shadow-md transition-all hover:scale-105 active:scale-95 font-serif-lao">
                                <span><?php echo $current_lang === 'lo' ? 'ເບິ່ງເມນູ' : 'View Item'; ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- =========================================================
     9. LUCKIN "STORES SHOWCASE" CAROUSEL SLIDER (Outlet Locations)
     ========================================================= -->
<section class="py-24 bg-white overflow-hidden select-none">
    <!-- Header Title Section (Matches Luckin Stores Reference) -->
    <div class="max-w-4xl mx-auto text-center space-y-3 px-6 mb-12">
        <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 font-serif-lao tracking-tight">
            <?php echo $current_lang === 'lo' ? 'ສາຂາ LaoFe & Beer ທົ່ວປະເທດ' : 'LaoFe & Beer Stores'; ?>
        </h2>
        <div class="w-16 h-0.5 bg-gray-400 mx-auto"></div>
        <p class="text-xs md:text-sm text-gray-600 font-light font-serif-lao tracking-wide">
            <?php echo $current_lang === 'lo' 
                ? '4 ສາຂາ Lounge ຫຼັກ ຢູ່ ນະຄອນຫຼວງວຽງຈັນ, ວັງວຽງ, ຫຼວງພະບາງ ແລະ ປາກເຊ.' 
                : '4 Flagship lounge stores across Vientiane, Vang Vieng, Luang Prabang & Pakse.'; ?>
        </p>
    </div>

    <!-- Center-Active Swiper Store Carousel -->
    <div class="relative w-full max-w-7xl mx-auto px-4 md:px-12">
        <div class="swiper store-carousel-swiper w-full rounded-2xl md:rounded-3xl overflow-hidden py-4 shadow-xl">
            <div class="swiper-wrapper">
                
                <?php
                // Store slides data array (duplicated once to ensure 8 slides for 100% seamless continuous Swiper looping)
                $store_slides_data = [
                    [
                        'name_lo' => 'ສາຂາ ນ້ຳພຸ (ນະຄອນຫຼວງວຽງຈັນ)',
                        'name_en' => 'NAMPHOU VIENTIANE',
                        'map' => 'https://www.google.com/maps?q=17.966801,102.606311',
                        'hours_lo' => 'ຈັນ - ອາທິດ: 07:00 - 23:00 ໂມງ',
                        'hours_en' => 'Mon - Sun: 07:00 AM - 11:00 PM',
                        'addr_lo' => 'ຖະໜົນນ້ຳພຸ, ບ້ານຊຽງຍືນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ',
                        'addr_en' => 'Namphou Rd, XiengNgeun Village, Vientiane Capital',
                        'img' => 'assets/images/branch_namphou.png'
                    ],
                    [
                        'name_lo' => 'ສາຂາ ຫຼວງພະບາງ',
                        'name_en' => 'LUANG PRABANG HERITAGE',
                        'map' => 'https://www.google.com/maps/search/?api=1&query=Luang+Prabang+Heritage+Town',
                        'hours_lo' => 'ຈັນ - ອາທິດ: 07:00 - 22:00 ໂມງ',
                        'hours_en' => 'Mon - Sun: 07:00 AM - 10:00 PM',
                        'addr_lo' => 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ແຂວງຫຼວງພະບາງ',
                        'addr_en' => 'Sisavangvong Rd, Luang Prabang World Heritage Town',
                        'img' => 'assets/images/branch_luangprabang.png'
                    ],
                    [
                        'name_lo' => 'ສາຂາ ວັງວຽງ',
                        'name_en' => 'VANG VIENG RIVERSIDE',
                        'map' => 'https://www.google.com/maps/search/?api=1&query=Vang+Vieng+Nam+Song',
                        'hours_lo' => 'ຈັນ - ອາທິດ: 07:00 - 23:00 ໂມງ',
                        'hours_en' => 'Mon - Sun: 07:00 AM - 11:00 PM',
                        'addr_lo' => 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ',
                        'addr_en' => 'Nam Song Riverside Rd, Savang Village, Vang Vieng',
                        'img' => 'assets/images/branch_vangvieng.png'
                    ],
                    [
                        'name_lo' => 'ສາຂາ ປາກເຊ (ຈຳປາສັກ)',
                        'name_en' => 'PAKSE CHAMPASAK',
                        'map' => 'https://www.google.com/maps/search/?api=1&query=Pakse+Champasak',
                        'hours_lo' => 'ຈັນ - ອາທິດ: 07:00 - 22:00 ໂມງ',
                        'hours_en' => 'Mon - Sun: 07:00 AM - 10:00 PM',
                        'addr_lo' => 'ຖະໜົນ 13 ໃຕ້, ເມືອງປາກເຊ, ແຂວງຈຳປາສັກ',
                        'addr_en' => 'Route 13 South, Pakse City, Champasak',
                        'img' => 'assets/images/branch_pakse.png'
                    ]
                ];
                
                // Duplicate slides list to guarantee 8 slides for infinite continuous Swiper loop
                $loop_slides = array_merge($store_slides_data, $store_slides_data);
                foreach ($loop_slides as $st):
                ?>
                    <div class="swiper-slide transition-all duration-500">
                        <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-[16/9] md:aspect-[16/8] group">
                            <img src="<?php echo htmlspecialchars($st['img']); ?>" alt="<?php echo htmlspecialchars($st['name_en']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            
                            <!-- Bottom Blue/Burgundy Overlay Banner (Luckin Style) -->
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#1C050B]/95 via-[#531321]/90 to-[#1C050B]/95 text-white p-5 md:p-8 border-t border-amber-400/30 backdrop-blur-md space-y-2">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                    <h3 class="text-2xl md:text-4xl font-black text-white font-serif-lao tracking-wider uppercase">
                                        <?php echo $current_lang === 'lo' ? htmlspecialchars($st['name_lo']) : htmlspecialchars($st['name_en']); ?>
                                    </h3>
                                    <a href="<?php echo htmlspecialchars($st['map']); ?>" target="_blank" class="w-11 h-11 md:w-12 md:h-12 rounded-2xl bg-white hover:bg-gray-50 p-2.5 shadow-xl shadow-black/30 border border-white transition-all duration-300 hover:scale-110 active:scale-95 flex items-center justify-center shrink-0 group" title="<?php echo $current_lang === 'lo' ? 'ແຜນທີ່ Google Maps' : 'Google Maps'; ?>">
                                        <img src="assets/images/google_maps_icon.png" alt="Google Maps" class="w-full h-full object-contain rounded-md group-hover:scale-105 transition-transform">
                                    </a>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs md:text-sm text-amber-200 font-serif-lao">
                                    <span class="flex items-center gap-1.5 font-semibold">
                                        <span>🕒</span>
                                        <span><?php echo $current_lang === 'lo' ? htmlspecialchars($st['hours_lo']) : htmlspecialchars($st['hours_en']); ?></span>
                                    </span>
                                    <span class="flex items-center gap-1.5 text-amber-100/90 font-light">
                                        <span>📍</span>
                                        <span><?php echo $current_lang === 'lo' ? htmlspecialchars($st['addr_lo']) : htmlspecialchars($st['addr_en']); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            
            <!-- Pagination Dots -->
            <div class="swiper-pagination store-swiper-pagination !relative mt-6"></div>
        </div>

        <!-- Navigation Buttons Positioned Cleanly on Container Edges -->
        <button type="button" class="store-prev-btn absolute left-0 md:-left-5 top-1/2 -translate-y-1/2 z-30 text-burgundy-700 bg-white/95 shadow-2xl border border-gray-200 rounded-full w-12 h-12 flex items-center justify-center hover:bg-burgundy-700 hover:text-white transition-all focus:outline-none" aria-label="Previous Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" class="store-next-btn absolute right-0 md:-right-5 top-1/2 -translate-y-1/2 z-30 text-burgundy-700 bg-white/95 shadow-2xl border border-gray-200 rounded-full w-12 h-12 flex items-center justify-center hover:bg-burgundy-700 hover:text-white transition-all focus:outline-none" aria-label="Next Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
</section>

<!-- SECTION: Customer Reviews & Testimonials (ສຽງຕອບຮັບຈາກລູກຄ້າ) -->
<section class="py-20 bg-gradient-to-b from-[#FFFDF2] to-[#FAF7EC] px-6 md:px-12 border-t border-amber-500/10">
    <div class="max-w-7xl mx-auto space-y-12">
        <div class="text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 font-serif-lao">
                ⭐ 4.9 / 5.0 (<?php echo $current_lang === 'lo' ? '500+ ຄຳຊົມ' : '500+ Reviews'; ?>)
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-burgundy-900 font-serif-lao">
                <?php echo $current_lang === 'lo' ? 'ສຽງຕອບຮັບຈາກລູກຄ້າຂອງ LaoFe & Beer' : 'What Our Customers Say'; ?>
            </h2>
            <p class="text-sm text-gray-600 font-light font-serif-lao max-w-2xl mx-auto">
                <?php echo $current_lang === 'lo' ? 'ຄວາມປະທັບໃຈຈາກລູກຄ້າປະຈຳ ແລະ ນັກທ່ອງທ່ຽວ ທີ່ໄດ້ມາສຳຜັດລົດຊາດ ແລະ ບັນຍາກາດຂອງພວກເຮົາ' : 'Real reviews and feedback from coffee lovers & lounge guests nationwide'; ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review Card 1 -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-amber-500/15 space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-1 text-amber-500 text-lg">
                    ★★★★★
                </div>
                <p class="text-sm text-gray-700 leading-relaxed font-serif-lao italic">
                    <?php echo $current_lang === 'lo'
                        ? '"ກາເຟອາຣາບິກ້າບໍລະເວນຂົ້ວສົດລົດຊາດເຂັ້ມຂຸ້ນດີຫຼາຍ ດີຊາຍຮ້ານໄມ້ເນື້ອແຂງງາມ ແລະ ຊິລຫຼາຍ! ເໝາະສຳລັບນັ່ງເຮັດວຽກຍາມກາງເວັນ ແລະ ນັ່ງຊິລຍາມຄ່ຳ."'
                        : '"The Bolaven Arabica coffee is freshly roasted and rich! Beautiful solid timber ambiance. Perfect for daytime work and evening relaxation."'; ?>
                </p>
                <div class="pt-4 border-t border-gray-100 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-burgundy-700 text-gold-300 flex items-center justify-center font-bold text-sm shadow-md">
                        S
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 font-serif-lao"><?php echo $current_lang === 'lo' ? 'ທ່ານ ສົມໄຊ' : 'Somchai V.'; ?></h4>
                        <span class="text-xs text-gray-400 font-serif-lao"><?php echo $current_lang === 'lo' ? 'ລູກຄ້າປະຈຳ ສາຂານ້ຳພຸ' : 'Regular at Nam Phou Branch'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Review Card 2 -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-amber-500/15 space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-1 text-amber-500 text-lg">
                    ★★★★★
                </div>
                <p class="text-sm text-gray-700 leading-relaxed font-serif-lao italic">
                    <?php echo $current_lang === 'lo'
                        ? '"ເບຍຄຣາບດຶງສົດເຢັນໆ ຈາກແທັບ ພ້ອມຄັອກເທວສະໝຸນໄພລາວສູດພິເສດ. ພະນັກງານບໍລິການດີ ແລະ ເປັນກັນເອງຫຼາຍ!"'
                        : '"Ice-cold draft craft beer straight from the tap with special Lao herbal cocktails. Amazing friendly service!"'; ?>
                </p>
                <div class="pt-4 border-t border-gray-100 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm shadow-md">
                        J
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 font-serif-lao">Jessica M.</h4>
                        <span class="text-xs text-gray-400 font-serif-lao"><?php echo $current_lang === 'lo' ? 'ນັກທ່ອງທ່ຽວ ສາຂາວັງວຽງ' : 'Tourist at Vang Vieng Branch'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Review Card 3 -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-amber-500/15 space-y-4 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-center space-x-1 text-amber-500 text-lg">
                    ★★★★★
                </div>
                <p class="text-sm text-gray-700 leading-relaxed font-serif-lao italic">
                    <?php echo $current_lang === 'lo'
                        ? '"ຮ້ານຕິດແຄມນ້ຳຂອງ ຫຼວງພະບາງ ວິວພະອາທິດ ຕົກດິນງາມສຸດໆ. ເມນູອາຫານລາວປະຍຸກ ແລະ ກາເຟ 10/10 ແນະນຳເລີຍ!"'
                        : '"Stunning sunset view over the Mekong in Luang Prabang. Lao-adapted menu and coffee are 10/10. Highly recommended!"'; ?>
                </p>
                <div class="pt-4 border-t border-gray-100 flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-burgundy-900 text-gold-400 flex items-center justify-center font-bold text-sm shadow-md">
                        B
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 font-serif-lao"><?php echo $current_lang === 'lo' ? 'ທ່ານ ບຸນເດດ' : 'Boundeth K.'; ?></h4>
                        <span class="text-xs text-gray-400 font-serif-lao"><?php echo $current_lang === 'lo' ? 'ລູກຄ້າ ສາຂາຫຼວງພະບາງ' : 'Luang Prabang Branch Guest'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: #LaoFeBeer Moments Customer Gallery -->
<section class="py-20 bg-burgundy-950 text-white px-6 md:px-12 border-t border-amber-500/20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto space-y-12 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-3">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-gold-300 border border-amber-500/30 font-serif-lao">
                    <?php echo $current_lang === 'lo' ? '📸 ແກເລີຣີບັນຍາກາດຮ້ານ' : '📸 Customer Social Gallery'; ?>
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white font-serif-lao">
                    <?php echo $current_lang === 'lo' ? '#LaoFeBeer ຊ່ວງເວລາອັນແສນພິເສດ' : '#LaoFeBeer Moments'; ?>
                </h2>
                <p class="text-sm text-gray-400 font-light font-serif-lao max-w-xl">
                    <?php echo $current_lang === 'lo'
                        ? 'ແຊຣ໌ຊ່ວງເວລາອັນແສນພິເສດຂອງທ່ານ ພ້ອມ Tag ຫາເຮົາເທິງ Instagram & TikTok @laofe_beer'
                        : 'Share your favorite moments with us by tagging @laofe_beer on Instagram & TikTok'; ?>
                </p>
            </div>
            <a href="https://www.instagram.com/laofe_beer?stkn=OG4wNGh0YmoxNWsw&utm_source=qr" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-burgundy-950 rounded-2xl font-bold text-sm hover:scale-105 transition-transform duration-300 shadow-lg font-serif-lao shrink-0">
                <span><?php echo $current_lang === 'lo' ? 'ຕິດຕາມ @laofe_beer' : 'Follow @laofe_beer'; ?></span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="relative group rounded-2xl overflow-hidden aspect-square bg-burgundy-900 border border-amber-500/20 shadow-lg">
                <img src="assets/images/branch_namphou.png" alt="LaoFe Atmosphere" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                    <span class="text-xs text-gold-300 font-bold font-serif-lao">📍 ສາຂາ ນ້ຳພຸ</span>
                    <p class="text-[11px] text-gray-300 font-light">"ກາເຟອາຣາບິກ້າຂົ້ວສົດ ☕"</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden aspect-square bg-burgundy-900 border border-amber-500/20 shadow-lg">
                <img src="assets/images/branch_luangprabang.png" alt="LaoFe Atmosphere" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                    <span class="text-xs text-gold-300 font-bold font-serif-lao">📍 ສາຂາ ຫຼວງພະບາງ</span>
                    <p class="text-[11px] text-gray-300 font-light">"ບັນຍາກາດແຄມນ້ຳຂອງ 🌅"</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden aspect-square bg-burgundy-900 border border-amber-500/20 shadow-lg">
                <img src="assets/images/branch_pakse.png" alt="LaoFe Atmosphere" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                    <span class="text-xs text-gold-300 font-bold font-serif-lao">📍 ສາຂາ ປາກເຊ</span>
                    <p class="text-[11px] text-gray-300 font-light">"ເບຍຄຣາຟດຶງສົດ 🍺"</p>
                </div>
            </div>

            <div class="relative group rounded-2xl overflow-hidden aspect-square bg-burgundy-900 border border-amber-500/20 shadow-lg">
                <img src="assets/images/branch_vangvieng.png" alt="LaoFe Atmosphere" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-end">
                    <span class="text-xs text-gold-300 font-bold font-serif-lao">📍 ສາຂາ ວັງວຽງ</span>
                    <p class="text-[11px] text-gray-300 font-light">"ວິວພູຜາແຄມຊອງ ⛰️"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include Swiper Init & Barista Tab Switcher JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        // 1. Main Hero Swiper
        new Swiper('.main-hero-swiper', {
            loop: true,
            loopAdditionalSlides: 2,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            speed: 800,
        });

        // 2. Luckin-Style Stores Center-Active Carousel Swiper (Continuous Endless Loop)
        new Swiper('.store-carousel-swiper', {
            slidesPerView: 1.15,
            centeredSlides: true,
            spaceBetween: 20,
            loop: true,
            loopAdditionalSlides: 4,
            speed: 800,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.store-swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.store-next-btn',
                prevEl: '.store-prev-btn',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.35,
                    spaceBetween: 24,
                    centeredSlides: true,
                },
                768: {
                    slidesPerView: 1.5,
                    spaceBetween: 28,
                    centeredSlides: true,
                },
                1024: {
                    slidesPerView: 1.8,
                    spaceBetween: 32,
                    centeredSlides: true,
                }
            }
        });
    }
});

function switchBarista(index) {
    var contents = document.querySelectorAll('.barista-content-fade');
    var btns = document.querySelectorAll('.barista-tab-btn');
    
    contents.forEach(function(el, i) {
        if (i === index) {
            el.classList.remove('hidden');
            setTimeout(function() {
                el.classList.remove('opacity-0', 'translate-y-4');
            }, 50);
        } else {
            el.classList.add('opacity-0', 'translate-y-4');
            setTimeout(function() {
                el.classList.add('hidden');
            }, 300);
        }
    });

    btns.forEach(function(btn, i) {
        if (i === index) {
            btn.classList.add('active', 'border-gold-400');
            btn.classList.remove('border-transparent');
        } else {
            btn.classList.remove('active', 'border-gold-400');
            btn.classList.add('border-transparent');
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
