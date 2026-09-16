<?php
// admin/news_manage.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 1. ຈັດການການຕັ້ງ Featured Hero Banner (POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'set_featured') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $feat_id = intval($_POST['feat_id'] ?? 0);
        try {
            $pdo->exec("UPDATE news SET is_featured = 0");
            $stmt = $pdo->prepare("UPDATE news SET is_featured = 1 WHERE id = ?");
            $stmt->execute([$feat_id]);
            $success = 'ຕັ້ງລາຍການນີ້ເປັນ Hero Banner (ປ້າຍໄຮໄລ້ເທິງສຸດໜ້າຂ່າວສານ) ຮຽບຮ້ອຍແລ້ວ! 🔥';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການຕັ້ງ Hero Banner: ' . $e->getMessage();
        }
    }
}

// 2. ຈັດການການລຶບ (DELETE - POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $delete_id = intval($_POST['delete_id'] ?? 0);
        try {
            $stmt_img = $pdo->prepare("SELECT image_path FROM news WHERE id = ?");
            $stmt_img->execute([$delete_id]);
            $img_path = $stmt_img->fetchColumn();
            if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'hero_banner') && !strpos($img_path, 'default')) {
                @unlink('../' . $img_path);
            }

            $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = 'ລຶບລາຍການຮຽບຮ້ອຍແລ້ວ!';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
        }
    }
}

// 3. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂ (CREATE / UPDATE - POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || !in_array($_POST['action'], ['delete', 'set_featured']))) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $id = intval($_POST['id'] ?? 0);
        $title_lo = trim($_POST['title_lo'] ?? '');
        $title_en = trim($_POST['title_en'] ?? '');
        $content_lo = trim($_POST['content_lo'] ?? '');
        $content_en = trim($_POST['content_en'] ?? '');
        $is_promo = intval($_POST['is_promo'] ?? 0);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $category = trim($_POST['category'] ?? 'article_news');
        $event_location = trim($_POST['event_location'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $status = trim($_POST['status'] ?? 'published');
        $publish_date = trim($_POST['publish_date'] ?? date('Y-m-d'));
        
        $image_path = $_POST['existing_image'] ?? 'assets/images/hero_banner.png';

        // ຈັດການການອັບໂຫຼດຮູບພາບ (ກວດຂະໜາດ ແລະ MIME type ທີ່ແທ້ຈິງ)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

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
                }
            }
        }

        if (empty($title_lo) || empty($title_en) || empty($content_lo) || empty($content_en)) {
            $error = 'ກະລຸນາກອກ ຫົວຂໍ້ ແລະ ເນື້ອຫາ ທັງພາສາລາວ ແລະ ອັງກິດ.';
        } elseif (empty($error)) {
            try {
                // ຖ້າເລືອກ set is_featured = 1 ໃຫ້ unset featured ອື່ນໆກ່ອນ
                if ($is_featured == 1) {
                    $pdo->exec("UPDATE news SET is_featured = 0");
                }

                if ($id > 0) {
                    // UPDATE
                    $stmt = $pdo->prepare("UPDATE news SET title_lo = ?, title_en = ?, content_lo = ?, content_en = ?, is_promo = ?, is_featured = ?, category = ?, event_location = ?, event_date = ?, status = ?, publish_date = ?, image_path = ? WHERE id = ?");
                    $stmt->execute([$title_lo, $title_en, $content_lo, $content_en, $is_promo, $is_featured, $category, $event_location, $event_date, $status, $publish_date, $image_path, $id]);
                    $success = 'ແກ້ໄຂຂໍ້ມູນບົດຄວາມ/ກິດຈະກຳ ຮຽບຮ້ອຍ!';
                } else {
                    // INSERT
                    $stmt = $pdo->prepare("INSERT INTO news (title_lo, title_en, content_lo, content_en, is_promo, is_featured, category, event_location, event_date, status, publish_date, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$title_lo, $title_en, $content_lo, $content_en, $is_promo, $is_featured, $category, $event_location, $event_date, $status, $publish_date, $image_path]);
                    $success = 'ເພີ່ມບົດຄວາມ/ກິດຈະກຳ ໃໝ່ຮຽບຮ້ອຍ!';
                }
            } catch (\Exception $e) {
                $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
            }
        }
    }
}

// Filter variables
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';

