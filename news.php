<?php
// news.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນຂ່າວສານ, ບົດຄວາມ, ແລະ ໂໂປຣໂມຊັນ ທັງໝົດຈາກ Database
$all_news = [];
$db_articles = [];
try {
    $stmt = $pdo->query("SELECT * FROM news WHERE status = 'published' ORDER BY is_promo DESC, publish_date DESC, id DESC");
    $all_news = $stmt->fetchAll();

    // ດຶງຂໍ້ມູນບົດຄວາມໂດຍສະເພາະ (Articles / Stories)
    $stmt_art = $pdo->query("SELECT * FROM news WHERE (is_promo = 0 OR category LIKE 'article_%') AND status = 'published' ORDER BY publish_date DESC, id DESC");
    $db_articles = $stmt_art->fetchAll();
} catch (\Exception $e) {
    // Handling error silently
}

// ຂໍ້ມູນ Featured Hero Promotion (ດຶງລາຍການ is_featured = 1 ຫຼື is_promo = 1)
$featured_item = null;
$grid_items = [];

if (!empty($all_news)) {
    // 1. ຄົ້ນຫາລາຍການທີ່ຕັ້ງ is_featured = 1 ມາກຳນົດເປັນ Hero Banner
    foreach ($all_news as $item) {
        if (!empty($item['is_featured']) && $item['is_featured'] == 1) {
            $featured_item = $item;
            break;
        }
    }
    // 2. ຖ້າບໍ່ມີ is_featured = 1 ເອົາລາຍການ is_promo = 1 (Promotion)
    if ($featured_item === null) {
        foreach ($all_news as $item) {
            if ($item['is_promo'] == 1) {
                $featured_item = $item;
                break;
            }
        }
    }
    // 3. ຖ້າບໍ່ມີອີກ ເອົາລາຍການທຳອິດ
    if ($featured_item === null && !empty($all_news)) {
        $featured_item = $all_news[0];
    }

    // ຈັດ grid_items (ບໍ່ລວມ featured_item)
    foreach ($all_news as $item) {
        if ($featured_item && $item['id'] == $featured_item['id']) continue;
        $grid_items[] = $item;
    }
}

// Fallback ຂໍ້ມູນ Featured Hero Card (ຖ້າ Database ຍັງບໍ່ມີ)
if ($featured_item === null) {
    $featured_item = [
        'id' => 999,
        'title_lo' => 'Happy Hour Craft Beer 1 ແຖມ 1!',
        'title_en' => 'Happy Hour Craft Beer Buy 1 Get 1 Free!',
        'content_lo' => 'ທຸກໆວັນຈັນ - ວັນພະຫັດ ເວລາ 17:00 - 19:00 ໂມງ! ຊື້ດຣາຟເບຍຄຣາຟ 1 ແກ້ວ ຮັບຟຣີອີກ 1 ແກ້ວທັນທີ (ທຸກສາຂາ).',
        'content_en' => 'Every Monday - Thursday 17:00 - 19:00! Buy 1 draft craft beer, get 1 free instantly at all branches.',
        'image_path' => 'assets/images/beer_drink.png',
        'publish_date' => '2026-08-15',
        'is_promo' => 1
    ];
}

// Fallback ຂໍ້ມູນ 3-Column Grid Cards (ຖ້າ Database ມີບໍ່ພໍ 3 ລາຍການ)
$fallback_grid = [
    [
        'id' => 101,
        'title_lo' => 'Acoustic Wood Night ທຸກໆວັນເສົາ',
        'title_en' => 'Acoustic Wood Night Every Saturday',
        'content_lo' => 'ຟັງດົນຕີໂຟກອະຄູສະຕິກ ແບບຟັງສະບາຍ ເພີ່ມທັ່ງກິ່ນໄມ້ ແລະ ເຄື່ອງດື່ມສຸດໂປດ.',
        'content_en' => 'Listen to relaxed acoustic folk music paired with wood-smoked craft drinks.',
        'image_path' => 'assets/images/beer_drink.png',
        'publish_date' => '2026-08-15',
        'category' => 'event_music'
    ],
    [
        'id' => 102,
        'title_lo' => 'ເປີດໂຕເມັດກາເຟໃໝ່: "Dark Wood Blend"',
        'title_en' => 'New Coffee Bean Blend: "Dark Wood Blend"',
        'content_lo' => 'ເປີດຕາແລ້ວເລີ່ມຕົ້ນມື້ໃໝ່ ໃຫ້ລົດຊາດ Dark Chocolate, Caramel & Nutty ທີ່ທ່ານຈະຕ້ອງຫຼົງໄຫຼ.',
        'content_en' => 'Discover rich flavors of Dark Chocolate, Caramel & Nutty in our newly released house roast.',
        'image_path' => 'assets/images/coffee.png',
        'publish_date' => '2026-08-01',
        'category' => 'article_coffee'
    ],
    [
        'id' => 103,
        'title_lo' => 'Wood Loyalty Card ລະບົບສະສົມແຕ້ມ',
        'title_en' => 'Wood Loyalty Card Member Rewards',
        'content_lo' => 'ດື່ມກາເຟ ຫຼື ເບຍ ຄົບ 10 ແກ້ວ ຮັບຟຣີ 1 ແກ້ວ ຜ່ານ Line Official Account.',
        'content_en' => 'Enjoy 10 cups of coffee or draft beer and get 1 cup free via our Line Official Account.',
        'image_path' => 'assets/images/our_story.png',
        'publish_date' => '2026-07-20',
        'category' => 'article_culture'
    ]
];

