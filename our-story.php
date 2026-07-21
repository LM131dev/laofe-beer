<?php
// our-story.php - Comprehensive Master Our Story & About Page
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Section 1: Hero Banner (Luckin Our Story full-screen video background layout) -->
<section class="relative w-full h-[75vh] md:h-[85vh] flex items-center justify-center overflow-hidden bg-burgundy-700">
    <!-- Autoplaying looping video -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="https://ilucky-fe-outside-oss-prod.luckincdn.com/iadmin/ab6140f6-129c-4ae9-aaa0-20190c43183b.mp4" type="video/mp4"/>
        <source src="https://web.luckincdn.com/default/assets/ourstory-41023fc9.webm" type="video/webm"/>
    </video>
    
    <!-- Dark & Burgundy Overlay to ensure perfect text contrast -->
    <div class="absolute inset-0 bg-gradient-to-t from-burgundy-700/90 via-black/40 to-black/60"></div>
    
    <!-- Video Content Overlay -->
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white space-y-6">
        <div class="space-y-2">
            <h1 class="text-4xl md:text-7xl font-extrabold tracking-tight font-serif-lao leading-none text-white drop-shadow-lg">
                <?php echo t('story_hero_title1'); ?>
            </h1>
            <h2 class="text-3xl md:text-6xl font-bold tracking-tight font-serif-lao text-white/90 drop-shadow-md">
                <?php echo t('story_hero_title2'); ?>
            </h2>
        </div>
        
        <p class="text-base md:text-xl text-gray-200 font-light max-w-2xl mx-auto leading-relaxed drop-shadow">
            <?php echo t('story_hero_desc'); ?>
        </p>

        <div class="pt-4">
            <a href="menu.php" class="inline-flex items-center justify-center px-8 py-4 bg-white text-burgundy-700 hover:bg-burgundy-700 hover:text-white rounded-full font-bold text-sm md:text-base transition-all duration-300 shadow-2xl hover:scale-105 border-2 border-white">
                <?php echo t('story_hero_cta'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Section 2: Content Description (Luckin Style Clean Text Blocks) -->
<section class="py-20 bg-white px-6 md:px-12">
    <div class="max-w-4xl mx-auto space-y-12">
        <div class="p-8 md:p-12 rounded-3xl bg-gray-50 border-l-8 border-burgundy-700 shadow-sm space-y-4">
            <h3 class="text-2xl md:text-3xl font-bold text-burgundy-700 font-serif-lao">
                <?php echo t('story_title'); ?>
            </h3>
            <p class="text-lg md:text-xl text-gray-800 font-light leading-relaxed">
                <?php echo t('story_p1'); ?>
            </p>
        </div>

        <div class="p-8 md:p-12 rounded-3xl bg-gray-50 border-l-8 border-burgundy-700 shadow-sm space-y-4">
            <h3 class="text-2xl md:text-3xl font-bold text-burgundy-700 font-serif-lao">
                <?php echo t('story_subtitle'); ?>
            </h3>
            <p class="text-lg md:text-xl text-gray-800 font-light leading-relaxed">
                <?php echo t('story_p2'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 3: Value Proposition Summary (3 Cards Grid) -->
<section class="py-20 bg-gray-50 px-6 md:px-12 border-t border-gray-100">
    <div class="max-w-6xl mx-auto space-y-12">
        <div class="text-center space-y-3">
            <h2 class="text-3xl md:text-4xl font-bold text-burgundy-700 font-serif-lao tracking-tight">
                <?php echo t('about_value_prop_title'); ?>
            </h2>
            <div class="w-20 h-1 bg-burgundy-700 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: High Quality -->
            <div class="bg-white hover:bg-white rounded-3xl p-8 text-center space-y-5 border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                <div class="w-20 h-20 bg-burgundy-700 text-white rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wide">
                    <?php echo t('about_high_quality'); ?>
                </h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo t('about_high_quality_desc'); ?>
                </p>
            </div>

            <!-- Card 2: High Affordability -->
            <div class="bg-white hover:bg-white rounded-3xl p-8 text-center space-y-5 border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                <div class="w-20 h-20 bg-burgundy-700 text-white rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wide">
                    <?php echo t('about_high_affordability'); ?>
                </h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo t('about_high_affordability_desc'); ?>
                </p>
            </div>

            <!-- Card 3: High Convenience -->
            <div class="bg-white hover:bg-white rounded-3xl p-8 text-center space-y-5 border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                <div class="w-20 h-20 bg-burgundy-700 text-white rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 tracking-wide">
                    <?php echo t('about_high_convenience'); ?>
                </h3>
                <p class="text-sm text-gray-600 font-light leading-relaxed">
                    <?php echo t('about_high_convenience_desc'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Spotlight 1 - HIGH QUALITY -->
<section class="py-20 bg-burgundy-700 text-white px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight">
                HIGH<br/>QUALITY
            </h2>
            <div class="w-20 h-1 bg-white"></div>
            <p class="text-white/90 font-light leading-relaxed text-base md:text-lg">
                <?php echo $current_lang === 'lo'
                    ? 'ແກ່ນກາເຟຂອງພວກເຮົາສົ່ງກົງຈາກແຫຼ່ງປູກບໍລະເວນ ເຊິ່ງເປັນແຫຼ່ງປູກກາເຟທີ່ມີຊື່ສຽງລະດັບໂລກ. ທຸກໆ Batch ຂອງກາເຟໄດ້ຮັບການຄັດສັນ ແລະ ປຸງແຕ່ງຢ່າງພິຖີພິຖັນໂດຍທີມງານແຊັມບາຣິສຕ້າລະດັບຊາດ ແລະ Q-Grader ມືອາຊີບ ຈາກຫຼາຍກວ່າ 180 ສູດປະສົມ ເພື່ອໃຫ້ໄດ້ລົດຊາດທີ່ຖືກໃຈລູກຄ້າທີ່ສຸດ.'
                    : 'Our coffee beans come directly from the world-renowned Bolaven Plateau. Every batch of coffee is carefully selected and blended by our team of national champion baristas and professional Q-Graders, chosen from over 180 blending formulas to perfectly match your taste.';
                ?>
            </p>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-white/20 h-80 md:h-[400px]">
            <img src="assets/images/coffee.png" alt="High Quality Coffee" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
    </div>
</section>

<!-- Section 5: Spotlight 2 - HIGH AFFORDABILITY -->
<section class="py-20 bg-white px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1 rounded-3xl overflow-hidden shadow-2xl border border-gray-100 h-80 md:h-[400px]">
            <img src="assets/images/our_story.png" alt="High Affordability" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
        <div class="order-1 lg:order-2 space-y-6">
            <h2 class="text-3xl md:text-5xl font-bold text-burgundy-700 tracking-tight">
                HIGH<br/>AFFORDABILITY
            </h2>
            <div class="w-20 h-1 bg-burgundy-700"></div>
            <p class="text-gray-700 font-light leading-relaxed text-base md:text-lg">
                <?php echo $current_lang === 'lo'
                    ? 'ພວກເຮົາສົ່ງເສີມຮູບແບບການບໍລິການທີ່ວ່ອງໄວ ແລະ ທັນສະໄໝ. ລູກຄ້າສາມາດເພີດເພີນກັບກາເຟພຣີມ່ຽມ ແລະ ເຄື່ອງດື່ມບາລາວປະຍຸກ ໃນລາຄາທີ່ຈ່າຍໄດ້ງ່າຍ ທຸກໆມື້ ໂດຍບໍ່ຕ້ອງກັງວົນກ່ຽວກັບຄ່າໃຊ້ຈ່າຍທີ່ສູງເກີນໄປ.'
                    : 'We advocate a fast, modern retail experience. Customers can enjoy premium coffee and Lao-adapted craft beverages at truly accessible prices every day without worrying about high markup costs.';
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 6: Spotlight 3 - HIGH CONVENIENCE -->
<section class="py-20 bg-burgundy-700 text-white px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="text-3xl md:text-5xl font-bold tracking-tight">
                HIGH<br/>CONVENIENCE
            </h2>
            <div class="w-20 h-1 bg-white"></div>
            <p class="text-white/90 font-light leading-relaxed text-base md:text-lg">
                <?php echo $current_lang === 'lo'
                    ? 'ດ້ວຍສາຂາເລົາຈ໌ທີ່ທັນສະໄໝ 4 ສາຂາ ໃນວຽງຈັນ, ວັງວຽງ, ຫຼວງພະບາງ, ແລະ ປາກເຊ, LaoFe & Beer ພ້ອມຕອບໂຈດວິຖີຊີວິດຂອງທ່ານ ທັງຕອນກາງເວັນ (ກາເຟ) ແລະ ຕອນຄ່ຳ (ບາ) ດ້ວຍຄວາມສະດວກສະບາຍ ແລະ ບໍລິການທີ່ເປັນກັນເອງ.'
                    : 'With 4 modern lounge locations across Vientiane, Vang Vieng, Luang Prabang, and Pakse, LaoFe & Beer perfectly fits your lifestyle day (specialty coffee) and night (craft bar) with ultimate convenience and warm service.';
                ?>
            </p>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-white/20 h-80 md:h-[400px]">
            <img src="assets/images/hero_banner.png" alt="High Convenience Lounge" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
    </div>
</section>

<!-- Section 7: Vision Section -->
<section class="py-20 bg-white px-6 md:px-12">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="rounded-3xl overflow-hidden shadow-xl h-80 border border-gray-100">
            <img src="assets/images/our_story.png" alt="LaoFe Vision" class="w-full h-full object-cover">
        </div>
        <div class="space-y-6">
            <h2 class="text-3xl font-bold text-burgundy-700 border-l-4 border-burgundy-700 pl-4 font-serif-lao">
                <?php echo t('story_vision'); ?>
            </h2>
            <p class="text-gray-700 font-light leading-relaxed">
                <?php echo t('story_vision_desc'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 8: Infinite Image Marquee Section (Based on Luckin style with 2 rows of sliding images moving in opposite directions) -->
<section class="py-24 bg-gray-50 overflow-hidden space-y-8 border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center mb-10">
        <h2 class="text-3xl font-bold text-burgundy-700 font-serif-lao">Atmosphere & Concept Gallery</h2>
        <p class="text-gray-500 font-light mt-2">Take a glimpse of our design and drinks</p>
    </div>

    <!-- Row 1: Sliding Left -->
    <div class="marquee-container">
        <div class="animate-marquee-left">
            <!-- Slide Part 1 -->
            <div class="marquee-slide">
                <img src="assets/images/hero_banner.png" alt="LaoFe Gallery">
                <img src="assets/images/our_story.png" alt="LaoFe Gallery">
                <img src="assets/images/coffee.png" alt="LaoFe Gallery">
                <img src="assets/images/beer_drink.png" alt="LaoFe Gallery">
            </div>
            <!-- Slide Part 2 (Duplicate for seamless loop) -->
            <div class="marquee-slide">
                <img src="assets/images/hero_banner.png" alt="LaoFe Gallery">
                <img src="assets/images/our_story.png" alt="LaoFe Gallery">
                <img src="assets/images/coffee.png" alt="LaoFe Gallery">
                <img src="assets/images/beer_drink.png" alt="LaoFe Gallery">
            </div>
        </div>
    </div>

    <!-- Row 2: Sliding Right -->
    <div class="marquee-container">
        <div class="animate-marquee-right">
            <!-- Slide Part 1 -->
            <div class="marquee-slide">
                <img src="assets/images/beer_drink.png" alt="LaoFe Gallery">
                <img src="assets/images/coffee.png" alt="LaoFe Gallery">
                <img src="assets/images/our_story.png" alt="LaoFe Gallery">
                <img src="assets/images/hero_banner.png" alt="LaoFe Gallery">
            </div>
            <!-- Slide Part 2 (Duplicate for seamless loop) -->
            <div class="marquee-slide">
                <img src="assets/images/beer_drink.png" alt="LaoFe Gallery">
                <img src="assets/images/coffee.png" alt="LaoFe Gallery">
                <img src="assets/images/our_story.png" alt="LaoFe Gallery">
                <img src="assets/images/hero_banner.png" alt="LaoFe Gallery">
            </div>
        </div>
    </div>
</section>


<?php
require_once __DIR__ . '/includes/footer.php';
?>
