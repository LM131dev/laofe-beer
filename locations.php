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

// ຮູບພາບປະກອບຕົວຢ່າງສໍາລັບສາຂາ
$branch_images = [
    'Sithong' => 'assets/images/hero_banner.png',
    'Vangvieng' => 'assets/images/beer_drink.png',
    'Phu 9 Lak' => 'assets/images/our_story.png',
    'Luang Prabang' => 'assets/images/coffee.png'
];
?>

<!-- Title Header Section -->
<section class="py-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('loc_title'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo t('loc_sub'); ?></p>
    </div>
</section>

<!-- Locations Grid Section -->
<section class="py-20 bg-gray-50 px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <?php 
            $index = 0;
            foreach ($db_branches as $branch): 
                // ເລືອກຮູບພາບຕົວຢ່າງຕາມລຳດັບສາຂາ
                $img_keys = ['Sithong', 'Vangvieng', 'Phu 9 Lak', 'Luang Prabang'];
                $current_img = isset($branch_images[$img_keys[$index % 4]]) ? $branch_images[$img_keys[$index % 4]] : 'assets/images/hero_banner.png';
                $index++;
            ?>
                <!-- Branch Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row card-hover-effect">
                    <!-- Branch Image -->
                    <div class="md:w-1/2 h-64 md:h-auto relative">
                        <img src="<?php echo htmlspecialchars($current_img); ?>" alt="<?php echo htmlspecialchars(td($branch, 'name')); ?>" class="w-full h-full object-cover">
                    </div>
                    <!-- Branch Content -->
                    <div class="md:w-1/2 p-8 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <span class="inline-block px-3 py-1 bg-burgundy-50 text-burgundy-700 text-xs font-semibold rounded-full">
                                Branch <?php echo $index; ?>
                            </span>
                            <h3 class="text-2xl font-bold text-gray-900 font-serif-lao"><?php echo htmlspecialchars(td($branch, 'name')); ?></h3>
                            <p class="text-sm text-gray-500 font-light leading-relaxed"><?php echo htmlspecialchars(td($branch, 'address')); ?></p>
                            
                            <div class="space-y-2 pt-2 text-sm text-gray-600">
                                <p class="flex items-center space-x-2">
                                    <strong class="text-burgundy-700"><?php echo t('loc_open'); ?></strong>
                                    <span><?php echo htmlspecialchars(td($branch, 'hours')); ?></span>
                                </p>
                                <p class="flex items-center space-x-2">
                                    <strong class="text-burgundy-700"><?php echo t('loc_phone'); ?></strong>
                                    <span><?php echo htmlspecialchars($branch['phone']); ?></span>
                                </p>
                            </div>
                        </div>

                        <div>
                            <a href="<?php echo htmlspecialchars($branch['map_link']); ?>" target="_blank" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-full text-sm font-semibold transition-all duration-300 shadow-md">
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

<?php
require_once __DIR__ . '/includes/footer.php';
?>
