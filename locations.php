<?php
// locations.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນສາຂາຈາກ Database
$db_branches = [];
try {
    $stmt = $pdo->query("SELECT * FROM branches ORDER BY id ASC");
    $db_branches = $stmt->fetchAll();
} catch (\Exception $e) {
    // ຈັດການ error
}

// ຂໍ້ມູນ 4 ສາຂາສໍາລັບ Swiper Banner Slider
$branch_hero_slides = [
    [
        'title_lo' => 'ສາຂາ ນ້ຳພຸ - ວຽງຈັນ',
        'title_en' => 'Nam Phou Branch - Vientiane',
        'desc_lo' => 'ໃຈກາງນະຄອນຫຼວງວຽງຈັນ ບັນຍາກາດ Cafe & Beer Garden ຫຼູຫຼາ',
        'desc_en' => 'Heart of Vientiane with European cafe & luxury beer garden atmosphere',
        'badge' => '📍 ນະຄອນຫຼວງວຽງຈັນ (Vientiane Capital)',
        'image' => 'assets/images/branch_namphou.png',
        'hours' => '07:00 - 23:30'
    ],
    [
        'title_lo' => 'ສາຂາ ຫຼວງພະບາງ ມໍລະດົກໂລກ',
        'title_en' => 'Luang Prabang Heritage Branch',
        'desc_lo' => 'ເຮືອນໄມ້ໂບຮານລາວ ຕິດແຄມນ້ຳຂອງ ຊົມພູເຂົາ ແລະ ພະອາທິດ ຕົກດິນ',
        'desc_en' => 'Traditional Lao wooden heritage lounge with Mekong river sunset view',
        'badge' => '📍 ເມືອງມໍລະດົກໂລກ ຫຼວງພະບາງ',
        'image' => 'assets/images/branch_luangprabang.png',
        'hours' => '07:00 - 23:00'
    ],
    [
        'title_lo' => 'ສາຂາ ປາກເຊ - ຈຳປາສັກ',
        'title_en' => 'Pakse Branch - Champasak',
        'desc_lo' => 'ເລົາຈ໌ແຄມນ້ຳແບບທ່ຽວສະໄໝ ເສີບເບຍຄຣາຟເຢັນໆ ແລະ ກາເຟພຣີມ້ຽມ',
        'desc_en' => 'Sleek riverside lounge serving ice-cold craft beers & premium specialty coffee',
        'badge' => '📍 ປາກເຊ, ຈຳປາສັກ (Pakse)',
        'image' => 'assets/images/branch_pakse.png',
        'hours' => '08:00 - 23:30'
    ],
    [
        'title_lo' => 'ສາຂາ ວັງວຽງ - ວິວພູຜາແຄມຊອງ',
        'title_en' => 'Vang Vieng Riverside Branch',
        'desc_lo' => 'ລະບຽງກາງແຈ້ງຊົມວິວພູຜາປາໂດ່ງ ແລະ ນ້ຳຊອງ ບັນຍາກາດຊິວໆ',
        'desc_en' => 'Stunning outdoor terrace overlooking limestone mountain peaks & Nam Song river',
        'badge' => '📍 ວັງວຽງ, ແຂວງວຽງຈັນ (Vang Vieng)',
        'image' => 'assets/images/branch_vangvieng.png',
        'hours' => '07:30 - 23:30'
    ]
];

// ດຶງຂໍ້ມູນ Banner ຈາກຖານຂໍ້ມູນ (ສະເພາະ ທີ່ກຳນົດໃຫ້ໜ້າ Locations)
$db_banners = [];
try {
    $stmt_banner = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND target_page = 'locations' ORDER BY sort_order ASC, id DESC");
    $db_banners = $stmt_banner->fetchAll();
} catch (\Exception $e) {}

// ຮູບພາບປະກອບສໍາລັບ Cards ສາຂາ
$branch_card_images = [
    'assets/images/branch_namphou.png',
    'assets/images/branch_luangprabang.png',
    'assets/images/branch_pakse.png',
    'assets/images/branch_vangvieng.png'
];
?>

