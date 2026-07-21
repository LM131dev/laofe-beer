<?php
// menu.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນເມນູຈາກ Database
$db_menus = [];
try {
    $stmt = $pdo->query("SELECT * FROM menus ORDER BY category, id DESC");
    $db_menus = $stmt->fetchAll();
} catch (\Exception $e) {
    // ຈັດການ error
}

// ຫາກຖານຂໍ້ມູນຍັງບໍ່ມີເມນູ ໃຫ້ໃຊ້ຂໍ້ມູນຕົວຢ່າງ (Fallback)
if (empty($db_menus)) {
    $db_menus = [
        [
            'id' => 101,
            'name_lo' => 'ລາວເຟ ໂຄໂຄນັດ ລາເຕ້',
            'name_en' => 'LaoFe Coconut Latte',
            'category' => 'coffee',
            'price' => 35000,
            'description_lo' => 'ກາເຟເອສເປຣສໂຊທີ່ເຂັ້ມຂຸ້ນ ຜສົມຜະສານກັບນ້ຳໝາກພ້າວສົດ ແລະ ນ້ຳນົມໝາກພ້າວສູດພິເສດ ຫວານມັນ ຫອມລະມຸນ.',
            'description_en' => 'A rich espresso shot layered with fresh coconut water and our signature coconut cream, delivering a smooth, refreshing, and tropical taste.',
            'image_path' => 'assets/images/coffee.png',
            'is_popular' => 1
        ],
        [
            'id' => 102,
            'name_lo' => 'ເອສເປຣສໂຊ ເຢັນ',
            'name_en' => 'Iced Espresso',
            'category' => 'coffee',
            'price' => 28000,
            'description_lo' => 'ກາເຟເອສເປຣສໂຊລົດຊາດເຂັ້ມຂຸ້ນ ແບບສະບັບຄວາມເຂັ້ມທີ່ລົງຕົວ ຕື່ນຕົວຕະຫຼອດວັນ.',
            'description_en' => 'Classic double shot espresso served chilled, bringing out the bold and rich chocolatey notes.',
            'image_path' => 'assets/images/coffee.png',
            'is_popular' => 0
        ],
        [
            'id' => 103,
            'name_lo' => 'ຊານົມເຜືອກລາວປະຍຸກ',
            'name_en' => 'Lao Taro Milk Tea',
            'category' => 'drinks',
            'price' => 30000,
            'description_lo' => 'ຊານົມຕົ້ມສົດໆ ຜສົມເນື້ອເຜືອກແທ້ຈາກທ້ອງຖິ່ນ ຫວານພໍດີ ຫອມກິ່ນໃບຊາ.',
            'description_en' => 'Freshly brewed milk tea blended with local organic taro paste, smooth and flavorful.',
            'image_path' => 'assets/images/coffee.png',
            'is_popular' => 0
        ],
        [
            'id' => 104,
            'name_lo' => 'ນ້ຳໝາກມ່ວງປັ່ນສະໝຸນໄພ',
            'name_en' => 'Mango Herbal Smoothie',
            'category' => 'drinks',
            'price' => 32000,
            'description_lo' => 'ນ້ຳໝາກມ່ວງສົດປັ່ນ ຜສົມໃບສະຫລະແໜ່ (Mint) ແລະ ນ້ຳເຜິ້ງປ່າ ປອດສານພິດ.',
            'description_en' => 'Fresh mango blend infused with organic wild honey and wild mint leaves, refreshing and nutritious.',
            'image_path' => 'assets/images/coffee.png',
            'is_popular' => 0
        ],
        [
            'id' => 105,
            'name_lo' => 'ເບຍລາວຄຣາບພຣີມ່ຽມ',
            'name_en' => 'Premium Lao Craft Beer',
            'category' => 'bar',
            'price' => 45000,
            'description_lo' => 'ເບຍສົດຄຣາບຄຸນນະພາບສູງ ໝັກຈາກເຂົ້າຫອມລາວແທ້ໆ ໃຫ້ລົດຊາດທີ່ນຸ້ມນວນ ແລະ ກິ່ນຫອມອັນເປັນເອກະລັກ.',
            'description_en' => 'High-quality draft craft beer brewed locally with authentic Lao jasmine rice, offering a smooth finish and a unique aroma.',
            'image_path' => 'assets/images/beer_drink.png',
            'is_popular' => 1
        ],
        [
            'id' => 106,
            'name_lo' => 'ຄັອກເທວສະໝຸນໄພ ວັງວຽງ',
            'name_en' => 'Vangvieng Herbal Cocktail',
            'category' => 'bar',
            'price' => 50000,
            'description_lo' => 'ຄັອກເທວສູດພິເສດ ທີ່ໃຊ້ເຫຼົ້າທ້ອງຖິ່ນຜສົມກັບນ້ຳຕະໄຄ້, ໃບໝາກຂາມ ແລະ ນ້ຳໝາກນາວ.',
            'description_en' => 'A refreshing local spirit cocktail mixed with fresh lemongrass infusion, lime, and local botanicals.',
            'image_path' => 'assets/images/beer_drink.png',
            'is_popular' => 0
        ],
        [
            'id' => 107,
            'name_lo' => 'ລາບໝູຄຣິສປີລາວປະຍຸກ',
            'name_en' => 'Crispy Lao Fusion Larb',
            'category' => 'food',
            'price' => 55000,
            'description_lo' => 'ລາບໝູສະໝຸນໄພລາວແບບດັ້ງເດີມ ແຕ່ເສີບພ້ອມໝູກອບ ແລະ ຜັກສົດອໍການິກ ຈັດຈານຢ່າງທັນສະໄໝ.',
            'description_en' => 'Traditional minced pork salad with Lao herbs, served crispy style with organic fresh vegetables, beautifully plated for a modern experience.',
            'image_path' => 'assets/images/our_story.png',
            'is_popular' => 1
        ],
        [
            'id' => 108,
            'name_lo' => 'ຕຳໝາກຫຸ່ງພຣີມ່ຽມ ເສີບພ້ອມໄກ່ປິ້ງ',
            'name_en' => 'Premium Papaya Salad with Grilled Chicken',
            'category' => 'food',
            'price' => 60000,
            'description_lo' => 'ຕຳໝາກຫຸ່ງລົດຊາດຈັດຈ້ານແບບດັ້ງເດີມ ເສີບຄູ່ກັບໄກ່ປິ້ງສະໝຸນໄພຮ້ອນໆ ແລະ ເຂົ້າໜຽວນຸ້ມ.',
            'description_en' => 'Spicy traditional papaya salad served with hot grilled herbal chicken and sticky rice.',
            'image_path' => 'assets/images/our_story.png',
            'is_popular' => 0
        ]
    ];
}
?>

