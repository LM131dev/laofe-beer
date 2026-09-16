<?php
// admin/members.php - Member Management Portal
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// ຈັດການການອັບເດດຂໍ້ມູນສະມາຊິກ (POST Actions + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. ເພີ່ມ/ລົບ ຄະແນນ ຫຼື ປ່ຽນ Tier ຂອງສະມາຊິກ
        if ($action === 'update_member') {
            $member_id = intval($_POST['member_id'] ?? 0);
            $points = intval($_POST['points'] ?? 0);
            $tier = trim($_POST['tier'] ?? 'Member');

            if ($member_id > 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET points = ?, tier = ? WHERE id = ?");
                    $stmt->execute([$points, $tier, $member_id]);
                    $success = 'ອັບເດດຂໍ້ມູນສະມາຊິກສຳເລັດແລ້ວ!';
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }

        // 2. 增加/扣减 Points (Quick Action)
        if ($action === 'adjust_points') {
            $member_id = intval($_POST['member_id'] ?? 0);
            $delta = intval($_POST['delta_points'] ?? 0);

            if ($member_id > 0 && $delta != 0) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET points = GREATEST(0, points + ?) WHERE id = ?");
                    $stmt->execute([$delta, $member_id]);
                    $success = 'ປັບປຸງຄະແນນສະສົມສຳເລັດແລ້ວ!';
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }

        // 3. ລົບສະມາຊິກ
        if ($action === 'delete_member') {
            $member_id = intval($_POST['member_id'] ?? 0);
            if ($member_id > 0) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
                    $stmt->execute([$member_id]);
                    $success = 'ລົບສະມາຊິກອອກຈາກລະບົບຮຽບຮ້ອຍແລ້ວ!';
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }
    }
}

// Search and Filter Setup
$search = trim($_GET['search'] ?? '');
$tier_filter = trim($_GET['tier'] ?? '');

$query = "SELECT * FROM users WHERE role != 'admin'";
$params = [];

if (!empty($search)) {
    $query .= " AND (fullname LIKE ? OR phone LIKE ? OR username LIKE ? OR email LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term, $term]);
}

