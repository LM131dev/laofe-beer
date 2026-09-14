<?php
// index.php - Luckin Coffee Singapore 1:1 Homepage Architecture (Fully Bilingual Lao & English)
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
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight font-serif-lao text-white drop-shadow-md sm:whitespace-nowrap">
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
                            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight font-serif-lao text-white drop-shadow-md sm:whitespace-nowrap">
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
                            ? 'ດີຊາຍຮ້ານທີ່ເນັ້ນງານໄມ້ເນື້ອແຂງ ຜສານຄວາມອົບອຸ່ນຂອງທຳມະຊາດ ເຮັດໃຫ້ທຸກໆມຸມເປັນບ່ອນພັກຜ່ອນທີ່ຜ່ອນຄາຍ ແລະ ທັນສະໄໝ.'
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
                    ? 'ກາເຟຂອງພວກເຮົາໄດ້ຮັບການຄັດເລືອກຈາກສູດການປະສົມຫຼາຍກວ່າ 180 ສູດ ທີ່ປັບປຸງມາໃຫ້ເຂົ້າກັບລົດຊາດຂອງຜູ້ບໍລິໂພກທັງພາຍໃນ ແລະ ຕ່າງປະເທດ ຢ່າງລົງຕົວ.'
                    : 'Our coffee is selected from more than 180 blending formulas that highly match the taste and preferences of modern coffee lovers.'; ?>
            </p>
        </div>
    </div>
</section>

<!-- =========================================================
     5. LUCKIN "BLENDED BY THE WBC CHAMPIONS" (Barista Showcase)
     ========================================================= -->
