<?php
// app.php
require_once __DIR__ . '/includes/lang.php';
$current_page = 'app.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Title Header Section -->
<section class="py-16 bg-burgundy-700 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 space-y-2">
        <h1 class="text-4xl md:text-5xl font-bold font-serif-lao"><?php echo t('app_title'); ?></h1>
        <p class="text-burgundy-200 font-light"><?php echo t('app_sub'); ?></p>
    </div>
</section>

<!-- Main Mockup & Info Section -->
<section class="py-20 bg-gray-50 px-6 md:px-12 overflow-hidden">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        
        <!-- Left Column: Interactive CSS Smartphone Mockup -->
        <div class="flex justify-center items-center relative">
            <!-- Background Decorative Glow -->
            <div class="absolute w-72 h-72 bg-burgundy-700/10 rounded-full blur-3xl -z-10"></div>
            
            <!-- Smartphone Outer Frame -->
            <div class="relative w-[310px] h-[620px] bg-gray-900 rounded-[45px] p-3 shadow-2xl border-4 border-gray-800">
                <!-- Notch/Dynamic Island -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2 w-32 h-6 bg-black rounded-full z-30 flex items-center justify-center">
                    <div class="w-3 h-3 bg-gray-900 rounded-full mr-2"></div>
                    <div class="w-1.5 h-1.5 bg-gray-900 rounded-full"></div>
                </div>

                <!-- Side Buttons -->
                <div class="absolute -left-[6px] top-24 w-[3px] h-10 bg-gray-800 rounded-r"></div>
                <div class="absolute -left-[6px] top-38 w-[3px] h-14 bg-gray-800 rounded-r"></div>
                <div class="absolute -left-[6px] top-56 w-[3px] h-14 bg-gray-800 rounded-r"></div>
                <div class="absolute -right-[6px] top-32 w-[3px] h-20 bg-gray-800 rounded-l"></div>

                <!-- Screen Container -->
                <div class="relative w-full h-full bg-white rounded-[35px] overflow-hidden border border-gray-950 flex flex-col justify-between">
                    <!-- App Inner Header -->
                    <div class="bg-burgundy-700 text-white pt-8 pb-4 px-4 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <img src="assets/images/logo.png" alt="LaoFe Logo" class="w-8 h-8 rounded-full bg-white p-0.5">
                            <span class="font-serif-lao text-sm font-bold tracking-wider">LaoFe</span>
                        </div>
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-white/80">Online</span>
                        </div>
                    </div>

                    <!-- Screen Content Scrollable Area -->
                    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-4">
                        <!-- Welcome Card -->
                        <div class="bg-burgundy-50 rounded-2xl p-4 border border-burgundy-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-burgundy-900 font-bold text-sm">ສະບາຍດີ! (Sabaidee)</h4>
                                <p class="text-burgundy-700 text-xs mt-0.5">ຄະແນນສະສົມ: 0 Pt</p>
                            </div>
                            <span class="px-2.5 py-1 bg-burgundy-700 text-white rounded-full text-[10px] font-bold">Gold Member</span>
                        </div>

                        <!-- Banner Slider -->
                        <div class="relative h-28 rounded-2xl bg-burgundy-800 text-white overflow-hidden shadow-inner flex flex-col justify-end p-3">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10"></div>
                            <!-- Mock coffee background image representation -->
                            <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('assets/images/story-1.jpg');"></div>
                            <div class="relative z-20">
                                <span class="px-2 py-0.5 bg-yellow-500 text-burgundy-950 rounded text-[9px] font-bold uppercase tracking-wider">Promo</span>
                                <h5 class="font-bold text-xs mt-1">Buy 5 Get 1 Free!</h5>
                                <p class="text-[9px] text-white/80 mt-0.5">ສະເພາະການສັ່ງຊື້ຜ່ານແອັບເທົ່ານັ້ນ</p>
                            </div>
                        </div>

                        <!-- Category Selector -->
                        <div class="flex space-x-2 overflow-x-auto pb-1 text-[11px] font-bold">
                            <span class="px-3 py-1.5 bg-burgundy-700 text-white rounded-full shrink-0">Coffee (ກາເຟ)</span>
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-full shrink-0">Mocktails</span>
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-full shrink-0">Beer Tap</span>
                        </div>

                        <!-- Item Grid Mockup -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Item 1 -->
                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
                                <div class="h-20 bg-cover bg-center" style="background-image: url('assets/images/hero-bg.jpg');"></div>
                                <div class="p-2 space-y-1">
                                    <h6 class="font-bold text-[10px] text-gray-800 truncate">Lao Latte Premium</h6>
                                    <div class="flex justify-between items-center">
                                        <span class="text-burgundy-700 text-[10px] font-extrabold">28,000 LAK</span>
                                        <span class="w-5 h-5 rounded-full bg-burgundy-700 text-white flex items-center justify-center text-xs font-bold font-mono">+</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Item 2 -->
                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
                                <div class="h-20 bg-cover bg-center" style="background-image: url('assets/images/story-2.jpg');"></div>
                                <div class="p-2 space-y-1">
                                    <h6 class="font-bold text-[10px] text-gray-800 truncate">Espresso Bolaven</h6>
                                    <div class="flex justify-between items-center">
                                        <span class="text-burgundy-700 text-[10px] font-extrabold">22,000 LAK</span>
                                        <span class="w-5 h-5 rounded-full bg-burgundy-700 text-white flex items-center justify-center text-xs font-bold font-mono">+</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- App Floating Order Action Bar -->
                    <div class="p-3 border-t border-gray-100 bg-white flex justify-between items-center">
                        <div>
                            <p class="text-[9px] text-gray-400">1 Item Selected</p>
                            <p class="text-sm font-extrabold text-burgundy-700">28,000 LAK</p>
                        </div>
                        <button class="px-4 py-2 bg-burgundy-700 text-white rounded-xl text-xs font-bold shadow-md hover:bg-burgundy-800">
                            Order Now (ສັ່ງເລີຍ)
                        </button>
                    </div>

                    <!-- App Bottom Navigation Bar -->
                    <div class="bg-gray-50 border-t border-gray-100 py-2 px-6 flex justify-between items-center text-[9px] text-gray-400">
                        <div class="flex flex-col items-center text-burgundy-700 font-bold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                            <span>Home</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Menu</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Cart</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Account</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Description & Download Buttons -->
        <div class="space-y-8">
            <div class="space-y-4">
                <span class="inline-block px-4 py-1 bg-yellow-500/10 text-yellow-600 border border-yellow-500/20 text-xs font-bold rounded-full uppercase tracking-wider font-mono">
                    ✨ <?php echo t('app_coming_soon'); ?>
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-serif-lao leading-tight">
                    <?php echo t('app_title'); ?>
                </h2>
                <p class="text-gray-600 font-light leading-relaxed">
                    <?php echo t('app_desc'); ?>
                </p>
            </div>

            <!-- Fake Download Buttons with Glowing Hover -->
            <div class="flex flex-wrap gap-4 pt-2">
                <!-- App Store -->
                <a href="#" onclick="alert('LaoFe app will be available on the App Store soon! ພົບກັນໄວໆນີ້!'); return false;" class="flex items-center space-x-3 px-6 py-3.5 bg-gray-900 text-white rounded-2xl hover:bg-burgundy-900 transition-all duration-300 shadow-lg group border border-white/5">
                    <svg class="w-7 h-7 text-white fill-current group-hover:scale-105 transition-transform" viewBox="0 0 24 24">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.82M15.97 4.17c.66-.81 1.11-1.93.99-3.06-1 .04-2.2.67-2.92 1.5-.62.71-1.16 1.85-1.01 2.96 1.12.09 2.27-.59 2.94-1.4z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[9px] uppercase text-white/50 tracking-wider">Download on the</p>
                        <p class="text-sm font-bold -mt-0.5">App Store</p>
                    </div>
                </a>

                <!-- Google Play -->
                <a href="#" onclick="alert('LaoFe app will be available on the Google Play Store soon! ພົບກັນໄວໆນີ້!'); return false;" class="flex items-center space-x-3 px-6 py-3.5 bg-gray-900 text-white rounded-2xl hover:bg-burgundy-900 transition-all duration-300 shadow-lg group border border-white/5">
                    <svg class="w-7 h-7 text-white fill-current group-hover:scale-105 transition-transform" viewBox="0 0 24 24">
                        <path d="M5 3.007c0-.12.01-.24.03-.36L12.59 10.2l7.56-7.56c-.02.12-.03.24-.03.36v17.986c0 .12.01.24.03.36l-7.56-7.56-7.56 7.56c-.02-.12-.03-.24-.03-.36V3.007zm.81-.81l12.78 6.39L12.59 10.2 5.81 2.197zM20.19 2.197l-6.39 12.78 6.39-6.39V2.197zm.81.81v17.986L15 12 21 3.007z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[9px] uppercase text-white/50 tracking-wider">Get it on</p>
                        <p class="text-sm font-bold -mt-0.5">Google Play</p>
                    </div>
                </a>
            </div>

            <!-- Interactive "Notify Me" Form -->
            <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm max-w-md">
                <h4 class="font-bold text-gray-800 text-sm mb-2">ຮັບການແຈ້ງເຕືອນເມື່ອແອັບເປີດໃຫ້ບໍລິການ</h4>
                <div class="flex space-x-2">
                    <input type="email" id="notify-email" placeholder="ອີເມລຂອງທ່ານ (Email Address)" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-burgundy-700">
                    <button onclick="submitNotification()" class="px-5 py-2 bg-burgundy-700 text-white rounded-xl text-sm font-bold hover:bg-burgundy-800 transition-colors">
                        ສົ່ງຂໍ້ມູນ
                    </button>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Detailed App Features Section -->
