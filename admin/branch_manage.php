<?php
// admin/branch_manage.php - Branch Management Page (CRUD for Branches)
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// 0. Auto-check image_path column in branches table
try {
    $cols_br = $pdo->query("SHOW COLUMNS FROM branches");
    $col_names_br = $cols_br->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('image_path', $col_names_br)) {
        $pdo->exec("ALTER TABLE branches ADD COLUMN image_path VARCHAR(255) NULL");
    }
} catch (\Exception $e) {}

// 1. ຈັດການການລຶບສາຂາ (DELETE)
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    try {
        // ດຶງຮູບພາບມາລຶບອອກຈາກເຊີເວີກ່ອນ (ຖ້າມີ ແລະ ບໍ່ແມ່ນຮູບ default/assets ຫຼັກ)
        $stmt_img = $pdo->prepare("SELECT image_path FROM branches WHERE id = ?");
        $stmt_img->execute([$delete_id]);
        $img_path = $stmt_img->fetchColumn();
        if ($img_path && file_exists('../' . $img_path) && !strpos($img_path, 'branch_namphou') && !strpos($img_path, 'branch_luangprabang') && !strpos($img_path, 'branch_pakse') && !strpos($img_path, 'branch_vangvieng')) {
            @unlink('../' . $img_path);
        }

        $stmt = $pdo->prepare("DELETE FROM branches WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = 'ລຶບຂໍ້ມູນສາຂາຮຽບຮ້ອຍແລ້ວ!';
    } catch (\Exception $e) {
        $error = 'ເກີດຂໍ້ຜິດພາດໃນການລຶບ: ' . $e->getMessage();
    }
}

// 2. ຈັດການເພີ່ມ ຫຼື ແກ້ໄຂສາຂາ (CREATE / UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name_lo = trim($_POST['name_lo'] ?? '');
    $name_en = trim($_POST['name_en'] ?? '');
    $address_lo = trim($_POST['address_lo'] ?? '');
    $address_en = trim($_POST['address_en'] ?? '');
    $hours_lo = trim($_POST['hours_lo'] ?? '');
    $hours_en = trim($_POST['hours_en'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $map_link = trim($_POST['map_link'] ?? '');
    
    $image_path = $_POST['existing_image'] ?? 'assets/images/branch_namphou.png';

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
                if ($id > 0 && $image_path && file_exists('../' . $image_path) && !strpos($image_path, 'branch_namphou') && !strpos($image_path, 'branch_luangprabang') && !strpos($image_path, 'branch_pakse') && !strpos($image_path, 'branch_vangvieng')) {
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

    if (empty($name_lo) || empty($name_en) || empty($address_lo)) {
        $error = 'ກະລຸນາກອກ ຊື່ສາຂາ ແລະ ທີ່ຢູ່ ໃຫ້ຄົບຖ້ວນ.';
    } elseif (empty($error)) {
        try {
            if ($id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE branches SET name_lo = ?, name_en = ?, address_lo = ?, address_en = ?, hours_lo = ?, hours_en = ?, phone = ?, map_link = ?, image_path = ? WHERE id = ?");
                $stmt->execute([$name_lo, $name_en, $address_lo, $address_en, $hours_lo, $hours_en, $phone, $map_link, $image_path, $id]);
                $success = 'ແກ້ໄຂຂໍ້ມູນສາຂາຮຽບຮ້ອຍ!';
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO branches (name_lo, name_en, address_lo, address_en, hours_lo, hours_en, phone, map_link, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name_lo, $name_en, $address_lo, $address_en, $hours_lo, $hours_en, $phone, $map_link, $image_path]);
                $success = 'ເພີ່ມສາຂາໃໝ່ຮຽບຮ້ອຍ!';
            }
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ: ' . $e->getMessage();
        }
    }
}

// ດຶງຂໍ້ມູນສາຂາທັງໝົດ
$branches = [];
try {
    $stmt = $pdo->query("SELECT * FROM branches ORDER BY id ASC");
    $branches = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}

// ຖ້າຕ້ອງການແກ້ໄຂ
$edit_branch = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    foreach ($branches as $b) {
        if ($b['id'] == $edit_id) {
            $edit_branch = $b;
            break;
        }
    }
}
?>

