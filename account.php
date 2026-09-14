<?php
// account.php - Member Portal Dashboard (100% Pure Dual Language Lao & English + Admin Theme Sync)
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

// ຫາກຍັງບໍ່ເຂົ້າສູ່ລະບົບ ໃຫ້ເດັ້ງໄປ login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$profile_success = '';
$profile_error = '';

// ຈັດການການອັບເດດຂໍ້ມູນສ່ວນຕົວ (POST Profile Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');

    if (empty($fullname) || empty($email) || empty($phone)) {
        $profile_error = $current_lang === 'lo' ? 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບຖ້ວນ!' : 'Please fill in all required fields!';
    } else {
        try {
            if (!empty($new_password)) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, password = ? WHERE id = ?");
                $stmt->execute([$fullname, $email, $phone, $hashed, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, phone = ? WHERE id = ?");
                $stmt->execute([$fullname, $email, $phone, $user_id]);
            }
            $_SESSION['user_fullname'] = $fullname;
            $profile_success = $current_lang === 'lo' ? 'ອັບເດດຂໍ້ມູນສ່ວນຕົວສຳເລັດແລ້ວ!' : 'Profile updated successfully!';
        } catch (\PDOException $e) {
            $profile_error = 'Error: ' . $e->getMessage();
        }
    }
}

// ດຶງຂໍ້ມູນຜູ້ໃຊ້ຫຼ້າສຸດ
$user = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_points'] = $user['points'];
        $_SESSION['user_tier'] = $user['tier'];
    }
} catch (\Exception $e) {}

if (!$user) {
    header("Location: login.php?logout=true");
    exit;
}

// ດຶງປະຫວັດການສັ່ງຊື້ (Orders)
$orders = [];
try {
    $stmt_orders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt_orders->execute([$user_id]);
    $orders = $stmt_orders->fetchAll();
} catch (\Exception $e) {}

// ດຶງປະຫວັດການຈອງໂຕະ (Bookings)
$bookings = [];
try {
    $stmt_bookings = $pdo->prepare("SELECT b.*, br.name_lo as branch_lo, br.name_en as branch_en FROM bookings b JOIN branches br ON b.branch_id = br.id WHERE b.user_id = ? ORDER BY b.id DESC");
    $stmt_bookings->execute([$user_id]);
    $bookings = $stmt_bookings->fetchAll();
} catch (\Exception $e) {}

// ຫາ Upcoming Booking ຫຼ້າສຸດ
$upcoming_booking = null;
foreach ($bookings as $b) {
    if ($b['status'] !== 'Cancelled' && strtotime($b['booking_date']) >= strtotime(date('Y-m-d'))) {
        $upcoming_booking = $b;
        break;
    }
}

// ຄິດໄລ່ Progress bar ສຳລັບ Points Next Tier
$points = intval($user['points'] ?? 0);
$next_reward_target = 1500;
if ($points >= 1500) {
    $next_reward_target = 3000;
}
$points_needed = max(0, $next_reward_target - $points);
$progress_pct = min(100, max(5, round(($points / $next_reward_target) * 100)));

