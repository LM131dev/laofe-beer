<?php
// news.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນຂ່າວສານທັງໝົດຈາກ Database
$db_news = [];
try {
    $stmt = $pdo->query("SELECT * FROM news ORDER BY publish_date DESC, id DESC");
    $db_news = $stmt->fetchAll();
} catch (\Exception $e) {
    // ຈັດການ error
}

// ຫາກຖານຂໍ້ມູນຍັງບໍ່ມີຂໍ້ມູນ ໃຫ້ໃຊ້ຂໍ້ມູນຕົວຢ່າງ (Fallback)
if (empty($db_news)) {
    $db_news = [
        [
            'id' => 201,
            'title_lo' => 'ສະຫຼອງເປີດສາຂາໃໝ່ ຫຼວງພະບາງ ພ້ອມໂປຣໂມຊັນພິເສດ!',
            'title_en' => 'Grand Opening of Luang Prabang Branch with Special Promotions!',
            'content_lo' => 'LaoFe & Beer ຂະຫຍາຍຄວາມສຸກໄປຫາເມືອງມໍລະດົກໂລກແລ້ວ! ພົບກັບສາຂາຫຼວງພະບາງ ທີ່ຖະໜົນສີສະຫວ່າງວົງ ພ້ອມໂປຣໂມຊັນ ຊື້ 1 ແຖມ 1 ສຳລັບເມນູກາເຟຕະຫຼອດອາທິດທຳອິດຂອງການເປີດຮ້ານ.',
            'content_en' => 'LaoFe & Beer is now open in the beautiful World Heritage town! Visit our Luang Prabang branch at Sisavangvong Road and enjoy Buy 1 Get 1 Free on all coffee items during the first week of opening.',
            'image_path' => 'assets/images/hero_banner.png',
            'is_promo' => 1,
            'publish_date' => '2026-07-15'
        ],
        [
            'id' => 202,
            'title_lo' => 'ເມັດກາເຟ LaoFe ໄດ້ຮັບລາງວັນອັນດັບ 1 ຈາກເວທີທ້ອງຖິ່ນ',
            'title_en' => 'LaoFe Coffee Beans Wins 1st Place at Local Roasting Awards',
            'content_lo' => 'ພວກເຮົາມີຄວາມພາກພູມໃຈເປັນຢ່າງຍິ່ງທີ່ເມັດກາເຟອາຣາບິກ້າ ຈາກພູພຽງບໍລະເວນ ທີ່ພວກເຮົາຄັດສັນ ແລະ ຂົ້ວເອງ ໄດ້ຮັບລາງວັນຊະນະເລີດໃນງານກວດສອບກາເຟປະຈຳປີ ເພື່ອຢັ້ງຢືນຄຸນນະພາບ ແລະ ຄວາມຕັ້ງໃຈຂອງພວກເຮົາ.',
            'content_en' => 'We are proud to announce that our Bolaven Plateau Arabica coffee beans, sourced and roasted in-house, won the first place in the annual local coffee roasting competition.',
            'image_path' => 'assets/images/coffee.png',
            'is_promo' => 0,
            'publish_date' => '2026-07-10'
        ]
    ];
}
?>

<!-- Title Header Section -->
<section class="py-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('nav_news'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo $current_lang === 'lo' ? 'ຕິດຕາມຂ່າວສານ ແລະ ຂໍ້ສະເໜີພິເສດຈາກພວກເຮົາ' : 'Stay updated with our latest news and exclusive offers'; ?></p>
    </div>
</section>

<!-- News & Promotions Section -->
<section class="py-20 bg-gray-50 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <?php foreach ($db_news as $item): ?>
                <!-- News Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col card-hover-effect">
                    <!-- Image -->
                    <div class="h-64 relative overflow-hidden">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars(td($item, 'title')); ?>" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 text-xs font-bold px-3 py-1 rounded-full text-white shadow-md <?php echo $item['is_promo'] == 1 ? 'bg-burgundy-700' : 'bg-gray-800'; ?>">
                            <?php echo $item['is_promo'] == 1 ? 'Promotion' : 'News'; ?>
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-8 flex-grow flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <span class="text-xs text-gray-400 font-medium">
                                <?php echo date("d M Y", strtotime($item['publish_date'])); ?>
                            </span>
                            <h3 class="text-2xl font-bold text-gray-900 line-clamp-2 leading-snug font-serif-lao">
                                <?php echo htmlspecialchars(td($item, 'title')); ?>
                            </h3>
                            <p class="text-sm text-gray-600 font-light leading-relaxed line-clamp-4">
                                <?php echo nl2br(htmlspecialchars(td($item, 'content'))); ?>
                            </p>
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
