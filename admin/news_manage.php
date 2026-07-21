<?php
// admin/news_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການການລຶບ (DELETE)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        // ດຶງຮູບພາບມາລຶບອອກຈາກເຊີເວີກ່ອນ (ຖ້າມີ)
        $stmt_img = $pdo->prepare("SELECT image_path FROM news WHERE id = ?");
        $stmt_img->execute([$delete_id]);
        $img_path = $stmt_img->fetchColumn();
        if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'default')) {
            // @unlink('../' . $img_path);
        }

        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = 'ລຶບບົດຄວາມຮຽບຮ້ອຍແລ້ວ!';
    } catch (\Exception $e) {
        $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
    }
}

// 2. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂ (CREATE / UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title_lo = trim($_POST['title_lo'] ?? '');
    $title_en = trim($_POST['title_en'] ?? '');
    $content_lo = trim($_POST['content_lo'] ?? '');
    $content_en = trim($_POST['content_en'] ?? '');
    $is_promo = intval($_POST['is_promo'] ?? 0);
    $publish_date = trim($_POST['publish_date'] ?? date('Y-m-d'));
    
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
                $image_path = 'assets/images/' . $newFileName;
            } else {
                $error = 'ມີບັນຫາໃນການຍ້າຍໄຟລ໌ທີ່ອັບໂຫຼດ.';
            }
        } else {
            $error = 'ອັບໂຫຼດບໍ່ສຳເລັດ. ນາມສະກຸນໄຟລ໌ທີ່ອະນຸຍາດ: ' . implode(',', $allowedExtensions);
        }
    }

    if (empty($title_lo) || empty($title_en) || empty($content_lo) || empty($content_en)) {
        $error = 'ກະລຸນາກອກ ຫົວຂໍ້ ແລະ ເນື້ອຫາ ທັງພາສາລາວ ແລະ ອັງກິດ.';
    } elseif (empty($error)) {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE news SET title_lo = ?, title_en = ?, content_lo = ?, content_en = ?, is_promo = ?, publish_date = ?, image_path = ? WHERE id = ?");
                $stmt->execute([$title_lo, $title_en, $content_lo, $content_en, $is_promo, $publish_date, $image_path, $id]);
                $success = 'ແກ້ໄຂບົດຄວາມຮຽບຮ້ອຍ!';
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO news (title_lo, title_en, content_lo, content_en, is_promo, publish_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title_lo, $title_en, $content_lo, $content_en, $is_promo, $publish_date, $image_path]);
                $success = 'ເພີ່ມບົດຄວາມໃໝ່ຮຽບຮ້ອຍ!';
            }
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
        }
    }
}

// ດຶງຂໍ້ມູນທັງໝົດ
$news_items = [];
try {
    $stmt = $pdo->query("SELECT * FROM news ORDER BY id DESC");
    $news_items = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}

// ດຶງຂໍ້ມູນສະເພາະ ID ທີ່ຕ້ອງການແກ້ໄຂ
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    foreach ($news_items as $item) {
        if ($item['id'] === $edit_id) {
            $edit_item = $item;
            break;
        }
    }
}
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການຂ່າວສານ & ໂປຣໂມຊັນ</h1>
        <p class="text-sm text-gray-500 mt-1">ເພີ່ມ, ແກ້ໄຂ, ຫຼື ລຶບບົດຄວາມຂ່າວສານ ແລະ ໂປຣໂມຊັນຂອງຮ້ານ</p>
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
        
        <!-- ຟອມ ເພີ່ມ/ແກ້ໄຂ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">
                <?php echo $edit_item ? 'ແກ້ໄຂບົດຄວາມ ID: ' . $edit_item['id'] : 'ເພີ່ມບົດຄວາມໃໝ່'; ?>
            </h3>
            
            <form action="news_manage.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? 'assets/images/hero_banner.png'; ?>">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ (ພາສາລາວ) *</label>
                    <input type="text" name="title_lo" required value="<?php echo htmlspecialchars($edit_item['title_lo'] ?? ''); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ (English) *</label>
                    <input type="text" name="title_en" required value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ປະເພດ *</label>
                        <select name="is_promo" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                            <option value="0" <?php echo (isset($edit_item['is_promo']) && $edit_item['is_promo'] == 0) ? 'selected' : ''; ?>>News (ຂ່າວສານ)</option>
                            <option value="1" <?php echo (isset($edit_item['is_promo']) && $edit_item['is_promo'] == 1) ? 'selected' : ''; ?>>Promotion (ໂປຣໂມຊັນ)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ວັນທີເຜີຍແຜ່ *</label>
                        <input type="date" name="publish_date" required value="<?php echo htmlspecialchars($edit_item['publish_date'] ?? date('Y-m-d')); ?>" class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ເນື້ອຫາ (ພາສາລາວ) *</label>
                    <textarea name="content_lo" rows="4" required class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700"><?php echo htmlspecialchars($edit_item['content_lo'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ເນື້ອຫາ (English) *</label>
                    <textarea name="content_en" rows="4" required class="w-full px-3 py-2 border rounded focus:ring-1 focus:ring-burgundy-700"><?php echo htmlspecialchars($edit_item['content_en'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບປະກອບ</label>
                    <?php if (isset($edit_item['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" class="w-full h-32 object-cover rounded mb-2 border">
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-burgundy-700 file:text-white hover:file:bg-burgundy-800">
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit" class="flex-grow py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded shadow transition-all duration-200">
                        <?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມບົດຄວາມ'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="news_manage.php" class="px-4 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-bold transition-all duration-200">ยกເລີກ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງສະແດງລາຍການ -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-950 border-b pb-3 font-serif-lao">ລາຍຊື່ຂ່າວສານ & ໂປຣໂມຊັນທັງໝົດ</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ຮູບພາບ</th>
                            <th class="px-4 py-3">ຫົວຂໍ້ບົດຄວາມ</th>
                            <th class="px-4 py-3">ປະເພດ</th>
                            <th class="px-4 py-3">ວັນທີເຜີຍແຜ່</th>
                            <th class="px-4 py-3 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($news_items) > 0): ?>
                            <?php foreach ($news_items as $item): ?>
                                <tr class="bg-white border-b hover:bg-gray-50 <?php echo (isset($edit_item['id']) && $edit_item['id'] === $item['id']) ? 'bg-burgundy-50/50' : ''; ?>">
                                    <td class="px-4 py-3">
                                        <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="News image" class="w-16 h-12 object-cover rounded-md border border-gray-100">
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        <div class="line-clamp-1"><?php echo htmlspecialchars($item['title_lo']); ?></div>
                                        <div class="text-xs text-gray-400 font-light line-clamp-1"><?php echo htmlspecialchars($item['title_en']); ?></div>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="px-2 py-0.5 rounded-full font-bold uppercase tracking-wider text-[9px] <?php echo $item['is_promo'] == 1 ? 'bg-burgundy-50 text-burgundy-700' : 'bg-gray-100 text-gray-800'; ?>">
                                            <?php echo $item['is_promo'] == 1 ? 'Promotion' : 'News'; ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold"><?php echo htmlspecialchars($item['publish_date']); ?></td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <a href="news_manage.php?edit=<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">ແກ້ໄຂ</a>
                                        <a href="news_manage.php?delete=<?php echo $item['id']; ?>" onclick="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບບົດຄວາມນີ້?')" class="text-red-600 hover:text-red-800 font-semibold text-xs">ລຶບ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-400 font-light">ຍັງບໍ່ມີຂ່າວສານ ຫຼື ໂປຣໂມຊັນໃນຖານຂໍ້ມູນ</td>
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