<!-- Title Header Section -->
<section class="py-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('nav_menu'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo $current_lang === 'lo' ? 'ຄັດສັນລົດຊາດພິເສດເພື່ອທ່ານ' : 'Specially curated tastes for you'; ?></p>
    </div>
</section>

<!-- Category Filter Section & Menu Grid -->
<section class="py-16 bg-gray-50 px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-12">
        
        <!-- Filter buttons -->
        <div class="flex flex-wrap justify-center gap-4">
            <button class="filter-btn px-6 py-2.5 rounded-full border border-burgundy-700 font-semibold text-sm transition-all duration-300 filter-active bg-burgundy text-white shadow-md" data-category="all">
                <?php echo t('menu_all'); ?>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full border border-burgundy-700 bg-white text-burgundy-700 font-semibold text-sm transition-all duration-300 hover:bg-burgundy-700 hover:text-white" data-category="coffee">
                <?php echo t('menu_coffee'); ?>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full border border-burgundy-700 bg-white text-burgundy-700 font-semibold text-sm transition-all duration-300 hover:bg-burgundy-700 hover:text-white" data-category="drinks">
                <?php echo t('menu_drinks'); ?>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full border border-burgundy-700 bg-white text-burgundy-700 font-semibold text-sm transition-all duration-300 hover:bg-burgundy-700 hover:text-white" data-category="bar">
                <?php echo t('menu_bar'); ?>
            </button>
            <button class="filter-btn px-6 py-2.5 rounded-full border border-burgundy-700 bg-white text-burgundy-700 font-semibold text-sm transition-all duration-300 hover:bg-burgundy-700 hover:text-white" data-category="food">
                <?php echo t('menu_food'); ?>
            </button>
        </div>

        <!-- Menu Cards Grid -->
        <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($db_menus as $item): ?>
                <div class="menu-item bg-white border border-gray-100 rounded-2xl shadow-md overflow-hidden flex flex-col card-hover-effect transition-all duration-300" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                    <div class="h-56 relative overflow-hidden">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars(td($item, 'name')); ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        <?php if (isset($item['is_popular']) && $item['is_popular'] == 1): ?>
                            <span class="absolute top-4 left-4 bg-burgundy-700 text-white text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded-full shadow-md">
                                Popular
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1"><?php echo htmlspecialchars(td($item, 'name')); ?></h3>
                            <p class="text-xs text-gray-500 mt-2 line-clamp-3 font-light leading-relaxed"><?php echo htmlspecialchars(td($item, 'description')); ?></p>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-base font-bold text-burgundy-700">
                                <?php echo number_format($item['price']); ?> <?php echo t('currency'); ?>
                            </span>
                            <span class="px-2.5 py-0.5 bg-burgundy-50 text-burgundy-700 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                <?php echo htmlspecialchars(t('menu_' . $item['category'])); ?>
                            </span>
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
