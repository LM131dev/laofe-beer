<?php
// news-detail.php - Dark Luxury Lounge News & Article Detail Page
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$news_item = null;
$related_items = [];

// ດຶງຂໍ້ມູນລາຍການຂ່າວສານ/ບົດຄວາມຕາມ ID
if ($news_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute([':id' => $news_id]);
        $news_item = $stmt->fetch();
    } catch (\Exception $e) {}
}

// Fallback ຂໍ້ມູນຖ້າ ID ບໍ່ມີໃນ Database ຫຼື ເປັນ Fallback ID
if (!$news_item) {
    $fallback_all = [
        999 => [
            'id' => 999,
            'title_lo' => 'Happy Hour Craft Beer 1 ແຖມ 1!',
            'title_en' => 'Happy Hour Craft Beer Buy 1 Get 1 Free!',
            'content_lo' => "ທຸກໆວັນຈັນ - ວັນພະຫັດ ເວລາ 17:00 - 19:00 ໂມງ!\n\nຊື້ດຣາຟເບຍຄຣາຟ 1 ແກ້ວ ຮັບຟຣີອີກ 1 ແກ້ວທັນທີ (ທຸກສາຂາ LaoFe). ເໝາະສຳລັບການມາສັງສັນກັບໝູ່ເພື່ອນ ຫຼື ຜ່ອນຄາຍຫຼັງຈາກເລີກວຽກ.\n\nຂໍ້ກຳນົດ ແລະ ເງື່ອນໄຂ:\n- ໂປຣໂມຊັນນີ້ໃຊ້ໄດ້ສະເພາະການດື່ມຢູ່ຮ້ານເທົ່ານັ້ນ\n- ສະເພາະຊ່ວງເວລາ Happy Hour (17:00 - 19:00)\n- ບໍ່ສາມາດໃຊ້ຮ່ວມກັບສ່ວນຫຼຸດອື່ນໆໄດ້",
            'content_en' => "Every Monday - Thursday from 17:00 - 19:00!\n\nBuy 1 draft craft beer, get 1 free instantly at all LaoFe branches. Perfect for unwinding with friends after work.\n\nTerms & Conditions:\n- Valid for dine-in only\n- Happy Hour timing: 17:00 - 19:00\n- Cannot be combined with other discounts",
            'image_path' => 'assets/images/beer_drink.png',
            'publish_date' => '2026-08-15',
            'category' => 'promotion',
            'is_promo' => 1
        ],
        101 => [
            'id' => 101,
            'title_lo' => 'Acoustic Wood Night ທຸກໆວັນເສົາ',
            'title_en' => 'Acoustic Wood Night Every Saturday',
            'content_lo' => "ຟັງດົນຕີໂຟກອະຄູສະຕິກ ແບບຟັງສະບາຍ ເພີ່ມທັ່ງກິ່ນໄມ້ ແລະ ເຄື່ອງດື່ມສຸດໂປດ.\n\nຂໍເຊີນທ່ານມາຮ່ວມສຳພັດບັນຍາກາດອົບອຸ່ນໃນຄ່ຳຄືນວັນເສົາ ພ້ອມກັບສຽງເພງອະຄູສະຕິກຈາກນັກດົນຕີຝີມືດີ ແລະ ເຄື່ອງດື່ມສູດພິເສດ LaoFe Draft Beer.",
            'content_en' => "Enjoy relaxed acoustic folk music paired with wood-smoked craft drinks.\n\nJoin us for a warm Saturday evening with live acoustic music from local artists and signature LaoFe draft beers.",
            'image_path' => 'assets/images/beer_drink.png',
            'publish_date' => '2026-08-15',
            'category' => 'event_music',
            'event_location' => 'LaoFe Namphou Branch',
            'event_date' => '2026-08-20 19:30:00',
            'is_promo' => 2
        ],
        102 => [
            'id' => 102,
            'title_lo' => 'ເປີດໂຕເມັດກາເຟໃໝ່: "Dark Wood Blend"',
            'title_en' => 'New Coffee Bean Blend: "Dark Wood Blend"',
            'content_lo' => "ເປີດຕາແລ້ວເລີ່ມຕົ້ນມື້ໃໝ່ ໃຫ້ລົດຊາດ Dark Chocolate, Caramel & Nutty ທີ່ທ່ານຈະຕ້ອງຫຼົງໄຫຼ.\n\n Dark Wood Blend ເປັນສູດກາເຟຄົ້າມືເອກະລັກທີ່ປະສົມປະສານເມັດກາເຟ ອາຣາບິກ້າ ບໍລະເວນ 100% ຜ່ານການຄົ້າມືລະດັບ Medium-Dark Roast ເຮັດໃຫ້ໄດ້ລົດຊາດເຂັ້ມຂຸ້ນ ຫອມຫວານ ກົມກ່ອມ.",
            'content_en' => "Discover rich flavors of Dark Chocolate, Caramel & Nutty in our newly released house roast.\n\nDark Wood Blend features 100% Bolaven Arabica coffee beans, roasted to perfection at Medium-Dark level for a deep, nutty, and chocolatey aroma.",
            'image_path' => 'assets/images/coffee.png',
            'publish_date' => '2026-08-01',
            'category' => 'article_coffee',
            'is_promo' => 0
        ],
        103 => [
            'id' => 103,
            'title_lo' => 'Wood Loyalty Card ລະບົບສະສົມແຕ້ມ',
            'title_en' => 'Wood Loyalty Card Member Rewards',
            'content_lo' => "ດື່ມກາເຟ ຫຼື ເບຍ ຄົບ 10 ແກ້ວ ຮັບຟຣີ 1 ແກ້ວ ຜ່ານ Line Official Account.\n\nສະສົມແຕ້ມງ່າຍໆພຽງແຕ່ແຈ້ງເບີໂທລະສັບ ຫຼື ສະແກນ QR Code ທຸກຄັ້ງທີ່ຊຳລະເງິນຢູ່ໜ້າເຄົາເຕີ ທຸກໆ 10 ແກ້ວ ຮັບຄູປອງເຄື່ອງດື່ມຟຣີ 1 ແກ້ວທັນທີ!",
            'content_en' => "Enjoy 10 cups of coffee or draft beer and get 1 cup free via our Line Official Account.\n\nCollect points easily by providing your phone number or scanning QR code at payment. Get 1 free beverage coupon every 10 cups!",
            'image_path' => 'assets/images/our_story.png',
            'publish_date' => '2026-07-20',
            'category' => 'article_culture',
            'is_promo' => 0
        ],
        401 => [
            'id' => 401,
            'title_lo' => 'ຄວາມລັບຂອງເມັດກາເຟ ອາຣາບິກ້າ ບໍລະເວນ (Bolaven Specialty)',
            'title_en' => 'The Secret Behind Bolaven Plateau Arabica Coffee Beans',
            'content_lo' => "ເຈາະເລິກຄວາມພິເສດຂອງດິນພູເຂົາໄຟເກົ່າ ດອຍບໍລະເວນ ທີ່ເຮັດໃຫ້ເມັດກາເຟ LaoFe ມີກິ່ນຫອມອະລົມມ່າ ຫອມຫວານຄືດອກໄມ້ປ່າ ແລະ ມີລົດຊາດກົມກ່ອມເປັນເອກະລັກ.\n\nພູພຽງບໍລະເວນ ຕັ້ງຢູ່ທາງພາກໃຕ້ຂອງປະເທດລາວ ເຊິ່ງເປັນໜຶ່ງໃນແຫຼ່ງປູກກາເຟ ອາຣາບິກ້າ ທີ່ດີທີ່ສຸດໃນໂລກ ດ້ວຍຄວາມສູງ 1,200 ແມັດຈາກລະດັບນ້ຳທະເລ ພ້ອມກັບສະພາບອາກາດເຢັນຕະຫຼອດປີ ແລະ ດິນພູເຂົາໄຟອຸດົມສົມບູນ.",
            'content_en' => "Explore how the fertile volcanic soil of Bolaven Plateau creates LaoFe’s distinctive aroma, floral sweetness, and smooth balanced flavor profile.\n\nThe Bolaven Plateau in Southern Laos is recognized as one of the finest Arabica coffee growing regions in the world, elevated at 1,200m above sea level with cool climate and volcanic soil.",
            'image_path' => 'assets/images/our_story.png',
            'publish_date' => '2026-10-10',
            'category' => 'article_coffee',
            'is_promo' => 0
        ],
        402 => [
            'id' => 402,
            'title_lo' => 'ເລື່ອງລາວຈາກ Brewmaster: ຄວາມເປັນມາຂອງ Amber Grain Ale & ຄັອກເທວສະໝຸນໄພ',
            'title_en' => 'Meet the Brewmaster: Crafting Amber Grain Ale & Herbal Cocktails',
            'content_lo' => "ບົດສຳພາດຜູ້ຢູ່ເບື້ອງຫຼັງຄວາມຮົ່ມເຢັນ ແລະ ລົດຊາດອັນເປັນເອກະລັກຂອງ Amber Grain Ale ພ້ອມກັບການປະສົມປະສານສະໝຸນໄພທ້ອງຖິ່ນລາວ ໃນຄັອກເທວສູດພິເສດ.\n\n\"ພວກເຮົາຕ້ອງການສ້າງເຄື່ອງດື່ມ ທີ່ບອກເລົ່າເລື່ອງລາວຂອງທຳມະຊາດ ແລະ ວັດທະນະທຳລາວ\" - LaoFe Master Brewer.",
            'content_en' => "An exclusive interview with our head brewmaster discussing the craftsmanship behind our Amber Grain Ale and locally-infused herbal signature cocktails.\n\n\"We wanted to craft beverages that express the soul of Lao nature and heritage.\" - LaoFe Master Brewer.",
            'image_path' => 'assets/images/beer_drink.png',
            'publish_date' => '2026-10-18',
            'category' => 'article_culture',
            'is_promo' => 0
        ]
    ];

    if (isset($fallback_all[$news_id])) {
        $news_item = $fallback_all[$news_id];
    } else {
        // ຖ້າ ID ບໍ່ຕົງ ເອົາລາຍການທຳອິດ
        $news_item = reset($fallback_all);
    }
}

