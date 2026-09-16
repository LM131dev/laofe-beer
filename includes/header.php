<?php
// includes/header.php
require_once __DIR__ . '/lang.php';
require_once __DIR__ . '/../config/db.php';

$current_page = basename($_SERVER['PHP_SELF']);
$standalone_pages = ['login.php', 'register.php', 'account.php', 'booking.php'];
$is_standalone = in_array($current_page, $standalone_pages);
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('brand_name'); ?> | <?php echo t('nav_' . str_replace('.php', '', $current_page === 'index.php' ? 'home' : str_replace('-', '', $current_page))); ?></title>
    
    <!-- Production Static Tailwind CSS (Pre-compiled, no CDN JS required) -->
    <link rel="stylesheet" href="assets/css/tailwind.min.css?v=1.0">
    
    <!-- Swiper Slider CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=1.0">

    <script>
        var isMobileMenuOpen = false;
        function toggleMobileMenu(show, event) {
            if (event) {
                if (event.stopPropagation) event.stopPropagation();
            }
            if (typeof show === 'undefined') {
                show = !isMobileMenuOpen;
            }
            isMobileMenuOpen = show;

            var overlay = document.getElementById('mobile-menu-overlay');
            var menu = document.getElementById('mobile-menu');
            if (!menu) return;

            if (show) {
                if (overlay) {
                    overlay.classList.remove('hidden');
                    overlay.style.display = 'block';
                }
                menu.classList.remove('hidden');
                menu.style.display = 'flex';
                menu.style.transform = 'none';
                document.body.style.overflow = 'hidden';
            } else {
                if (overlay) {
                    overlay.classList.add('hidden');
                    overlay.style.display = 'none';
                }
                menu.style.transform = 'translateX(100%)';
                document.body.style.overflow = '';
            }
        }

        function updateHeaderScroll() {
            var header = document.getElementById('main-header');
            if (!header) return;
            if (window.scrollY > 30) {
                header.classList.add('nav-scrolled');
                header.classList.remove('nav-at-top');
            } else {
                header.classList.add('nav-at-top');
                header.classList.remove('nav-scrolled');
            }
        }
        window.addEventListener('scroll', updateHeaderScroll);
        document.addEventListener('DOMContentLoaded', updateHeaderScroll);
    </script>