<div class="space-y-6">
    <!-- Top Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 font-serif-lao flex items-center gap-2">
                <span>📍</span> ຈັດການຂໍ້ມູນສາຂາ LaoFe (Branch Management)
            </h1>
            <p class="text-xs text-gray-500 font-serif-lao mt-1">ເພີ່ມ, ແກ້ໄຂ, ລຶບ ແລະ ຈັດການທີ່ຕັ້ງ, ເວລາເປີດ-ປິດ, ເບີໂທຕິດຕໍ່ ແລະ ຮູບພາບຂອງສາຂາ</p>
        </div>
        <div>
            <button onclick="openModal()" class="px-5 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 font-serif-lao">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>ເພີ່ມສາຂາໃໝ່</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success)): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
            <span>✓ <?php echo htmlspecialchars($success); ?></span>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">✕</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-800 text-xs font-semibold flex items-center justify-between shadow-sm">
            <span>⚠️ <?php echo htmlspecialchars($error); ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">✕</button>
        </div>
    <?php endif; ?>

    <!-- Branch Grid / Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (!empty($branches)): ?>
            <?php foreach ($branches as $idx => $br): 
                $card_img = !empty($br['image_path']) ? '../' . $br['image_path'] : '../assets/images/branch_namphou.png';
            ?>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <!-- Image Container -->
                        <div class="h-48 relative overflow-hidden bg-gray-100">
                            <img src="<?php echo htmlspecialchars($card_img); ?>" alt="<?php echo htmlspecialchars($br['name_lo']); ?>" class="w-full h-full object-cover">
                            <div class="absolute top-3 right-3 bg-gray-950/80 backdrop-blur-md text-amber-300 px-3 py-1 rounded-full text-[10px] font-bold">
                                Branch #<?php echo $br['id']; ?>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 space-y-3 font-serif-lao">
                            <h3 class="text-lg font-bold text-gray-900">
                                <?php echo htmlspecialchars($br['name_lo']); ?>
                            </h3>
                            <p class="text-xs text-gray-400 font-sans">
                                <?php echo htmlspecialchars($br['name_en']); ?>
                            </p>

                            <div class="space-y-1.5 text-xs text-gray-600 border-t border-gray-100 pt-3">
                                <p class="flex items-start gap-2">
                                    <span class="text-burgundy-700 font-bold shrink-0">📍 ທີ່ຢູ່:</span>
                                    <span><?php echo htmlspecialchars($br['address_lo']); ?></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span class="text-burgundy-700 font-bold shrink-0">🕒 ເວລາ:</span>
                                    <span><?php echo htmlspecialchars($br['hours_lo']); ?> (<?php echo htmlspecialchars($br['hours_en']); ?>)</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span class="text-burgundy-700 font-bold shrink-0">📞 ເບີໂທ:</span>
                                    <span><?php echo htmlspecialchars($br['phone']); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                        <?php if (!empty($br['map_link'])): ?>
                            <a href="<?php echo htmlspecialchars($br['map_link']); ?>" target="_blank" class="px-3 py-1.5 bg-amber-100 text-amber-900 hover:bg-amber-200 rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1 font-serif-lao">
                                <span>🗺️ แผนທີ່ Google Maps</span>
                            </a>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>

                        <div class="flex items-center gap-2">
                            <a href="branch_manage.php?edit=<?php echo $br['id']; ?>" class="px-3.5 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold rounded-lg transition-all font-serif-lao">
                                ✏️ ແກ້ໄຂ
                            </a>
                            <a href="branch_manage.php?delete=<?php echo $br['id']; ?>" onclick="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບສາຂານີ້?');" class="px-3.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition-all font-serif-lao">
                                🗑️ ລຶບ
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 bg-white p-12 text-center rounded-2xl border border-gray-200 text-gray-400 font-serif-lao space-y-2">
                <p class="text-4xl">🏢</p>
                <p class="text-sm font-semibold">ຍັງບໍ່ມີຂໍ້ມູນສາຂາໃນລະບົບ</p>
                <button onclick="openModal()" class="px-4 py-2 bg-burgundy-700 text-white font-bold text-xs rounded-xl mt-2">ເພີ່ມສາຂາທຳອິດ</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add / Edit Branch Modal -->
