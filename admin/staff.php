<?php
// admin/staff.php - Admin Staff & Role Management Portal
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// Check permission: Only admin and manager can access staff management
$current_user_role = $_SESSION['admin_role'] ?? 'staff';
if (!in_array($current_user_role, ['admin', 'manager'])) {
    echo "<div class='p-8 text-center font-serif-lao'><div class='inline-block bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 font-bold'>ທ່ານບໍ່ມີສິດໃນການເຂົ້າເຖິງໜ້າຈັດການທີມງານ ແລະ ສິດ (Requires Admin/Manager Role)</div></div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Handle Form Submissions (POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token()) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. Create New Staff/Admin Account
        if ($action === 'create_staff') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $role     = trim($_POST['role'] ?? 'staff');

            // Non-super-admins cannot create super-admin accounts
            if ($role === 'admin' && $current_user_role !== 'admin') {
                $error = 'ສະເພາະ Super Admin ເທົ່ານັ້ນທີ່ສາມາດສ້າງບັນຊີ Super Admin ໄດ້!';
            } elseif (empty($username) || empty($password)) {
                $error = 'ກະລຸນາກອກ ຊື່ຜູ້ໃຊ້ ແລະ ລະຫັດຜ່ານ!';
            } elseif (strlen($password) < 6) {
                $error = 'ລະຫັດຜ່ານຕ້ອງມີຢ່າງນ້ອຍ 6 ຕົວອັກສອນ!';
            } else {
                try {
                    // Check if username exists
                    $stmt_c = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                    $stmt_c->execute([$username]);
                    if ($stmt_c->fetchColumn() > 0) {
                        $error = 'ຊື່ຜູ້ໃຊ້ນີ້ຖືກນຳໃຊ້ແລ້ວ! ກະລຸນາເລືອກຊື່ຜູ້ໃຊ້ອື່ນ.';
                    } else {
                        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                        $stmt_ins = $pdo->prepare("INSERT INTO users (username, password, fullname, phone, email, role, tier) VALUES (?, ?, ?, ?, ?, ?, 'Staff')");
                        $stmt_ins->execute([$username, $hashed_pass, $fullname, $phone, $email, $role]);
                        $success = 'ສ້າງບັນຊີທີມງານໃໝ່ຮຽບຮ້ອຍແລ້ວ!';
                    }
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }

        // 2. Update Staff/User Role & Info
        if ($action === 'update_role') {
            $staff_id = intval($_POST['staff_id'] ?? 0);
            $fullname = trim($_POST['fullname'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $role     = trim($_POST['role'] ?? 'staff');

            if ($staff_id > 0) {
                try {
                    // Check target user role
                    $stmt_target = $pdo->prepare("SELECT role, username FROM users WHERE id = ?");
                    $stmt_target->execute([$staff_id]);
                    $target = $stmt_target->fetch();

                    if ($target) {
                        if ($target['role'] === 'admin' && $current_user_role !== 'admin') {
                            $error = 'ທ່ານບໍ່ມີສິດແກ້ໄຂບັນຊີ Super Admin!';
                        } elseif ($role === 'admin' && $current_user_role !== 'admin') {
                            $error = 'ສະເພາະ Super Admin ເທົ່ານັ້ນທີ່ສາມາດມອບສິດ Super Admin ໄດ້!';
                        } else {
                            $stmt_u = $pdo->prepare("UPDATE users SET fullname = ?, phone = ?, email = ?, role = ? WHERE id = ?");
                            $stmt_u->execute([$fullname, $phone, $email, $role, $staff_id]);
                            $success = 'ອັບເດດສິດ ແລະ ຂໍ້ມູນທີມງານສຳເລັດແລ້ວ!';
                        }
                    }
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }

        // 3. Reset Staff Password
        if ($action === 'reset_password') {
            $staff_id     = intval($_POST['staff_id'] ?? 0);
            $new_password = trim($_POST['new_password'] ?? '');

            if ($staff_id > 0 && !empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $error = 'ລະຫັດຜ່ານໃໝ່ຕ້ອງມີຢ່າງນ້ອຍ 6 ຕົວອັກສອນ!';
                } else {
                    try {
                        $stmt_target = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                        $stmt_target->execute([$staff_id]);
                        $target_role = $stmt_target->fetchColumn();

                        if ($target_role === 'admin' && $current_user_role !== 'admin') {
                            $error = 'ທ່ານບໍ່ມີສິດ Reset ລະຫັດຜ່ານຂອງ Super Admin!';
                        } else {
                            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                            $stmt_p = $pdo->prepare("UPDATE users SET password = ?, failed_attempts = 0, lockout_stage = 0, lockout_until = NULL, is_locked = 0 WHERE id = ?");
                            $stmt_p->execute([$hashed, $staff_id]);
                            $success = 'Reset ລະຫັດຜ່ານສຳເລັດແລ້ວ!';
                        }
                    } catch (\PDOException $e) {
                        $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                    }
                }
            }
        }

        // 4. Unlock Staff Account
        if ($action === 'unlock_staff') {
            $staff_id = intval($_POST['staff_id'] ?? 0);
            if ($staff_id > 0) {
                try {
                    $stmt_p = $pdo->prepare("UPDATE users SET failed_attempts = 0, lockout_stage = 0, lockout_until = NULL, is_locked = 0 WHERE id = ?");
                    $stmt_p->execute([$staff_id]);
                    $success = 'ປົດລັອກບັນຊີຮຽບຮ້ອຍແລ້ວ!';
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }

        // 5. Delete Staff/Admin User
        if ($action === 'delete_staff') {
            $staff_id = intval($_POST['staff_id'] ?? 0);
            if ($staff_id > 0) {
                try {
                    $stmt_target = $pdo->prepare("SELECT id, role, username FROM users WHERE id = ?");
                    $stmt_target->execute([$staff_id]);
                    $target = $stmt_target->fetch();

                    if ($target) {
                        if ($target['username'] === $_SESSION['admin_user']) {
                            $error = 'ທ່ານບໍ່ສາມາດລົບບັນຊີຂອງຕົນເອງທີ່ກຳລັງ Login ຢູ່ໄດ້!';
                        } elseif ($target['role'] === 'admin' && $current_user_role !== 'admin') {
                            $error = 'ທ່ານບໍ່ມີສິດລົບບັນຊີ Super Admin!';
                        } else {
                            $stmt_d = $pdo->prepare("DELETE FROM users WHERE id = ?");
                            $stmt_d->execute([$staff_id]);
                            $success = 'ລົບຜູ້ໃຊ້ຮຽບຮ້ອຍແລ້ວ!';
                        }
                    }
                } catch (\PDOException $e) {
                    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
                }
            }
        }
    }
}

// Search and Filter Logic
$search = trim($_GET['search'] ?? '');
$role_filter = trim($_GET['role'] ?? '');

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (fullname LIKE ? OR username LIKE ? OR phone LIKE ? OR email LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term, $term]);
}

if (!empty($role_filter)) {
    $query .= " AND role = ?";
    $params[] = $role_filter;
} else {
    // Default show staff, admin, manager
    $query .= " AND role IN ('admin', 'manager', 'staff')";
}

$query .= " ORDER BY CASE role WHEN 'admin' THEN 1 WHEN 'manager' THEN 2 WHEN 'staff' THEN 3 ELSE 4 END, id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$staff_list = $stmt->fetchAll();

// Statistics
$total_staff = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'manager', 'staff')")->fetchColumn();
$super_admins = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$managers     = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'manager'")->fetchColumn();
$staff_members = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'staff'")->fetchColumn();
?>

<div class="space-y-8 pb-12 font-serif-lao">
    
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
                <span>🛡️ ຈັດການສິດ & ທີມງານ (Staff & Roles)</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">ກຳນົດສິດການເຂົ້າເຖິງ, ເພີ່ມ/ແກ້ໄຂ ທີມງານ Admin, Manager, Staff ແລະ ປົດລັອກບັນຊີ</p>
        </div>
        <button onclick="openCreateModal()" class="px-5 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-2">
            <span>+ ເພີ່ມທີມງານ/ຜູ້ໃຊ້ໃໝ່</span>
        </button>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm font-semibold shadow-sm">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm font-semibold shadow-sm">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Stat Metrics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center font-bold text-xl shadow-inner">
                👥
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">ທີມງານທັງໝົດ</p>
                <p class="text-2xl font-bold text-gray-900 font-mono"><?php echo number_format($total_staff); ?></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xl shadow-inner">
                👑
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Super Admin</p>
                <p class="text-2xl font-bold text-gray-900 font-mono"><?php echo number_format($super_admins); ?></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xl shadow-inner">
                👔
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Manager</p>
                <p class="text-2xl font-bold text-gray-900 font-mono"><?php echo number_format($managers); ?></p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xl shadow-inner">
                🖥️
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Staff / ພະນັກງານ</p>
                <p class="text-2xl font-bold text-gray-900 font-mono"><?php echo number_format($staff_members); ?></p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
        <form method="GET" action="staff.php" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-grow w-full">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ຄົ້ນຫາຊື່ຜູ້ໃຊ້, ຊື່ເຕັມ, ເບີໂທ ຫຼື ອີເມລ..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700 focus:bg-white transition-all">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="role" onchange="this.form.submit()" class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    <option value="">-- ທຸກສິດ (All Roles) --</option>
                    <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Super Admin 👑</option>
                    <option value="manager" <?php echo $role_filter === 'manager' ? 'selected' : ''; ?>>Manager 👔</option>
                    <option value="staff" <?php echo $role_filter === 'staff' ? 'selected' : ''; ?>>Staff / ພະນັກງານ 🖥️</option>
                    <option value="customer" <?php echo $role_filter === 'customer' ? 'selected' : ''; ?>>Customer Member 👤</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow transition-all">
                    ຄົ້ນຫາ
                </button>
                <?php if (!empty($search) || !empty($role_filter)): ?>
                    <a href="staff.php" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded-xl transition-all">
                        ລ້າງ
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Staff & Role Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">ຜູ້ໃຊ້ & ຊື່ເຕັມ</th>
                        <th class="px-6 py-4">ສິດການໃຊ້ງານ (Role)</th>
                        <th class="px-6 py-4">ເບີໂທ & ອີເມລ</th>
                        <th class="px-6 py-4">ສະຖານະບັນຊີ</th>
                        <th class="px-6 py-4">ວັນທີສ້າງ</th>
                        <th class="px-6 py-4 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($staff_list)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                ບໍ່ພົບຂໍ້ມູນທີມງານ ຫຼື ຜູ້ໃຊ້ຕາມເງື່ອນໄຂ
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($staff_list as $st): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-gray-400">#<?php echo $st['id']; ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-burgundy-700 text-white flex items-center justify-center font-bold text-sm shadow-sm uppercase font-mono">
                                            <?php echo substr($st['username'], 0, 1); ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900"><?php echo htmlspecialchars($st['fullname'] ?: $st['username']); ?></span>
                                            <span class="text-xs text-gray-400 font-mono">@<?php echo htmlspecialchars($st['username']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $r = $st['role'] ?: 'customer';
                                        $r_badge = 'bg-gray-100 text-gray-700';
                                        $r_label = 'Customer 👤';

                                        if ($r === 'admin') {
                                            $r_badge = 'bg-purple-100 text-purple-900 border border-purple-300 font-bold';
                                            $r_label = 'Super Admin 👑';
                                        } elseif ($r === 'manager') {
                                            $r_badge = 'bg-blue-100 text-blue-900 border border-blue-300 font-bold';
                                            $r_label = 'Manager 👔';
                                        } elseif ($r === 'staff') {
                                            $r_badge = 'bg-amber-100 text-amber-900 border border-amber-300 font-bold';
                                            $r_label = 'Staff 🖥️';
                                        }
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $r_badge; ?>">
                                        <?php echo htmlspecialchars($r_label); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs space-y-1">
                                    <div class="font-medium text-gray-800">📞 <?php echo htmlspecialchars($st['phone'] ?: '-'); ?></div>
                                    <div class="text-gray-400">✉️ <?php echo htmlspecialchars($st['email'] ?: '-'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $now_ts = time();
                                        $until_ts = !empty($st['lockout_until']) ? strtotime($st['lockout_until']) : 0;
                                        $is_locked = false;
                                        $lock_msg = 'ປົກຕິ (Active)';

                                        if (!empty($st['is_locked']) || intval($st['lockout_stage'] ?? 0) >= 3) {
                                            if ($until_ts > $now_ts) {
                                                $is_locked = true;
                                                $rem_h = ceil(($until_ts - $now_ts) / 3600);
                                                $lock_msg = "🔒 ຖືກລັອກ Stage 3 ({$rem_h}h)";
                                            } elseif (!empty($st['is_locked'])) {
                                                $is_locked = true;
                                                $lock_msg = "🔒 ຖືກລັອກ (Admin)";
                                            }
                                        } elseif ($until_ts > $now_ts) {
                                            $is_locked = true;
                                            $rem_m = ceil(($until_ts - $now_ts) / 60);
                                            $stg = intval($st['lockout_stage']);
                                            $lock_msg = "🔒 ຖືກລັອກ Stage {$stg} ({$rem_m}m)";
                                        }
                                    ?>
                                    <?php if ($is_locked): ?>
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 border border-red-300 rounded-full text-[10px] font-bold">
                                            <?php echo htmlspecialchars($lock_msg); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 border border-green-200 rounded-full text-[10px] font-bold inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            <span>Active</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400">
                                    <?php echo date('d/m/Y H:i', strtotime($st['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if ($is_locked || intval($st['failed_attempts'] ?? 0) > 0 || intval($st['lockout_stage'] ?? 0) > 0): ?>
                                            <!-- Unlock Button -->
                                            <form method="POST" action="staff.php" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການປົດລັອກບັນຊີນີ້?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                                <input type="hidden" name="action" value="unlock_staff">
                                                <input type="hidden" name="staff_id" value="<?php echo $st['id']; ?>">
                                                <button type="submit" title="ປົດລັອກບັນຊີ" class="px-2.5 py-1.5 bg-green-50 hover:bg-green-600 text-green-700 hover:text-white rounded-lg text-xs font-bold transition-all border border-green-300 flex items-center gap-1">
                                                    <span>🔓</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Edit Role Trigger -->
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($st)); ?>)" class="px-3 py-1.5 bg-burgundy-50 hover:bg-burgundy-700 text-burgundy-700 hover:text-white rounded-lg text-xs font-bold transition-all border border-burgundy-200">
                                            ແກ້ໄຂສິດ
                                        </button>

                                        <!-- Reset Password Trigger -->
                                        <button onclick="openResetModal(<?php echo $st['id']; ?>, '<?php echo htmlspecialchars($st['username']); ?>')" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-500 text-amber-800 hover:text-white rounded-lg text-xs font-bold transition-all border border-amber-300" title="Reset ລະຫັດຜ່ານ">
                                            🔑
                                        </button>

                                        <!-- Delete Button -->
                                        <?php if ($st['username'] !== $_SESSION['admin_user']): ?>
                                            <form method="POST" action="staff.php" onsubmit="return confirm('ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລົບຜູ້ໃຊ້ນີ້?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                                <input type="hidden" name="action" value="delete_staff">
                                                <input type="hidden" name="staff_id" value="<?php echo $st['id']; ?>">
                                                <button type="submit" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-600 text-red-600 hover:text-white rounded-lg text-xs font-bold transition-all border border-red-200" title="ລົບ">
                                                    🗑️
                                                </button>
                                            </form>
                                        <?php endif; ?>
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

<!-- =========================================================
     MODALS SECTION
     ========================================================= -->

<!-- 1. Create Staff/Admin Modal -->
<div id="createModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-6 border border-gray-100 font-serif-lao animate-fade-in">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span>➕ ເພີ່ມທີມງານ/ຜູ້ໃຊ້ໃໝ່</span>
            </h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form method="POST" action="staff.php" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="create_staff">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ຊື່ຜູ້ໃຊ້ (Username) *</label>
                <input type="text" name="username" required placeholder="ເຊັ່ນ: staff_somchai" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ລະຫັດຜ່ານ (Password) *</label>
                <input type="password" name="password" required placeholder="ຢ່າງນ້ອຍ 6 ຕົວອັກສອນ" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ກຳນົດສິດການໃຊ້ງານ (Role) *</label>
                <select name="role" required class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                    <option value="staff" selected>Staff / ພະນັກງານ 🖥️</option>
                    <option value="manager">Manager / ຜູ້ຈັດການ 👔</option>
                    <?php if ($current_user_role === 'admin'): ?>
                        <option value="admin">Super Admin 👑</option>
                    <?php endif; ?>
                    <option value="customer">Customer / ສະມາຊິກ 👤</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ຊື່ ແລະ ນາມສະກຸນເຕັມ</label>
                <input type="text" name="fullname" placeholder="ເຊັ່ນ: ສົມຊາຍ ໃຈດີ" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ເບີໂທລະສັບ</label>
                    <input type="text" name="phone" placeholder="020..." class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ອີເມລ</label>
                    <input type="email" name="email" placeholder="example@gmail.com" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeCreateModal()" class="w-1/2 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl text-xs transition-all">
                    ຍົກເລີກ
                </button>
                <button type="submit" class="w-1/2 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                    ບັນທຶກສ້າງທີມງານ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit Role & Info Modal -->
<div id="editModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-6 border border-gray-100 font-serif-lao animate-fade-in">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span>✏️ ແກ້ໄຂສິດ ແລະ ຂໍ້ມູນທີມງານ</span>
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form method="POST" action="staff.php" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="update_role">
            <input type="hidden" name="staff_id" id="edit_staff_id">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">ຊື່ຜູ້ໃຊ້ (Username)</label>
                <input type="text" id="edit_username" readonly class="w-full px-3.5 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ສິດການໃຊ້ງານ (Role) *</label>
                <select name="role" id="edit_role" required class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                    <option value="staff">Staff / ພະນັກງານ 🖥️</option>
                    <option value="manager">Manager / ຜູ້ຈັດການ 👔</option>
                    <?php if ($current_user_role === 'admin'): ?>
                        <option value="admin">Super Admin 👑</option>
                    <?php endif; ?>
                    <option value="customer">Customer / ສະມາຊິກ 👤</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ຊື່ ແລະ ນາມສະກຸນເຕັມ</label>
                <input type="text" name="fullname" id="edit_fullname" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ເບີໂທລະສັບ</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ອີເມລ</label>
                    <input type="email" name="email" id="edit_email" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeEditModal()" class="w-1/2 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl text-xs transition-all">
                    ຍົກເລີກ
                </button>
                <button type="submit" class="w-1/2 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                    ບັນທຶກການແກ້ໄຂ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Reset Password Modal -->
<div id="resetModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 space-y-6 border border-gray-100 font-serif-lao animate-fade-in">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span>🔑 Reset ລະຫັດຜ່ານ</span>
            </h3>
            <button onclick="closeResetModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <form method="POST" action="staff.php" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="reset_password">
            <input type="hidden" name="staff_id" id="reset_staff_id">

            <div>
                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">ຊື່ຜູ້ໃຊ້ (Username)</label>
                <input type="text" id="reset_username" readonly class="w-full px-3.5 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1">ລະຫັດຜ່ານໃໝ່ (New Password) *</label>
                <input type="password" name="new_password" required placeholder="ຢ່າງນ້ອຍ 6 ຕົວອັກສອນ" class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-burgundy-700 focus:outline-none">
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeResetModal()" class="w-1/2 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl text-xs transition-all">
                    ຍົກເລີກ
                </button>
                <button type="submit" class="w-1/2 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                    Reset ລະຫັດຜ່ານ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(data) {
    document.getElementById('edit_staff_id').value = data.id;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_role').value = data.role || 'staff';
    document.getElementById('edit_fullname').value = data.fullname || '';
    document.getElementById('edit_phone').value = data.phone || '';
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function openResetModal(id, username) {
    document.getElementById('reset_staff_id').value = id;
    document.getElementById('reset_username').value = username;
    document.getElementById('resetModal').classList.remove('hidden');
}
function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
