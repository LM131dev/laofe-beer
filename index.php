<?php
// index.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນໂປຣໂມຊັນຫຼ້າສຸດ 1 ລາຍການ
$promo = null;
try {
    $stmt_promo = $pdo->query("SELECT * FROM news WHERE is_promo = 1 ORDER BY publish_date DESC LIMIT 1");
    $promo = $stmt_promo->fetch();
} catch (\Exception $e) {
    // ຈັດການ error ຖ້າມີ
}

// ດຶງເມນູຍອດນິຍົມ 3 ລາຍການ
$popular_items = [];
try {
    $stmt_pop = $pdo->query("SELECT * FROM menus WHERE is_popular = 1 ORDER BY id DESC LIMIT 3");
    $popular_items = $stmt_pop->fetchAll();
} catch (\Exception $e) {
    // ຈັດການ error ຖ້າມີ
}
?>

<!-- Section 1: Hero Swiper Banner -->
<section class="relative w-full h-[70vh] md:h-[80vh] bg-burgundy-700 overflow-hidden">
    <div class="swiper main-hero-swiper w-full h-full">
        <div class="swiper-wrapper">
            <!-- Slide 1: Coffee Focus -->
            <div class="swiper-slide relative w-full h-full">
                <img src="assets/images/hero_banner.png" alt="LaoFe & Beer Coffee" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 hero-gradient-overlay"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="max-w-5xl mx-auto px-6 text-center text-white space-y-6">
                        <span class="inline-block px-4 py-1.5 bg-white text-burgundy-700 rounded-full text-xs font-bold uppercase tracking-widest shadow-md">
                            <?php echo $current_lang === 'lo' ? 'ກາເຟ ແລະ ບາ ແບບລາວປະຍຸກ' : 'Lao-Adapted Coffee & Bar'; ?>
                        </span>
                        <h1 class="text-4xl md:text-7xl font-bold tracking-tight leading-tight font-serif-lao">
                            <?php echo t('hero_title'); ?>
                        </h1>
                        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed">
                            <?php echo t('hero_subtitle'); ?>
                        </p>
                        <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                            <a href="menu.php" class="btn-premium px-8 py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full font-bold shadow-lg shadow-burgundy-900/30 transition-all duration-300 text-base">
                                <?php echo t('hero_cta'); ?>
                            </a>
                            <a href="our-story.php" class="px-8 py-3.5 border border-white hover:bg-white hover:text-burgundy-700 text-white rounded-full font-bold transition-all duration-300 text-base">
                                <?php echo t('nav_story'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2: Bar Focus -->
            <div class="swiper-slide relative w-full h-full">
                <img src="assets/images/beer_drink.png" alt="LaoFe & Beer Bar" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 hero-gradient-overlay"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="max-w-5xl mx-auto px-6 text-center text-white space-y-6">
                        <span class="inline-block px-4 py-1.5 bg-white text-burgundy-700 rounded-full text-xs font-bold uppercase tracking-widest shadow-md">
                            <?php echo $current_lang === 'lo' ? 'ແນວຄິດການພັກຜ່ອນຍາມຄ່ຳຄືນ' : 'Premium Nightlife Concept'; ?>
                        </span>
                        <h1 class="text-4xl md:text-7xl font-bold tracking-tight leading-tight font-serif-lao">
                            <?php echo $current_lang === 'lo' ? 'ກາງເວັນກາເຟ ກາງຄືນເບຍສົດ' : 'Coffee By Day, Beer By Night'; ?>
                        </h1>
                        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed">
                            <?php echo $current_lang === 'lo' 
                                ? 'ດື່ມດ່ຳກັບເບຍຄຣາບລາວ ແລະ ຄັອກເທວສະໝຸນໄພສູດພິເສດ ໃນບັນຍາກາດຊິລໆຍາມຄ່ຳຄືນ' 
                                : 'Savor the craft Lao beer and herbal signature cocktails in a relaxed premium lounge evening.'; ?>
                        </p>
                        <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                            <a href="menu.php" class="btn-premium px-8 py-3.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full font-bold shadow-lg shadow-burgundy-900/30 transition-all duration-300 text-base">
                                <?php echo t('hero_cta'); ?>
                            </a>
                            <a href="locations.php" class="px-8 py-3.5 border border-white hover:bg-white hover:text-burgundy-700 text-white rounded-full font-bold transition-all duration-300 text-base">
                                <?php echo t('nav_locations'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Swiper Controls -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next hidden md:flex"></div>
        <div class="swiper-button-prev hidden md:flex"></div>
    </div>
</section>

<!-- Section 2: Explore Our Coffee & Bar World -->
<section class="py-24 bg-cream-100 px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-5xl font-bold text-burgundy-700 font-serif-lao"><?php echo t('explore_title'); ?></h2>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <!-- Pillar 1 -->
            <div class="bg-white p-8 rounded-2xl shadow-md border border-burgundy-700/5 space-y-5 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-burgundy-50 rounded-full flex items-center justify-center mx-auto text-burgundy-700">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wider"><?php echo t('high_quality_beans'); ?></h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ພວກເຮົາຄັດສັນແກ່ນກາເຟອາຣາບິກ້າແທ້ 100% ຈາກພູພຽງບໍລະເວນ ເຫຼັ່ງປູກທີ່ດີທີ່ສຸດໃນລາວ.'
                        : 'At LaoFe, we use only the finest Arabica beans sourced directly from the Bolaven Plateau, roasted to perfection.'; ?>
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-white p-8 rounded-2xl shadow-md border border-burgundy-700/5 space-y-5 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-burgundy-50 rounded-full flex items-center justify-center mx-auto text-burgundy-700">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11V7a4 4 0 00-8 0v4c0 2.508 1.11 4.757 2.876 6.302m9.124-6.302A13.916 13.916 0 0121 11v4c0 2.508-1.11 4.757-2.876 6.302M12 11h.01" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wider"><?php echo t('blended_by_champions'); ?></h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ທຸກໆຈອກໄດ້ຮັບການທົດສອບ ແລະ ປຸງແຕ່ງຢ່າງພິຖີພິຖັນ ໂດຍທີມງານບາຣິສຕ້າ ແລະ ມິກໂຊໂລຈິສມືອາຊີບ.'
                        : 'Every batch and recipe is carefully crafted and tested by our master baristas and expert mixologists.'; ?>
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-white p-8 rounded-2xl shadow-md border border-burgundy-700/5 space-y-5 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-burgundy-50 rounded-full flex items-center justify-center mx-auto text-burgundy-700">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wider"><?php echo t('freshly_roasted_poured'); ?></h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo $current_lang === 'lo'
                        ? 'ຮັບປະກັນຄວາມສົດໃໝ່ຂອງກາເຟຂົ້ວບົດ ແລະ ເບຍຄຣາບດຶງສົດເຢັນໆ ທີ່ຮັກສາລົດຊາດທີ່ດີທີ່ສຸດ.'
                        : 'We guarantee freshly ground coffee and cold draft beers poured straight from the tap to preserve maximum freshness.'; ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section 2.5: Dynamic Promotion (Database Driven) -->
<section class="py-20 bg-white px-6 md:px-12 border-t border-gray-50">
    <div class="max-w-7xl mx-auto space-y-12">
        <div class="text-center space-y-2">
            <h2 class="text-3xl md:text-5xl font-bold text-burgundy-700 font-serif-lao"><?php echo t('promo_title'); ?></h2>
            <p class="text-gray-600 font-light max-w-md mx-auto"><?php echo t('promo_sub'); ?></p>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4"></div>
        </div>

        <?php if ($promo): ?>
            <div class="bg-cream-100 rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 gap-0 card-hover-effect">
                <div class="h-64 lg:h-full relative min-h-[300px]">
                    <img src="<?php echo htmlspecialchars($promo['image_path']); ?>" alt="<?php echo htmlspecialchars(td($promo, 'title')); ?>" class="w-full h-full object-cover">
                </div>
                <div class="p-8 md:p-12 flex flex-col justify-center space-y-6">
                    <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider"><?php echo t('special_offer'); ?></span>
                    <h3 class="text-2xl md:text-4xl font-bold text-gray-900 font-serif-lao"><?php echo htmlspecialchars(td($promo, 'title')); ?></h3>
                    <p class="text-gray-600 leading-relaxed font-light"><?php echo nl2br(htmlspecialchars(td($promo, 'content'))); ?></p>
                    <div>
                        <a href="news.php" class="inline-flex items-center space-x-2 text-burgundy-700 hover:text-burgundy-800 font-bold">
                            <span><?php echo t('learn_more'); ?></span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-cream-100 rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 gap-0 card-hover-effect">
                <div class="h-64 lg:h-full relative min-h-[300px]">
                    <img src="assets/images/beer_drink.png" alt="LaoFe Beer Promotion" class="w-full h-full object-cover">
                </div>
                <div class="p-8 md:p-12 flex flex-col justify-center space-y-6">
                    <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider">LaoFe & Beer</span>
                    <h3 class="text-2xl md:text-4xl font-bold text-gray-900 font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ຊື້ 2 ແຖມ 1 ທຸກໆວັນສຸກ!' : 'Buy 2 Get 1 Free Every Friday!'; ?>
                    </h3>
                    <p class="text-gray-600 leading-relaxed font-light">
                        <?php echo $current_lang === 'lo' 
                            ? 'ພິເສດສຸດໆ ສຳລັບຄົນຮັກເບຍ ແລະ ຄັອກເທວລາວປະຍຸກ! ທຸກໆວັນສຸກເວລາ 17:00 - 20:00 ໂມງ ຊື້ 2 ຈອກ ແຖມຟຣີ 1 ຈອກທັນທີ ເພື່ອສ້າງບັນຍາກາດການພັກຜ່ອນທ້າຍອາທິດທີ່ດີທີ່ສຸດຂອງທ່ານ.'
                            : 'Specially for craft beer and Lao-adapted cocktail lovers! Every Friday from 5:00 PM to 8:00 PM, buy any 2 drinks and get 1 free to start your weekend relaxation in style.'; ?>
                    </p>
                    <div>
                        <a href="news.php" class="inline-flex items-center space-x-2 text-burgundy-700 hover:text-burgundy-800 font-bold">
                            <span><?php echo t('learn_more'); ?></span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Section 3: Bolaven Arabica Premium Selection (Parallax style) -->
<section class="relative py-32 parallax-bg overflow-hidden text-white" style="background-image: url('assets/images/coffee.png');">
    <!-- Burgundy semi-transparent overlay to keep text readable and branded -->
    <div class="absolute inset-0 bg-gradient-to-r from-burgundy-700/90 via-burgundy-700/60 to-transparent"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2">
        <div class="space-y-6 max-w-xl">
            <span class="text-xs font-bold text-gold-400 uppercase tracking-widest"><?php echo t('premium_selection_badge'); ?></span>
            <h2 class="text-3xl md:text-5xl font-bold font-serif-lao leading-tight">
                <?php echo $current_lang === 'lo' ? 'ພວກເຮົາພາກພູມໃຈໃນການນຳສະເໜີ ແກ່ນກາເຟບໍລະເວນ' : 'We are proud to present you Bolaven Arabica beans'; ?>
            </h2>
            <div class="w-20 h-1 bg-white"></div>
            <p class="text-gray-300 font-light leading-relaxed">
                <?php echo $current_lang === 'lo'
                    ? 'ກາເຟຂອງພວກເຮົາໄດ້ຮັບການຄັດເລືອກຈາກສູດການປະສົມຫຼາຍກວ່າ 50 ສູດ ທີ່ປັບປຸງມາໃຫ້ເຂົ້າກັບລົດຊາດຂອງຜູ້ບໍລິໂພກທັງພາຍໃນ ແລະ ຕ່າງປະເທດ ຢ່າງລົງຕົວ.'
                    : 'Our coffee is curated from more than 50 blending formulas, carefully balanced and adjusted to highly match the taste and preferences of modern coffee lovers.'; ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 4: Master Baristas & Mixologists Showcase (Tab system) -->
<section class="py-24 bg-burgundy-700 text-white px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-5xl font-bold font-serif-lao text-white"><?php echo t('blended_by_masters'); ?></h2>
            <p class="text-gray-400 font-light max-w-md mx-auto"><?php echo t('meet_experts'); ?></p>
            <div class="w-24 h-1 bg-white mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left: Barista Tab Contents (Lg: 8 cols) -->
            <div class="lg:col-span-8 bg-white/10 p-8 md:p-12 rounded-3xl border border-white/20 space-y-8 relative overflow-hidden min-h-[350px]">
                
                <!-- Barista 1 content -->
                <div id="barista-content-0" class="barista-content-fade space-y-6">
                    <span class="text-xs font-bold text-gold-400 uppercase tracking-widest"><?php echo t('master_barista'); ?></span>
                    <h3 class="text-3xl font-bold font-serif-lao">Sengdavone</h3>
                    <p class="text-xl italic text-gray-300 font-light leading-relaxed">
                        "ກາເຟບໍ່ແມ່ນພຽງແຕ່ເຄື່ອງດື່ມ, ແຕ່ມັນຄືສິລະປະ ແລະ ຄວາມຕັ້ງໃຈໃນທຸກໆຢອດເພື່ອມອບຄວາມສຸກໃຫ້ທ່ານ."
                    </p>
                    <div class="flex flex-col space-y-1">
                        <strong class="text-white text-lg">Sengdavone V.</strong>
                        <span class="text-xs text-gray-400"><?php echo t('sengdavone_title'); ?></span>
                    </div>
                </div>

                <!-- Barista 2 content (Hidden initially) -->
                <div id="barista-content-1" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-bold text-gold-400 uppercase tracking-widest"><?php echo t('master_mixologist'); ?></span>
                    <h3 class="text-3xl font-bold font-serif-lao">Bounmy</h3>
                    <p class="text-xl italic text-gray-300 font-light leading-relaxed">
                        "ການປະສົມເຄື່ອງດື່ມໃນຍາມຄ່ຳຄືນ ຄືການປະສົມປະສານລົດຊາດທ້ອງຖິ່ນໃຫ້ເກີດຄວາມສຸກ ແລະ ຄວາມຊົງຈຳທີ່ດີ."
                    </p>
                    <div class="flex flex-col space-y-1">
                        <strong class="text-white text-lg">Bounmy S.</strong>
                        <span class="text-xs text-gray-400"><?php echo t('bounmy_title'); ?></span>
                    </div>
                </div>

                <!-- Barista 3 content (Hidden initially) -->
                <div id="barista-content-2" class="barista-content-fade space-y-6 hidden opacity-0 translate-y-4">
                    <span class="text-xs font-bold text-gold-400 uppercase tracking-widest"><?php echo t('master_roaster'); ?></span>
                    <h3 class="text-3xl font-bold font-serif-lao">Aloun</h3>
                    <p class="text-xl italic text-gray-300 font-light leading-relaxed">
                        "ໄຟ, ອຸນຫະພູມ ແລະ ເວລາ ຄືສິ່ງກຳນົດຈິດວິນຍານ ແລະ ລົດຊາດທີ່ແທ້ຈິງຂອງເມັດກາເຟ."
                    </p>
                    <div class="flex flex-col space-y-1">
                        <strong class="text-white text-lg">Aloun K.</strong>
                        <span class="text-xs text-gray-400"><?php echo t('aloun_title'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right: Tab selectors (Lg: 4 cols) -->
             <div class="lg:col-span-4 flex lg:flex-col justify-center lg:justify-start gap-6">
                <!-- Tab btn 1 -->
                <button onclick="switchBarista(0)" class="barista-tab-btn active w-20 h-20 md:w-24 md:h-24 rounded-full border-2 border-burgundy-700 overflow-hidden shrink-0">
                    <img src="assets/images/our_story.png" alt="Sengdavone" class="w-full h-full object-cover">
                </button>
                <!-- Tab btn 2 -->
                <button onclick="switchBarista(1)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-2 border-transparent overflow-hidden shrink-0">
                    <img src="assets/images/beer_drink.png" alt="Bounmy" class="w-full h-full object-cover">
                </button>
                <!-- Tab btn 3 -->
                <button onclick="switchBarista(2)" class="barista-tab-btn w-20 h-20 md:w-24 md:h-24 rounded-full border-2 border-transparent overflow-hidden shrink-0">
                    <img src="assets/images/coffee.png" alt="Aloun" class="w-full h-full object-cover">
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Freshly Brewed & Cold Draft Poured -->
<section class="py-24 bg-white px-6 md:px-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="space-y-6">
            <span class="text-xs font-bold text-burgundy-700 uppercase tracking-wider"><?php echo t('equipment_badge'); ?></span>
            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 font-serif-lao leading-tight"><?php echo t('equipment_title'); ?></h2>
            <div class="w-20 h-1 bg-burgundy-700"></div>
            <p class="text-gray-600 font-light leading-relaxed">
                <?php echo $current_lang === 'lo'
                    ? 'ພວກເຮົາໃຊ້ເຄື່ອງຊົງກາເຟທີ່ທັນສະໄໝລະດັບໂລກຈາກສະວິດເຊີແລນ (SCHAERER) ໃນທຸກສາຂາ. ແລະ ລະບົບດຶງເບຍສົດທີ່ຮັກສາອຸນຫະພູມ ແລະ ຄວາມກົດດັນຢ່າງເໝາະສົມ ເພື່ອໃຫ້ເຄື່ອງດື່ມທຸກຈອກມີຄວາມສົດໃໝ່ ແລະ ມີລົດຊາດທີ່ດີທີ່ສຸດ.'
                    : 'We adopt state-of-the-art Swiss SCHAERER espresso machines and advanced draft systems in all outlets. By combining premium ingredients with top-tier technology, we guarantee the perfect freshness and taste in every single cup.'; ?>
            </p>
        </div>
        <div class="relative rounded-2xl overflow-hidden shadow-2xl h-96">
            <img src="assets/images/our_story.png" alt="LaoFe Lounge Concept" class="absolute inset-0 w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- Section 6: Brand Video Showcase (Autoplayer Loop Video) -->
<section class="w-full bg-burgundy-700">
    <div class="video-container">
        <!-- Autoplaying loop stock video of coffee and bar crafting -->
        <video autoplay muted loop playsinline class="w-full h-full object-cover">
            <source src="https://ilucky-fe-outside-oss-prod.luckincdn.com/iadmin/f7427c5a-f6f7-4b47-bbc7-37ac36020ae8.mp4" type="video/mp4"/>
            Your browser does not support the video tag.
        </video>
    </div>
</section>

<!-- Popular Items Section (CMS Dynamic Grid) -->
<section class="py-24 px-6 md:px-12 bg-cream-100">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-2">
            <h2 class="text-3xl md:text-5xl font-bold text-burgundy-700 font-serif-lao"><?php echo t('popular_title'); ?></h2>
            <p class="text-gray-600 font-light max-w-md mx-auto"><?php echo t('popular_sub'); ?></p>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (count($popular_items) > 0): ?>
                <?php foreach ($popular_items as $item): ?>
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden flex flex-col card-hover-effect">
                        <div class="h-64 relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars(td($item, 'name')); ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 font-serif-lao"><?php echo htmlspecialchars(td($item, 'name')); ?></h3>
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2 font-light leading-relaxed"><?php echo htmlspecialchars(td($item, 'description')); ?></p>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="text-lg font-bold text-burgundy-700">
                                    <?php echo number_format($item['price']); ?> <?php echo t('currency'); ?>
                                </span>
                                <span class="px-3 py-1 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-semibold">
                                    <?php echo htmlspecialchars(t('menu_' . $item['category'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- 1. Coconut Latte -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden flex flex-col card-hover-effect">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/coffee.png" alt="Coconut Latte" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 font-serif-lao">
                                <?php echo $current_lang === 'lo' ? 'ລາວເຟ ໂຄໂຄນັດ ລາເຕ້' : 'LaoFe Coconut Latte'; ?>
                            </h3>
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2 font-light leading-relaxed">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ກາເຟເອສເປຣສໂຊທີ່ເຂັ້ມຂຸ້ນ ຜສົມຜະສານກັບນ້ຳໝາກພ້າວສົດ ແລະ ນ້ຳນົມໝາກພ້າວສູດພິເສດ ໃຫ້ລົດຊາດທີ່ຫວານມັນ ຫອມລະມຸນ.'
                                    : 'A rich espresso shot layered with fresh coconut water and our signature coconut cream, delivering a smooth, refreshing, and tropical taste.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-lg font-bold text-burgundy-700">35,000 <?php echo t('currency'); ?></span>
                            <span class="px-3 py-1 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-semibold">
                                <?php echo t('menu_coffee'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 2. Craft Lao Beer -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden flex flex-col card-hover-effect">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/beer_drink.png" alt="Lao Craft Beer" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 font-serif-lao">
                                <?php echo $current_lang === 'lo' ? 'ເບຍລາວຄຣາບພຣີມ່ຽມ' : 'Premium Lao Craft Beer'; ?>
                            </h3>
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2 font-light leading-relaxed">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ເບຍສົດຄຣາບຄຸນນະພາບສູງ ໝັກຈາກເຂົ້າຫອມລາວແທ້ໆ ໃຫ້ລົດຊາດທີ່ນຸ້ມນວນ ແລະ ກິ່ນຫອມອັນເປັນເອກະລັກ.'
                                    : 'High-quality draft craft beer brewed locally with authentic Lao jasmine rice, offering a smooth finish and a unique aroma.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-lg font-bold text-burgundy-700">45,000 <?php echo t('currency'); ?></span>
                            <span class="px-3 py-1 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-semibold">
                                <?php echo t('menu_bar'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 3. Lao Fusion Food -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden flex flex-col card-hover-effect">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/our_story.png" alt="Lao Fusion Food" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 font-serif-lao">
                                <?php echo $current_lang === 'lo' ? 'ລາບໝູຄຣິສປີລາວປະຍຸກ' : 'Crispy Lao Fusion Larb'; ?>
                            </h3>
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2 font-light leading-relaxed">
                                <?php echo $current_lang === 'lo' 
                                    ? 'ລາບໝູສະໝຸນໄພລາວແບບດັ້ງເດີມ ແຕ່ເສີບພ້ອມໝູກອບ ແລະ ຜັກສົດອໍການິກ ຈັດຈານຢ່າງທັນສະໄໝ.'
                                    : 'Traditional minced pork salad with Lao herbs, served crispy style with organic fresh vegetables, beautifully plated for a modern experience.'; ?>
                            </p>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-lg font-bold text-burgundy-700">55,000 <?php echo t('currency'); ?></span>
                            <span class="px-3 py-1 bg-burgundy-50 text-burgundy-700 rounded-full text-xs font-semibold">
                                <?php echo t('menu_food'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center pt-8">
            <a href="menu.php" class="inline-flex items-center justify-center px-8 py-3 border-2 border-burgundy-700 text-burgundy-700 hover:bg-burgundy-700 hover:text-white rounded-full transition-all duration-300 font-bold shadow-sm">
                <?php echo t('view_all'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Section 7: LaoFe Outlets & Lounges (Swiper Slider) -->
<section class="py-24 bg-white px-6 md:px-12 overflow-hidden">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-5xl font-bold font-serif-lao text-burgundy-700"><?php echo t('outlets_title'); ?></h2>
            <p class="text-gray-500 font-light mt-2"><?php echo t('outlets_sub'); ?></p>
            <div class="w-24 h-1 bg-burgundy-700 mx-auto mt-4"></div>
        </div>

        <!-- Outlets Slider -->
        <div class="swiper outlets-swiper w-full px-4">
            <div class="swiper-wrapper py-6">
                <!-- Slide 1 -->
                <div class="swiper-slide card-hover-effect border border-gray-100 rounded-2xl overflow-hidden shadow-md bg-cream-50 flex flex-col h-full">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/hero_banner.png" alt="Sithong Branch" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 space-y-3 flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 font-serif-lao"><?php echo t('lounge_sithong'); ?></h3>
                        <p class="text-xs text-gray-500 font-light leading-relaxed"><?php echo t('sithong_hours'); ?></p>
                        <a href="locations.php" class="inline-block text-xs font-semibold text-burgundy-700 hover:underline pt-2"><?php echo t('view_map_phone'); ?> &rarr;</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide card-hover-effect border border-gray-100 rounded-2xl overflow-hidden shadow-md bg-cream-50 flex flex-col h-full">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/beer_drink.png" alt="Vangvieng Branch" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 space-y-3 flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 font-serif-lao"><?php echo t('lounge_vangvieng'); ?></h3>
                        <p class="text-xs text-gray-500 font-light leading-relaxed"><?php echo t('vangvieng_hours'); ?></p>
                        <a href="locations.php" class="inline-block text-xs font-semibold text-burgundy-700 hover:underline pt-2"><?php echo t('view_map_phone'); ?> &rarr;</a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide card-hover-effect border border-gray-100 rounded-2xl overflow-hidden shadow-md bg-cream-50 flex flex-col h-full">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/coffee.png" alt="Luang Prabang Branch" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 space-y-3 flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 font-serif-lao"><?php echo t('lounge_luangprabang'); ?></h3>
                        <p class="text-xs text-gray-500 font-light leading-relaxed"><?php echo t('luangprabang_hours'); ?></p>
                        <a href="locations.php" class="inline-block text-xs font-semibold text-burgundy-700 hover:underline pt-2"><?php echo t('view_map_phone'); ?> &rarr;</a>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="swiper-slide card-hover-effect border border-gray-100 rounded-2xl overflow-hidden shadow-md bg-cream-50 flex flex-col h-full">
                    <div class="h-64 relative overflow-hidden">
                        <img src="assets/images/our_story.png" alt="Pakse Branch" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 space-y-3 flex-grow">
                        <h3 class="text-xl font-bold text-gray-900 font-serif-lao"><?php echo t('lounge_pakse'); ?></h3>
                        <p class="text-xs text-gray-500 font-light leading-relaxed"><?php echo t('pakse_hours'); ?></p>
                        <a href="locations.php" class="inline-block text-xs font-semibold text-burgundy-700 hover:underline pt-2"><?php echo t('view_map_phone'); ?> &rarr;</a>
                    </div>
                </div>
            </div>
            <!-- Pagination/Navigation -->
            <div class="swiper-pagination !bottom-0"></div>
            <div class="swiper-button-next hidden md:flex"></div>
            <div class="swiper-button-prev hidden md:flex"></div>
        </div>
    </div>
</section>

<!-- Inline scripts to initialize Swipers and handle Tab switching -->
<script>
    // Initialize Main Hero Swiper
    const heroSwiper = new Swiper('.main-hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.main-hero-swiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.main-hero-swiper .swiper-button-next',
            prevEl: '.main-hero-swiper .swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    // Initialize Outlets Swiper
    const outletsSwiper = new Swiper('.outlets-swiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        pagination: {
            el: '.outlets-swiper .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.outlets-swiper .swiper-button-next',
            prevEl: '.outlets-swiper .swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });

    // Barista & Mixologist Tab Switching Logic
    function switchBarista(index) {
        // Hide all barista contents
        const contents = document.querySelectorAll('.barista-content-fade');
        contents.forEach(content => {
            content.classList.add('hidden', 'opacity-0', 'translate-y-4');
        });

        // Remove active state from all buttons
        const buttons = document.querySelectorAll('.barista-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active', 'border-burgundy-700');
            btn.classList.add('border-transparent');
        });

        // Show selected content
        const activeContent = document.getElementById(`barista-content-${index}`);
        activeContent.classList.remove('hidden');
        
        // Trigger smooth browser reflow for fade animation
        setTimeout(() => {
            activeContent.classList.remove('opacity-0', 'translate-y-4');
        }, 50);

        // Highlight selected button
        buttons[index].classList.add('active', 'border-burgundy-700');
        buttons[index].classList.remove('border-transparent');
    }
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
