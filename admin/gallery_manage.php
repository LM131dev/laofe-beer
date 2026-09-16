<?php
// admin/gallery_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການການລຶບ (DELETE - POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $delete_id = intval($_POST['delete_id'] ?? 0);
        try {
            // ດຶງຮູບພາບມາລຶບອອກຈາກເຊີເວີກ່ອນ (ຖ້າມີ)
            $stmt_img = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt_img->execute([$delete_id]);
            $img_path = $stmt_img->fetchColumn();
            if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'hero_banner') && !strpos($img_path, 'our_story') && !strpos($img_path, 'coffee') && !strpos($img_path, 'beer_drink')) {
                @unlink('../' . $img_path);
            }

            $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = 'ລຶບຮູບພາບ Gallery ຮຽບຮ້ອຍແລ້ວ!';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
        }
    }
}

// 2. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂ (CREATE / UPDATE - POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || $_POST['action'] !== 'delete')) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $id = intval($_POST['id'] ?? 0);
        $title_lo = trim($_POST['title_lo'] ?? '');
        $title_en = trim($_POST['title_en'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $image_path = $_POST['existing_image'] ?? '';

        // ຈັດການການອັບໂຫຼດຮູບພາບ (ກວດຂະໜາດ ແລະ MIME type ທີ່ແທ້ຈິງ)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            if ($fileSize > 5 * 1024 * 1024) {
                $error = 'ຂະໜາດໄຟລ໌ຮູບພາບໃຫຍ່ເກີນໄປ (ຕ້ອງບໍ່ເກີນ 5MB).';
            } elseif (!in_array($fileExtension, $allowedExtensions)) {
                $error = 'ນາມສະກຸນໄຟລ໌ບໍ່ຖືກຕ້ອງ. ອະນຸຍາດສະເພາະ: ' . implode(',', $allowedExtensions);
            } else {
                $realMime = '';
                if (function_exists('finfo_open')) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $realMime = finfo_file($finfo, $fileTmpPath);
                    finfo_close($finfo);
                } elseif (function_exists('mime_content_type')) {
                    $realMime = mime_content_type($fileTmpPath);
                } else {
                    $imgInfo = getimagesize($fileTmpPath);
                    $realMime = $imgInfo['mime'] ?? '';
                }

                if (!in_array($realMime, $allowedMimes)) {
                    $error = 'ໄຟລ໌ທີ່ອັບໂຫຼດບໍ່ແມ່ນຮູບພາບທີ່ຖືກຕ້ອງ (MIME type invalid).';
                } else {
                    $newFileName = 'gal_' . md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = '../assets/images/';
                    
                    if (!file_exists($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // ລຶບຮູບເກົ່າ (ຖ້າມີ ແລະ ບໍ່ແມ່ນຮູບ default)
                        if ($id > 0 && $image_path && file_exists('../' . $image_path) && !strpos($image_path, 'hero_banner') && !strpos($image_path, 'our_story') && !strpos($image_path, 'coffee') && !strpos($image_path, 'beer_drink')) {
                            @unlink('../' . $image_path);
                        }
                        $image_path = 'assets/images/' . $newFileName;
                    } else {
                        $error = 'ມີບັນຫາໃນການຍ້າຍໄຟລ໌ທີ່ອັບໂຫຼດ.';
                    }
                }
            }
        }

        if (empty($image_path)) {
            $error = 'ກະລຸນາເລືອກ ຫຼື ອັບໂຫຼດຮູບພາບ Gallery.';
        } elseif (empty($error)) {
            try {
                if ($id > 0) {
                    // UPDATE
                    $stmt = $pdo->prepare("UPDATE gallery SET title_lo = ?, title_en = ?, sort_order = ?, is_active = ?, image_path = ? WHERE id = ?");
                    $stmt->execute([$title_lo, $title_en, $sort_order, $is_active, $image_path, $id]);
                    $success = 'ແກ້ໄຂຮູບພາບ Gallery ຮຽບຮ້ອຍ!';
                } else {
                    // INSERT
                    $stmt = $pdo->prepare("INSERT INTO gallery (title_lo, title_en, sort_order, is_active, image_path) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$title_lo, $title_en, $sort_order, $is_active, $image_path]);
                    $success = 'ເພີ່ມຮູບພາບ Gallery ໃໝ່ຮຽບຮ້ອຍ!';
                }
            } catch (\Exception $e) {
                $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
            }
        }
    }
}

// ດຶງຂໍ້ມູນທັງໝົດ
$gallery_items = [];
try {
    $stmt = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, id DESC");
    $gallery_items = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}