if (!empty($tier_filter)) {
    $query .= " AND tier = ?";
    $params[] = $tier_filter;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$members = $stmt->fetchAll();

// General Stats
$stmt_total = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
$total_members = $stmt_total->fetchColumn();

$stmt_pts = $pdo->query("SELECT SUM(points) FROM users WHERE role != 'admin'");
$total_points = $stmt_pts->fetchColumn() ?: 0;

$stmt_vip = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin' AND tier IN ('Gold', 'VIP')");
$vip_count = $stmt_vip->fetchColumn();
?>

<div class="space-y-6">
    <!-- Header Title Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-serif-lao flex items-center gap-2.5">
                <svg class="w-6 h-6 text-burgundy-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>ຈັດການສະມາຊິກ (Customer Members)</span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">ກວດສອບ, ແກ້ໄຂຂໍ້ມູນສະມາຊິກ ແລະ ປັບປຸງຄະແນນສະສົມ (Loyalty Points)</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (!empty($success)): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm">
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-800 text-sm font-semibold flex items-center justify-between shadow-sm">
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-500">ສະມາຊິກທັງໝົດ</span>
                <p class="text-3xl font-extrabold text-burgundy-700"><?php echo number_format($total_members); ?> <span class="text-sm font-normal text-gray-500">ຄົນ</span></p>
            </div>
            <div class="w-12 h-12 bg-burgundy-50 text-burgundy-700 rounded-xl flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-500">ຄະແນນສະສົມລວມທັງໝົດ</span>
                <p class="text-3xl font-extrabold text-amber-600"><?php echo number_format($total_points); ?> <span class="text-sm font-normal text-gray-500">Pts</span></p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-500">ສະມາຊິກລະດັບ Gold / VIP</span>
                <p class="text-3xl font-extrabold text-emerald-600"><?php echo number_format($vip_count); ?> <span class="text-sm font-normal text-gray-500">ຄົນ</span></p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" action="members.php" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ຄົ້ນຫາຕາມຊື່, ເບີໂທ, ຫຼື Email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <select name="tier" onchange="this.form.submit()" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700 text-gray-700">
                    <option value="">-- ທຸກໆ Tier --</option>
                    <option value="Member" <?php echo $tier_filter === 'Member' ? 'selected' : ''; ?>>Member</option>
                    <option value="Silver" <?php echo $tier_filter === 'Silver' ? 'selected' : ''; ?>>Silver</option>
                    <option value="Gold" <?php echo $tier_filter === 'Gold' ? 'selected' : ''; ?>>Gold</option>
                    <option value="VIP" <?php echo $tier_filter === 'VIP' ? 'selected' : ''; ?>>VIP</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="px-5 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    ຄົ້ນຫາ
                </button>
                <?php if (!empty($search) || !empty($tier_filter)): ?>
                    <a href="members.php" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition-all">
                        ລ້າງຄົ້ນຫາ
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">ສະມາຊິກ (Member)</th>
                        <th class="px-6 py-4">ເບີໂທ (Phone)</th>
                        <th class="px-6 py-4">ອີເມລ (Email)</th>
                        <th class="px-6 py-4">ຄະແນນ (Points)</th>
                        <th class="px-6 py-4">ລະດັບ (Tier)</th>
                        <th class="px-6 py-4">ວັນທີສະໝັກ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ (Action)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                ບໍ່ພົບຂໍ້ມູນສະມາຊິກ
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $m): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-gray-400">#<?php echo $m['id']; ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-burgundy-100 text-burgundy-700 flex items-center justify-center font-bold text-sm shadow-sm">
                                            <?php echo mb_substr($m['fullname'] ?: $m['username'], 0, 1, 'UTF-8'); ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900"><?php echo htmlspecialchars($m['fullname'] ?: 'ບໍ່ມີຊື່ບັນຊີ'); ?></span>
                                            <span class="text-xs text-gray-400 font-mono">@<?php echo htmlspecialchars($m['username']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <?php echo htmlspecialchars($m['phone'] ?: '-'); ?>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    <?php echo htmlspecialchars($m['email'] ?: '-'); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 bg-amber-50 text-amber-700 font-extrabold rounded-full text-xs border border-amber-200 inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            <span><?php echo number_format($m['points']); ?> Pts</span>
                                        </span>
                                        <!-- Quick Adjust Buttons -->
                                        <form method="POST" action="members.php" class="inline-flex gap-1">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                            <input type="hidden" name="action" value="adjust_points">
                                            <input type="hidden" name="member_id" value="<?php echo $m['id']; ?>">
                                            <button type="submit" name="delta_points" value="50" title="+50 Points" class="w-6 h-6 rounded bg-gray-100 hover:bg-emerald-600 hover:text-white text-xs font-bold text-gray-600 flex items-center justify-center transition-colors">
                                                +
                                            </button>
                                            <button type="submit" name="delta_points" value="-50" title="-50 Points" class="w-6 h-6 rounded bg-gray-100 hover:bg-red-600 hover:text-white text-xs font-bold text-gray-600 flex items-center justify-center transition-colors">
                                                -
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $t = $m['tier'] ?: 'Member';
                                        $badge = 'bg-gray-100 text-gray-700';
                                        if ($t === 'Silver') $badge = 'bg-slate-100 text-slate-700 border border-slate-300';
                                        if ($t === 'Gold') $badge = 'bg-amber-100 text-amber-800 border border-amber-300 font-bold';
                                        if ($t === 'VIP') $badge = 'bg-purple-100 text-purple-800 border border-purple-300 font-bold animate-pulse';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $badge; ?>">
                                        <?php echo htmlspecialchars($t); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    <?php echo date('d/m/Y H:i', strtotime($m['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Edit Modal Trigger -->
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($m)); ?>)" class="px-3 py-1.5 bg-burgundy-50 hover:bg-burgundy-700 text-burgundy-700 hover:text-white rounded-lg text-xs font-bold transition-all border border-burgundy-200">
                                            ແກ້ໄຂ
                                        </button>
                                        <!-- Delete Button -->
                                        <form method="POST" action="members.php" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລົບສະມາຊິກນີ້?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                            <input type="hidden" name="action" value="delete_member">
                                            <input type="hidden" name="member_id" value="<?php echo $m['id']; ?>">
                                            <button type="submit" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-lg text-xs font-bold transition-all border border-red-200">
                                                ລົບ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-5 border border-gray-100">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900 font-serif-lao">ແກ້ໄຂຂໍ້ມູນສະມາຊິກ</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form method="POST" action="members.php" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="update_member">
            <input type="hidden" name="member_id" id="modal-member-id">

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">ຊື່ສະມາຊິກ</label>
                <input type="text" id="modal-member-name" readonly class="w-full px-4 py-2 bg-gray-100 rounded-xl text-sm font-bold text-gray-700 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ຄະແນນສະສົມ (Points)</label>
                <input type="number" name="points" id="modal-member-points" required min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">ລະດັບສະມາຊິກ (Tier)</label>
                <select name="tier" id="modal-member-tier" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    <option value="Member">Member</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="VIP">VIP</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all">
                    ຍົກເລີກ
                </button>
                <button type="submit" class="px-5 py-2 bg-burgundy-700 text-white rounded-xl text-xs font-bold hover:bg-burgundy-800 transition-all shadow-sm">
                    ບັນທຶກການປ່ຽນແປງ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(m) {
    document.getElementById('modal-member-id').value = m.id;
    document.getElementById('modal-member-name').value = m.fullname || m.username;
    document.getElementById('modal-member-points').value = m.points || 0;
    document.getElementById('modal-member-tier').value = m.tier || 'Member';
    document.getElementById('edit-modal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
</script>

</body>
</html>