<!-- Hero Banner Swiper Slider Section (4 Branches Showcase / Admin Dynamic Banners) -->
<section class="relative w-full h-[65vh] min-h-[500px] max-h-[700px] overflow-hidden bg-black">
    <div class="swiper branchHeroSwiper w-full h-full">
        <div class="swiper-wrapper">
            <?php if (!empty($db_banners)): ?>
                <?php foreach ($db_banners as $b): ?>
                    <div class="swiper-slide relative w-full h-full">
                        <!-- Slide Background Image -->
                        <img src="<?php echo htmlspecialchars($b['image_path']); ?>" alt="<?php echo htmlspecialchars(td($b, 'title')); ?>" class="w-full h-full object-cover">
                        
                        <!-- Dark Gradient Vignette Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1C050B] via-black/45 to-black/30"></div>

                        <!-- Slide Content Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center text-center px-6 md:px-12">
                            <div class="max-w-4xl mx-auto space-y-4 pt-16">
                                
                                <?php if (!empty(td($b, 'badge'))): ?>
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#531321]/80 backdrop-blur-md text-amber-200 border border-amber-400/40 text-xs font-bold shadow-lg font-serif-lao">
                                        <span><?php echo htmlspecialchars(td($b, 'badge')); ?></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Slide Title -->
                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white font-serif-lao tracking-wide drop-shadow-lg leading-tight sm:whitespace-nowrap">
                                    <?php echo htmlspecialchars(str_replace([' ໆ', 'ເບຍສົດ'], ['ໆ', 'ເບຍ' . "\xC2\xA0" . 'ສົດ'], td($b, 'title'))); ?>
                                </h1>

                                <!-- Subtitle Description -->
                                <?php if (!empty(td($b, 'subtitle'))): ?>
                                    <p class="text-sm md:text-lg text-amber-100/90 font-light max-w-2xl mx-auto font-serif-lao leading-relaxed drop-shadow-md">
                                        <?php echo nl2br(htmlspecialchars(td($b, 'subtitle'))); ?>
                                    </p>
                                <?php endif; ?>

                                <!-- CTA Buttons -->
                                <div class="pt-4 flex items-center justify-center gap-4">
                                    <?php if (!empty(td($b, 'btn1_text')) && !empty($b['btn1_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($b['btn1_link']); ?>" class="px-6 py-2.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs md:text-sm shadow-xl transition-all hover:scale-105 inline-flex items-center space-x-2 font-serif-lao">
                                            <span><?php echo htmlspecialchars(td($b, 'btn1_text')); ?></span>
                                            <span>↓</span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty(td($b, 'btn2_text')) && !empty($b['btn2_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($b['btn2_link']); ?>" class="px-6 py-2.5 rounded-full bg-amber-400/90 hover:bg-amber-400 text-burgundy-950 font-bold text-xs md:text-sm shadow-xl transition-all hover:scale-105 inline-flex items-center space-x-2 font-serif-lao">
                                            <span><?php echo htmlspecialchars(td($b, 'btn2_text')); ?></span>
                                            <span>→</span>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($branch_hero_slides as $slide): ?>
                    <div class="swiper-slide relative w-full h-full">
                        <!-- Slide Background Image -->
                        <img src="<?php echo htmlspecialchars($slide['image']); ?>" alt="<?php echo htmlspecialchars($slide['title_lo']); ?>" class="w-full h-full object-cover">
                        
                        <!-- Dark Gradient Vignette Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1C050B] via-black/45 to-black/30"></div>

                        <!-- Slide Content Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center text-center px-6 md:px-12">
                            <div class="max-w-4xl mx-auto space-y-4 pt-16">
                                
                                <!-- Location Badge Pill -->
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#531321]/80 backdrop-blur-md text-amber-200 border border-amber-400/40 text-xs font-bold shadow-lg font-serif-lao">
                                    <span><?php echo htmlspecialchars($slide['badge']); ?></span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span><?php echo htmlspecialchars($slide['hours']); ?></span>
                                </div>

                                <!-- Branch Slide Title -->
                                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white font-serif-lao tracking-wide drop-shadow-lg leading-tight">
                                    <?php echo htmlspecialchars($current_lang === 'lo' ? $slide['title_lo'] : $slide['title_en']); ?>
                                </h1>

                                <!-- Branch Subtitle Description -->
                                <p class="text-sm md:text-lg text-amber-100/90 font-light max-w-2xl mx-auto font-serif-lao leading-relaxed drop-shadow-md">
                                    <?php echo htmlspecialchars($current_lang === 'lo' ? $slide['desc_lo'] : $slide['desc_en']); ?>
                                </p>

                                <!-- CTA Buttons -->
                                <div class="pt-4 flex items-center justify-center gap-4">
                                    <a href="#branch-list" class="px-6 py-2.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs md:text-sm shadow-xl transition-all hover:scale-105 inline-flex items-center space-x-2 font-serif-lao">
                                        <span><?php echo $current_lang === 'lo' ? 'ເບິ່ງລາຍລະອຽດສາຂານີ້' : 'Explore Branch Details'; ?></span>
                                        <span>↓</span>
                                    </a>
                                    <a href="booking.php" class="px-6 py-2.5 rounded-full bg-amber-400/90 hover:bg-amber-400 text-burgundy-950 font-bold text-xs md:text-sm shadow-xl transition-all hover:scale-105 inline-flex items-center space-x-2 font-serif-lao">
                                        <span><?php echo $current_lang === 'lo' ? 'ຈອງໂຕ໊ະ' : 'Book Table'; ?></span>
                                        <span>→</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Swiper Navigation Arrows & Pagination -->
        <div class="swiper-button-next text-white hover:text-amber-300"></div>
        <div class="swiper-button-prev text-white hover:text-amber-300"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Locations Grid Section -->
<section id="branch-list" class="py-20 bg-[#FAF7F2] px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-16">
        
        <div class="text-center space-y-3">
            <h2 class="text-3xl md:text-4xl font-extrabold font-serif-lao text-[#2C1810]">
                <?php echo $current_lang === 'lo' ? 'ລາຍຊື່ສາຂາ LaoFe & Beer ທົ່ວປະເທດ' : 'All LaoFe & Beer Branch Locations'; ?>
            </h2>
            <p class="text-sm text-[#6E584E] font-light font-serif-lao">
                <?php echo $current_lang === 'lo' ? 'ເລືອກສາຂາທີ່ໃກ້ທ່ານເພື່ອເບິ່ງທີ່ຕັ້ງ, ເວລາເປີດ-ປິດ ແລະ ເບີໂທຕິດຕໍ່' : 'Find your nearest branch for address, operating hours and contact info'; ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <?php 
            $index = 0;
            foreach ($db_branches as $branch): 
                $fallback_img = isset($branch_card_images[$index % 4]) ? $branch_card_images[$index % 4] : 'assets/images/branch_namphou.png';
                $current_img = !empty($branch['image_path']) ? $branch['image_path'] : $fallback_img;
                $index++;
            ?>
                <!-- Branch Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row card-hover-effect border border-[#EBE4D8]">
                    <!-- Branch Image -->
                    <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden bg-[#EFE8DD]">
                        <img src="<?php echo htmlspecialchars($current_img); ?>" alt="<?php echo htmlspecialchars(td($branch, 'name')); ?>" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                    <!-- Branch Content -->
                    <div class="md:w-1/2 p-8 flex flex-col justify-between space-y-6 bg-[#FAF7F2]">
                        <div class="space-y-4">
                            <span class="inline-block px-3 py-1 bg-burgundy-100 text-burgundy-800 text-xs font-bold rounded-full font-serif-lao">
                                Branch <?php echo $index; ?>
                            </span>
                            <h3 class="text-2xl font-bold text-[#2C1810] font-serif-lao"><?php echo htmlspecialchars(td($branch, 'name')); ?></h3>
                            <p class="text-sm text-[#6E584E] font-light leading-relaxed font-serif-lao"><?php echo htmlspecialchars(td($branch, 'address')); ?></p>
                            
                            <div class="space-y-2 pt-2 text-sm text-[#4A3B34] font-serif-lao">
                                <p class="flex items-center space-x-2">
                                    <strong class="text-burgundy-800"><?php echo t('loc_open'); ?></strong>
                                    <span><?php echo htmlspecialchars(td($branch, 'hours')); ?></span>
                                </p>
                                <p class="flex items-center space-x-2">
                                    <strong class="text-burgundy-800"><?php echo t('loc_phone'); ?></strong>
                                    <span><?php echo htmlspecialchars($branch['phone']); ?></span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <a href="<?php echo htmlspecialchars($branch['map_link']); ?>" target="_blank" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full text-sm font-bold transition-all duration-300 shadow-md font-serif-lao">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <?php echo t('loc_map'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- Swiper Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.branchHeroSwiper', {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
        },
        speed: 1000,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
