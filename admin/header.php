<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ກວດສອບຄວາມປອດໄພ: ຫາກຍັງບໍ່ທັນເຂົ້າສູ່ລະບົບ ໃຫ້ເດັ້ງໄປໜ້າ login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/db.php';
$admin_page = basename($_SERVER['PHP_SELF']);

// CSRF Protection Token Initialization
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verify_csrf_token() {
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaoFe & Beer Admin Panel</title>
    <!-- Google Fonts Lao -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&family=Noto+Serif+Lao:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Noto Serif Lao', serif !important;
        }
        body, input, button, select, textarea, label, div, p, span, h1, h2, h3, h4, h5, h6, a, .font-sans, .font-serif, .font-serif-lao, .font-sans-lao {
            font-family: 'Noto Serif Lao', serif !important;
        }
    </style>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Noto Serif Lao', 'serif'],
                        serif: ['Noto Serif Lao', 'serif'],
                        'serif-lao': ['Noto Serif Lao', 'serif'],
                        'sans-lao': ['Noto Serif Lao', 'serif'],
                    },
                    colors: {
                        burgundy: {
                            50: '#FFFFFF',   // Pure White
                            100: '#F8FAFC',  // Very soft gray
                            200: '#FFFFFF',  // Changed from gold to pure white
                            600: '#6A182F',  // Unified to primary burgundy
                            700: '#6A182F',  // Unified to primary burgundy
                            800: '#6A182F',  // Unified to primary burgundy
                            900: '#6A182F',  // Unified to primary burgundy
                        },
                        gold: {
                            100: '#FFFFFF',
                            200: '#FFFFFF',
                            300: '#FFFFFF',
                            400: '#FFFFFF',
                            500: '#6A182F',
                            600: '#6A182F',  // Changed to primary burgundy
                            700: '#4D0E1E',
                            800: '#340713',
                        },
                        cream: {
                            50: '#FFFFFF',   // White
                            100: '#FFFFFF',  // White
                            200: '#F8FAFC',  // Very soft gray
                            300: '#E2E8F0',  // Muted gray
                        }
                    }
                }
            }
        };

        function toggleMobileSidebar(isOpen) {
            const sidebar = document.getElementById('mobile-sidebar');
            if (!sidebar) return;
            if (isOpen) {
                sidebar.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    sidebar.querySelector('.sidebar-container').classList.remove('-translate-x-full');
                }, 10);
            } else {
                sidebar.querySelector('.sidebar-container').classList.add('-translate-x-full');
                document.body.style.overflow = '';
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                }, 300);
            }
        }
    </script>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">

    <!-- 1. LEFT SIDEBAR (Desktop) -->
    <aside class="hidden lg:flex flex-col w-64 bg-gray-950 text-white border-r border-burgundy-700/20 fixed inset-y-0 left-0 z-50">
        <!-- Logo Section -->
        <div class="h-16 flex items-center px-6 border-b border-white/5 space-x-3 select-none">
            <img src="../assets/images/logo.png" alt="LaoFe Logo" class="w-8 h-8 object-contain rounded-full bg-white p-0.5 shadow-md">
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-wider font-serif-lao text-white leading-none">LaoFe & Beer</span>
                <span class="text-[8px] font-bold text-yellow-500 tracking-wider uppercase mt-0.5">Admin Portal</span>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'dashboard.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="menu_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'menu_manage.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>ຈັດການເມນູ</span>
            </a>
            <a href="orders.php" class="flex items-center justify-between px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'orders.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>ຈັດການອໍເດີ້</span>
                </div>
                <span id="sidebar-pending-badge" class="ml-auto bg-red-600 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full hidden">0</span>
            </a>
            <a href="kitchen.php" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-lg text-xs font-semibold text-yellow-500 hover:bg-white/5 hover:text-white transition-all duration-200">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 text-yellow-500 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>ຫ້ອງຄົວ 🖥️</span>
                </div>
                <span id="sidebar-paid-badge" class="ml-auto bg-amber-500 text-burgundy-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full hidden">0</span>
            </a>
            <a href="bookings.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'bookings.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>ຈອງໂຕະ</span>
            </a>
            <a href="branch_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'branch_manage.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>ຈັດການສາຂາ</span>
            </a>
            <a href="news_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'news_manage.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span>ບົດຄວາມ & ກິດຈະກຳ</span>
            </a>
            <a href="banner_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'banner_manage.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>ຈັດການ Banner</span>
            </a>
            <a href="gallery_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'gallery_manage.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>ຈັດການ Gallery</span>
            </a>
            <a href="members.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'members.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>ຈັດການສະມາຊິກ</span>
            </a>
            <a href="applications.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'applications.php' ? 'bg-burgundy-700 text-white shadow-sm' : 'text-gray-300 hover:bg-white/5 hover:text-white'; ?>">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>ໃບສະໝັກ</span>
            </a>
        </nav>

        <!-- Admin user profile & Logout Section -->
        <div class="p-4 border-t border-white/5 space-y-3 bg-gray-950">
            <div class="flex items-center space-x-3 bg-white/5 p-2.5 rounded-lg border border-white/5">
                <div class="w-8 h-8 rounded-full bg-burgundy-700 flex items-center justify-center text-xs font-bold uppercase text-white font-mono shadow-sm">
                    <?php echo substr($_SESSION['admin_user'], 0, 1); ?>
                </div>
                <div class="flex flex-col truncate">
                    <span class="text-xs font-semibold text-gray-200 truncate"><?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
                    <span class="text-[9px] text-gray-500 font-medium">Administrator</span>
                </div>
            </div>
            <a href="logout.php" class="flex items-center justify-center space-x-1.5 w-full py-2 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs font-bold transition-all border border-red-600/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- 2. DRAWER SIDEBAR (Mobile) -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 lg:hidden hidden">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity duration-300" onclick="toggleMobileSidebar(false)"></div>
        <!-- Drawer Panel -->
        <div class="sidebar-container fixed inset-y-0 left-0 flex flex-col w-64 bg-gray-950 text-white border-r border-burgundy-700/20 transform -translate-x-full transition-transform duration-300 ease-out z-50">
            <!-- Mobile Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/5">
                <div class="flex items-center space-x-2">
                    <img src="../assets/images/logo.png" alt="LaoFe Logo" class="w-8 h-8 object-contain rounded-full bg-white p-0.5">
                    <span class="text-sm font-bold tracking-wider font-serif-lao text-white">LaoFe & Beer</span>
                </div>
                <button onclick="toggleMobileSidebar(false)" class="p-1 rounded-md hover:bg-white/5 text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Mobile Links -->
            <nav class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
                <a href="dashboard.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'dashboard.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>Dashboard</span>
                </a>
                <a href="menu_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'menu_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈັດການເມນູ</span>
                </a>
                <a href="orders.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'orders.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈັດການອໍເດີ້</span>
                </a>
                <a href="kitchen.php" target="_blank" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold text-yellow-500 hover:bg-white/5">
                    <span>ຫ້ອງຄົວ 🖥️</span>
                </a>
                <a href="bookings.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'bookings.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈອງໂຕະ</span>
                </a>
                <a href="branch_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'branch_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈັດການສາຂາ</span>
                </a>
                <a href="news_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'news_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ບົດຄວາມ & ກິດຈະກຳ</span>
                </a>
                <a href="banner_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'banner_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈັດການ Banner</span>
                </a>
                <a href="gallery_manage.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'gallery_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ຈັດການ Gallery</span>
                </a>
                <a href="applications.php" class="flex items-center px-4 py-2.5 rounded-lg text-xs font-semibold transition-all duration-200 <?php echo $admin_page === 'applications.php' ? 'bg-burgundy-700 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'; ?>">
                    <span>ໃບສະໝັກ</span>
                </a>
            </nav>
            
            <!-- Mobile User Profile -->
            <div class="p-4 border-t border-white/5 space-y-3 bg-gray-950">
                <div class="flex items-center space-x-3 bg-white/5 p-2 rounded-lg border border-white/5">
                    <div class="w-8 h-8 rounded-full bg-burgundy-700 flex items-center justify-center text-xs font-bold uppercase text-white font-mono shadow">
                        <?php echo substr($_SESSION['admin_user'], 0, 1); ?>
                    </div>
                    <div class="flex flex-col truncate">
                        <span class="text-xs font-semibold text-gray-200 truncate"><?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
                    </div>
                </div>
                <a href="logout.php" class="flex items-center justify-center space-x-1.5 w-full py-2 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs font-bold transition-all border border-red-600/20">
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. TOP NAV HEADER (Desktop & Mobile view title bar) -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 fixed top-0 left-0 lg:left-64 right-0 z-40 select-none">
        <div class="flex items-center space-x-3">
            <!-- Mobile Hamburger Toggle button -->
            <button onclick="toggleMobileSidebar(true)" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="text-sm font-bold text-gray-800 font-serif-lao tracking-wider">
                <?php 
                    $pages_names = [
                        'dashboard.php' => 'Dashboard',
                        'menu_manage.php' => 'ຈັດການເມນູອາຫານ & ເຄື່ອງດື່ມ',
                        'orders.php' => 'ຈັດການອໍເດີ້ສັ່ງຊື້',
                        'bookings.php' => 'ຈັດການການຈອງໂຕະ',
                        'news_manage.php' => 'ຈັດການບົດຄວາມ, ຂ່າວສານ & ກິດຈະກຳ',
                        'banner_manage.php' => 'ຈັດການ Hero Banners',
                        'members.php' => 'ຈັດການສະມາຊິກ (Customer Members)',
                        'applications.php' => 'ຈັດການໃບສະໝັກແຟຣນໄຊສ໌'
                    ];
                    echo $pages_names[$admin_page] ?? 'ລະບົບຫຼັງບ້ານ';
                ?>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Sound Alert Toggle Button -->
            <button id="admin-sound-toggle-btn" onclick="toggleAdminSound()" class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-burgundy-900 border border-amber-300 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm" title="ເປີດ/ປິດ ສຽງແຈ້ງເຕືອນອໍເດີ້">
                <span id="sound-icon" class="text-sm">🔔</span>
                <span id="sound-status-text" class="hidden sm:inline">ສຽງແຈ້ງເຕືອນ: ເປີດ</span>
            </button>

            <!-- Quick View Site -->
            <a href="../index.php" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold transition-all border border-gray-200" title="ເບິ່ງເວັບໄຊ້">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <span>ເບິ່ງເວັບໄຊ</span>
            </a>
        </div>
    </header>

    <!-- Admin Real-time Notification Toast Container -->
    <div id="admin-toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 max-w-sm pointer-events-none font-serif-lao"></div>

    <script>
    let adminSoundEnabled = true;
    let lastMaxOrderId = 0;
    let isFirstOrderCheck = true;

    // Web Audio API Order Chime Generator (2-Tone Pleasant Bell)
    function playOrderChimeSound() {
        if (!adminSoundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();

            const playTone = (freq, startTime, duration) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, startTime);

                gain.gain.setValueAtTime(0.3, startTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(startTime);
                osc.stop(startTime + duration);
            };

            const now = ctx.currentTime;
            playTone(880, now, 0.4);        // A5 tone
            playTone(1046.5, now + 0.25, 0.6); // C6 tone
        } catch(e) {
            console.error(e);
        }
    }

    function toggleAdminSound() {
        adminSoundEnabled = !adminSoundEnabled;
        const btn = document.getElementById('admin-sound-toggle-btn');
        const statusText = document.getElementById('sound-status-text');
        const icon = document.getElementById('sound-icon');
        if (adminSoundEnabled) {
            btn.classList.replace('bg-gray-100', 'bg-amber-50');
            statusText.innerText = 'ສຽງແຈ້ງເຕືອນ: ເປີດ';
            icon.innerText = '🔔';
            playOrderChimeSound();
        } else {
            btn.classList.replace('bg-amber-50', 'bg-gray-100');
            statusText.innerText = 'ສຽງແຈ້ງເຕືອນ: ປິດ';
            icon.innerText = '🔕';
        }
    }

    function showOrderNotificationToast(order) {
        const container = document.getElementById('admin-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto bg-gray-950/95 border-2 border-amber-400 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between gap-3 font-serif-lao transform transition-all duration-500 translate-y-4 opacity-0';
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-400/20 border border-amber-400/50 flex items-center justify-center shrink-0 text-xl animate-pulse">
                    🔔
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-amber-300 uppercase tracking-wider">ອໍເດີ້ໃໝ່ເຂົ້າມາ!</span>
                        <span class="text-[10px] bg-amber-400 text-burgundy-950 font-bold px-1.5 py-0.5 rounded font-mono">${order.formatted_id}</span>
                    </div>
                    <div class="text-xs font-bold text-white mt-1">
                        ${order.table_text} | ${order.guest_name}
                    </div>
                    <div class="text-xs text-amber-200 font-mono font-bold mt-0.5">
                        ຍອດລວມ: ${order.formatted_amount}
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1.5 shrink-0">
                <a href="orders.php" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-burgundy-950 rounded-lg text-xs font-black transition-all shadow text-center">
                    ເບິ່ງອໍເດີ້
                </a>
                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-white text-xs text-center">&times;</button>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
        }, 50);

        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 9000);
    }

    function checkAdminNewOrders() {
        fetch('check_new_orders.php?last_id=' + lastMaxOrderId)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update badges in sidebar
                    const pendingBadge = document.getElementById('sidebar-pending-badge');
                    if (pendingBadge) {
                        if (data.pending_count > 0) {
                            pendingBadge.innerText = data.pending_count;
                            pendingBadge.classList.remove('hidden');
                        } else {
                            pendingBadge.classList.add('hidden');
                        }
                    }

                    const paidBadge = document.getElementById('sidebar-paid-badge');
                    if (paidBadge) {
                        if (data.paid_count > 0) {
                            paidBadge.innerText = data.paid_count;
                            paidBadge.classList.remove('hidden');
                        } else {
                            paidBadge.classList.add('hidden');
                        }
                    }

                    if (!isFirstOrderCheck && data.has_new && data.new_orders) {
                        playOrderChimeSound();
                        data.new_orders.forEach(order => {
                            showOrderNotificationToast(order);
                        });
                    }

                    lastMaxOrderId = data.max_id;
                    isFirstOrderCheck = false;
                }
            })
            .catch(err => console.error(err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkAdminNewOrders();
        setInterval(checkAdminNewOrders, 4000);
    });
    </script>

    <!-- 4. MAIN CONTENT AREA -->
    <main class="flex-grow lg:ml-64 pt-24 px-4 pb-4 sm:px-6 sm:pb-6 lg:px-8 lg:pb-8 min-h-screen">
