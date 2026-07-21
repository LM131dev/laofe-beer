<?php
// admin/menu_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການການລຶບເມນູ (DELETE)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        // ດຶງຮູບພາບມາລຶບອອກຈາກເຊີເວີກ່ອນ (ຖ້າມີ ແລະ ບໍ່ແມ່ນຮູບພື້ນຖານ)
        $stmt_img = $pdo->prepare("SELECT image_path FROM menus WHERE id = ?");
        $stmt_img->execute([$delete_id]);
        $img_path = $stmt_img->fetchColumn();
        if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'default')) {
            // @unlink('../' . $img_path);
        }

        $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = 'ລຶບເມນູຮຽບຮ້ອຍແລ້ວ!';
    } catch (\Exception $e) {
        $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
    }
}

// 2. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂເມນູ (CREATE / UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name_lo = trim($_POST['name_lo'] ?? '');
    $name_en = trim($_POST['name_en'] ?? '');
    $category = trim($_POST['category'] ?? 'coffee');
    $price = floatval($_POST['price'] ?? 0);
    $description_lo = trim($_POST['description_lo'] ?? '');
    $description_en = trim($_POST['description_en'] ?? '');
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    
    $image_path = $_POST['existing_image'] ?? 'assets/images/coffee.png';

    // ຈັດການການອັບໂຫຼດຮູບພາບ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // ນາມສະກຸນໄຟລ໌ທີ່ອະນຸຍາດ
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../assets/images/';
            
            // ສ້າງໂຟນເດີຖ້າຍັງບໍ່ມີ
            if (!file_exists($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_path = 'assets/images/' . $newFileName;
            } else {
                $error = 'ມີບັນຫາໃນການຍ້າຍໄຟລ໌ທີ່ອັບໂຫຼດ.';
            }
        } else {
            $error = 'ອັບໂຫຼດບໍ່ສຳເລັດ. ນາມສະກຸນໄຟລ໌ທີ່ອະນຸຍາດ: ' . implode(',', $allowedExtensions);
        }
    }

    if (empty($name_lo) || empty($name_en) || $price <= 0) {
        $error = 'ກະລຸນາກອກ ຊື່ເມນູ ແລະ ລາຄາ ໃຫ້ຖືກຕ້ອງ.';
    } elseif (empty($error)) {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE menus SET name_lo = ?, name_en = ?, category = ?, price = ?, description_lo = ?, description_en = ?, image_path = ?, is_popular = ? WHERE id = ?");
                $stmt->execute([$name_lo, $name_en, $category, $price, $description_lo, $description_en, $image_path, $is_popular, $id]);
                $success = 'ແກ້ໄຂເມນູຮຽບຮ້ອຍ!';
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO menus (name_lo, name_en, category, price, description_lo, description_en, image_path, is_popular) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name_lo, $name_en, $category, $price, $description_lo, $description_en, $image_path, $is_popular]);
                $success = 'ເພີ່ມເມນູໃໝ່ຮຽບຮ້ອຍ!';
            }
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
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
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? 'assets/images/coffee.png'; ?>">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຊື່ເມນູ (ພາສາລາວ) *</label>
                    <input type="text" name="name_lo" required value="<?php echo htmlspecialchars($edit_item['name_lo'] ?? ''); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຊື່ເມນູ (English) *</label>
                    <input type="text" name="name_en" required value="<?php echo htmlspecialchars($edit_item['name_en'] ?? ''); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ປະເພດ *</label>
                        <select name="category" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                            <option value="coffee" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'coffee') ? 'selected' : ''; ?>>Coffee</option>
                            <option value="drinks" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'drinks') ? 'selected' : ''; ?>>Drinks</option>
                            <option value="bar" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'bar') ? 'selected' : ''; ?>>Bar/Cocktail</option>
                            <option value="food" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'food') ? 'selected' : ''; ?>>Lao Food</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ລາຄາ (LAK) *</label>
                        <input type="number" name="price" required value="<?php echo htmlspecialchars($edit_item['price'] ?? ''); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຄຳອະທິບາຍ (ພາສາລາວ)</label>
                    <textarea name="description_lo" rows="3" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700"><?php echo htmlspecialchars($edit_item['description_lo'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຄຳອະທິບາຍ (English)</label>
                    <textarea name="description_en" rows="3" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700"><?php echo htmlspecialchars($edit_item['description_en'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບເມນູ</label>
                    <?php if (isset($edit_item['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" class="w-20 h-20 object-cover rounded mb-2 border">
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-burgundy-700 file:text-white hover:file:bg-burgundy-800">
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="is_popular" id="is_popular" value="1" <?php echo (isset($edit_item['is_popular']) && $edit_item['is_popular'] == 1) ? 'checked' : ''; ?> class="rounded text-burgundy-700 focus:ring-burgundy-700">
                    <label for="is_popular" class="text-xs font-bold text-gray-700">ຕັ້ງເປັນ ເມນູຍອດນິຍົມ (Popular Item)</label>
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit" class="flex-grow py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded shadow transition-all duration-200">
                        <?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມເມນູ'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="menu_manage.php" class="px-4 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-bold transition-all duration-200">ยกເລີກ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງສະແດງລາຍການເມນູທັງໝົດ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">ລາຍຊື່ເມນູອາຫານ & ເຄື່ອງດື່ມທັງໝົດ</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ຮູບພາບ</th>
                            <th class="px-4 py-3">ຊື່ເມນູ (ລາວ / En)</th>
                            <th class="px-4 py-3">ປະເພດ</th>
                            <th class="px-4 py-3">ລາຄາ (ກີບ)</th>
                            <th class="px-4 py-3 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($menus) > 0): ?>
                            <?php foreach ($menus as $m): ?>
                                <tr class="bg-white border-b hover:bg-gray-50 <?php echo (isset($edit_item['id']) && $edit_item['id'] === $m['id']) ? 'bg-burgundy-50/50' : ''; ?>">
                                    <td class="px-4 py-3">
                                        <img src="../<?php echo htmlspecialchars($m['image_path']); ?>" alt="Menu image" class="w-12 h-12 object-cover rounded-md border border-gray-100">
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        <div><?php echo htmlspecialchars($m['name_lo']); ?></div>
                                        <div class="text-xs text-gray-400 font-light"><?php echo htmlspecialchars($m['name_en']); ?></div>
                                        <?php if ($m['is_popular'] == 1): ?>
                                            <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-800 text-[9px] rounded-full mt-1">Popular</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-xs uppercase"><?php echo htmlspecialchars($m['category']); ?></td>
                                    <td class="px-4 py-3 font-bold text-burgundy-700"><?php echo number_format($m['price']); ?></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="menu_manage.php?edit=<?php echo $m['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">ແກ້ໄຂ</a>
                                        <a href="menu_manage.php?delete=<?php echo $m['id']; ?>" onclick="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບເມນູນີ້?')" class="text-red-600 hover:text-red-800 font-semibold text-xs">ລຶບ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-400 font-light">ຍັງບໍ່ມີເມນູອາຫານ ຫຼື ເຄື່ອງດື່ມໃນຖານຂໍ້ມູນ</td>
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