// ເຕັມ 3-Column Grid ໃຫ້ຄົບ 3 ລາຍການສະເໝີ
if (count($grid_items) < 3) {
    foreach ($fallback_grid as $fb) {
        if (count($grid_items) >= 3) break;
        $grid_items[] = $fb;
    }
}

// Fallback ບົດຄວາມ (ຖ້າ Database ຍັງບໍ່ມີ)
$fallback_articles = [
    [
        'id' => 401,
        'category' => 'article_coffee',
        'title_lo' => 'ຄວາມລັບຂອງເມັດກາເຟ ອາຣາບິກ້າ ບໍລະເວນ (Bolaven Specialty)',
        'title_en' => 'The Secret Behind Bolaven Plateau Arabica Coffee Beans',
        'content_lo' => 'ເຈາະເລິກຄວາມພິເສດຂອງດິນພູເຂົາໄຟເກົ່າ ດອຍບໍລະເວນ ທີ່ເຮັດໃຫ້ເມັດກາເຟ LaoFe ມີກິ່ນຫອມອະລົມມ່າ ຫອມຫວານຄືດອກໄມ້ປ່າ ແລະ ມີລົດຊາດກົມກ່ອມເປັນເອກະລັກ.',
        'content_en' => 'Explore how the fertile volcanic soil of Bolaven Plateau creates LaoFe’s distinctive aroma, floral sweetness, and smooth balanced flavor profile.',
        'image_path' => 'assets/images/our_story.png',
        'publish_date' => '2026-10-10'
    ],
    [
        'id' => 402,
        'category' => 'article_culture',
        'title_lo' => 'ເລື່ອງລາວຈາກ Brewmaster: ຄວາມເປັນມາຂອງ Amber Grain Ale & ຄັອກເທວສະໝຸນໄພ',
        'title_en' => 'Meet the Brewmaster: Crafting Amber Grain Ale & Herbal Cocktails',
        'content_lo' => 'ບົດສຳພາດຜູ້ຢູ່ເບື້ອງຫຼັງຄວາມຮົ່ມເຢັນ ແລະ ລົດຊາດອັນເປັນເອກະລັກຂອງ Amber Grain Ale ພ້ອມກັບການປະສົມປະສານສະໝຸນໄພທ້ອງຖິ່ນລາວ ໃນຄັອກເທວສູດພິເສດ.',
        'content_en' => 'An exclusive interview with our head brewmaster discussing the craftsmanship behind our Amber Grain Ale and locally-infused herbal signature cocktails.',
        'image_path' => 'assets/images/beer_drink.png',
        'publish_date' => '2026-10-18'
    ]
];

$display_articles = !empty($db_articles) ? $db_articles : $fallback_articles;
?>

