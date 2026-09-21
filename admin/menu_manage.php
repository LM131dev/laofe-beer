<?php
// admin/menu_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການນຳເຂົ້າເມນູເລີ່ມຕົ້ນ (SEED via POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'seed') {
    if (!verify_csrf_token()) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        try {
            $pdo->exec("INSERT INTO menus (name_lo, name_en, category, price, description_lo, description_en, image_path, is_popular) VALUES
            ('ລາວເຟ ໂຄໂຄນັດ ລາເຕ້', 'LaoFe Coconut Latte', 'coffee', 35000, 'ກາເຟເອສເປຣສໂຊທີ່ເຂັ້ມຂຸ້ນ ຜສົມຜະສານກັບນ້ຳໝາກພ້າວສົດ ແລະ ນ້ຳນົມໝາກພ້າວສູດພິເສດ ຫວານມັນ ຫອມລະມຸນ.', 'A rich espresso shot layered with fresh coconut water and our signature coconut cream, delivering a smooth, refreshing, and tropical taste.', 'assets/images/coffee.png', 1),
            ('ເອສເປຣສໂຊ ເຢັນ', 'Iced Espresso', 'coffee', 28000, 'ກາເຟເອສເປຣສໂຊລົດຊາດເຂັ້ມຂຸ້ນ ແບບສະບັບຄວາມເຂັ້ມທີ່ລົງຕົວ ຕື່ນຕົວຕະຫຼອດວັນ.', 'Classic double shot espresso served chilled, bringing out the bold and rich chocolatey notes.', 'assets/images/coffee.png', 0),
            ('ຊານົມເຜືອກລາວປະຍຸກ', 'Lao Taro Milk Tea', 'drinks', 30000, 'ຊານົມຕົ້ມສົດໆ ຜສົມເນື້ອເຜືອກແທ້ຈາກທ້ອງຖິ່ນ ຫວານພໍດີ ຫອມກິ່ນໃບຊາ.', 'Freshly brewed milk tea blended with local organic taro paste, smooth and flavorful.', 'assets/images/coffee.png', 0),
            ('ນ້ຳໝາກມ່ວງປັ່ນສະໝຸນໄພ', 'Mango Herbal Smoothie', 'drinks', 32000, 'ນ້ຳໝາກມ່ວງສົດປັ່ນ ຜສົມໃບສະຫລະແໜ່ (Mint) ແລະ ນ້ຳເຜິ້ງປ່າ ປອດສານພິດ.', 'Fresh mango blend infused with organic wild honey and wild mint leaves, refreshing and nutritious.', 'assets/images/coffee.png', 0),
            ('ເບຍລາວຄຣາບພຣີມ່ຽມ', 'Premium Lao Craft Beer', 'bar', 45000, 'ເບຍສົດຄຣາບຄຸນນະພາບສູງ ໝັກຈາກເຂົ້າຫອມລາວແທ້ໆ ໃຫ້ລົດຊາດທີ່ນຸ້ມນວນ ແລະ ກິ່ນຫອມອັນເປັນເອກະລັກ.', 'High-quality draft craft beer brewed locally with authentic Lao jasmine rice, offering a smooth finish and a unique aroma.', 'assets/images/beer_drink.png', 1),
            ('ຄັອກເທວສະໝຸນໄພ ວັງວຽງ', 'Vangvieng Herbal Cocktail', 'bar', 50000, 'ຄັອກເທວສູດພິເສດ ທີ່ໃຊ້ເຫຼົ້າທ້ອງຖິ່ນຜສົມກັບນ້ຳຕະໄຄ້, ໃບໝາກຂາມ ແລະ ນ້ຳໝາກນາວ.', 'A refreshing local spirit cocktail mixed with fresh lemongrass infusion, lime, and local botanicals.', 'assets/images/beer_drink.png', 0),
            ('ລາບໝູຄຣິສປີລາວປະຍຸກ', 'Crispy Lao Fusion Larb', 'food', 55000, 'ລາບໝູສະໝຸນໄພລາວແບບດັ້ງເດີມ ແຕ່ເສີບພ້ອມໝູກອບ ແລະ ຜັກສົດອໍການິກ ຈັດຈານຢ່າງທັນສະໄໝ.', 'Traditional minced pork salad with Lao herbs, served crispy style with organic fresh vegetables, beautifully plated for a modern experience.', 'assets/images/our_story.png', 1),
            ('ຕຳໝາກຫຸ່ງພຣີມ່ຽມ ເສີບພ້ອມໄກ່ປິ້ງ', 'Premium Papaya Salad with Grilled Chicken', 'food', 60000, 'ຕຳໝາກຫຸ່ງລົດຊາດຈັດຈ້ານແບບດັ້ງເດີມ ເສີບຄູ່ກັບໄກ່ປິ້ງສະໝຸນໄພຮ້ອນໆ ແລະ ເຂົ້າໜຽວນຸ້ມ.', 'Spicy traditional papaya salad served with hot grilled herbal chicken and sticky rice.', 'assets/images/our_story.png', 0)");
            $success = 'ນຳເຂົ້າເມນູເລີ່ມຕົ້ນ 8 ລາຍການ ຮຽບຮ້ອຍແລ້ວ!';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການນຳເຂົ້າຂໍ້ມູນ: ' . $e->getMessage();
        }
    }
}