// ດຶງຂໍ້ມູນທັງໝົດ
$news_items = [];
try {
    if ($filter_type === 'article') {
        $stmt = $pdo->query("SELECT * FROM news WHERE is_promo = 0 ORDER BY is_featured DESC, id DESC");
    } elseif ($filter_type === 'event') {
        $stmt = $pdo->query("SELECT * FROM news WHERE is_promo = 2 ORDER BY is_featured DESC, id DESC");
    } elseif ($filter_type === 'promo') {
        $stmt = $pdo->query("SELECT * FROM news WHERE is_promo = 1 ORDER BY is_featured DESC, id DESC");
    } else {
        $stmt = $pdo->query("SELECT * FROM news ORDER BY is_featured DESC, id DESC");
    }
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 font-serif-lao tracking-tight flex items-center gap-2.5">
                <svg class="w-7 h-7 text-burgundy-700 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h6"/></svg>
                <span>ຈັດການບົດຄວາມ, ຂ່າວສານ & ກິດຈະກຳ</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1 font-serif-lao">ເພີ່ມ, ແກ້ໄຂ, ຈັດການບົດຄວາມຄວາມຮູ້, ຂ່າວສານຮ້ານ, ເວີກຊອບ, ກິດຈະກຳ ແລະ ໂປຣໂມຊັນຫຼັງບ້ານ</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="../news.php" target="_blank" class="px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 font-bold text-xs font-serif-lao transition-all flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>ເບິ່ງໜ້າບ້ານ (Live Page)</span>
            </a>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 bg-gray-100 p-1.5 rounded-xl border border-gray-200 text-xs font-bold font-serif-lao overflow-x-auto">
                <a href="news_manage.php?type=all" class="px-3.5 py-2 rounded-lg transition-all <?php echo $filter_type === 'all' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'; ?>">
                    ທັງໝົດ
                </a>
                <a href="news_manage.php?type=article" class="px-3.5 py-2 rounded-lg transition-all <?php echo $filter_type === 'article' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'; ?>">
                    ບົດຄວາມ
                </a>
                <a href="news_manage.php?type=event" class="px-3.5 py-2 rounded-lg transition-all <?php echo $filter_type === 'event' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'; ?>">
                    ກິດຈະກຳ
                </a>
                <a href="news_manage.php?type=promo" class="px-3.5 py-2 rounded-lg transition-all <?php echo $filter_type === 'promo' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'; ?>">
                    ໂປຣໂມຊັນ
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm font-semibold shadow-sm flex items-center justify-between font-serif-lao">
            <span><?php echo htmlspecialchars($success); ?></span>
            <button onclick="this.parentElement.remove()" class="text-green-900 hover:opacity-75">✕</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm font-semibold shadow-sm flex items-center justify-between font-serif-lao">
            <span><?php echo htmlspecialchars($error); ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-900 hover:opacity-75">✕</button>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- ຟອມ ເພີ່ມ/ແກ້ໄຂ -->
        <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 h-fit space-y-6">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-lg font-extrabold text-gray-950 font-serif-lao">
                    <?php echo $edit_item ? 'ແກ້ໄຂລາຍການ ID: #' . $edit_item['id'] : 'ເພີ່ມບົດຄວາມ / ກິດຈະກຳໃໝ່'; ?>
                </h3>
                <?php if ($edit_item): ?>
                    <a href="news_manage.php" class="text-xs text-burgundy-700 hover:underline font-bold font-serif-lao">+ ເພີ່ມໃໝ່</a>
                <?php endif; ?>
            </div>
            
            <form action="news_manage.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <input type="hidden" name="id" value="<?php echo $edit_item['id'] ?? 0; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? 'assets/images/hero_banner.png'; ?>">

                <!-- Checkbox Hero Banner Highlight -->
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <label class="flex items-center space-x-2.5 text-xs font-bold text-amber-950 font-serif-lao cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" <?php echo (!empty($edit_item['is_featured']) && $edit_item['is_featured'] == 1) ? 'checked' : ''; ?> class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                        <span>ຕັ້ງເປັນ Hero Banner (ສະແດງຢູ່ເທິງສຸດໜ້າຂ່າວສານ)</span>
                    </label>
                </div>

                <!-- ປະເພດລາຍການ -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ປະເພດລາຍການ *</label>
                        <select name="is_promo" id="is_promo_select" onchange="toggleEventFields()" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 font-serif-lao text-xs font-bold">
                            <option value="0" <?php echo (isset($edit_item['is_promo']) && $edit_item['is_promo'] == 0) ? 'selected' : ''; ?>>ບົດຄວາມ / ຂ່າວສານ</option>
                            <option value="2" <?php echo (isset($edit_item['is_promo']) && $edit_item['is_promo'] == 2) ? 'selected' : ''; ?>>ກິດຈະກຳ / ເວີກຊອບ</option>
                            <option value="1" <?php echo (isset($edit_item['is_promo']) && $edit_item['is_promo'] == 1) ? 'selected' : ''; ?>>ໂໂປຣໂມຊັນພິເສດ</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ໝວດໝູ່ຍ່ອຍ *</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 font-serif-lao text-xs">
                            <option value="article_coffee" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'article_coffee') ? 'selected' : ''; ?>>ຄວາມຮູ້ກາເຟ (Coffee)</option>
                            <option value="article_culture" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'article_culture') ? 'selected' : ''; ?>>ວັດທະນະທຳກາເຟລາວ</option>
                            <option value="article_brand" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'article_brand') ? 'selected' : ''; ?>>ເລື່ອງລາວ LaoFe</option>
                            <option value="event_workshop" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'event_workshop') ? 'selected' : ''; ?>>ເວີກຊອບ & ແຂ່ງຂັນ</option>
                            <option value="event_music" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'event_music') ? 'selected' : ''; ?>>ດົນຕີສົດ & Night Lounge</option>
                            <option value="promo_discount" <?php echo (isset($edit_item['category']) && $edit_item['category'] === 'promo_discount') ? 'selected' : ''; ?>>ໂປຣໂມຊັນສ່ວນຫຼຸດ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ຫົວຂໍ້ (ພາສາລາວ) *</label>
                    <input type="text" name="title_lo" required value="<?php echo htmlspecialchars($edit_item['title_lo'] ?? ''); ?>" placeholder="ຕົວຢ່າງ: Happy Hour Craft Beer Buy 1 Get 1 Free!" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 font-serif-lao text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ຫົວຂໍ້ (English) *</label>
                    <input type="text" name="title_en" required value="<?php echo htmlspecialchars($edit_item['title_en'] ?? ''); ?>" placeholder="Example: Happy Hour Craft Beer Buy 1 Get 1 Free!" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 text-sm">
                </div>
                <div id="event_fields_container" class="space-y-3 p-3.5 rounded-xl bg-amber-50/60 border border-amber-200">
                    <div>
                        <label class="block text-xs font-bold text-amber-900 uppercase mb-1 font-serif-lao">ສະຖານທີ່ຈັດງານ (Location)</label>
                        <input type="text" name="event_location" value="<?php echo htmlspecialchars($edit_item['event_location'] ?? ''); ?>" placeholder="ຕົວຢ່າງ: LaoFe & Beer ສາຂາ ນ້ຳພຸ (Vientiane)" class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 font-serif-lao text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-900 uppercase mb-1 font-serif-lao">ວັນທີ & ເວລາຈັດງານ (Event Date & Time)</label>
                        <input type="text" name="event_date" value="<?php echo htmlspecialchars($edit_item['event_date'] ?? ''); ?>" placeholder="ຕົວຢ່າງ: 15 ພະຈິກ 2026 | 09:00 - 17:00" class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 font-serif-lao text-xs bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ສະຖານະ *</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 font-serif-lao text-xs">
                            <option value="published" <?php echo (isset($edit_item['status']) && $edit_item['status'] === 'published') ? 'selected' : ''; ?>>ເຜີຍແຜ່ (Published)</option>
                            <option value="draft" <?php echo (isset($edit_item['status']) && $edit_item['status'] === 'draft') ? 'selected' : ''; ?>>ຮ່າງໄວ້ (Draft)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ວັນທີເຜີຍແຜ່ *</label>
                        <input type="date" name="publish_date" required value="<?php echo htmlspecialchars($edit_item['publish_date'] ?? date('Y-m-d')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ເນື້ອຫາ / ລາຍລະອຽດ (ພາສາລາວ) *</label>
                    <textarea name="content_lo" rows="4" required placeholder="ກອກເນື້ອຫາບົດຄວາມ ຫຼື ລາຍລະອຽດກິດຈະກຳ..." class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 font-serif-lao text-xs leading-relaxed"><?php echo htmlspecialchars($edit_item['content_lo'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ເນື້ອຫາ / ລາຍລະອຽດ (English) *</label>
                    <textarea name="content_en" rows="4" required placeholder="Enter article content or event details in English..." class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-burgundy-700 text-xs leading-relaxed"><?php echo htmlspecialchars($edit_item['content_en'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 font-serif-lao">ຮູບພາບປະກອບ (Header Image)</label>
                    <?php if (isset($edit_item['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($edit_item['image_path']); ?>" class="w-full h-36 object-cover rounded-xl mb-2 border border-gray-200 shadow-sm">
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-burgundy-700 file:text-white hover:file:bg-burgundy-800 transition-all cursor-pointer">
                </div>

                <div class="pt-4 flex gap-2">
                    <button type="submit" class="flex-grow py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-extrabold rounded-xl shadow-md transition-all duration-200 font-serif-lao flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span><?php echo $edit_item ? 'ບັນທຶກການແກ້ໄຂ' : 'ບັນທຶກ ແລະ ເຜີຍແຜ່'; ?></span>
                    </button>
                    <?php if ($edit_item): ?>
                        <a href="news_manage.php" class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-bold transition-all duration-200 font-serif-lao">ຍົກເລີກ</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ຕາຕະລາງສະແດງລາຍການ -->
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-lg font-extrabold text-gray-950 font-serif-lao">
                    ລາຍຊື່ບົດຄວາມ, ຂ່າວສານ & ກິດຈະກຳທັງໝົດ (<?php echo count($news_items); ?>)
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b font-serif-lao">
                        <tr>
                            <th class="px-4 py-3">ຮູບພາບ</th>
                            <th class="px-4 py-3">ຫົວຂໍ້ & ປະເພດ</th>
                            <th class="px-4 py-3">ສະຖານທີ່ / ວັນທີ</th>
                            <th class="px-4 py-3">ສະຖານະ</th>
                            <th class="px-4 py-3 text-right">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-serif-lao">
                        <?php if (count($news_items) > 0): ?>
                            <?php foreach ($news_items as $item): ?>
                                <tr class="bg-white hover:bg-gray-50 transition-colors <?php echo (!empty($item['is_featured']) && $item['is_featured'] == 1) ? 'bg-amber-50/80 border-l-4 border-amber-500' : ((isset($edit_item['id']) && $edit_item['id'] === $item['id']) ? 'bg-blue-50/50' : ''); ?>">
                                    <td class="px-4 py-3.5 align-top">
                                        <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="Image" class="w-20 h-14 object-cover rounded-xl border border-gray-200 shadow-sm">
                                    </td>
                                    <td class="px-4 py-3.5 align-top space-y-1">
                                        <div class="font-extrabold text-gray-900 line-clamp-1 flex items-center gap-1.5">
                                            <?php if (!empty($item['is_featured']) && $item['is_featured'] == 1): ?>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-500 text-white shadow-sm shrink-0">
                                                    HERO BANNER
                                                </span>
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($item['title_lo']); ?></span>
                                        </div>
                                        <div class="text-xs text-gray-500 font-light line-clamp-1"><?php echo htmlspecialchars($item['title_en']); ?></div>
                                        
                                        <div class="pt-1 flex items-center gap-1.5 flex-wrap">
                                            <?php if ($item['is_promo'] == 2): ?>
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-100 text-purple-900 border border-purple-200">
                                                    ກິດຈະກຳ (Event)
                                                </span>
                                            <?php elseif ($item['is_promo'] == 1): ?>
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-200">
                                                    ໂປຣໂມຊັນ (Promo)
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-900 border border-blue-200">
                                                    ບົດຄວາມ (Article)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3.5 align-top text-xs space-y-1">
                                        <?php if (!empty($item['event_location'])): ?>
                                            <div class="text-gray-800 font-semibold flex items-center gap-1">
                                                <span class="line-clamp-1"><?php echo htmlspecialchars($item['event_location']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($item['event_date'])): ?>
                                            <div class="text-amber-800 font-bold flex items-center gap-1">
                                                <span class="line-clamp-1"><?php echo htmlspecialchars($item['event_date']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="text-gray-400 text-[11px]">ວັນທີ: <?php echo htmlspecialchars($item['publish_date']); ?></div>
                                    </td>

                                    <td class="px-4 py-3.5 align-top text-xs">
                                        <?php if (($item['status'] ?? 'published') === 'published'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> ເຜີຍແຜ່
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-200 text-gray-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> ຮ່າງໄວ້
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-4 py-3.5 align-top text-right space-y-1">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="news_manage.php?edit=<?php echo $item['id']; ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-bold text-xs transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span>ແກ້ໄຂ</span>
                                            </a>
                                            <a href="news_manage.php?delete=<?php echo $item['id']; ?>" onclick="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບລາຍການນີ້?')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg font-bold text-xs transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>ລຶບ</span>
                                            </a>
                                        </div>
                                        <div>
                                            <?php if (empty($item['is_featured'])): ?>
                                                <a href="news_manage.php?set_featured=<?php echo $item['id']; ?>" class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 text-amber-900 hover:bg-amber-200 rounded text-[10px] font-extrabold transition-colors">
                                                    <span>ຕັ້ງເປັນ Hero Banner</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-16 text-center text-gray-400 font-light font-serif-lao">
                                    <div>ຍັງບໍ່ມີຂໍ້ມູນບົດຄວາມ ຫຼື ກິດຈະກຳໃນລະບົບ</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
function toggleEventFields() {
    const promoSelect = document.getElementById('is_promo_select');
    const eventFieldsContainer = document.getElementById('event_fields_container');
    if (promoSelect.value === '2' || promoSelect.value === '1') {
        eventFieldsContainer.style.display = 'block';
    } else {
        eventFieldsContainer.style.display = 'block';
    }
}
toggleEventFields();
</script>

</main>
</body>
</html>