$tier_badge = 'bg-amber-50 text-amber-700 border-amber-300';
if ($user['tier'] === 'VIP') $tier_badge = 'bg-purple-50 text-purple-700 border-purple-300 font-bold';
if ($user['tier'] === 'Silver') $tier_badge = 'bg-slate-100 text-slate-700 border-slate-300';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('nav_account'); ?> - LaoFe & Beer Member Portal</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&family=Noto+Serif+Lao:wght@400;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        burgundy: {
                            50: '#FDF2F4',
                            100: '#FBE5E9',
                            200: '#F7CE89',
                            700: '#6B1D2F',  // Signature Burgundy
                            800: '#531321',  // Dark Burgundy
                            900: '#3D0B16',
                        },
                        gold: {
                            400: '#E5A024',
                            500: '#CD9947',
                            600: '#BE8510',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            background-color: #F8FAFC !important;
            color: #1E293B;
            font-family: 'Noto Sans Lao', 'Outfit', sans-serif !important;
        }
        .active-tab {
            background-color: #6B1D2F !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(107, 29, 47, 0.25) !important;
        }
        .active-tab svg {
            color: #FFFFFF !important;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col lg:flex-row bg-slate-50">

    <!-- 1. LEFT SIDEBAR NAVIGATION (Matching Admin Dark Sidebar) -->
    <aside id="member-sidebar" class="w-full lg:w-72 bg-gray-950 border-b lg:border-b-0 lg:border-r border-gray-800 shrink-0 flex flex-col justify-between p-6 select-none z-30 text-white">
        <div class="space-y-8">
            <!-- Brand Logo & Name -->
            <div class="flex items-center space-x-3">
                <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-10 h-10 object-contain rounded-full bg-white p-0.5 shadow-md">
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-wider text-white font-serif-lao">LaoFe & Beer</span>
                    <span class="text-[9px] font-bold text-gray-400 tracking-widest uppercase">
                        <?php echo $current_lang === 'lo' ? 'ສະໂມສອນສະມາຊິກ' : 'Member Portal'; ?>
                    </span>
                </div>
            </div>

            <!-- Welcome User Card -->
            <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-burgundy-700 text-white flex items-center justify-center font-bold text-sm shadow">
                    <?php echo mb_substr($user['fullname'] ?: $user['username'], 0, 1, 'UTF-8'); ?>
                </div>
                <div class="flex flex-col truncate">
                    <span class="text-[10px] text-gray-400 font-medium"><?php echo t('acc_welcome'); ?></span>
                    <span class="text-xs font-bold text-white truncate"><?php echo htmlspecialchars($user['fullname'] ?: $user['username']); ?></span>
                    <span class="text-[10px] font-bold text-amber-400 mt-0.5">
                        <?php echo htmlspecialchars($user['tier'] ?? 'Member'); ?> <?php echo $current_lang === 'lo' ? 'ສະມາຊິກ' : 'Member'; ?>
                    </span>
                </div>
            </div>

            <!-- Nav Tabs Menu -->
            <nav class="space-y-1.5">
                <button onclick="switchTab('dashboard')" id="nav-btn-dashboard" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all active-tab">
                    <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    <span><?php echo t('acc_tab_dashboard'); ?></span>
                </button>

                <button onclick="switchTab('reservations')" id="nav-btn-reservations" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span><?php echo t('acc_tab_reservations'); ?></span>
                </button>

                <button onclick="switchTab('orders')" id="nav-btn-orders" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?php echo t('acc_tab_orders'); ?></span>
                </button>

                <button onclick="switchTab('loyalty')" id="nav-btn-loyalty" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span><?php echo t('acc_tab_loyalty'); ?></span>
                </button>

                <button onclick="switchTab('settings')" id="nav-btn-settings" class="w-full flex items-center px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 hover:text-white transition-all">
                    <svg class="w-4 h-4 mr-3 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span><?php echo t('acc_tab_settings'); ?></span>
                </button>
            </nav>
        </div>

        <!-- Sidebar Bottom Actions -->
        <div class="space-y-2.5 pt-6 border-t border-white/10">
            <a href="menu.php" class="w-full inline-flex items-center justify-center py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs transition-all shadow-md">
                <?php echo t('acc_order_now_btn'); ?>
            </a>

            <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                <a href="admin/" class="w-full inline-flex items-center justify-center gap-1.5 py-2 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 rounded-xl text-xs font-bold transition-all border border-amber-500/30">
                    <span>⚙️ <?php echo $current_lang === 'lo' ? 'ລະບົບຈັດການຫຼັງບ້ານ' : 'Admin Panel'; ?></span>
                </a>
            <?php endif; ?>

            <a href="login.php?logout=true" class="w-full inline-flex items-center justify-center py-2 text-red-400 hover:text-white hover:bg-red-900/30 rounded-xl text-xs font-semibold transition-all">
                <?php echo t('acc_logout'); ?>
            </a>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT AREA (Clean Light Theme matching Admin Panel) -->
    <main class="flex-1 p-6 md:p-10 space-y-8 max-w-7xl mx-auto overflow-y-auto">
        
        <!-- Top Header Welcome Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 font-serif-lao tracking-tight">
                    <?php echo t('acc_welcome'); ?> <?php echo htmlspecialchars($user['fullname'] ?: $user['username']); ?>
                </h1>
                <p class="text-xs md:text-sm text-gray-500 mt-1 font-light">
                    <?php echo t('acc_subtitle'); ?>
                </p>
            </div>

            <!-- Top Right Member Status Badge & Language Switcher -->
            <div class="flex items-center space-x-3">
                <!-- Language Switcher Toggle -->
                <?php if ($current_lang === 'lo'): ?>
                    <a href="?lang=en" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm" title="Switch to English">
                        <svg class="w-4 h-3 rounded-sm shrink-0" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                            <clipPath id="s_uk_acc"><path d="M0 0v30h60V0z"/></clipPath>
                            <clipPath id="t_uk_acc"><path d="M30 15h30v15zM0 0h30v15zM30 15H0v15zM60 0H30v15z"/></clipPath>
                            <g clip-path="url(#s_uk_acc)">
                                <path d="M0 0v30h60V0z" fill="#012169"/>
                                <path d="M0 0l60 30m0-30L0 30" stroke="#fff" stroke-width="6"/>
                                <path d="M0 0l60 30m0-30L0 30" clip-path="url(#t_uk_acc)" stroke="#C8102E" stroke-width="4"/>
                                <path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/>
                                <path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/>
                            </g>
                        </svg>
                        <span>EN</span>
                    </a>
                <?php else: ?>
                    <a href="?lang=lo" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm" title="ປ່ຽນເປັນພາສາລາວ">
                        <svg class="w-4 h-3 rounded-sm shrink-0" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                            <rect width="600" height="400" fill="#CE1126"/>
                            <rect y="100" width="600" height="200" fill="#002868"/>
                            <circle cx="300" cy="200" r="80" fill="#FFFFFF"/>
                        </svg>
                        <span>LAO</span>
                    </a>
                <?php endif; ?>

                <span class="px-4 py-1.5 rounded-full border text-xs font-extrabold uppercase tracking-wider flex items-center gap-1.5 shadow-sm <?php echo $tier_badge; ?>">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span><?php echo htmlspecialchars($user['tier'] ?? 'Member'); ?> <?php echo $current_lang === 'lo' ? 'ສະມາຊິກ' : 'MEMBER'; ?></span>
                </span>

                <a href="index.php" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 transition-all" title="<?php echo $current_lang === 'lo' ? 'ກັບໄປໜ້າຫຼັກ' : 'Back to Home'; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
            </div>
        </div>

        <!-- Notification Alerts -->
        <?php if (!empty($profile_success)): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                <span>✅ <?php echo htmlspecialchars($profile_success); ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($profile_error)): ?>
            <div class="p-4 rounded-xl bg-red-50 border-l-4 border-red-500 text-red-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                <span>⚠️ <?php echo htmlspecialchars($profile_error); ?></span>
            </div>
        <?php endif; ?>

        <!-- TAB 1: DASHBOARD -->
        <div id="tab-dashboard" class="tab-content space-y-8">
            <!-- Row 1: Loyalty Rewards & Upcoming Reservation -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Loyalty Rewards Card (2 Columns wide) -->
                <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6 relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-burgundy-700 text-xs font-extrabold uppercase tracking-widest">
                            <span><?php echo t('acc_loyalty_title'); ?></span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-baseline space-x-2">
                            <span class="text-4xl md:text-5xl font-extrabold text-gray-900 font-serif-lao"><?php echo number_format($points); ?></span>
                            <span class="text-xl font-bold text-amber-600"><?php echo t('acc_pts'); ?></span>
                        </div>
                        <p class="text-xs text-gray-500 font-light">
                            <?php echo sprintf(t('acc_points_away'), number_format($points_needed)); ?>
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden p-0.5 border border-gray-200">
                            <div class="bg-gradient-to-r from-amber-500 to-burgundy-700 h-full rounded-full transition-all duration-500" style="width: <?php echo $progress_pct; ?>%;"></div>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            <span><?php echo t('acc_current_tier'); ?> <?php echo htmlspecialchars($user['tier'] ?? 'Member'); ?></span>
                            <span class="text-burgundy-700 font-bold"><?php echo t('acc_next_reward'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Reservation Card (1 Column wide) -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-burgundy-700 flex items-center gap-1.5">
                            <?php echo t('acc_upcoming_title'); ?>
                        </span>
                    </div>

                    <?php if ($upcoming_booking): ?>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <h4 class="text-base font-bold text-gray-900 font-serif-lao">
                                <?php echo htmlspecialchars($current_lang === 'lo' ? $upcoming_booking['branch_lo'] : $upcoming_booking['branch_en']); ?>
                            </h4>
                            <p class="text-xs text-amber-700 font-semibold">
                                📅 <?php echo date('d/m/Y', strtotime($upcoming_booking['booking_date'])); ?> ⏰ <?php echo date('H:i', strtotime($upcoming_booking['booking_time'])); ?>
                            </p>
                            <div class="flex items-center gap-4 text-xs text-gray-600 pt-1 border-t border-gray-200">
                                <span>👥 <?php echo $upcoming_booking['guest_count']; ?> <?php echo $current_lang === 'lo' ? 'ຄົນ' : 'People'; ?></span>
                                <span>🪑 <?php echo $current_lang === 'lo' ? 'ໂຊນ' : 'Zone'; ?>: <?php echo htmlspecialchars($upcoming_booking['table_zone']); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-6 text-center space-y-3 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <p class="text-xs text-gray-500"><?php echo t('acc_no_upcoming'); ?></p>
                            <a href="menu.php" class="inline-block px-4 py-2 bg-burgundy-700 text-white rounded-xl text-xs font-bold hover:bg-burgundy-800 transition-all shadow-sm">
                                <?php echo t('acc_book_now'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Row 2: Recent Orders & Quick Settings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Recent Orders Card -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <h3 class="text-base font-bold text-gray-900 font-serif-lao flex items-center gap-2">
                            <span><?php echo t('acc_recent_orders'); ?></span>
                        </h3>
                        <button onclick="switchTab('orders')" class="text-xs text-burgundy-700 hover:underline font-bold"><?php echo t('acc_view_all'); ?></button>
                    </div>

                    <?php if (empty($orders)): ?>
                        <p class="text-xs text-gray-400 text-center py-6"><?php echo t('acc_no_orders'); ?></p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach (array_slice($orders, 0, 3) as $ord): ?>
                                <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between hover:border-burgundy-700/30 transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center font-bold text-sm">
                                            ☕
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-900">Order #LFB-<?php echo str_pad($ord['id'], 5, '0', STR_PAD_LEFT); ?></h5>
                                            <span class="text-[10px] text-gray-500"><?php echo date('d/m/Y H:i', strtotime($ord['created_at'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-extrabold text-burgundy-700 block"><?php echo number_format($ord['total_amount']); ?> ₭</span>
                                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                                            <?php echo htmlspecialchars($ord['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Settings Card -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-5">
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-base font-bold text-gray-900 font-serif-lao flex items-center gap-2">
                            <span><?php echo t('acc_quick_settings'); ?></span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <button onclick="switchTab('settings')" class="p-4 rounded-xl bg-gray-50 hover:bg-burgundy-50 border border-gray-200 text-left space-y-2 group transition-all">
                            <span class="text-xl block group-hover:scale-110 transition-transform">👤</span>
                            <span class="text-xs font-bold text-gray-800 group-hover:text-burgundy-700 block"><?php echo t('acc_edit_profile'); ?></span>
                        </button>

                        <button onclick="switchTab('loyalty')" class="p-4 rounded-xl bg-gray-50 hover:bg-burgundy-50 border border-gray-200 text-left space-y-2 group transition-all">
                            <span class="text-xl block group-hover:scale-110 transition-transform">💳</span>
                            <span class="text-xs font-bold text-gray-800 group-hover:text-burgundy-700 block"><?php echo t('acc_loyalty_card'); ?></span>
                        </button>

                        <button onclick="switchTab('reservations')" class="p-4 rounded-xl bg-gray-50 hover:bg-burgundy-50 border border-gray-200 text-left space-y-2 group transition-all">
                            <span class="text-xl block group-hover:scale-110 transition-transform">📅</span>
                            <span class="text-xs font-bold text-gray-800 group-hover:text-burgundy-700 block"><?php echo t('acc_tab_reservations'); ?></span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Row 3: Crafting Moments Hero Showcase Banner -->
            <div class="relative rounded-2xl overflow-hidden min-h-[220px] flex items-center p-8 md:p-12 shadow-md border border-gray-200" style="background: linear-gradient(to right, rgba(0,0,0,0.85), rgba(0,0,0,0.4)), url('assets/images/hero_banner.png') center/cover no-repeat;">
                <div class="max-w-xl space-y-3 relative z-10 text-white">
                    <h2 class="text-3xl md:text-4xl font-extrabold font-serif-lao"><?php echo t('acc_crafting_title'); ?></h2>
                    <p class="text-xs md:text-sm text-gray-200 font-light leading-relaxed">
                        <?php echo t('acc_crafting_desc'); ?>
                    </p>
                    <div class="pt-2">
                        <a href="menu.php" class="inline-flex items-center px-6 py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs transition-all shadow-lg hover:scale-105">
                            <?php echo t('acc_explore_menu'); ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 2: RESERVATIONS -->
        <div id="tab-reservations" class="tab-content hidden space-y-6">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-900 font-serif-lao">📅 <?php echo t('acc_tab_reservations'); ?></h3>
                    <a href="menu.php" class="px-4 py-2 bg-burgundy-700 text-white font-bold rounded-xl text-xs shadow-sm"><?php echo t('acc_book_now'); ?></a>
                </div>

                <?php if (empty($bookings)): ?>
                    <p class="text-xs text-gray-400 text-center py-12"><?php echo t('acc_no_upcoming'); ?></p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600 divide-y divide-gray-100">
                            <thead class="bg-gray-50 text-gray-700 uppercase font-bold">
                                <tr>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ສາຂາ' : 'Branch'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ວັນທີ & ເວລາ' : 'Date & Time'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ຈຳນວນຄົນ' : 'Guests'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ມັດຈຳ' : 'Deposit'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ສະຖານະ' : 'Status'; ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($bookings as $b): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-bold text-gray-900"><?php echo htmlspecialchars($current_lang === 'lo' ? $b['branch_lo'] : $b['branch_en']); ?></td>
                                        <td class="px-4 py-3"><?php echo date('d/m/Y', strtotime($b['booking_date'])); ?> <?php echo date('H:i', strtotime($b['booking_time'])); ?></td>
                                        <td class="px-4 py-3">👥 <?php echo $b['guest_count']; ?> <?php echo $current_lang === 'lo' ? 'ຄົນ' : 'People'; ?></td>
                                        <td class="px-4 py-3 text-burgundy-700 font-bold"><?php echo number_format($b['deposit_amount']); ?> ₭</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <?php echo htmlspecialchars($b['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAB 3: ORDERS -->
        <div id="tab-orders" class="tab-content hidden space-y-6">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-bold text-gray-900 font-serif-lao">📜 <?php echo t('acc_tab_orders'); ?></h3>
                    <a href="menu.php" class="px-4 py-2 bg-burgundy-700 text-white font-bold rounded-xl text-xs shadow-sm"><?php echo t('acc_order_now_btn'); ?></a>
                </div>

                <?php if (empty($orders)): ?>
                    <p class="text-xs text-gray-400 text-center py-12"><?php echo t('acc_no_orders'); ?></p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600 divide-y divide-gray-100">
                            <thead class="bg-gray-50 text-gray-700 uppercase font-bold">
                                <tr>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ເລກບິນອໍເດີ້' : 'Order ID'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ວັນທີ' : 'Date'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ຍອດລວມ' : 'Total Amount'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ຄະແນນທີ່ໄດ້' : 'Points Earned'; ?></th>
                                    <th class="px-4 py-3"><?php echo $current_lang === 'lo' ? 'ສະຖານະ' : 'Status'; ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($orders as $ord): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-mono font-bold text-burgundy-700">#LFB-<?php echo str_pad($ord['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-4 py-3"><?php echo date('d/m/Y H:i', strtotime($ord['created_at'])); ?></td>
                                        <td class="px-4 py-3 font-bold text-gray-900"><?php echo number_format($ord['total_amount']); ?> ₭</td>
                                        <td class="px-4 py-3 text-emerald-600 font-bold">+<?php echo number_format($ord['points_earned']); ?> Pts</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <?php echo htmlspecialchars($ord['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAB 4: LOYALTY CLUB -->
        <div id="tab-loyalty" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- 3D Digital Membership Card -->
                <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('acc_digital_card'); ?></h3>
                    <div class="relative overflow-hidden rounded-2xl aspect-[1.58/1] bg-gradient-to-br from-burgundy-700 via-burgundy-800 to-black text-white p-6 shadow-xl flex flex-col justify-between border border-burgundy-200/30">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-sm font-serif-lao text-amber-300">LaoFe & Beer</span>
                            <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold text-amber-400 uppercase">
                                <?php echo htmlspecialchars($user['tier']); ?>
                            </span>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase text-gray-300"><?php echo $current_lang === 'lo' ? 'ຄະແນນສະສົມລວມ' : 'Total Points'; ?></p>
                            <p class="text-3xl font-extrabold text-white font-mono"><?php echo number_format($user['points']); ?> <span class="text-xs text-amber-300"><?php echo t('acc_pts'); ?></span></p>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <h4 class="text-xs font-bold text-white"><?php echo htmlspecialchars($user['fullname']); ?></h4>
                                <p class="text-[9px] text-gray-300 font-mono">ID: LFB-<?php echo str_pad($user['id'], 6, '0', STR_PAD_LEFT); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-white rounded p-1 flex items-center justify-center shadow">
                                <div class="grid grid-cols-3 gap-0.5 w-full h-full bg-gray-900"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tier Privileges -->
                <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('acc_tier_benefits'); ?></h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <span class="font-bold text-amber-700 block">🥉 <?php echo $current_lang === 'lo' ? 'ລະດັບ Member' : 'Member Tier'; ?></span>
                            <p class="text-gray-500">0 - 500 Points</p>
                            <p class="text-gray-700 text-[11px]"><?php echo t('acc_tier_member_desc'); ?></p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-2">
                            <span class="font-bold text-slate-700 block">🥈 <?php echo $current_lang === 'lo' ? 'ລະດັບ Silver' : 'Silver Tier'; ?></span>
                            <p class="text-gray-500">501 - 1,500 Points</p>
                            <p class="text-gray-700 text-[11px]"><?php echo t('acc_tier_silver_desc'); ?></p>
                        </div>
                        <div class="p-4 rounded-xl bg-burgundy-50 border border-burgundy-200 space-y-2">
                            <span class="font-bold text-burgundy-700 block">🥇 <?php echo $current_lang === 'lo' ? 'ລະດັບ Gold & VIP' : 'Gold / VIP Tier'; ?></span>
                            <p class="text-gray-500">1,501+ Points</p>
                            <p class="text-gray-700 text-[11px]"><?php echo t('acc_tier_gold_desc'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: SETTINGS -->
        <div id="tab-settings" class="tab-content hidden space-y-6">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 max-w-2xl mx-auto space-y-6">
                <h3 class="text-xl font-bold text-gray-900 font-serif-lao border-b border-gray-100 pb-4"><?php echo t('acc_edit_profile_title'); ?></h3>
                
                <form method="POST" action="account.php" class="space-y-4">
                    <input type="hidden" name="action" value="update_profile">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1"><?php echo t('acc_fullname'); ?></label>
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1"><?php echo t('acc_phone'); ?></label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1"><?php echo t('acc_email'); ?></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1"><?php echo t('acc_new_pass'); ?></label>
                        <input type="password" name="new_password" placeholder="••••••••" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs transition-all shadow-md">
                            <?php echo t('acc_save_btn'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FOOTER BAR -->
        <footer class="pt-12 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-burgundy-700 font-serif-lao">LAOFE & BEER</span>
                <span>© <?php echo date('Y'); ?> <?php echo t('footer_rights'); ?></span>
            </div>
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-gray-900"><?php echo t('footer_policy'); ?></a>
                <a href="#" class="hover:text-gray-900"><?php echo t('footer_terms'); ?></a>
                <a href="contact.php" class="hover:text-gray-900"><?php echo t('nav_contact'); ?></a>
            </div>
        </footer>

    </main>

    <!-- JS TAB SWITCHING -->
    <script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show target tab
        const target = document.getElementById('tab-' + tabId);
        if (target) target.classList.remove('hidden');

        // Reset nav buttons
        document.querySelectorAll('#member-sidebar nav button').forEach(btn => {
            btn.classList.remove('active-tab');
        });
        const activeBtn = document.getElementById('nav-btn-' + tabId);
        if (activeBtn) activeBtn.classList.add('active-tab');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
</body>
</html>