<section class="py-24 bg-white border-t border-gray-100 px-6 md:px-12">
    <div class="max-w-6xl mx-auto space-y-16">
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <span class="text-xs font-bold text-burgundy-700 uppercase tracking-widest">LaoFe Application Features</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-serif-lao">ແອັບດຽວຄົບທຸກບໍລິການ</h2>
            <p class="text-gray-500 font-light">ຄົ້ນພົບຟີເຈີຫຼັກທີ່ຈະມາຊ່ວຍເພີ່ມຄວາມສະດວກສະບາຍໃຫ້ກັບປະສົບການກາເຟ ແລະ ບາຂອງທ່ານ</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Feature 1 -->
            <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-5">
                <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('app_feature_1_title'); ?></h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed"><?php echo t('app_feature_1_desc'); ?></p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-5">
                <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7.463 8.53c0-.662.538-1.2 1.2-1.2H12m-6.263 2.4a1.2 1.2 0 110-2.4c.662 0 1.2.538 1.2 1.2v1.2H5.737z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('app_feature_2_title'); ?></h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed"><?php echo t('app_feature_2_desc'); ?></p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-5">
                <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('app_feature_3_title'); ?></h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed"><?php echo t('app_feature_3_desc'); ?></p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-start space-x-5">
                <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-bold text-gray-900 font-serif-lao"><?php echo t('app_feature_4_title'); ?></h3>
                    <p class="text-sm text-gray-600 font-light leading-relaxed"><?php echo t('app_feature_4_desc'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function submitNotification() {
        var emailInput = document.getElementById('notify-email');
        if (emailInput && emailInput.value.trim() !== '') {
            alert('ຂອບໃຈສໍາລັບການລົງທະບຽນ! ລະບົບຈະແຈ້ງເຕືອນຫາອີເມລ ' + emailInput.value + ' ເມື່ອແອັບເປີດຕົວຢ່າງເປັນທາງການ.');
            emailInput.value = '';
        } else {
            alert('ກະລຸນາກອກອີເມລຂອງທ່ານກ່ອນ.');
        }
    }
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