</head>
<body class="bg-white font-sans text-gray-800 antialiased selection:bg-burgundy-700 selection:text-white">

    <!-- Header (Luckin-inspired dynamic transparent top -> glass scroll header) -->
    <header id="main-header" class="fixed top-0 left-0 right-0 h-20 border-b border-transparent z-50 flex items-center justify-between px-6 md:px-12 nav-at-top <?php echo $is_standalone ? 'is-standalone-page' : ''; ?>">
        <!-- Logo -->
        <a href="index.php" class="flex items-center space-x-3 group">
            <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-12 h-12 object-contain rounded-full shadow-md bg-white border border-white/20 p-0.5 group-hover:scale-105 transition-transform duration-300">
            <div class="flex flex-col">
                <span class="logo-title text-xl font-extrabold tracking-wider font-sans-lao leading-none">LaoFe</span>
                <span class="logo-subtitle text-[10px] font-bold tracking-widest mt-0.5">CAFE & BAR</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center space-x-8">
            <a href="index.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'index.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_home'); ?>
            </a>
            <a href="our-story.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo ($current_page === 'our-story.php' || $current_page === 'about.php') ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_story'); ?>
            </a>
            <a href="menu.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'menu.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_menu'); ?>
            </a>
            <a href="locations.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'locations.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_locations'); ?>
            </a>
            <a href="news.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'news.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_news'); ?>
            </a>
            <a href="franchise.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'franchise.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_franchise'); ?>
            </a>
            <a href="contact.php" class="nav-link font-medium transition-colors duration-200 py-2 border-b-2 <?php echo $current_page === 'contact.php' ? 'active-link' : 'border-transparent'; ?>">
                <?php echo t('nav_contact'); ?>
            </a>
        </nav>

        <!-- Right Side: Lang Toggle & Mobile Menu Btn -->
        <div class="flex items-center space-x-4">
            <!-- Member Login/Account Button -->
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                <a href="account.php" class="nav-btn hidden md:inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full transition-all duration-300 text-xs font-bold shadow-sm" title="<?php echo t('nav_account'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span><?php echo htmlspecialchars($_SESSION['user_fullname'] ?? t('nav_account')); ?></span>
                </a>
            <?php else: ?>
                <a href="login.php" class="nav-btn hidden md:inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full transition-all duration-300 text-xs font-bold shadow-sm" title="<?php echo t('nav_login'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span><?php echo t('nav_login'); ?></span>
                </a>
            <?php endif; ?>

            <!-- Language Switcher with Flag Icon -->
            <?php if ($current_lang === 'lo'): ?>
                <a href="?lang=en" class="nav-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full transition-all duration-300 text-xs font-bold shadow-sm group" title="Switch to English">
                    <svg class="w-5 h-3.5 rounded-sm shadow-sm shrink-0" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                        <clipPath id="s_uk"><path d="M0 0v30h60V0z"/></clipPath>
                        <clipPath id="t_uk"><path d="M30 15h30v15zM0 0h30v15zM30 15H0v15zM60 0H30v15z"/></clipPath>
                        <g clip-path="url(#s_uk)">
                            <path d="M0 0v30h60V0z" fill="#012169"/>
                            <path d="M0 0l60 30m0-30L0 30" stroke="#fff" stroke-width="6"/>
                            <path d="M0 0l60 30m0-30L0 30" clip-path="url(#t_uk)" stroke="#C8102E" stroke-width="4"/>
                            <path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/>
                            <path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/>
                        </g>
                    </svg>
                    <span>EN</span>
                </a>
            <?php else: ?>
                <a href="?lang=lo" class="nav-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full transition-all duration-300 text-xs font-bold shadow-sm group" title="ປ່ຽນເປັນພາສາລາວ">
                    <svg class="w-5 h-3.5 rounded-sm shadow-sm shrink-0" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                        <rect width="600" height="400" fill="#CE1126"/>
                        <rect y="100" width="600" height="200" fill="#002868"/>
                        <circle cx="300" cy="200" r="80" fill="#FFFFFF"/>
                    </svg>
                    <span>LAO</span>
                </a>
            <?php endif; ?>

            <!-- Mobile Menu Trigger -->
            <button id="mobile-menu-btn" onclick="toggleMobileMenu(true, event)" class="lg:hidden p-2 text-gray-700 hover:text-burgundy-700 focus:outline-none" aria-label="Toggle mobile menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Luckin Style Mobile Menu Backdrop Overlay -->
    <div id="mobile-menu-overlay" style="display: none;" onclick="toggleMobileMenu(false, event)" class="hidden fixed inset-0 z-[998] bg-black/60 backdrop-blur-sm transition-opacity duration-300"></div>

    <!-- Luckin Style Mobile Navigation Side Drawer -->
    <div id="mobile-menu" style="transform: translateX(100%); transition: transform 0.3s ease-in-out;" class="fixed top-0 right-0 bottom-0 w-[85%] max-w-sm z-[999] bg-burgundy-700 text-white p-8 flex flex-col justify-between shadow-2xl overflow-y-auto">
        <div>
            <!-- Header with Brand Logo & Close Button -->
            <div class="flex justify-between items-center mb-10 pb-4 border-b border-white/15">
                <div class="flex items-center space-x-3">
                    <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-10 h-10 object-contain rounded-full bg-white p-0.5 shadow">
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-wider font-sans-lao text-white leading-none">LaoFe</span>
                        <span class="text-[9px] font-semibold text-white/70 tracking-widest mt-0.5">CAFE & BAR</span>
                    </div>
                </div>
                <button onclick="toggleMobileMenu(false)" class="p-2 text-white hover:text-white/80 focus:outline-none rounded-full hover:bg-white/10 transition-colors" aria-label="Close menu">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links (Luckin Style Left-Aligned White Typography) -->
            <nav class="flex flex-col space-y-6">
                <a href="index.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'index.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_home'); ?>
                </a>
                <a href="our-story.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo ($current_page === 'our-story.php' || $current_page === 'about.php') ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_story'); ?>
                </a>
                <a href="menu.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'menu.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_menu'); ?>
                </a>
                <a href="locations.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'locations.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_locations'); ?>
                </a>
                <a href="news.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'news.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_news'); ?>
                </a>
                <a href="franchise.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'franchise.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_franchise'); ?>
                </a>
                <!-- Hidden for Phase 1 - Uncomment to show when App launches
                <a href="app.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'app.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_app'); ?>
                </a>
                -->
                <a href="contact.php" onclick="toggleMobileMenu(false)" class="text-2xl font-medium tracking-wide text-white hover:text-white/80 transition-colors <?php echo $current_page === 'contact.php' ? 'font-bold border-b-2 border-white pb-1 w-max' : ''; ?>">
                    <?php echo t('nav_contact'); ?>
                </a>

                <!-- Account / Login for mobile drawer -->
                <div class="pt-6 border-t border-white/15 flex flex-col space-y-4">
                    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                        <div class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                        </div>
                        <a href="account.php" onclick="toggleMobileMenu(false)" class="text-sm font-medium text-white/80 hover:text-white transition-colors underline">
                            <?php echo t('nav_account'); ?>
                        </a>
                        <a href="login.php?logout=true" onclick="toggleMobileMenu(false)" class="text-sm font-medium text-red-300 hover:text-red-400 transition-colors">
                            <?php echo $current_lang === 'lo' ? 'ອອກຈາກລະບົບ' : 'Logout'; ?>
                        </a>
                    <?php else: ?>
                        <a href="login.php" onclick="toggleMobileMenu(false)" class="text-lg font-semibold text-white flex items-center gap-2 hover:text-white/85 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span><?php echo t('nav_login'); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>

        <!-- Language Switcher at Bottom -->
        <div class="pt-6 border-t border-white/15 mt-8">
            <?php if ($current_lang === 'lo'): ?>
                <a href="?lang=en" onclick="toggleMobileMenu(false)" class="inline-flex items-center gap-3 px-5 py-2.5 bg-white text-burgundy-700 rounded-full font-bold text-sm shadow-md hover:bg-gray-100 transition-all">
                    <svg class="w-6 h-4 rounded-sm shadow-sm shrink-0" viewBox="0 0 60 30" xmlns="http://www.w3.org/2000/svg">
                        <clipPath id="s_uk_drawer"><path d="M0 0v30h60V0z"/></clipPath>
                        <clipPath id="t_uk_drawer"><path d="M30 15h30v15zM0 0h30v15zM30 15H0v15zM60 0H30v15z"/></clipPath>
                        <g clip-path="url(#s_uk_drawer)">
                            <path d="M0 0v30h60V0z" fill="#012169"/>
                            <path d="M0 0l60 30m0-30L0 30" stroke="#fff" stroke-width="6"/>
                            <path d="M0 0l60 30m0-30L0 30" clip-path="url(#t_uk_drawer)" stroke="#C8102E" stroke-width="4"/>
                            <path d="M30 0v30M0 15h60" stroke="#fff" stroke-width="10"/>
                            <path d="M30 0v30M0 15h60" stroke="#C8102E" stroke-width="6"/>
                        </g>
                    </svg>
                    <span>English (EN)</span>
                </a>
            <?php else: ?>
                <a href="?lang=lo" onclick="toggleMobileMenu(false)" class="inline-flex items-center gap-3 px-5 py-2.5 bg-white text-burgundy-700 rounded-full font-bold text-sm shadow-md hover:bg-gray-100 transition-all">
                    <svg class="w-6 h-4 rounded-sm shadow-sm shrink-0" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                        <rect width="600" height="400" fill="#CE1126"/>
                        <rect y="100" width="600" height="200" fill="#002868"/>
                        <circle cx="300" cy="200" r="80" fill="#FFFFFF"/>
                    </svg>
                    <span>ພາສາລາວ (LAO)</span>
                </a>
            <?php endif; ?>
        </div>
    </div>


