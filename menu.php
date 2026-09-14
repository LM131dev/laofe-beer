<?php
// menu.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$just_selected_table = false;
if (isset($_GET['table'])) {
    $table_req = trim($_GET['table']);
    if ($table_req === 'clear' || $table_req === '') {
        unset($_SESSION['table_number']);
        $current_table = '';
    } else {
        $_SESSION['table_number'] = $table_req;
        $just_selected_table = true;
        $current_table = $_SESSION['table_number'];
    }
} else {
    $current_table = $_SESSION['table_number'] ?? '';
}

require_once __DIR__ . '/includes/header.php';

// ດຶງຂໍ້ມູນເມນູຈາກ Database
$db_menus = [];
$db_banners = [];
try {
    $stmt_b = $pdo->query("SELECT * FROM banners WHERE is_active = 1 AND (target_page = 'menu' OR target_page = 'all' OR target_page IS NULL OR target_page = '') ORDER BY sort_order ASC, id DESC");
    $db_banners = $stmt_b->fetchAll();
} catch (\Exception $e) {}
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

<!-- Featured Menu Swiper Slide Banner Section (Full Bleed with LaoFe Signature Deep Burgundy Theme) -->
<section class="relative pt-20 bg-gradient-to-b from-[#1C050B] via-[#3D0B16] to-[#1F050C] text-white overflow-hidden w-full">
    

    <!-- Table Selection Modal -->
    <div id="table-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" onclick="closeTableModal()"></div>
        <div class="relative w-full max-w-md bg-[#1F110B] border border-[#331B12] text-white rounded-3xl p-6 shadow-2xl z-10 font-serif-lao space-y-5">
            <div class="flex items-center justify-between border-b border-[#331B12] pb-3">
                <h3 class="text-lg font-extrabold text-white flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11h18M6 11v8M18 11v8M10 11v8M14 11v8M5 6h14a1 1 0 011 1v4H4V7a1 1 0 011-1z"/>
                    </svg>
                    <span><?php echo $current_lang === 'lo' ? 'ເລືອກໂຕະນັ່ງຂອງທ່ານ' : 'Select Your Table Number'; ?></span>
                </h3>
                <button onclick="closeTableModal()" class="text-gray-400 hover:text-white text-xl font-bold transition-colors">&times;</button>
            </div>

            <p class="text-xs text-[#C4B7AC]">
                <?php echo $current_lang === 'lo' ? 'ກະລຸນາເລືອກໝາຍເລກໂຕະທີ່ທ່ານນັ່ງຢູ່ ລະບົບຈະສົ່ງອໍເດີ້ໄປຍັງໂຕະຂອງທ່ານທັນທີ' : 'Please select your table number to receive your order directly.'; ?>
            </p>

            <form action="menu.php" method="GET" class="space-y-4">
                <div class="grid grid-cols-4 gap-2 text-xs">
                    <?php for ($t = 1; $t <= 16; $t++): 
                        $t_num = str_pad($t, 2, '0', STR_PAD_LEFT);
                        $is_curr = ($current_table === $t_num);
                    ?>
                        <button type="submit" name="table" value="<?php echo $t_num; ?>" class="py-2.5 px-1.5 rounded-xl border flex items-center justify-center gap-1.5 font-bold transition-all font-mono shadow-sm hover:border-amber-400/80 cursor-pointer <?php echo $is_curr ? 'bg-amber-500 text-burgundy-950 border-amber-400 font-extrabold ring-2 ring-amber-400/50' : 'bg-[#110906] text-amber-200 border-[#331B12] hover:bg-amber-500/10'; ?>">
                            <svg class="w-3.5 h-3.5 shrink-0 <?php echo $is_curr ? 'text-burgundy-950' : 'opacity-70 text-amber-400'; ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16M6 10v7M18 10v7" />
                            </svg>
                            <span>ໂຕະ <?php echo $t_num; ?></span>
                        </button>
                    <?php endfor; ?>
                </div>
                
                <div class="pt-2 border-t border-[#331B12] space-y-2">
                    <?php $is_takeaway = ($current_table === 'Takeaway'); ?>
                    <button type="submit" name="table" value="Takeaway" class="w-full p-3.5 rounded-xl border flex items-center justify-center gap-2.5 font-bold transition-all font-serif-lao shadow-sm hover:border-amber-400/80 cursor-pointer <?php echo $is_takeaway ? 'bg-amber-500 text-burgundy-950 border-amber-400 font-extrabold ring-2 ring-amber-400/50' : 'bg-[#110906] text-white border-[#331B12] hover:bg-amber-500/10'; ?>">
                        <svg class="w-5 h-5 shrink-0 <?php echo $is_takeaway ? 'text-burgundy-950' : 'text-amber-400'; ?>" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119.993zM8.25 10.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm7.5 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <span class="text-sm font-extrabold"><?php echo $current_lang === 'lo' ? 'ສັ່ງກັບບ້ານ (Takeaway)' : 'Takeaway Order'; ?></span>
                    </button>
                    
                    <?php if (!empty($current_table)): ?>
                        <a href="menu.php?table=clear" class="block text-center text-xs text-amber-200/60 hover:text-amber-300 font-medium py-1 transition-colors">
                            <?php echo $current_lang === 'lo' ? '❌ ຍົກເລີກການເລືອກໂຕະ' : '❌ Clear Selection'; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Selection Success Alert Banner / Toast -->
    <?php if ($just_selected_table && !empty($current_table)): ?>
        <div id="table-success-toast" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-11/12 max-w-lg bg-[#3D0B16]/95 backdrop-blur-md border-2 border-amber-400/80 text-white rounded-2xl p-4 shadow-2xl flex items-center justify-between gap-3 font-serif-lao animate-bounce-short">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-400/20 border border-amber-400/40 flex items-center justify-center shrink-0">
                    <?php if ($current_table === 'Takeaway'): ?>
                        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119.993z" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 11h18M6 11v8M18 11v8M10 11v8M14 11v8M5 6h14a1 1 0 011 1v4H4V7a1 1 0 011-1z"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="text-sm font-black text-amber-300 leading-tight">
                        <?php 
                        if ($current_table === 'Takeaway') {
                            echo $current_lang === 'lo' ? 'ເລືອກ "ສັ່ງກັບບ້ານ" ຮຽບຮ້ອຍແລ້ວ! 🥡' : 'Takeaway Selected! 🥡';
                        } else {
                            echo $current_lang === 'lo' ? 'ເປີດ "ໂຕະ ' . htmlspecialchars($current_table) . '" ຮຽບຮ້ອຍແລ້ວ! 📍' : 'Table ' . htmlspecialchars($current_table) . ' Opened! 📍';
                        }
                        ?>
                    </h4>
                    <p class="text-xs text-amber-100/90 font-light mt-0.5">
                        <?php echo $current_lang === 'lo' ? 'ເຊີນທ່ານເລືອກເມນູອັນແຊບຊ້ອຍ ແລະ ເຄື່ອງດື່ມ LaoFe ໄດ້ເລີຍ...' : 'Please feel free to select your favorite LaoFe menu items...'; ?>
                    </p>
                </div>
            </div>
            <button onclick="document.getElementById('table-success-toast').remove()" class="text-amber-200/70 hover:text-white text-xl font-bold p-1">&times;</button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('table-success-toast');
                if (toast) {
                    toast.style.transition = 'all 0.5s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- Swiper Full Bleed Container with LaoFe Deep Burgundy Theme -->
    <div class="swiper menu-banner-swiper w-full bg-gradient-to-r from-[#1C050B] via-[#3D0B16] to-[#1F050C]">
        <div class="swiper-wrapper">
            <?php if (!empty($db_banners)): ?>
                <?php foreach ($db_banners as $b): ?>
                    <div class="swiper-slide w-full bg-gradient-to-r from-[#1C050B] via-[#3D0B16] to-[#1F050C] py-10 md:py-16">
                        <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-16 grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                            <div class="md:col-span-7 space-y-3 md:space-y-5 text-left">
                                <?php if (!empty(td($b, 'badge'))): ?>
                                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-serif-lao text-amber-300 tracking-wide">
                                        <?php echo htmlspecialchars(td($b, 'badge')); ?>
                                    </h3>
                                <?php endif; ?>
                                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold font-serif-lao text-white tracking-tight leading-snug md:leading-tight drop-shadow-md sm:whitespace-nowrap">
                                    <?php echo htmlspecialchars(str_replace([' ໆ', 'ເບຍສົດ'], ['ໆ', 'ເບຍ' . "\xC2\xA0" . 'ສົດ'], td($b, 'title'))); ?>
                                </h2>
                                <?php if (!empty(td($b, 'subtitle'))): ?>
                                    <p class="text-xs sm:text-sm md:text-base text-amber-100/90 font-light font-serif-lao max-w-xl">
                                        <?php echo nl2br(htmlspecialchars(td($b, 'subtitle'))); ?>
                                    </p>
                                <?php endif; ?>
                                 <div class="pt-2">
                                    <?php if (!empty(td($b, 'btn1_text'))): ?>
                                        <a href="<?php echo htmlspecialchars($b['btn1_link'] ?: 'menu.php'); ?>" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-burgundy-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center gap-2">
                                            <?php echo htmlspecialchars(td($b, 'btn1_text')); ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="menu.php" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-burgundy-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center gap-2">
                                            <?php echo $current_lang === 'lo' ? 'ສັ່ງເລີຍ' : 'ORDER NOW'; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="md:col-span-5 flex items-center justify-center md:justify-end">
                                <img src="<?php echo htmlspecialchars($b['image_path']); ?>" alt="<?php echo htmlspecialchars(td($b, 'title')); ?>" class="max-h-72 md:max-h-[380px] w-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500 rounded-2xl">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            
            <!-- Slide 1: Signature Coconut Latte -->
            <div class="swiper-slide w-full bg-gradient-to-r from-[#1C050B] via-[#3D0B16] to-[#1F050C] py-10 md:py-16">
                <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-16 grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                    <div class="md:col-span-7 space-y-3 md:space-y-5 text-left">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-serif-lao text-amber-300 tracking-wide">
                            <?php echo $current_lang === 'lo' ? 'ຊອຍເຟິເຊິມາພ້າວ' : 'LaoFe Signature Coconut'; ?>
                        </h3>
                        <h2 class="text-3xl sm:text-5xl md:text-6xl font-black font-serif-lao text-white tracking-wider uppercase leading-tight drop-shadow-md">
                            EXPERIENCE BOLAVEN
                        </h2>
                        <p class="text-xs sm:text-sm md:text-base text-amber-100/90 font-light font-serif-lao max-w-xl">
                            <?php echo $current_lang === 'lo' ? 'ກາເຟໝາກພ້າວສູດພິເສດ ຫອມມັນກົມກ່ອມ ຈາກດອນໂບລະເວນ' : 'Try our Signature Coconut Latte'; ?>
                        </p>
                        <div class="pt-2">
                            <button onclick="openLuckinDrinkModal(<?php echo htmlspecialchars(json_encode($db_menus[0] ?? []), ENT_QUOTES, 'UTF-8'); ?>)" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-burgundy-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center gap-2">
                                <?php echo $current_lang === 'lo' ? 'ສັ່ງເລີຍ' : 'ORDER NOW'; ?>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-5 flex items-center justify-center md:justify-end">
                        <img src="assets/images/coconut_latte_banner.png" alt="Signature Coconut Latte" class="max-h-72 md:max-h-[380px] w-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500 rounded-2xl">
                    </div>
                </div>
            </div>

            <!-- Slide 2: Premium Lao Craft Beer -->
            <div class="swiper-slide w-full bg-gradient-to-r from-[#1C050B] via-[#3D0B16] to-[#1F050C] py-10 md:py-16">
                <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-16 grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                    <div class="md:col-span-7 space-y-3 md:space-y-5 text-left">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-serif-lao text-amber-300 tracking-wide">
                            <?php echo $current_lang === 'lo' ? 'ເບຍລາວຄຣາບພຣີມ່ຽມ' : 'Lao Craft Beer'; ?>
                        </h3>
                        <h2 class="text-3xl sm:text-5xl md:text-6xl font-black font-serif-lao text-white tracking-wider uppercase leading-tight drop-shadow-md">
                            CRAFTED FOR NIGHTS
                        </h2>
                        <p class="text-xs sm:text-sm md:text-base text-amber-100/90 font-light font-serif-lao max-w-xl">
                            <?php echo $current_lang === 'lo' ? 'ເບຍສົດຄຣາບຄຸນນະພາບສູງ ໝັກຈາກເຂົ້າຫອມລາວແທ້' : 'Authentic Lao Draft Craft Beer'; ?>
                        </p>
                        <div class="pt-2">
                            <button onclick="openLuckinDrinkModal(<?php echo htmlspecialchars(json_encode($db_menus[4] ?? []), ENT_QUOTES, 'UTF-8'); ?>)" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-burgundy-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center gap-2">
                                <?php echo $current_lang === 'lo' ? 'ສັ່ງເລີຍ' : 'ORDER NOW'; ?>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-5 flex items-center justify-center md:justify-end">
                        <img src="assets/images/beer_drink.png" alt="Craft Beer" class="max-h-72 md:max-h-[380px] w-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <!-- Slide 3: Fusion Larb Dish -->
            <div class="swiper-slide w-full bg-gradient-to-r from-[#1C050B] via-[#3D0B16] to-[#1F050C] py-10 md:py-16">
                <div class="max-w-7xl mx-auto px-6 sm:px-12 md:px-16 grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                    <div class="md:col-span-7 space-y-3 md:space-y-5 text-left">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-bold font-serif-lao text-amber-300 tracking-wide">
                            <?php echo $current_lang === 'lo' ? 'ລາບໝູຄຣິສປີລາວປະຍຸກ' : 'Crispy Fusion Larb'; ?>
                        </h3>
                        <h2 class="text-3xl sm:text-5xl md:text-6xl font-black font-serif-lao text-white tracking-wider uppercase leading-tight drop-shadow-md">
                            FRESH & CRISPY LAO
                        </h2>
                        <p class="text-xs sm:text-sm md:text-base text-amber-100/90 font-light font-serif-lao max-w-xl">
                            <?php echo $current_lang === 'lo' ? 'ລາບໝູສະໝຸນໄພລາວແບບດັ້ງເດີມ ເສີບພ້ອມໝູກອບ' : 'Signature Fusion Lao Larb Dish'; ?>
                        </p>
                        <div class="pt-2">
                            <button onclick="openLuckinDrinkModal(<?php echo htmlspecialchars(json_encode($db_menus[6] ?? []), ENT_QUOTES, 'UTF-8'); ?>)" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-burgundy-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-xl hover:scale-105 transition-all duration-300 font-serif-lao inline-flex items-center gap-2">
                                <?php echo $current_lang === 'lo' ? 'ສັ່ງເລີຍ' : 'ORDER NOW'; ?>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-5 flex items-center justify-center md:justify-end">
                        <img src="assets/images/our_story.png" alt="Lao Larb" class="max-h-72 md:max-h-[380px] w-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500 rounded-2xl">
                    </div>
                </div>
            </div>

        <?php endif; ?>
        </div>

        <!-- Swiper Controls -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-amber-300 hidden md:flex"></div>
        <div class="swiper-button-prev text-amber-300 hidden md:flex"></div>
    </div>
</section>
<!-- Category Filter Section & Menu Grid -->
<section class="py-16 bg-[#EFEADF] px-4 sm:px-6 md:px-12">
    <div class="max-w-7xl mx-auto space-y-10">

        <!-- Active Table Status Indicator -->
        <div class="flex items-center justify-between bg-[#3D0B16] border border-amber-500/40 text-white rounded-2xl px-5 py-3.5 shadow-lg font-serif-lao max-w-2xl mx-auto">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo !empty($current_table) ? 'bg-emerald-400' : 'bg-amber-400'; ?> opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 <?php echo !empty($current_table) ? 'bg-emerald-500' : 'bg-amber-500'; ?>"></span>
                </span>
                <div>
                    <span class="text-xs text-amber-200/80 block uppercase font-bold tracking-wider">
                        <?php echo $current_lang === 'lo' ? 'ສະຖານະໂຕະ / ການອໍເດີ້ຂອງທ່ານ:' : 'Your Table / Order Status:'; ?>
                    </span>
                    <span class="text-sm sm:text-base font-black text-white flex items-center gap-1.5 mt-0.5">
                        <?php if (empty($current_table)): ?>
                            <span class="text-amber-300">⚠️ <?php echo $current_lang === 'lo' ? 'ຍັງບໍ່ໄດ້ເລືອກໂຕະ (ກະລຸນາເລືອກໂຕະກ່ອນສັ່ງ)' : 'No Table Selected (Please select table)'; ?></span>
                        <?php elseif ($current_table === 'Takeaway'): ?>
                            <span>🥡</span> <?php echo $current_lang === 'lo' ? 'ສັ່ງກັບບ້ານ (Takeaway)' : 'Takeaway Order'; ?>
                        <?php else: ?>
                            <span>📍</span> <?php echo $current_lang === 'lo' ? 'ໂຕະ ' . htmlspecialchars($current_table) : 'Table ' . htmlspecialchars($current_table); ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <button onclick="openTableModal()" class="px-3.5 py-1.5 rounded-xl <?php echo empty($current_table) ? 'bg-amber-400 text-burgundy-950 hover:bg-amber-300 font-extrabold shadow-md animate-pulse' : 'bg-amber-400/20 hover:bg-amber-400/30 text-amber-300 font-extrabold border border-amber-400/40 shadow-sm'; ?> text-xs transition-all flex items-center gap-1.5 hover:scale-105 active:scale-95">
                <span><?php echo empty($current_table) ? ($current_lang === 'lo' ? 'ເລືອກໂຕະ / ສັ່ງກັບບ້ານ' : 'Select Table') : ($current_lang === 'lo' ? 'ປ່ຽນໂຕະ' : 'Change'); ?></span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            </button>
        </div>
        <!-- 5 Category Line-Art Icon Tiles (Matching Reference Screenshot 100%) -->
        <div class="flex items-center justify-center gap-3 sm:gap-5 overflow-x-auto pb-4 pt-2 no-scrollbar px-2">
            <!-- Tile 1: ທັງໝົດ (All) -->
            <button class="filter-btn category-tile-btn shrink-0 flex flex-col items-center justify-center p-3 gap-1.5 group filter-active" data-category="all">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 stroke-current" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span class="text-[11px] sm:text-xs font-bold font-serif-lao tracking-wide text-center leading-tight">
                    <?php echo $current_lang === 'lo' ? 'ທັງໝົດ' : 'All'; ?>
                </span>
                <span class="tile-dot w-1.5 h-1.5 rounded-full bg-amber-400"></span>
            </button>

            <!-- Tile 2: ອາຫານ (Food) -->
            <button class="filter-btn category-tile-btn shrink-0 flex flex-col items-center justify-center p-3 gap-1.5 group" data-category="food">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 stroke-current" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 009-9H3a9 9 0 009 9zM12 3v6m-4-6v6m8-6v6" />
                </svg>
                <span class="text-[11px] sm:text-xs font-bold font-serif-lao tracking-wide text-center leading-tight">
                    <?php echo $current_lang === 'lo' ? 'ອາຫານ' : 'Food'; ?>
                </span>
                <span class="tile-dot w-1.5 h-1.5 rounded-full bg-amber-800/30"></span>
            </button>

            <!-- Tile 3: ເຄື່ອງດື່ມ (Drinks) -->
            <button class="filter-btn category-tile-btn shrink-0 flex flex-col items-center justify-center p-3 gap-1.5 group" data-category="drinks">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 stroke-current" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3" />
                </svg>
                <span class="text-[11px] sm:text-xs font-bold font-serif-lao tracking-wide text-center leading-tight">
                    <?php echo $current_lang === 'lo' ? 'ເຄື່ອງດື່ມ' : 'Drinks'; ?>
                </span>
                <span class="tile-dot w-1.5 h-1.5 rounded-full bg-amber-800/30"></span>
            </button>

            <!-- Tile 4: ເຫຼົ້າ & ເບຍ (Alcohol & Beer) -->
            <button class="filter-btn category-tile-btn shrink-0 flex flex-col items-center justify-center p-3 gap-1.5 group" data-category="bar">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 stroke-current" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5a3.75 3.75 0 01-3.75 3.75H8.75A3.75 3.75 0 015 14.5m14 0V9a2.25 2.25 0 00-2.25-2.25H7.25A2.25 2.25 0 005 9v5.5" />
                </svg>
                <span class="text-[11px] sm:text-xs font-bold font-serif-lao tracking-wide text-center leading-tight">
                    <?php echo $current_lang === 'lo' ? 'ເຫຼົ້າ & ເບຍ' : 'Alcohol & Beer'; ?>
                </span>
                <span class="tile-dot w-1.5 h-1.5 rounded-full bg-amber-800/30"></span>
            </button>

            <!-- Tile 5: Promotion (Promotion) -->
            <button class="filter-btn category-tile-btn shrink-0 flex flex-col items-center justify-center p-3 gap-1.5 group" data-category="promo">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 stroke-current" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-9-13.5h18a1.5 1.5 0 011.5 1.5v1.5a1.5 1.5 0 01-1.5 1.5H3a1.5 1.5 0 01-1.5-1.5v-1.5A1.5 1.5 0 013 7.5z" />
                </svg>
                <span class="text-[11px] sm:text-xs font-bold font-serif-lao tracking-wide text-center leading-tight">
                    <?php echo $current_lang === 'lo' ? 'Promotion' : 'Promotion'; ?>
                </span>
                <span class="tile-dot w-1.5 h-1.5 rounded-full bg-amber-800/30"></span>
            </button>
        </div>

        <!-- Menu Cards Grid (Matching Reference Warm Bakery/Cafe Design) -->
        <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-8">
            <?php foreach ($db_menus as $item): ?>
                <div class="menu-item reference-menu-card overflow-hidden flex flex-col justify-between group" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                    
                    <!-- Top Full-Bleed Product Photography Image -->
                    <div class="reference-card-image-wrap relative">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars(td($item, 'name')); ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        
                        <!-- Floating Glassmorphic Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start z-10">
                            <?php if (isset($item['is_popular']) && $item['is_popular'] == 1): ?>
                                <span class="bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 text-[10px] uppercase font-bold tracking-widest px-2.5 py-0.5 rounded-md shadow-md font-serif-lao flex items-center gap-1">
                                    ★ POPULAR
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($item['tag'])): ?>
                                <span class="bg-amber-400 text-gray-950 text-[10px] uppercase font-black tracking-wider px-2.5 py-0.5 rounded-md shadow-sm font-serif-lao">
                                    <?php echo htmlspecialchars($item['tag']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Body (Warm Ivory Background matching screenshot) -->
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4 bg-[#FAF7F2]">
                        <div class="space-y-1.5">
                            <h3 class="reference-card-title text-base sm:text-lg line-clamp-1 group-hover:text-burgundy-700 transition-colors duration-300">
                                <?php echo htmlspecialchars(td($item, 'name')); ?>
                            </h3>
                            <p class="text-xs text-[#6E584E] font-light leading-relaxed line-clamp-2 font-serif-lao min-h-[2.25rem]">
                                <?php echo htmlspecialchars(td($item, 'description') ?: ($current_lang === 'lo' ? 'ກາເຟ ແລະ ເຄື່ອງດື່ມສູດພິເສດ LaoFe' : 'Signature LaoFe coffee & beverage')); ?>
                            </p>
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

                            <!-- Quick Add Button -->
                            <button onclick="openLuckinDrinkModal(<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)" class="px-3.5 py-1.5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs flex items-center space-x-1 shadow-md hover:scale-105 active:scale-95 transition-all duration-300 font-serif-lao">
                                <svg class="w-3.5 h-3.5 text-amber-200" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span><?php echo t('btn_add_cart'); ?></span>
                            </button>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Luckin Drink Customization Modal -->
<div id="luckin-drink-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" onclick="closeLuckinDrinkModal()"></div>
    <div class="relative w-full max-w-lg bg-[#FAF7F2] rounded-3xl shadow-2xl overflow-hidden z-10 border border-[#EBE4D8] flex flex-col max-h-[90vh] font-serif-lao">
        
        <!-- Modal Top Header -->
        <div class="relative bg-gradient-to-b from-[#3D0B16] to-[#1F050C] p-6 text-white shrink-0">
            <button onclick="closeLuckinDrinkModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex items-center gap-4">
                <img id="modal-drink-img" src="assets/images/coffee.png" alt="Drink" class="w-20 h-20 object-cover rounded-2xl border-2 border-amber-400/40 shadow-lg shrink-0">
                <div class="space-y-1">
                    <h3 id="modal-drink-name" class="text-lg sm:text-xl font-bold text-amber-200"></h3>
                    <p id="modal-drink-desc" class="text-xs text-amber-100/80 line-clamp-2 font-light"></p>
                    <div class="text-base font-extrabold text-amber-400 font-mono pt-1" id="modal-drink-price-display">0 LAK</div>
                </div>
            </div>
        </div>

        <!-- Modal Customization Form -->
        <form action="cart_action.php" method="GET" class="p-6 overflow-y-auto space-y-5 text-gray-800 flex-grow">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" id="modal-drink-id" value="">
            <input type="hidden" id="modal-drink-price-val" value="0">
            <input type="hidden" name="temp" id="modal-temp-input" value="iced">
            <input type="hidden" name="sweet" id="modal-sweet-input" value="100">
            <input type="hidden" name="qty" id="modal-qty-input" value="1">

            <!-- Temperature Choice -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-amber-900/80">
                    <?php echo $current_lang === 'lo' ? 'ລະດັບຄວາມຮ້ອນ/ເຢັນ' : 'Temperature'; ?>
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="setTemp('hot')" id="btn-temp-hot" class="temp-opt-btn py-2.5 px-3 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-2 border-gray-200 bg-white text-gray-700 hover:bg-amber-50">
                        <svg class="w-4 h-4 text-orange-500 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 3.974 3.974 0 01-1.22-1.979A3.75 3.75 0 0012 18z" />
                        </svg>
                        <span><?php echo $current_lang === 'lo' ? 'ຮ້ອນ' : 'Hot'; ?></span>
                    </button>
                    <button type="button" onclick="setTemp('iced')" id="btn-temp-iced" class="temp-opt-btn py-2.5 px-3 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-2 border-amber-500 bg-amber-500 text-burgundy-950 font-black shadow-sm">
                        <svg class="w-4 h-4 text-sky-600 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20m10-10H2m17.071-7.071L4.929 19.071M19.071 19.071L4.929 4.929" />
                        </svg>
                        <span><?php echo $current_lang === 'lo' ? 'ເຢັນ' : 'Iced'; ?></span>
                    </button>
                    <button type="button" onclick="setTemp('cold')" id="btn-temp-cold" class="temp-opt-btn py-2.5 px-3 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-2 border-gray-200 bg-white text-gray-700 hover:bg-amber-50">
                        <svg class="w-4 h-4 text-indigo-500 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <span><?php echo $current_lang === 'lo' ? 'ປັ່ນ' : 'Frappe'; ?></span>
                    </button>
                </div>
            </div>

            <!-- Sweetness Level Choice -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-amber-900/80">
                    <?php echo $current_lang === 'lo' ? 'ລະດັບຄວາມຫວານ' : 'Sweetness Level'; ?>
                </label>
                <div class="grid grid-cols-4 gap-2 text-xs">
                    <button type="button" onclick="setSweet('100')" id="btn-sweet-100" class="sweet-opt-btn py-2 rounded-xl border font-bold transition-all border-amber-500 bg-amber-500 text-burgundy-950 font-black">
                        100%
                    </button>
                    <button type="button" onclick="setSweet('50')" id="btn-sweet-50" class="sweet-opt-btn py-2 rounded-xl border font-bold transition-all border-gray-200 bg-white text-gray-700 hover:bg-amber-50">
                        50%
                    </button>
                    <button type="button" onclick="setSweet('30')" id="btn-sweet-30" class="sweet-opt-btn py-2 rounded-xl border font-bold transition-all border-gray-200 bg-white text-gray-700 hover:bg-amber-50">
                        30%
                    </button>
                    <button type="button" onclick="setSweet('0')" id="btn-sweet-0" class="sweet-opt-btn py-2 rounded-xl border font-bold transition-all border-gray-200 bg-white text-gray-700 hover:bg-amber-50">
                        0%
                    </button>
                </div>
            </div>

            <!-- Notes / Remarks -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold uppercase tracking-wider text-amber-900/80">
                    <?php echo $current_lang === 'lo' ? 'ໝາຍເຫດເພີ່ມເຕີມ' : 'Special Instructions'; ?>
                </label>
                <input type="text" name="notes" id="modal-notes" placeholder="<?php echo $current_lang === 'lo' ? 'ເຊັ່ນ: ບໍ່ໃສ່ນົມ, ຂໍນ້ຳແຂງເພີ່ມ...' : 'e.g. Less ice, oat milk...'; ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <!-- Quantity & Submit Footer -->
            <div class="pt-4 border-t border-gray-200 flex items-center justify-between gap-4">
                <!-- Quantity Stepper -->
                <div class="flex items-center border border-gray-300 rounded-full bg-white px-2 py-1 space-x-3 shadow-sm">
                    <button type="button" onclick="changeQty(-1)" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition-colors text-sm">-</button>
                    <span id="modal-qty-display" class="font-extrabold text-sm w-4 text-center">1</span>
                    <button type="button" onclick="changeQty(1)" class="w-7 h-7 rounded-full bg-amber-500 hover:bg-amber-400 text-burgundy-950 font-bold flex items-center justify-center transition-colors text-sm">+</button>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="flex-grow py-3 px-5 rounded-full bg-burgundy-700 hover:bg-burgundy-800 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-lg hover:scale-105 active:scale-95 transition-all duration-300 font-serif-lao">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span><?php echo t('btn_add_cart'); ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const currentTable = "<?php echo htmlspecialchars($current_table); ?>";

document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');

            filterButtons.forEach(btn => {
                btn.classList.remove('filter-active');
                const dot = btn.querySelector('.tile-dot');
                if (dot) dot.classList.replace('bg-amber-400', 'bg-amber-800/30');
            });
            this.classList.add('filter-active');
            const currentDot = this.querySelector('.tile-dot');
            if (currentDot) currentDot.classList.replace('bg-amber-800/30', 'bg-amber-400');

            menuItems.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                if (category === 'all') {
                    item.style.display = 'flex';
                } else if (category === 'drinks' && (itemCat === 'drinks' || itemCat === 'coffee')) {
                    item.style.display = 'flex';
                } else if (category === 'bar' && (itemCat === 'bar' || itemCat === 'alcohol')) {
                    item.style.display = 'flex';
                } else if (category === 'promo' && (itemCat === 'promo' || itemCat === 'promotion')) {
                    item.style.display = 'flex';
                } else if (category === itemCat) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

function openLuckinDrinkModal(item) {
    if (!currentTable) {
        openTableModal();
        return;
    }
    document.getElementById('modal-drink-id').value = item.id;
    document.getElementById('modal-drink-name').innerText = item.name_lo || item.name_en;
    document.getElementById('modal-drink-desc').innerText = item.description_lo || item.description_en || '';
    
    const price = parseFloat(item.price) || 0;
    document.getElementById('modal-drink-price-val').value = price;
    document.getElementById('modal-drink-price-display').innerText = new Intl.NumberFormat().format(price) + ' LAK';
    document.getElementById('modal-drink-img').src = item.image_path || 'assets/images/coffee.png';
    
    document.getElementById('modal-qty-input').value = 1;
    document.getElementById('modal-qty-display').innerText = 1;
    document.getElementById('modal-notes').value = '';
    
    setTemp('iced');
    setSweet('100');
    
    document.getElementById('luckin-drink-modal').classList.remove('hidden');
}

function closeLuckinDrinkModal() {
    document.getElementById('luckin-drink-modal').classList.add('hidden');
}

function openTableModal() {
    document.getElementById('table-modal').classList.remove('hidden');
}

function closeTableModal() {
    document.getElementById('table-modal').classList.add('hidden');
}

function setTemp(tempVal) {
    document.getElementById('modal-temp-input').value = tempVal;
    const tempBtns = document.querySelectorAll('.temp-opt-btn');
    tempBtns.forEach(btn => {
        btn.classList.remove('border-amber-500', 'bg-amber-500', 'text-burgundy-950', 'font-black', 'shadow-sm');
        btn.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
    });
    const target = document.getElementById('btn-temp-' + tempVal);
    if (target) {
        target.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
        target.classList.add('border-amber-500', 'bg-amber-500', 'text-burgundy-950', 'font-black', 'shadow-sm');
    }
}

function setSweet(sweetVal) {
    document.getElementById('modal-sweet-input').value = sweetVal;
    const sweetBtns = document.querySelectorAll('.sweet-opt-btn');
    sweetBtns.forEach(btn => {
        btn.classList.remove('border-amber-500', 'bg-amber-500', 'text-[#531321]', 'font-black');
        btn.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
    });
    const target = document.getElementById('btn-sweet-' + sweetVal);
    if (target) {
        target.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
        target.classList.add('border-amber-500', 'bg-amber-500', 'text-[#531321]', 'font-black');
    }
}

function changeQty(delta) {
    const qtyInput = document.getElementById('modal-qty-input');
    const qtyDisplay = document.getElementById('modal-qty-display');
    let currentQty = parseInt(qtyInput.value) || 1;
    currentQty += delta;
    if (currentQty < 1) currentQty = 1;
    qtyInput.value = currentQty;
    qtyDisplay.innerText = currentQty;
}
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