<!-- Deep Luxury Obsidian Dark Lounge Main Page Background -->
<div class="bg-[#110906] text-white min-h-screen pt-32 pb-24 font-sans selection:bg-amber-500 selection:text-black">
    <div class="max-w-6xl mx-auto px-6 md:px-10 space-y-12">

        <!-- 1. Header Title Section (Matches Screenshot Exactly) -->
        <div class="text-center space-y-2.5">
            <span class="text-[#D97706] font-extrabold tracking-[0.25em] text-[11px] uppercase block font-mono">
                WHAT'S NEW
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white font-serif-lao tracking-tight leading-tight">
                <?php echo $current_lang === 'lo' ? 'ຂ່າວສານ & ໂປຣໂມຊັນ' : 'News & Promotions'; ?>
            </h1>
            <p class="text-xs md:text-sm text-[#A8988B] font-serif-lao font-light tracking-wide max-w-xl mx-auto">
                <?php echo $current_lang === 'lo' ? 'ອັບເດດກິດຈະກຳ, ສ່ວນຫຼຸດ ແລະ ກິດຈະກຳດົນຕີສົດ' : 'Update events, discounts, and live acoustic music sessions.'; ?>
            </p>
        </div>

        <!-- 2. Featured Highlight Hero Promo Card (Large Top Card - Matches Screenshot) -->
        <?php 
            $hero_title = isset($featured_item['title_lo']) ? td($featured_item, 'title') : ($current_lang === 'lo' ? $featured_item['title_lo'] : $featured_item['title_en']);
            $hero_desc = isset($featured_item['content_lo']) ? td($featured_item, 'content') : ($current_lang === 'lo' ? $featured_item['content_lo'] : $featured_item['content_en']);
            $hero_img = $featured_item['image_path'] ?? 'assets/images/beer_drink.png';
            $hero_id = $featured_item['id'] ?? 999;
        ?>
        <div class="bg-[#1F110B] border border-[#331B12] rounded-3xl p-7 md:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.6)] relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 group hover:border-[#D97706]/40 transition-all duration-300">
            <!-- Left Text Content -->
            <div class="w-full md:w-7/12 space-y-4 z-10">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-[#D97706] to-[#B45309] text-white text-[11px] font-extrabold uppercase tracking-wider shadow-md font-serif-lao">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>PROMOTION HOT</span>
                </span>
                
                <h2 class="text-2xl md:text-4xl font-extrabold text-white font-serif-lao leading-snug md:leading-tight group-hover:text-amber-400 transition-colors">
                    <a href="news-detail.php?id=<?php echo $hero_id; ?>">
                        <?php echo htmlspecialchars($hero_title); ?>
                    </a>
                </h2>

                <p class="text-xs md:text-sm text-[#C4B7AC] font-serif-lao font-light leading-relaxed max-w-xl">
                    <?php echo htmlspecialchars($hero_desc); ?>
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="booking.php" class="px-7 py-3 rounded-full bg-gradient-to-r from-[#D97706] via-[#F59E0B] to-[#D97706] text-[#1F100A] font-extrabold text-xs tracking-wide shadow-[0_8px_20px_rgba(217,119,6,0.35)] hover:shadow-[0_12px_28px_rgba(217,119,6,0.5)] hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center justify-center gap-2">
                        <span><?php echo $current_lang === 'lo' ? 'ຈອງໂຕະຮັບໂປຣໂມຊັນ' : 'Book Table & Claim Offer'; ?></span>
                    </a>
                    <a href="news-detail.php?id=<?php echo $hero_id; ?>" class="px-5 py-3 rounded-full border border-amber-500/40 text-amber-300 font-extrabold text-xs hover:bg-amber-500/10 transition-all font-serif-lao inline-flex items-center gap-1">
                        <span><?php echo $current_lang === 'lo' ? 'ອ່ານລາຍລະອຽດ' : 'Read Details'; ?></span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Right Featured Image Container -->
            <div class="w-full md:w-5/12 h-64 md:h-72 rounded-2xl overflow-hidden bg-black/50 border border-white/10 relative shrink-0 shadow-2xl group">
                <a href="news-detail.php?id=<?php echo $hero_id; ?>" class="block w-full h-full">
                    <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="Featured Promo" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1F110B]/60 via-transparent to-transparent"></div>
                </a>
            </div>
        </div>

        <!-- 3. 3-Column Grid Cards Section (Matches Bottom 3 Cards in Screenshot) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 pt-4">
            <?php foreach (array_slice($grid_items, 0, 3) as $card): 
                $card_title = isset($card['title_lo']) ? td($card, 'title') : ($current_lang === 'lo' ? $card['title_lo'] : $card['title_en']);
                $card_desc = isset($card['content_lo']) ? td($card, 'content') : ($current_lang === 'lo' ? $card['content_lo'] : $card['content_en']);
                $card_img = $card['image_path'] ?? 'assets/images/coffee.png';
                $card_date = !empty($card['publish_date']) ? date("d M Y", strtotime($card['publish_date'])) : '15 ສິງຫາ 2026';
                $card_id = $card['id'] ?? 101;
            ?>
                <div class="bg-[#1F110B] border border-[#331B12] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-between group hover:border-[#D97706]/40 hover:-translate-y-1.5 transition-all duration-300">
                    
                    <!-- Top Thumbnail Image -->
                    <div class="w-full h-52 relative overflow-hidden bg-black/40">
                        <a href="news-detail.php?id=<?php echo $card_id; ?>" class="block w-full h-full">
                            <img src="<?php echo htmlspecialchars($card_img); ?>" alt="<?php echo htmlspecialchars($card_title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#1F110B] via-transparent to-transparent opacity-80"></div>
                        </a>
                    </div>

                    <!-- Card Body Content -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4 bg-[#1F110B]">
                        <div class="space-y-2">
                            <span class="text-amber-400 font-bold text-xs font-serif-lao block">
                                <?php echo htmlspecialchars($card_date); ?>
                            </span>

                            <h3 class="text-base md:text-lg font-extrabold text-white font-serif-lao line-clamp-1 group-hover:text-amber-400 transition-colors leading-snug">
                                <a href="news-detail.php?id=<?php echo $card_id; ?>">
                                    <?php echo htmlspecialchars($card_title); ?>
                                </a>
                            </h3>

                            <p class="text-xs text-[#B5A79C] font-serif-lao font-light leading-relaxed line-clamp-2">
                                <?php echo htmlspecialchars($card_desc); ?>
                            </p>
                        </div>

                        <div class="pt-2">
                            <a href="news-detail.php?id=<?php echo $card_id; ?>" class="text-amber-400 hover:text-amber-300 font-bold text-xs font-serif-lao inline-flex items-center gap-1 transition-colors">
                                <span><?php echo $current_lang === 'lo' ? 'ອ່ານເພີ່ມເຕີມ' : 'Read More'; ?></span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- 4. Articles & Journal Stories Section (ສ່ວນບົດຄວາມຄວາມຮູ້ - Dark Lounge Aesthetic) -->
        <?php if (!empty($display_articles)): ?>
        <div class="pt-14 space-y-8 border-t border-[#2A160F]">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-[#D97706] font-extrabold tracking-[0.2em] text-[11px] uppercase block font-mono">
                        KNOWLEDGE & STORIES
                    </span>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-white font-serif-lao flex items-center gap-3 mt-1">
                        <span>📖</span>
                        <span><?php echo $current_lang === 'lo' ? 'ບົດຄວາມ & ເລື່ອງລາວກາເຟ' : 'Articles & Coffee Stories'; ?> (<?php echo count($display_articles); ?>)</span>
                    </h2>
                </div>
            </div>

            <!-- 2-Column Horizontal Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($display_articles as $story): 
                    $story_title = isset($story['title_lo']) ? td($story, 'title') : ($current_lang === 'lo' ? $story['title_lo'] : $story['title_en']);
                    $story_desc = isset($story['content_lo']) ? td($story, 'content') : ($current_lang === 'lo' ? $story['content_lo'] : $story['content_en']);
                    $story_img = $story['image_path'] ?? 'assets/images/coffee.png';
                    $story_cat = $story['category'] === 'article_coffee' ? ($current_lang === 'lo' ? '☕ ຄວາມຮູ້ກາເຟ' : 'Coffee Knowledge') : ($story['category'] === 'article_culture' ? ($current_lang === 'lo' ? '🇱🇦 ວັດທະນະທຳກາເຟ' : 'Lao Culture') : ($current_lang === 'lo' ? '📜 ເລື່ອງລາວແບຣນ' : 'Brand Story'));
                    $story_date = !empty($story['publish_date']) ? date("d M Y", strtotime($story['publish_date'])) : '18 ຕຸລາ 2026';
                    $story_id = $story['id'] ?? 401;
                ?>
                    <div class="bg-[#1F110B] border border-[#331B12] rounded-2xl overflow-hidden shadow-xl flex flex-col sm:flex-row group hover:border-[#D97706]/40 hover:-translate-y-1 transition-all duration-300">
                        
                        <!-- Left Image Thumbnail -->
                        <div class="sm:w-5/12 h-52 sm:h-auto relative overflow-hidden bg-black/40 shrink-0">
                            <a href="news-detail.php?id=<?php echo $story_id; ?>" class="block w-full h-full">
                                <img src="<?php echo htmlspecialchars($story_img); ?>" alt="<?php echo htmlspecialchars($story_title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </a>
                        </div>

                        <!-- Right Text Content -->
                        <div class="p-6 sm:w-7/12 flex flex-col justify-between space-y-4 bg-[#1F110B]">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-block px-2.5 py-0.5 rounded bg-amber-950/80 border border-amber-600/40 text-amber-300 text-[10px] font-extrabold uppercase font-serif-lao">
                                        <?php echo htmlspecialchars($story_cat); ?>
                                    </span>
                                    <span class="text-[11px] text-[#A8988B] font-mono">
                                        <?php echo htmlspecialchars($story_date); ?>
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-extrabold text-white font-serif-lao line-clamp-2 group-hover:text-amber-400 transition-colors leading-snug">
                                    <a href="news-detail.php?id=<?php echo $story_id; ?>">
                                        <?php echo htmlspecialchars($story_title); ?>
                                    </a>
                                </h3>

                                <p class="text-xs text-[#B5A79C] font-serif-lao font-light leading-relaxed line-clamp-3">
                                    <?php echo htmlspecialchars($story_desc); ?>
                                </p>
                            </div>

                            <div class="pt-2 border-t border-[#2A160F]">
                                <a href="news-detail.php?id=<?php echo $story_id; ?>" class="text-amber-400 hover:text-amber-300 font-bold text-xs font-serif-lao inline-flex items-center gap-1 transition-colors">
                                    <span><?php echo $current_lang === 'lo' ? 'ອ່ານບົດຄວາມເຕັມ' : 'Read Full Article'; ?></span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