// 2. ຈັດການການລຶບເມນູ (DELETE via POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token()) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $delete_id = intval($_POST['delete_id'] ?? 0);
        try {
            $stmt_img = $pdo->prepare("SELECT image_path FROM menus WHERE id = ?");
            $stmt_img->execute([$delete_id]);
            $img_path = $stmt_img->fetchColumn();
            if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'coffee.png') && !strpos($img_path, 'beer_drink.png') && !strpos($img_path, 'default')) {
                @unlink('../' . $img_path);
            }

            $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = 'ລຶບເມນູຮຽບຮ້ອຍແລ້ວ!';
        } catch (\Exception $e) {
            error_log("Menu delete error: " . $e->getMessage());
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບຂໍ້ມູນ.';
        }
    }
}

// 3. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂເມນູ (CREATE / UPDATE via POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || (!in_array($_POST['action'], ['delete', 'seed'])))) {
    if (!verify_csrf_token()) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $id = intval($_POST['id'] ?? 0);
        $name_lo = trim($_POST['name_lo'] ?? '');
        $name_en = trim($_POST['name_en'] ?? '');
        $category = trim($_POST['category'] ?? 'coffee');
        $price = floatval($_POST['price'] ?? 0);
        $description_lo = trim($_POST['description_lo'] ?? '');
        $description_en = trim($_POST['description_en'] ?? '');
        $is_popular = isset($_POST['is_popular']) ? 1 : 0;
        
        $image_path = $_POST['existing_image'] ?? 'assets/images/coffee.png';

        // ຈັດການການອັບໂຫຼດຮູບພາບ (ກວດສອບຂະໜາດ ແລະ MIME type ແທ້)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $mimeType = function_exists('finfo_open') 
                ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileTmpPath)
                : (function_exists('mime_content_type') ? mime_content_type($fileTmpPath) : '');

            if ($fileSize > 5 * 1024 * 1024) {
                $error = 'ຂະໜາດໄຟລ໌ຮູບພາບໃຫຍ່ເກີນໄປ (ອະນຸຍາດບໍ່ເກີນ 5MB).';
            } elseif (!in_array($fileExtension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                $error = 'ໄຟລ໌ບໍ່ແມ່ນຮູບພາບທີ່ຖືກຕ້ອງ! ອະນຸຍາດສະເພາະ (JPG, PNG, WEBP, GIF).';
            } else {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/images/';
                
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // ລຶບຮູບເກົ່າ (ຖ້າມີ ແລະ ບໍ່ແມ່ນຮູບ default)
                    $old_img = $_POST['existing_image'] ?? '';
                    if ($id > 0 && $old_img && file_exists('../' . $old_img) && !strpos($old_img, 'coffee.png') && !strpos($old_img, 'beer_drink.png') && !strpos($old_img, 'default')) {
                        @unlink('../' . $old_img);
                    }
                    $image_path = 'assets/images/' . $newFileName;
                } else {
                    $error = 'ມີບັນຫາໃນການຍ້າຍໄຟລ໌ທີ່ອັບໂຫຼດ.';
                }
            }
        }

        if (empty($name_lo) || empty($name_en) || $price <= 0) {
            $error = 'ກະລຸນາກອກ ຊື່ເມນູ ແລະ ລາຄາ ໃຫ້ຖືກຕ້ອງ.';
        } elseif (empty($error)) {
            try {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE menus SET name_lo = ?, name_en = ?, category = ?, price = ?, description_lo = ?, description_en = ?, image_path = ?, is_popular = ? WHERE id = ?");
                    $stmt->execute([$name_lo, $name_en, $category, $price, $description_lo, $description_en, $image_path, $is_popular, $id]);
                    $success = 'ແກ້ໄຂເມນູຮຽບຮ້ອຍ!';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO menus (name_lo, name_en, category, price, description_lo, description_en, image_path, is_popular) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name_lo, $name_en, $category, $price, $description_lo, $description_en, $image_path, $is_popular]);
                    $success = 'ເພີ່ມເມນູໃໝ່ຮຽບຮ້ອຍ!';
                }
            } catch (\Exception $e) {
                error_log("Menu save error: " . $e->getMessage());
                $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກຂໍ້ມູນ.';
            }
        }
    }
}