// ດຶງຂໍ້ມູນລາຍການທີ່ກ່ຽວຂ້ອງ (Related News / Other Promotions)
try {
    $stmt_rel = $pdo->prepare("SELECT * FROM news WHERE id != :id AND status = 'published' ORDER BY publish_date DESC LIMIT 3");
    $stmt_rel->execute([':id' => $news_item['id']]);
    $related_items = $stmt_rel->fetchAll();
} catch (\Exception $e) {}

// ຖ້າ Related Items ໃນ Database ບໍ່ພໍ ເອົາຂໍ້ມູນ Fallback
if (count($related_items) < 3) {
    $fallback_rel = [
        [
            'id' => 999,
            'title_lo' => 'Happy Hour Craft Beer 1 ແຖມ 1!',
            'title_en' => 'Happy Hour Craft Beer Buy 1 Get 1 Free!',
            'content_lo' => 'ທຸກໆວັນຈັນ - ວັນພະຫັດ ເວລາ 17:00 - 19:00 ໂມງ!',
            'content_en' => 'Every Monday - Thursday 17:00 - 19:00!',
            'image_path' => 'assets/images/beer_drink.png',
            'publish_date' => '2026-08-15'
        ],
        [
            'id' => 101,
            'title_lo' => 'Acoustic Wood Night ທຸກໆວັນເສົາ',
            'title_en' => 'Acoustic Wood Night Every Saturday',
            'content_lo' => 'ຟັງດົນຕີໂຟກອະຄູສະຕິກ ແບບຟັງສະບາຍ ເພີ່ມທັ່ງກິ່ນໄມ້.',
            'content_en' => 'Listen to relaxed acoustic folk music paired with craft drinks.',
            'image_path' => 'assets/images/beer_drink.png',
            'publish_date' => '2026-08-15'
        ],
        [
            'id' => 102,
            'title_lo' => 'ເປີດໂຕເມັດກາເຟໃໝ່: "Dark Wood Blend"',
            'title_en' => 'New Coffee Bean Blend: "Dark Wood Blend"',
            'content_lo' => 'ເປີດຕາແລ້ວເລີ່ມຕົ້ນມື້ໃໝ່ ໃຫ້ລົດຊາດ Dark Chocolate & Caramel.',
            'content_en' => 'Discover rich flavors of Dark Chocolate & Caramel.',
            'image_path' => 'assets/images/coffee.png',
            'publish_date' => '2026-08-01'
        ]
    ];
    foreach ($fallback_rel as $fr) {
        if (count($related_items) >= 3) break;
        if ($fr['id'] != $news_item['id']) {
            $related_items[] = $fr;
        }
    }
}

