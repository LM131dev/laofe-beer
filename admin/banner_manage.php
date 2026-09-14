<?php
// admin/banner_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການການລຶບ (DELETE)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        // ດຶງຮູບພາບມາລຶບອອກຈາກເຊີເວີກ່ອນ (ຖ້າມີ)
        $stmt_img = $pdo->prepare("SELECT image_path FROM banners WHERE id = ?");
        $stmt_img->execute([$delete_id]);
        $img_path = $stmt_img->fetchColumn();
        if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'default') && !strpos($img_path, 'hero_banner.png') && !strpos($img_path, 'beer_drink.png')) {
            @unlink('../' . $img_path);
        }

        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = 'ລຶບ Banner ຮຽບຮ້ອຍແລ້ວ!';
    } catch (\Exception $e) {
        $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
    }
}

// 2. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂ (CREATE / UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $badge_lo = trim($_POST['badge_lo'] ?? '');
    $badge_en = trim($_POST['badge_en'] ?? '');
    $title_lo = trim($_POST['title_lo'] ?? '');
    $title_en = trim($_POST['title_en'] ?? '');
    $subtitle_lo = trim($_POST['subtitle_lo'] ?? '');
    $subtitle_en = trim($_POST['subtitle_en'] ?? '');
    $btn1_text_lo = trim($_POST['btn1_text_lo'] ?? '');
    $btn1_text_en = trim($_POST['btn1_text_en'] ?? '');
    $btn1_link = trim($_POST['btn1_link'] ?? '');
    $btn2_text_lo = trim($_POST['btn2_text_lo'] ?? '');
    $btn2_text_en = trim($_POST['btn2_text_en'] ?? '');
    $btn2_link = trim($_POST['btn2_link'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $target_page = trim($_POST['target_page'] ?? 'all');
    
    $image_path = $_POST['existing_image'] ?? 'assets/images/hero_banner.png';

    // ຈັດການການອັບໂຫຼດຮູບພາບ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../assets/images/';
            
            if (!file_exists($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // ລຶບຮູບເກົ່າ (ຖ້າມີ ແລະ ບໍ່ແມ່ນຮູບ default)
                if ($id > 0 && $image_path && file_exists('../' . $image_path) && !strpos($image_path, 'default') && !strpos($image_path, 'hero_banner.png') && !strpos($image_path, 'beer_drink.png')) {
                    @unlink('../' . $image_path);
                }
                $image_path = 'assets/images/' . $newFileName;
            } else {
                $error = 'ມີບັນຫາໃນການຍ້າຍໄຟລ໌ທີ່ອັບໂຫຼດ.';
            }
        } else {
            $error = 'ອັບໂຫຼດບໍ່ສຳເລັດ. ນາມສະກຸນໄຟລ໌ທີ່ອະນຸຍາດ: ' . implode(',', $allowedExtensions);
        }
    }

    if (empty($title_lo) || empty($title_en)) {
        $error = 'ກະລຸນາກອກ ຫົວຂໍ້ໃຫຍ່ ທັງພາສາລາວ ແລະ ອັງກິດ.';
    } elseif (empty($image_path)) {
        $error = 'ກະລຸນາເລືອກຮູບພາບ Banner.';
    } elseif (empty($error)) {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE banners SET badge_lo = ?, badge_en = ?, title_lo = ?, title_en = ?, subtitle_lo = ?, subtitle_en = ?, btn1_text_lo = ?, btn1_text_en = ?, btn1_link = ?, btn2_text_lo = ?, btn2_text_en = ?, btn2_link = ?, sort_order = ?, is_active = ?, target_page = ?, image_path = ? WHERE id = ?");
                $stmt->execute([$badge_lo, $badge_en, $title_lo, $title_en, $subtitle_lo, $subtitle_en, $btn1_text_lo, $btn1_text_en, $btn1_link, $btn2_text_lo, $btn2_text_en, $btn2_link, $sort_order, $is_active, $target_page, $image_path, $id]);
                $success = 'ແກ້ໄຂ Banner ຮຽບຮ້ອຍ!';
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO banners (badge_lo, badge_en, title_lo, title_en, subtitle_lo, subtitle_en, btn1_text_lo, btn1_text_en, btn1_link, btn2_text_lo, btn2_text_en, btn2_link, sort_order, is_active, target_page, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$badge_lo, $badge_en, $title_lo, $title_en, $subtitle_lo, $subtitle_en, $btn1_text_lo, $btn1_text_en, $btn1_link, $btn2_text_lo, $btn2_text_en, $btn2_link, $sort_order, $is_active, $target_page, $image_path]);
                $success = 'ເພີ່ມ Banner ໃໝ່ຮຽບຮ້ອຍ!';
            }
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
        }
    }
}

