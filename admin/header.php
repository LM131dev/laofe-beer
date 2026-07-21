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
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaoFe & Beer Admin Panel</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
        }
    </script>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Admin Navigation Bar -->
    <nav class="bg-gray-900 text-white border-b border-burgundy-700/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="dashboard.php" class="flex items-center space-x-2">
                        <img src="../assets/images/logo.png" alt="LaoFe Logo" class="w-8 h-8 object-contain rounded-full shadow-sm bg-cream-100 border border-burgundy-700/10">
                        <span class="text-lg font-bold tracking-wider text-burgundy-700">LaoFe</span>
                        <span class="text-gray-500">/</span>
                        <span class="text-xs font-semibold tracking-wider">ADMIN</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="dashboard.php" class="px-3 py-2 rounded-md text-sm font-medium hover:text-white transition-colors duration-200 <?php echo $admin_page === 'dashboard.php' ? 'bg-burgundy-700 text-white' : 'text-gray-300 hover:bg-gray-800'; ?>">
                        Dashboard
                    </a>
                    <a href="menu_manage.php" class="px-3 py-2 rounded-md text-sm font-medium hover:text-white transition-colors duration-200 <?php echo $admin_page === 'menu_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-300 hover:bg-gray-800'; ?>">
                        ຈັດການເມນູ
                    </a>
                    <a href="news_manage.php" class="px-3 py-2 rounded-md text-sm font-medium hover:text-white transition-colors duration-200 <?php echo $admin_page === 'news_manage.php' ? 'bg-burgundy-700 text-white' : 'text-gray-300 hover:bg-gray-800'; ?>">
                        ຈັດການຂ່າວສານ & ໂປຣ
                    </a>
                    <a href="applications.php" class="px-3 py-2 rounded-md text-sm font-medium hover:text-white transition-colors duration-200 <?php echo $admin_page === 'applications.php' ? 'bg-burgundy-700 text-white' : 'text-gray-300 hover:bg-gray-800'; ?>">
                        ໃບສະໝັກ & ຂໍ້ຄວາມ
                    </a>
                </div>

                <!-- Admin Action -->
                <div class="flex items-center space-x-4">
                    <span class="text-xs text-gray-400">ເຂົ້າໃຊ້ໂດຍ: <strong class="text-white"><?php echo htmlspecialchars($_SESSION['admin_user']); ?></strong></span>
                    <a href="../index.php" target="_blank" class="text-xs text-gray-300 hover:text-white underline">ເບິ່ງເວັບໄຊ</a>
                    <a href="logout.php" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-bold transition-all duration-200">
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu (visible below md) -->
        <div class="md:hidden bg-gray-800 flex justify-around py-2.5 text-xs">
            <a href="dashboard.php" class="<?php echo $admin_page === 'dashboard.php' ? 'text-burgundy-700 font-bold' : 'text-gray-300'; ?>">Dashboard</a>
            <a href="menu_manage.php" class="<?php echo $admin_page === 'menu_manage.php' ? 'text-burgundy-700 font-bold' : 'text-gray-300'; ?>">ຈັດການເມນູ</a>
            <a href="news_manage.php" class="<?php echo $admin_page === 'news_manage.php' ? 'text-burgundy-700 font-bold' : 'text-gray-300'; ?>">ຈັດການຂ່າວ & ໂປຣ</a>
            <a href="applications.php" class="<?php echo $admin_page === 'applications.php' ? 'text-burgundy-700 font-bold' : 'text-gray-300'; ?>">ໃບສະໝັກ</a>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl w-full mx-auto py-10 px-4 sm:px-6 lg:px-8">