// ດຶງຂໍ້ມູນສະເພາະ ID ທີ່ຕ້ອງການແກ້ໄຂ
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    foreach ($gallery_items as $item) {
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
            <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການ Gallery ບັນຍາກາດ ແລະ ແນວຄິດ</h1>
            <p class="text-sm text-gray-500 mt-1">ເພີ່ມ, ແກ້ໄຂ, ຫຼື ລຶບຮູບພາບສະແດງບັນຍາກາດຮ້ານ (Atmosphere & Concept Gallery)</p>
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
                <?php echo $edit_item ? 'ແກ້ໄຂ Gallery ID: ' . $edit_item['id'] : 'ເພີ່ມຮູບ Gallery ໃໝ່'; ?>
            </h3>
            
            <form action="gallery_manage.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? ''; ?>">

                <!-- Title Lao -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ຮູບ / ຄຳອະທິບາຍ (ລາວ)</label>
                    <input type="text" name="title_lo" value="<?php echo htmlspecialchars($edit_item['title_lo'] ?? ''); ?>" placeholder="ເຊັ່ນ: ບັນຍາກາດຮ້ານ LaoFe" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>

                <!-- Title English -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຫົວຂໍ້ຮູບ / ຄຳອະທິບາຍ (English)</label>
                    <input type="text" name="title_en" value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>" placeholder="e.g. LaoFe Atmosphere" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບ Gallery *</label>
                    <?php if (!empty($edit_item['image_path'])): ?>
                        <div class="mb-2">
                            <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" alt="Current Image" class="w-full h-32 object-cover rounded-lg border">
                            <span class="text-[10px] text-gray-500">ຮູບພາບປັດຈຸບັນ: <?php echo htmlspecialchars($edit_item['image_path']); ?></span>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-1.5 border rounded text-xs text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-burgundy-50 file:text-burgundy-700 hover:file:bg-burgundy-100">
                </div>

                <!-- Sort Order & Status -->
                <div class="grid grid-cols-2 gap-4 pt-2 border-t">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ລຳດັບ (Sort Order)</label>
                        <input type="number" name="sort_order" value="<?php echo $edit_item['sort_order'] ?? 0; ?>" class="w-full px-3 py-2 border rounded text-sm focus:ring-1 focus:ring-burgundy-700 focus:outline-none">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" <?php echo (!isset($edit_item) || !empty($edit_item['is_active'])) ? 'checked' : ''; ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-burgundy-700"></div>
                            <span class="ml-2 text-xs font-bold text-gray-700">ເປີດໃຊ້ງານ</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex space-x-2">
                    <button type="submit" class="flex-1 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-all shadow-md">
                        <?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ເພີ່ມຮູບ Gallery'; ?>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="gallery_manage.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-sm transition-all">
                            ຍົກເລີກ
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງລາຍການ Gallery -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4 overflow-hidden">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ລາຍການຮູບພາບ Gallery ບັນຍາກາດ</h3>
                <span class="text-xs bg-burgundy-50 text-burgundy-700 font-bold px-3 py-1 rounded-full">
                    ທັງໝົດ: <?php echo count($gallery_items); ?> ຮູບ
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                            <th class="py-3 px-4">ຮູບພາບ</th>
                            <th class="py-3 px-4">ຫົວຂໍ້ (ລາວ / EN)</th>
                            <th class="py-3 px-4 text-center">ລຳດັບ</th>
                            <th class="py-3 px-4 text-center">ສະຖານະ</th>
                            <th class="py-3 px-4 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php if (empty($gallery_items)): ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 text-sm">ບໍ່ມີຂໍ້ມູນ Gallery</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($gallery_items as $item): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4">
                                        <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="Gallery Image" class="w-20 h-14 object-cover rounded-lg border shadow-sm">
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-gray-900"><?php echo htmlspecialchars($item['title_lo'] ?: 'ບໍ່ມີຫົວຂໍ້'); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($item['title_en'] ?: '-'); ?></div>
                                    </td>
                                    <td class="py-3 px-4 text-center font-mono font-semibold text-gray-700">
                                        <?php echo $item['sort_order']; ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <?php if ($item['is_active']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                ເປີດ
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                                ປິດ
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                     <td class="py-3 px-4 text-right space-x-2 flex items-center justify-end">
                                         <a href="gallery_manage.php?edit=<?php echo $item['id']; ?>" class="inline-flex items-center px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition-all border border-amber-200">
                                             ແກ້ໄຂ
                                         </a>
                                         <form action="gallery_manage.php" method="POST" class="inline" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຮູບພາບນີ້?');">
                                             <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                             <input type="hidden" name="action" value="delete">
                                             <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                             <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-bold transition-all border border-red-200">
                                                 ລຶບ
                                             </button>
                                         </form>
                                     </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php
// Footer template
?>
    </main>
</div>
</body>
</html>