// ຈັດການຕົວປ່ຽນ Dynamic Variables
$title = isset($news_item['title_lo']) ? td($news_item, 'title') : ($current_lang === 'lo' ? $news_item['title_lo'] : $news_item['title_en']);
$content = isset($news_item['content_lo']) ? td($news_item, 'content') : ($current_lang === 'lo' ? $news_item['content_lo'] : $news_item['content_en']);
$img_path = !empty($news_item['image_path']) ? $news_item['image_path'] : 'assets/images/coffee.png';
$pub_date = !empty($news_item['publish_date']) ? date("d M Y", strtotime($news_item['publish_date'])) : '15 ສິງຫາ 2026';

$is_promo = isset($news_item['is_promo']) ? intval($news_item['is_promo']) : 0;
$badge_label = ($is_promo === 1) 
    ? ($current_lang === 'lo' ? 'ໂປຣໂມຊັນພິເສດ' : 'SPECIAL PROMOTION')
    : (($is_promo === 2) 
        ? ($current_lang === 'lo' ? '🎉 ກິດຈະກຳ & ເວນຊັອບ' : '🎉 EVENT & WORKSHOP')
        : ($current_lang === 'lo' ? '📖 ບົດຄວາມ & ເລື່ອງລາວ' : '📖 ARTICLE & STORY'));