<div id="branchModal" class="fixed inset-0 z-50 <?php echo $edit_branch ? 'flex' : 'hidden'; ?> items-center justify-center p-4 bg-gray-950/70 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 md:p-8 shadow-2xl border border-gray-100 space-y-6 my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h2 class="text-lg font-bold text-gray-900 font-serif-lao flex items-center gap-2">
                <span>📍</span>
                <span id="modalTitle"><?php echo $edit_branch ? 'ແກ້ໄຂຂໍ້ມູນສາຂາ' : 'ເພີ່ມສາຂາໃໝ່'; ?></span>
            </h2>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center font-bold">✕</button>
        </div>

        <form action="branch_manage.php" method="POST" enctype="multipart/form-data" class="space-y-5 font-serif-lao">
            <input type="hidden" name="id" value="<?php echo $edit_branch['id'] ?? 0; ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($edit_branch['image_path'] ?? 'assets/images/branch_namphou.png'); ?>">

            <!-- Branch Names LO & EN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຊື່ສາຂາ (ພາສາລາວ) *</label>
                    <input type="text" name="name_lo" required value="<?php echo htmlspecialchars($edit_branch['name_lo'] ?? ''); ?>" placeholder="ຕົວຢ່າງ: ສາຂາ ນ້ຳພຸ (ນະຄອນຫຼວງວຽງຈັນ)" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Branch Name (English) *</label>
                    <input type="text" name="name_en" required value="<?php echo htmlspecialchars($edit_branch['name_en'] ?? ''); ?>" placeholder="e.g., Nam Phou Branch (Vientiane)" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none font-sans">
                </div>
            </div>

            <!-- Address LO & EN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ທີ່ຢູ່ສາຂາ (ພາສາລາວ) *</label>
                    <textarea name="address_lo" required rows="3" placeholder="ຕົວຢ່າງ: ຖະໜົນນ້ຳພຸ, ບ້ານຊຽງຍືນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none"><?php echo htmlspecialchars($edit_branch['address_lo'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Address (English) *</label>
                    <textarea name="address_en" required rows="3" placeholder="e.g., Namphou Rd, XiengNgeun Village, Chanthabouly District, Vientiane Capital" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none font-sans"><?php echo htmlspecialchars($edit_branch['address_en'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Operating Hours LO & EN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ເວລາເປີດ-ປິດ (ພາສາລາວ) *</label>
                    <input type="text" name="hours_lo" required value="<?php echo htmlspecialchars($edit_branch['hours_lo'] ?? '07:00 - 23:00'); ?>" placeholder="07:00 - 23:00 ໂມງ" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Operating Hours (English) *</label>
                    <input type="text" name="hours_en" required value="<?php echo htmlspecialchars($edit_branch['hours_en'] ?? '07:00 AM - 11:00 PM'); ?>" placeholder="07:00 AM - 11:00 PM" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none font-sans">
                </div>
            </div>

            <!-- Phone & Map Link -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ເບີໂທຕິດຕໍ່ສາຂາ</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($edit_branch['phone'] ?? '+856 20 91 111 104'); ?>" placeholder="+856 20 91 111 104" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none font-sans">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ລິ້ງແຜນທີ່ (Google Maps Link)</label>
                    <input type="url" name="map_link" value="<?php echo htmlspecialchars($edit_branch['map_link'] ?? 'https://www.google.com/maps?q=17.966801,102.606311'); ?>" placeholder="https://www.google.com/maps?q=..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-burgundy-700 focus:outline-none font-sans">
                </div>
            </div>

            <!-- Branch Image File Upload -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ຮູບພາບສາຂາ (Branch Photo)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs bg-gray-50 text-gray-600 focus:outline-none">
                <?php if (!empty($edit_branch['image_path'])): ?>
                    <div class="mt-2 flex items-center gap-3">
                        <span class="text-[11px] text-gray-500">ຮູບປະຈຸບັນ:</span>
                        <img src="../<?php echo htmlspecialchars($edit_branch['image_path']); ?>" alt="Current Branch Image" class="w-16 h-12 object-cover rounded-lg border border-gray-200">
                    </div>
                <?php endif; ?>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-all">
                    ຍົກເລີກ
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-burgundy-700 hover:bg-burgundy-800 text-white text-xs font-bold shadow-md transition-all">
                    💾 ບັນທຶກຂໍ້ມູນສາຂາ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('branchModal').classList.remove('hidden');
    document.getElementById('branchModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('branchModal').classList.add('hidden');
    document.getElementById('branchModal').classList.remove('flex');
    window.location.href = 'branch_manage.php';
}
</script>

    </main>
</body>
</html>