// ດຶງຂໍ້ມູນເມນູທັງໝົດມາສະແດງ
$menus = [];
try {
    $stmt = $pdo->query("SELECT * FROM menus ORDER BY id DESC");
    $menus = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}

// ດຶງຂໍ້ມູນເມນູສະເພາະ ID ທີ່ຕ້ອງການແກ້ໄຂ (ຖ້າມີການກົດແກ້ໄຂ)
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    foreach ($menus as $m) {
        if ($m['id'] === $edit_id) {
            $edit_item = $m;
            break;
        }
    }
}
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການເມນູອາຫານ & ເຄື່ອງດື່ມ</h1>
        <p class="text-sm text-gray-500 mt-1">ເພີ່ມ, ແກ້ໄຂ, ຫຼື ລຶບເມນູອາຫານ ແລະ ເຄື່ອງດື່ມຂອງຮ້ານ</p>
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
        
        <!-- ຟອມ ເພີ່ມ/ແກ້ໄຂ ເມນູ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">
                <?php echo $edit_item ? 'ແກ້ໄຂເມນູ ID: ' . $edit_item['id'] : 'ເພີ່ມເມນູໃໝ່'; ?>
            </h3>
            
            <form action="menu_manage.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? 'assets/images/coffee.png'; ?>">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຊື່ເມນູ (ພາສາລາວ) *</label>
                    <input type="text" name="name_lo" required value="<?php echo htmlspecialchars($edit_item['name_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ລາເຕ້ຮ້ອນ" class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຊື່ເມນູ (English) *</label>
                    <input type="text" name="name_en" required value="<?php echo htmlspecialchars($edit_item['name_en'] ?? ''); ?>" placeholder="e.g. Hot Latte" class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ປະເພດ *</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                            <option value="food" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'food') ? 'selected' : ''; ?>>ອາຫານ (Food)</option>
                            <option value="drinks" <?php echo (isset($edit_item['category']) && ($edit_item['category'] === 'drinks' || $edit_item['category'] === 'coffee')) ? 'selected' : ''; ?>>ເຄື່ອງດື່ມ (Drinks)</option>
                            <option value="bar" <?php echo (isset($edit_item['category']) && ($edit_item['category'] === 'bar' || $edit_item['category'] === 'alcohol')) ? 'selected' : ''; ?>>ເຫຼົ້າ & ເບຍ (Alcohol & Beer)</option>
                            <option value="promo" <?php echo (isset($edit_item['category']) && ($edit_item['category'] === 'promo' || $edit_item['category'] === 'promotion')) ? 'selected' : ''; ?>>Promotion</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ລາຄາ (LAK) *</label>
                        <input type="number" name="price" required value="<?php echo htmlspecialchars($edit_item['price'] ?? ''); ?>" placeholder="ເຊັ່ນ: 35000" class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຄຳອະທິບາຍ (ພາສາລາວ)</label>
                    <textarea name="description_lo" rows="3" placeholder="ຄຳອະທິບາຍລາຍລະອຽດ..." class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none"><?php echo htmlspecialchars($edit_item['description_lo'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຄຳອະທິບາຍ (English)</label>
                    <textarea name="description_en" rows="3" placeholder="Description details..." class="w-full px-3 py-2 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none"><?php echo htmlspecialchars($edit_item['description_en'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບເມນູ</label>
                    <?php if (isset($edit_item['image_path'])): ?>
                        <div class="w-20 h-20 rounded overflow-hidden mb-2 border border-gray-150 shadow-sm">
                            <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-burgundy-700 file:text-white hover:file:bg-burgundy-800 cursor-pointer">
                    <span class="block text-[10px] text-gray-400 mt-1">ແນະນຳຮູບພາບອັດຕາສ່ວນ 1:1 (ຈຳນວນຫຼ່ຽມ)</span>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="is_popular" id="is_popular" value="1" <?php echo (isset($edit_item['is_popular']) && $edit_item['is_popular'] == 1) ? 'checked' : ''; ?> class="rounded text-burgundy-700 focus:ring-burgundy-700">
                    <label for="is_popular" class="text-xs font-bold text-gray-700">ຕັ້ງເປັນ ເມນູຍອດນິຍົມ (Popular Item)</label>
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit" class="flex-grow py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded shadow text-sm transition-all duration-200">
                        <?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມເມນູ'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="menu_manage.php" class="px-4 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-bold text-sm transition-all duration-200">ຍົກເລີກ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງສະແດງລາຍການເມນູທັງໝົດ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">ລາຍຊື່ເມນູອາຫານ & ເຄື່ອງດື່ມທັງໝົດ</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 rounded-lg">
                        <tr>
                            <th class="px-4 py-3">ຮູບພາບ</th>
                            <th class="px-4 py-3">ຊື່ເມນູ (ລາວ / En)</th>
                            <th class="px-4 py-3">ປະເພດ</th>
                            <th class="px-4 py-3">ລາຄາ</th>
                            <th class="px-4 py-3 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($menus) > 0): ?>
                            <?php foreach ($menus as $m): ?>
                                <tr class="hover:bg-gray-50/50 transition-all duration-200 <?php echo (isset($edit_item['id']) && $edit_item['id'] === $m['id']) ? 'bg-burgundy-50/30' : ''; ?>">
                                    <td class="px-4 py-4">
                                        <div class="w-14 h-14 rounded overflow-hidden border border-gray-100 shadow-sm">
                                            <img src="../<?php echo htmlspecialchars($m['image_path']); ?>" class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 max-w-xs">
                                        <div class="space-y-1">
                                            <div class="text-sm font-bold text-gray-950"><?php echo htmlspecialchars($m['name_lo']); ?></div>
                                            <div class="text-xs text-gray-400 font-mono">EN: <?php echo htmlspecialchars($m['name_en']); ?></div>
                                            <?php if ($m['is_popular'] == 1): ?>
                                                <span class="inline-flex items-center px-1.5 py-0.5 bg-yellow-100 text-yellow-800 text-[9px] font-bold rounded-full mt-1">
                                                    ⭐ Popular
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php 
                                            $cat_colors = [
                                                'coffee' => 'bg-amber-100 text-amber-800',
                                                'drinks' => 'bg-blue-100 text-blue-800',
                                                'bar' => 'bg-purple-100 text-purple-800',
                                                'food' => 'bg-emerald-100 text-emerald-800',
                                                'promo' => 'bg-red-100 text-red-800'
                                            ];
                                            $cat_color = $cat_colors[$m['category']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo $cat_color; ?> uppercase">
                                            <?php echo htmlspecialchars($m['category']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 font-extrabold text-burgundy-700 font-mono">
                                        <?php echo number_format($m['price']); ?> LAK
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2.5">
                                            <a href="menu_manage.php?edit=<?php echo $m['id']; ?>" class="inline-flex items-center justify-center p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all" title="ແກ້ໄຂ">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="menu_manage.php" method="POST" class="inline" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບເມນູນີ້?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="delete_id" value="<?php echo $m['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="inline-flex items-center justify-center p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all" title="ລຶບ">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-500 font-serif-lao space-y-3">
                                    <div class="text-base font-bold text-gray-700">ຍັງບໍ່ມີເມນູອາຫານ ຫຼື ເຄື່ອງດື່ມໃນຖານຂໍ້ມູນ (0 ລາຍການ)</div>
                                    <p class="text-xs text-gray-400 max-w-md mx-auto">ທ່ານສາມາດເພີ່ມເມນູໃໝ່ຈາກຟອມດ້ານຊ້າຍ ຫຼື ກົດປຸ່ມດ້ານລຸ່ມນີ້ເພື່ອດຶງເມນູເລີ່ມຕົ້ນ 8 ລາຍການ ເຂົ້າຖານຂໍ້ມູນໄດ້ທັນທີ:</p>
                                    <form action="menu_manage.php" method="POST" class="pt-2">
                                        <input type="hidden" name="action" value="seed">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span>📥 ກົດນຳເຂົ້າເມນູເລີ່ມຕົ້ນ (8 ລາຍການ) ເຂົ້າຖານຂໍ້ມູນ</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</main>
</body>
</html>