?>

<!-- Deep Luxury Obsidian Dark Lounge Main Container -->
<div class="bg-[#110906] text-white min-h-screen pt-28 pb-24 font-sans selection:bg-amber-500 selection:text-black">
    <div class="max-w-4xl mx-auto px-6 md:px-8 space-y-10">

        <!-- 1. Back Navigation Button -->
        <div>
            <a href="news.php" class="inline-flex items-center gap-2 text-xs md:text-sm text-amber-400 hover:text-amber-300 font-serif-lao font-bold transition-all hover:-translate-x-1">
                <span>←</span>
                <span><?php echo $current_lang === 'lo' ? 'ກັບໄປໜ້າຂ່າວສານ & ໂປຣໂມຊັນ' : 'Back to News & Promotions'; ?></span>
            </a>
        </div>

        <!-- 2. Detail Header Section -->
        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-[11px] font-extrabold uppercase font-serif-lao tracking-wider">
                    <?php if ($is_promo === 1): ?>
                        <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($badge_label); ?></span>
                </span>
                <span class="text-xs text-[#A8988B] font-mono flex items-center gap-1">
                    <span>🗓️</span>
                    <span><?php echo htmlspecialchars($pub_date); ?></span>
                </span>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold text-white font-serif-lao leading-snug md:leading-tight tracking-tight">
                <?php echo htmlspecialchars($title); ?>
            </h1>

            <?php if (!empty($news_item['event_location']) || !empty($news_item['event_date'])): ?>
                <div class="flex flex-wrap gap-4 pt-2 text-xs md:text-sm text-amber-200/90 font-serif-lao bg-[#1F110B] p-4 rounded-xl border border-[#331B12]">
                    <?php if (!empty($news_item['event_location'])): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-amber-400">📍 Location:</span>
                            <span><?php echo htmlspecialchars($news_item['event_location']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($news_item['event_date'])): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-amber-400">🕒 Time:</span>
                            <span><?php echo date("d M Y, H:i", strtotime($news_item['event_date'])); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 3. Featured Image Container -->
        <div class="w-full h-80 md:h-[420px] rounded-3xl overflow-hidden bg-black/60 border border-[#331B12] shadow-2xl relative">
            <img src="<?php echo htmlspecialchars($img_path); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#110906] via-transparent to-transparent opacity-60"></div>
        </div>

        <!-- 4. Content Body Section -->
        <div class="bg-[#1F110B] border border-[#331B12] rounded-3xl p-8 md:p-12 shadow-2xl space-y-6">
            <div class="prose prose-invert max-w-none text-[#D4C5B9] font-serif-lao text-base md:text-lg leading-relaxed space-y-4 whitespace-pre-line">
                <?php echo htmlspecialchars($content); ?>
            </div>

            <!-- Action Call to Action Box -->
            <div class="pt-8 border-t border-[#331B12] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h4 class="text-lg font-bold text-white font-serif-lao">
                        <?php echo $current_lang === 'lo' ? 'ສົນໃຈສຳຮອງໂຕະ ຫຼື ຕິດສອບຖາມເພີ່ມເຕີມ?' : 'Interested in booking a table or inquiry?'; ?>
                    </h4>
                    <p class="text-xs text-[#A8988B] font-serif-lao font-light">
                        <?php echo $current_lang === 'lo' ? 'ພວກເຮົາພ້ອມຕ້ອນຮັບທ່ານດ້ວຍຄວາມອົບອຸ່ນທຸກໆວັນ' : 'We welcome you warmly every day at LaoFe.'; ?>
                    </p>
                </div>

                <a href="booking.php" class="px-7 py-3.5 rounded-full bg-gradient-to-r from-[#D97706] via-[#F59E0B] to-[#D97706] text-[#1F100A] font-extrabold text-xs tracking-wide shadow-[0_8px_20px_rgba(217,119,6,0.35)] hover:shadow-[0_12px_28px_rgba(217,119,6,0.5)] hover:scale-105 transition-all font-serif-lao shrink-0">
                    <?php echo $current_lang === 'lo' ? 'ຈອງໂຕະຕອນນີ້' : 'Book a Table Now'; ?>
                </a>
            </div>
        </div>

        <!-- 5. Related Other News & Events Section -->
        <?php if (!empty($related_items)): ?>
            <div class="pt-12 border-t border-[#2A160F] space-y-6">
                <h3 class="text-2xl font-extrabold text-white font-serif-lao">
                    <?php echo $current_lang === 'lo' ? 'ຂ່າວສານ & ໂໂປຣໂມຊັນອື່ນໆ' : 'Other News & Promotions'; ?>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($related_items as $rel): 
                        $rel_title = isset($rel['title_lo']) ? td($rel, 'title') : ($current_lang === 'lo' ? $rel['title_lo'] : $rel['title_en']);
                        $rel_desc = isset($rel['content_lo']) ? td($rel, 'content') : ($current_lang === 'lo' ? $rel['content_lo'] : $rel['content_en']);
                        $rel_img = !empty($rel['image_path']) ? $rel['image_path'] : 'assets/images/coffee.png';
                        $rel_date = !empty($rel['publish_date']) ? date("d M Y", strtotime($rel['publish_date'])) : '15 ສິງຫາ 2026';
                    ?>
                        <div class="bg-[#1F110B] border border-[#331B12] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-between group hover:border-[#D97706]/40 hover:-translate-y-1 transition-all duration-300">
                            
                            <div class="w-full h-44 relative overflow-hidden bg-black/40">
                                <img src="<?php echo htmlspecialchars($rel_img); ?>" alt="<?php echo htmlspecialchars($rel_title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1F110B] via-transparent to-transparent opacity-80"></div>
                            </div>

                            <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                                <div class="space-y-1.5">
                                    <span class="text-amber-400 font-bold text-xs font-serif-lao block">
                                        <?php echo htmlspecialchars($rel_date); ?>
                                    </span>
                                    <h4 class="text-sm font-bold text-white font-serif-lao line-clamp-1 group-hover:text-amber-400 transition-colors">
                                        <?php echo htmlspecialchars($rel_title); ?>
                                    </h4>
                                    <p class="text-xs text-[#B5A79C] font-serif-lao font-light line-clamp-2 leading-relaxed">
                                        <?php echo htmlspecialchars($rel_desc); ?>
                                    </p>
                                </div>

                                <div class="pt-2">
                                    <a href="news-detail.php?id=<?php echo $rel['id']; ?>" class="text-amber-400 hover:text-amber-300 font-bold text-xs font-serif-lao inline-flex items-center gap-1 transition-colors">
                                        <span><?php echo $current_lang === 'lo' ? 'ອ່ານເພີ່ມເຕີມ' : 'Read More'; ?></span>
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