// ດຶງຂໍ້ມູນທັງໝົດ
$banners = [];
try {
    $stmt = $pdo->query("SELECT * FROM banners ORDER BY sort_order ASC, id DESC");
    $banners = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}

// ດຶງຂໍ້ມູນສະເພາະ ID ທີ່ຕ້ອງການແກ້ໄຂ
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    foreach ($banners as $item) {
        if ($item['id'] === $edit_id) {
            $edit_item = $item;
            break;
        }
    }
}
?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການ Hero Banners</h1>
            <p class="text-sm text-gray-500 mt-1">ເພີ່ມ, ແກ້ໄຂ, ຫຼື ລຶບ Banner ທີ່ສະແດງໃນໜ້າຫຼັກ (Homepage Slider)</p>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded text-green-700 text-sm font-semibold">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- ຟອມ ເພີ່ມ / ແກ້ໄຂ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">
                <?php echo $edit_item ? 'ແກ້ໄຂ Banner ID: ' . $edit_item['id'] : 'ເພີ່ມ Banner ໃໝ່'; ?>
            </h3>
            
            <form action="banner_manage.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? ''; ?>">

                <!-- Badge -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Badge (ລາວ)</label>
                        <input type="text" name="badge_lo" value="<?php echo htmlspecialchars($edit_item['badge_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ກາເຟພຣີມ່ຽມ" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Badge (English)</label>
                        <input type="text" name="badge_en" value="<?php echo htmlspecialchars($edit_item['badge_en'] ?? ''); ?>" placeholder="e.g. Premium Coffee" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ໃຫຍ່ (ລາວ) *</label>
                    <input type="text" name="title_lo" required value="<?php echo htmlspecialchars($edit_item['title_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ລົດຊາດລາວປະຍຸກ ແທ້ໆ" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ໃຫຍ່ (English) *</label>
                    <input type="text" name="title_en" required value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>" placeholder="e.g. Truly Lao-Adapted Flavor" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>

                <!-- Subtitle -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ຍ່ອຍ (ລາວ)</label>
                    <textarea name="subtitle_lo" rows="2" placeholder="ຄຳອະທິບາຍເພີ່ມເຕີມ..." class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none"><?php echo htmlspecialchars($edit_item['subtitle_lo'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ຍ່ອຍ (English)</label>
                    <textarea name="subtitle_en" rows="2" placeholder="Additional description..." class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none"><?php echo htmlspecialchars($edit_item['subtitle_en'] ?? ''); ?></textarea>
                </div>

                <!-- Button 1 -->
                <div class="border-t pt-3 space-y-3">
                    <span class="block text-xs font-bold text-burgundy-700 uppercase">ປຸ່ມຫຼັກ (Button 1)</span>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-600 mb-1">ຊື່ປຸ່ມ (ລາວ)</label>
                            <input type="text" name="btn1_text_lo" value="<?php echo htmlspecialchars($edit_item['btn1_text_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ເບິ່ງເມນູ" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-600 mb-1">ຊື່ປຸ່ມ (English)</label>
                            <input type="text" name="btn1_text_en" value="<?php echo htmlspecialchars($edit_item['btn1_text_en'] ?? ''); ?>" placeholder="e.g. View Menu" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-600 mb-1">ລິ້ງປຸ່ມ (URL)</label>
                        <input type="text" name="btn1_link" value="<?php echo htmlspecialchars($edit_item['btn1_link'] ?? ''); ?>" placeholder="ເຊັ່ນ: menu.php" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                </div>

                <!-- Button 2 -->
                <div class="border-t pt-3 space-y-3">
                    <span class="block text-xs font-bold text-burgundy-700 uppercase">ປຸ່ມຍ່ອຍ (Button 2)</span>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-600 mb-1">ຊື່ປຸ່ມ (ລາວ)</label>
                            <input type="text" name="btn2_text_lo" value="<?php echo htmlspecialchars($edit_item['btn2_text_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ກ່ຽວກັບເຮົາ" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-600 mb-1">ຊື່ປຸ່ມ (English)</label>
                            <input type="text" name="btn2_text_en" value="<?php echo htmlspecialchars($edit_item['btn2_text_en'] ?? ''); ?>" placeholder="e.g. Our Story" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-600 mb-1">ລິ້ງປຸ່ມ (URL)</label>
                        <input type="text" name="btn2_link" value="<?php echo htmlspecialchars($edit_item['btn2_link'] ?? ''); ?>" placeholder="ເຊັ່ນ: our-story.php" class="w-full px-3 py-1.5 border rounded text-xs focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                </div>

                <!-- Target Page Selection -->
                <div class="border-t pt-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ສະແດງຢູ່ໜ້າໃດ (Target Page) *</label>
                    <select name="target_page" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none bg-white font-serif-lao">
                        <option value="all" <?php echo (($edit_item['target_page'] ?? 'all') === 'all') ? 'selected' : ''; ?>>🌐 ທຸກໆໜ້າ (All Pages)</option>
                        <option value="home" <?php echo (($edit_item['target_page'] ?? '') === 'home') ? 'selected' : ''; ?>>🏠 ໜ້າຫຼັກ (Home Page - index.php)</option>
                        <option value="menu" <?php echo (($edit_item['target_page'] ?? '') === 'menu') ? 'selected' : ''; ?>>🍽️ ໜ້າເມນູ (Menu Page - menu.php)</option>
                        <option value="locations" <?php echo (($edit_item['target_page'] ?? '') === 'locations') ? 'selected' : ''; ?>>📍 ໜ້າສາຂາ (Locations Page - locations.php)</option>
                    </select>
                </div>

                <!-- Settings -->
                <div class="border-t pt-3 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ລຳດັບການສະແດງ</label>
                        <input type="number" name="sort_order" value="<?php echo htmlspecialchars($edit_item['sort_order'] ?? 0); ?>" class="w-full px-3 py-1.5 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_item['is_active']) || $edit_item['is_active'] == 1) ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-burgundy-700 relative"></div>
                            <span class="ml-2 text-xs font-bold text-gray-700 uppercase">ເປີດໃຊ້ງານ</span>
                        </label>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="border-t pt-3">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບ Banner *</label>
                    <?php if (isset($edit_item['image_path']) && !empty($edit_item['image_path'])): ?>
                        <div class="relative w-full h-32 rounded overflow-hidden mb-2 border">
                            <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" <?php echo $edit_item ? '' : 'required'; ?> class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-burgundy-700 file:text-white hover:file:bg-burgundy-800 cursor-pointer">
                    <span class="block text-[10px] text-gray-400 mt-1">ແນະນຳຂະໜາດ: 1920x1080 ຫຼື ອັດຕາສ່ວນ 16:9</span>
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit" class="flex-grow py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded shadow text-sm transition-all duration-200">
                        <?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມ Banner ໃໝ່'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="banner_manage.php" class="px-4 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-bold text-sm transition-all duration-200">ຍົກເລີກ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງສະແດງລາຍການ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">ລາຍຊື່ Banner ທັງໝົດ</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-lg">
                        <tr>
                            <th class="px-4 py-3">ຮູບພາບ</th>
                            <th class="px-4 py-3">ຫົວຂໍ້ (ລາວ / En)</th><th class="px-4 py-3 text-center">ໜ້າທີີ່ສະແດງ</th>
                            <th class="px-4 py-3 text-center">ປຸ່ມ</th>
                            <th class="px-4 py-3 text-center">ລຳດັບ</th>
                            <th class="px-4 py-3 text-center">ສະຖານະ</th>
                            <th class="px-4 py-3 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($banners) > 0): ?>
                            <?php foreach ($banners as $b): ?>
                                <tr class="hover:bg-gray-50/50 transition-all duration-200">
                                    <!-- Image Thumbnail -->
                                    <td class="px-4 py-4">
                                        <div class="w-24 h-14 rounded overflow-hidden border border-gray-100">
                                            <img src="../<?php echo htmlspecialchars($b['image_path']); ?>" class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    
                                    <!-- Titles -->
                                    <td class="px-4 py-4 max-w-xs">
                                        <div class="space-y-1">
                                            <?php if (!empty($b['badge_lo']) || !empty($b['badge_en'])): ?>
                                                <span class="inline-block px-1.5 py-0.5 bg-burgundy-50 text-burgundy-700 rounded text-[9px] font-bold">
                                                    <?php echo htmlspecialchars($b['badge_lo'] ?: $b['badge_en']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <div class="text-xs font-bold text-gray-950 line-clamp-1">ລາວ: <?php echo htmlspecialchars($b['title_lo']); ?></div>
                                            <div class="text-xs text-gray-400 line-clamp-1 font-mono">EN: <?php echo htmlspecialchars($b['title_en']); ?></div>
                                        </div>
                                    </td>

                                    <!-- Target Page Badge -->
                                    <td class="px-4 py-4 text-center">
                                        <?php 
                                        $tp = $b['target_page'] ?? 'all';
                                        if ($tp === 'home'): ?>
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-bold">🏠 ໜ້າຫຼັກ</span>
                                        <?php elseif ($tp === 'menu'): ?>
                                            <span class="px-2 py-1 bg-amber-50 text-amber-800 rounded-full text-[10px] font-bold">🍽️ ໜ້າເມນູ</span>
                                        <?php elseif ($tp === 'locations'): ?>
                                            <span class="px-2 py-1 bg-emerald-50 text-emerald-800 rounded-full text-[10px] font-bold">📍 ໜ້າສາຂາ</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full text-[10px] font-bold">🌐 ທຸກໆໜ້າ</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Buttons status -->
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <?php if (!empty($b['btn1_text_lo']) && !empty($b['btn1_link'])): ?>
                                                <span class="inline-block px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded text-[10px] max-w-[80px] truncate" title="<?php echo htmlspecialchars($b['btn1_link']); ?>">
                                                    🔗 <?php echo htmlspecialchars($b['btn1_text_lo']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($b['btn2_text_lo']) && !empty($b['btn2_link'])): ?>
                                                <span class="inline-block px-1.5 py-0.5 border border-gray-200 text-gray-500 rounded text-[10px] max-w-[80px] truncate" title="<?php echo htmlspecialchars($b['btn2_link']); ?>">
                                                    🔗 <?php echo htmlspecialchars($b['btn2_text_lo']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (empty($b['btn1_text_lo']) && empty($b['btn2_text_lo'])): ?>
                                                <span class="text-gray-300 text-xs">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- Sort Order -->
                                    <td class="px-4 py-4 text-center text-xs font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($b['sort_order']); ?>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-4 py-4 text-center">
                                        <?php if ($b['is_active'] == 1): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                                ເປີດໃຊ້ງານ
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">
                                                ປິດໄວ້
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="banner_manage.php?edit=<?php echo $b['id']; ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-all" title="ແກ້ໄຂ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <a href="banner_manage.php?delete=<?php echo $b['id']; ?>" onclick="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບ Banner ນີ້?')" class="p-1.5 text-red-650 hover:bg-red-50 rounded transition-all" title="ລຶບ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-xs">
                                    ບໍ່ມີຂໍ້ມູນ Banner ໃນລະບົບ. ລະບົບຈະສະແດງ Banner ເລີ່ມຕົ້ນ (Fallback Static Slides) ຢູ່ໜ້າເວັບຫຼັກ.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<?php
// We don't have footer files explicitly included in dashboard/news pages since it's wrapped in header main content or separate layout structure. Let's make sure it closes appropriately.
?>