<section class="relative py-24 bg-gradient-to-r from-[#1C050B] via-[#4D0F1E] to-[#1C050B] text-white px-6 md:px-12 border-t border-amber-400/20 overflow-hidden shadow-2xl">
    <!-- Subtle Golden Ambient Glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent pointer-events-none"></div>
    <div class="max-w-7xl mx-auto space-y-16 relative z-10">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-5xl font-extrabold font-serif-lao text-white tracking-tight">
                <?php echo $current_lang === 'lo' ? 'ຄັດສັນ ແລະ ປຸງແຕ່ງໂດຍທີມແຊັມບາຣິສຕ້າ' : 'Blended by the WBC Champions'; ?>
            </h2>
            <div class="w-24 h-1.5 bg-gold-400 mx-auto rounded-full"></div>
            <p class="text-gray-300 font-light max-w-2xl mx-auto text-base font-serif-lao">
                <?php echo $current_lang === 'lo' 
                    ? 'ທຸກໆ Batch ຂອງກາເຟ ແລະ ເຄື່ອງດື່ມ ໄດ້ຮັບການທົດສອບ ແລະ ປຸງແຕ່ງຢ່າງພິຖີພິຖັນ ໂດຍທີມງານແຊັມບາຣິສຕ້າ ແລະ ມິກໂຊໂລຈິສມືອາຊີບ.'
                    : 'Every batch of our coffee is carefully tested and blended by our team of master baristas and mixologists.'; ?>
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
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">Sengdavone V.</h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        "ກາເຟບໍ່ແມ່ນພຽງແຕ່ເຄື່ອງດື່ມ, ແຕ່ມັນຄືສິລະປະ ແລະ ຄວາມຕັ້ງໃຈໃນທຸກໆຢອດເພື່ອມອບຄວາມສຸກໃຫ້ທ່ານ."
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">Sengdavone V.</strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ແຊັມບາຣິສຕ້າລະດັບຊາດ ປີ 2022 & Q-Grader' : '2022 Lao National Barista Champion & Q-Grader'; ?>
                        </span>
                    </div>
                </div>

                <!-- Barista 2 content (Hidden initially) -->
                <div id="barista-content-1" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ມິກໂຊໂລຈິສ ຜູ້ຊ່ຽວຊານ LaoFe' : 'LaoFe Master Mixologist'; ?>
                    </span>
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">Bounmy S.</h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        "ການປະສົມເຄື່ອງດື່ມໃນຍາມຄ່ຳຄືນ ຄືການປະສົມປະສານລົດຊາດທ້ອງຖິ່ນໃຫ້ເກີດຄວາມສຸກ ແລະ ຄວາມຊົງຈຳທີ່ດີ."
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">Bounmy S.</strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ຫົວໜ້າມິກໂຊໂລຈິສ & ຜູ້ຊ່ຽວຊານຄັອກເທວສະໝຸນໄພ' : 'Head Mixologist & Craft Herbal Cocktail Specialist'; ?>
                        </span>
                    </div>
                </div>

                <!-- Barista 3 content (Hidden initially) -->
                <div id="barista-content-2" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-extrabold text-gold-400 uppercase tracking-widest font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ນັກຂົ້ວກາເຟ ຜູ້ຊ່ຽວຊານ LaoFe' : 'LaoFe Master Roaster'; ?>
                    </span>
                    <h3 class="text-3xl font-extrabold font-serif-lao text-white">Aloun K.</h3>
                    <p class="text-xl md:text-2xl italic text-gray-200 font-light leading-relaxed font-serif-lao">
                        "ໄຟ, ອຸນຫະພູມ ແລະ ເວລາ ຄືສິ່ງກຳນົດຈິດວິນຍານ ແລະ ລົດຊາດທີ່ແທ້ຈິງຂອງເມັດກາເຟ."
                    </p>
                    <div class="pt-4 border-t border-white/15 flex flex-col space-y-1">
                        <strong class="text-white text-lg font-bold">Aloun K.</strong>
                        <span class="text-sm text-gray-300 font-light font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ຜູ້ຊ່ຽວຊານການຂົ້ວກາເຟ ບໍລະເວນ' : 'Bolaven Coffee Roasting Specialist'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Avatar Selectors (4 Cols) -->
            <div class="lg:col-span-4 flex lg:flex-col justify-center lg:justify-start gap-6">
                <button onclick="switchBarista(0)" class="barista-tab-btn active w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-gold-400 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/our_story.png" alt="Sengdavone" class="w-full h-full object-cover">
                </button>
                <button onclick="switchBarista(1)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-transparent hover:border-gold-400/50 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/beer_drink.png" alt="Bounmy" class="w-full h-full object-cover">
                </button>
                <button onclick="switchBarista(2)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-transparent hover:border-gold-400/50 overflow-hidden shrink-0 shadow-xl transition-all">
                    <img src="assets/images/coffee.png" alt="Aloun" class="w-full h-full object-cover">
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
    <div class="relative w-full max-w-7xl mx-auto px-4">
        <div class="swiper store-carousel-swiper w-full overflow-visible py-4">
            <div class="swiper-wrapper">
                
                <!-- Store 1: Namphou Vientiane -->
                <div class="swiper-slide transition-all duration-500">
                    <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-[16/9] md:aspect-[16/8] group">
                        <img src="assets/images/branch_namphou.png" alt="Namphou Vientiane Branch" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Bottom Blue/Burgundy Overlay Banner (Luckin Style) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#1C050B]/95 via-[#531321]/90 to-[#1C050B]/95 text-white p-5 md:p-8 border-t border-amber-400/30 backdrop-blur-md space-y-2">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <h3 class="text-2xl md:text-4xl font-black text-white font-serif-lao tracking-wider uppercase">
                                    <?php echo $current_lang === 'lo' ? 'ສາຂາ ນ້ຳພຸ (ນະຄອນຫຼວງວຽງຈັນ)' : 'NAMPHOU VIENTIANE'; ?>
                                </h3>
                                <a href="https://www.google.com/maps?q=17.966801,102.606311" target="_blank" class="self-start md:self-auto px-4 py-1.5 rounded-full bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold text-xs shadow-md transition-all font-serif-lao inline-flex items-center gap-1">
                                    <span>🗺️</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ແຜນທີ່ Google Maps' : 'Google Maps'; ?></span>
                                </a>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs md:text-sm text-amber-200 font-serif-lao">
                                <span class="flex items-center gap-1.5 font-semibold">
                                    <span>🕒</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຈັນ - ອາທິດ: 07:00 - 23:00 ໂມງ' : 'Mon - Sun: 07:00 AM - 11:00 PM'; ?></span>
                                </span>
                                <span class="flex items-center gap-1.5 text-amber-100/90 font-light">
                                    <span>📍</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຖະໜົນນ້ຳພຸ, ບ້ານຊຽງຍືນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ' : 'Namphou Rd, XiengNgeun Village, Vientiane Capital'; ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store 2: Luang Prabang -->
                <div class="swiper-slide transition-all duration-500">
                    <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-[16/9] md:aspect-[16/8] group">
                        <img src="assets/images/branch_luangprabang.png" alt="Luang Prabang Branch" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Bottom Blue/Burgundy Overlay Banner (Luckin Style) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#1C050B]/95 via-[#531321]/90 to-[#1C050B]/95 text-white p-5 md:p-8 border-t border-amber-400/30 backdrop-blur-md space-y-2">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <h3 class="text-2xl md:text-4xl font-black text-white font-serif-lao tracking-wider uppercase">
                                    <?php echo $current_lang === 'lo' ? 'ສາຂາ ຫຼວງພະບາງ' : 'LUANG PRABANG HERITAGE'; ?>
                                </h3>
                                <a href="https://maps.google.com" target="_blank" class="self-start md:self-auto px-4 py-1.5 rounded-full bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold text-xs shadow-md transition-all font-serif-lao inline-flex items-center gap-1">
                                    <span>🗺️</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ແຜນທີ່ Google Maps' : 'Google Maps'; ?></span>
                                </a>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs md:text-sm text-amber-200 font-serif-lao">
                                <span class="flex items-center gap-1.5 font-semibold">
                                    <span>🕒</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຈັນ - ອາທິດ: 07:00 - 22:00 ໂມງ' : 'Mon - Sun: 07:00 AM - 10:00 PM'; ?></span>
                                </span>
                                <span class="flex items-center gap-1.5 text-amber-100/90 font-light">
                                    <span>📍</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ແຂວງຫຼວງພະບາງ' : 'Sisavangvong Rd, Luang Prabang World Heritage Town'; ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store 3: Vang Vieng -->
                <div class="swiper-slide transition-all duration-500">
                    <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-[16/9] md:aspect-[16/8] group">
                        <img src="assets/images/branch_vangvieng.png" alt="Vang Vieng Branch" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Bottom Blue/Burgundy Overlay Banner (Luckin Style) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#1C050B]/95 via-[#531321]/90 to-[#1C050B]/95 text-white p-5 md:p-8 border-t border-amber-400/30 backdrop-blur-md space-y-2">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <h3 class="text-2xl md:text-4xl font-black text-white font-serif-lao tracking-wider uppercase">
                                    <?php echo $current_lang === 'lo' ? 'ສາຂາ ວັງວຽງ' : 'VANG VIENG RIVERSIDE'; ?>
                                </h3>
                                <a href="https://maps.google.com" target="_blank" class="self-start md:self-auto px-4 py-1.5 rounded-full bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold text-xs shadow-md transition-all font-serif-lao inline-flex items-center gap-1">
                                    <span>🗺️</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ແຜນທີ່ Google Maps' : 'Google Maps'; ?></span>
                                </a>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs md:text-sm text-amber-200 font-serif-lao">
                                <span class="flex items-center gap-1.5 font-semibold">
                                    <span>🕒</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຈັນ - ອາທິດ: 07:00 - 23:00 ໂມງ' : 'Mon - Sun: 07:00 AM - 11:00 PM'; ?></span>
                                </span>
                                <span class="flex items-center gap-1.5 text-amber-100/90 font-light">
                                    <span>📍</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ' : 'Nam Song Riverside Rd, Savang Village, Vang Vieng'; ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store 4: Pakse -->
                <div class="swiper-slide transition-all duration-500">
                    <div class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl border border-gray-200 aspect-[16/9] md:aspect-[16/8] group">
                        <img src="assets/images/branch_pakse.png" alt="Pakse Branch" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <!-- Bottom Blue/Burgundy Overlay Banner (Luckin Style) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-[#1C050B]/95 via-[#531321]/90 to-[#1C050B]/95 text-white p-5 md:p-8 border-t border-amber-400/30 backdrop-blur-md space-y-2">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <h3 class="text-2xl md:text-4xl font-black text-white font-serif-lao tracking-wider uppercase">
                                    <?php echo $current_lang === 'lo' ? 'ສາຂາ ປາກເຊ (ຈຳປາສັກ)' : 'PAKSE CHAMPASAK'; ?>
                                </h3>
                                <a href="https://maps.google.com" target="_blank" class="self-start md:self-auto px-4 py-1.5 rounded-full bg-amber-400 hover:bg-amber-300 text-gray-950 font-bold text-xs shadow-md transition-all font-serif-lao inline-flex items-center gap-1">
                                    <span>🗺️</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ແຜນທີ່ Google Maps' : 'Google Maps'; ?></span>
                                </a>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs md:text-sm text-amber-200 font-serif-lao">
                                <span class="flex items-center gap-1.5 font-semibold">
                                    <span>🕒</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຈັນ - ອາທິດ: 07:00 - 22:00 ໂມງ' : 'Mon - Sun: 07:00 AM - 10:00 PM'; ?></span>
                                </span>
                                <span class="flex items-center gap-1.5 text-amber-100/90 font-light">
                                    <span>📍</span>
                                    <span><?php echo $current_lang === 'lo' ? 'ຖະໜົນ 13 ໃຕ້, ເມືອງປາກເຊ, ແຂວງຈຳປາສັກ' : 'Route 13 South, Pakse City, Champasak'; ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Pagination Dots & Navigation Arrows -->
            <div class="swiper-pagination store-swiper-pagination !relative mt-8"></div>
            <div class="swiper-button-next store-next-btn text-burgundy-700 bg-white/90 shadow-xl rounded-full w-12 h-12 hidden md:flex items-center justify-center hover:bg-burgundy-700 hover:text-white transition-all"></div>
            <div class="swiper-button-prev store-prev-btn text-burgundy-700 bg-white/90 shadow-xl rounded-full w-12 h-12 hidden md:flex items-center justify-center hover:bg-burgundy-700 hover:text-white transition-all"></div>
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

        // 2. Luckin-Style Stores Center-Active Carousel Swiper
        new Swiper('.store-carousel-swiper', {
            slidesPerView: 1.15,
            centeredSlides: true,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
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
                768: {
                    slidesPerView: 1.35,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 1.55,
                    spaceBetween: 40,
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
