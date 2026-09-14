<?php
// our-story.php - Comprehensive Master Our Story & About Page (100% Bilingual Lao & English)
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Section 1: Hero Banner (Luckin Our Story full-screen video background layout) -->
<section class="relative w-full h-[75vh] md:h-[85vh] flex items-center justify-center overflow-hidden bg-burgundy-700">
    <!-- Autoplaying looping video -->
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="https://ilucky-fe-outside-oss-prod.luckincdn.com/iadmin/ab6140f6-129c-4ae9-aaa0-20190c43183b.mp4" type="video/mp4"/>
        <source src="https://web.luckincdn.com/default/assets/ourstory-41023fc9.webm" type="video/webm"/>
    </video>
    
    <!-- Dark & Burgundy Overlay to ensure perfect text contrast -->
    <div class="absolute inset-0 bg-gradient-to-t from-burgundy-700/90 via-black/40 to-black/60"></div>
    
    <!-- Video Content Overlay -->
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white space-y-6">
        <div class="space-y-2">
            <h1 class="text-4xl md:text-7xl font-extrabold tracking-tight font-serif-lao leading-none text-white drop-shadow-lg">
                <?php echo t('story_hero_title1'); ?>
            </h1>
            <h2 class="text-3xl md:text-6xl font-bold tracking-tight font-serif-lao text-white/90 drop-shadow-md">
                <?php echo t('story_hero_title2'); ?>
            </h2>
        </div>
        
        <p class="text-base md:text-xl text-gray-200 font-light max-w-2xl mx-auto leading-relaxed drop-shadow">
            <?php echo t('story_hero_desc'); ?>
        </p>

        <div class="pt-4">
            <a href="menu.php" class="inline-flex items-center justify-center px-8 py-4 bg-white text-burgundy-700 hover:bg-burgundy-700 hover:text-white rounded-full font-bold text-sm md:text-base transition-all duration-300 shadow-2xl hover:scale-105 border-2 border-white font-serif-lao">
                <?php echo t('story_hero_cta'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Section 2: Content Description (Ultra-Premium Bakery/Cafe Hybrid Story Cards) -->
<section class="py-20 bg-[#EFEADF] px-6 md:px-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 items-stretch">
        
        <!-- Story Card 1 -->
        <div class="reference-story-card p-8 md:p-12 flex flex-col justify-between group">
            <!-- Top visual photo banner -->
            <div class="-mx-8 -mt-8 md:-mx-12 md:-mt-12 mb-6 h-52 relative overflow-hidden shrink-0">
                <img src="assets/images/hero_banner.png" alt="Bolaven Coffee Story" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-[#FAF7F2] via-[#FAF7F2]/40 to-transparent"></div>
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 text-xs font-bold font-serif-lao tracking-wider shadow-md">
                        ✦ <?php echo $current_lang === 'lo' ? 'ເລື່ອງລາວຕົ້ນກຳເນີດ' : 'ORIGIN STORY'; ?> ✦
                    </span>
                </div>
                <div class="absolute top-4 right-4 z-10">
                    <span class="text-4xl font-serif-lao font-bold text-amber-500/80 drop-shadow">01</span>
                </div>
            </div>

            <div class="space-y-6 flex-grow flex flex-col justify-between">
                <div class="space-y-3">
                    <h3 class="text-2xl md:text-3xl font-extrabold text-[#2C1810] font-serif-lao leading-tight">
                        <?php echo t('story_title'); ?>
                    </h3>
                    <p class="text-sm md:text-base text-[#6E584E] font-light leading-relaxed font-serif-lao">
                        <?php echo t('story_p1'); ?>
                    </p>
                </div>
                
                <div class="pt-5 border-t border-[#EBE4D8] flex items-center justify-between text-xs text-amber-900/80 font-bold font-serif-lao">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        <?php echo $current_lang === 'lo' ? 'ສ້າງຕັ້ງ 2021 • ພູພຽງບໍລະເວນ' : 'EST. 2021 • BOLAVEN PLATEAU'; ?>
                    </span>
                    <span class="w-12 h-0.5 bg-amber-500/60"></span>
                </div>
            </div>
        </div>

        <!-- Story Card 2 -->
        <div class="reference-story-card p-8 md:p-12 flex flex-col justify-between group">
            <!-- Top visual photo banner -->
            <div class="-mx-8 -mt-8 md:-mx-12 md:-mt-12 mb-6 h-52 relative overflow-hidden shrink-0">
                <img src="assets/images/our_story.png" alt="LaoFe Vision" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-[#FAF7F2] via-[#FAF7F2]/40 to-transparent"></div>
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#531321]/90 backdrop-blur-md border border-amber-400/40 text-amber-200 text-xs font-bold font-serif-lao tracking-wider shadow-md">
                        ✦ <?php echo $current_lang === 'lo' ? 'ວິໄສທັດຂອງເຮົາ' : 'OUR VISION'; ?> ✦
                    </span>
                </div>
                <div class="absolute top-4 right-4 z-10">
                    <span class="text-4xl font-serif-lao font-bold text-amber-500/80 drop-shadow">02</span>
                </div>
            </div>

            <div class="space-y-6 flex-grow flex flex-col justify-between">
                <div class="space-y-3">
                    <h3 class="text-2xl md:text-3xl font-extrabold text-[#2C1810] font-serif-lao leading-tight">
                        <?php echo t('story_subtitle'); ?>
                    </h3>
                    <p class="text-sm md:text-base text-[#6E584E] font-light leading-relaxed font-serif-lao">
                        <?php echo t('story_p2'); ?>
                    </p>
                </div>
                
                <div class="pt-5 border-t border-[#EBE4D8] flex items-center justify-between text-xs text-amber-900/80 font-bold font-serif-lao">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                        <?php echo $current_lang === 'lo' ? 'ປະສົບການລາວທັນສະໄໝ' : 'MODERN LAO EXPERIENCE'; ?>
                    </span>
                    <span class="w-12 h-0.5 bg-amber-500/60"></span>
                </div>
            </div>
        </div>

    </div>
</section>

<?php if (false): ?>
<!-- Section 3: Value Proposition Summary (Bright Luxury Champagne Cream & Gold Cards Grid) -->
<section class="py-20 bg-gradient-to-b from-[#FAF7F0] via-[#F4EFE6] to-[#FAF7F0] px-6 md:px-12 border-t border-[#E8DFC9] relative overflow-hidden">
    <!-- Ambient Soft Golden Glow Effects -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-amber-200/40 via-transparent to-transparent pointer-events-none"></div>

    <div class="max-w-6xl mx-auto space-y-14 relative z-10">
        <!-- Section Title -->
        <div class="text-center space-y-3">
            <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-burgundy-50 border border-burgundy-100 text-burgundy-900 text-xs font-bold font-serif-lao tracking-widest uppercase">
                ✦ EXCELLENCE & LUXURY ✦
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#3B0A15] font-serif-lao tracking-tight">
                <?php echo t('about_value_prop_title'); ?>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-transparent via-[#6B1D2F] to-transparent mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Card 1: Crown Quality & Solid Hardwood Atmosphere -->
            <div class="bg-white rounded-3xl p-8 space-y-6 border border-[#E6DEC9] shadow-[0_15px_40px_rgba(83,19,33,0.08)] hover:shadow-[0_25px_50px_rgba(83,19,33,0.18)] hover:border-amber-400/80 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden group">
                <!-- Top Header: Badge Pill + Number 01 -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-gradient-to-r from-burgundy-50 to-amber-50 border border-burgundy-200/80 text-burgundy-900 text-[11px] font-extrabold uppercase tracking-wider font-serif-lao shadow-sm">
                        <span>👑</span>
                        <span>CROWN QUALITY</span>
                    </span>
                    <span class="text-4xl font-black text-burgundy-950/15 group-hover:text-burgundy-900/35 transition-colors font-serif-lao">01</span>
                </div>

                <!-- Tile Icon (Ultra-Luxurious 3D Burgundy & Gold Crown Badge) -->
                <div class="w-18 h-18 rounded-2xl bg-gradient-to-br from-[#4A0E1C] via-[#7D1D32] to-[#2B060F] border-2 border-[#F59E0B]/60 flex items-center justify-center shadow-[0_12px_28px_rgba(77,15,30,0.35)] group-hover:scale-110 group-hover:shadow-[0_16px_36px_rgba(245,158,11,0.3)] transition-all duration-300 p-3.5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(253,230,138,0.25),transparent)] pointer-events-none"></div>
                    <!-- Detailed Royal Crown & Shield SVG -->
                    <svg class="w-10 h-10 drop-shadow-md relative z-10" viewBox="0 0 24 24" fill="none">
                        <!-- Crown Base & Pillars -->
                        <path d="M3 17.5L4.5 9L9 12L12 5L15 12L19.5 9L21 17.5H3Z" fill="url(#crown_gold_grad_1)" stroke="#FEF08A" stroke-width="1.2" stroke-linejoin="round"/>
                        <path d="M3 18.5H21V19.5C21 20 20.5 20.5 20 20.5H4C3.5 20.5 3 20 3 19.5V18.5Z" fill="#F59E0B" stroke="#FEF08A" stroke-width="1"/>
                        <!-- Crown Jewels -->
                        <circle cx="4.5" cy="8" r="1.3" fill="#FDE68A" stroke="#B45309" stroke-width="0.8"/>
                        <circle cx="9" cy="11" r="1.1" fill="#FDE68A" stroke="#B45309" stroke-width="0.8"/>
                        <circle cx="12" cy="4" r="1.5" fill="#FEF08A" stroke="#92400E" stroke-width="0.8"/>
                        <circle cx="15" cy="11" r="1.1" fill="#FDE68A" stroke="#B45309" stroke-width="0.8"/>
                        <circle cx="19.5" cy="8" r="1.3" fill="#FDE68A" stroke="#B45309" stroke-width="0.8"/>
                        <!-- Sparkle stars -->
                        <path d="M12 1.5L12.5 3L14 3.5L12.5 4L12 5.5L11.5 4L10 3.5L11.5 3L12 1.5Z" fill="#FFF"/>
                        <defs>
                            <linearGradient id="crown_gold_grad_1" x1="3" y1="5" x2="21" y2="20" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FBBF24"/>
                                <stop offset="0.5" stop-color="#F59E0B"/>
                                <stop offset="1" stop-color="#B45309"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <!-- Card Content -->
                <div class="space-y-3">
                    <h3 class="text-lg font-extrabold text-[#2C1810] group-hover:text-[#531321] transition-colors tracking-wide font-serif-lao leading-snug">
                        <?php echo $current_lang === 'lo' ? 'ຄຸນນະພາບລະດັບສູງ (HIGH QUALITY)' : 'HIGH QUALITY SELECTION'; ?>
                    </h3>
                    <p class="text-xs md:text-sm text-[#5C483E] font-normal leading-relaxed font-serif-lao">
                        <?php echo t('about_high_quality_desc'); ?>
                    </p>
                </div>
            </div>

            <!-- Card 2: 100% Bolaven Arabica Coffee Specialty -->
            <div class="bg-white rounded-3xl p-8 space-y-6 border border-[#E6DEC9] shadow-[0_15px_40px_rgba(139,69,19,0.08)] hover:shadow-[0_25px_50px_rgba(139,69,19,0.18)] hover:border-amber-400/80 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden group">
                <!-- Top Header: Badge Pill + Number 02 -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/80 text-amber-950 text-[11px] font-extrabold uppercase tracking-wider font-serif-lao shadow-sm">
                        <span>☕</span>
                        <span>COFFEE CRAFT</span>
                    </span>
                    <span class="text-4xl font-black text-amber-950/15 group-hover:text-amber-900/35 transition-colors font-serif-lao">02</span>
                </div>

                <!-- Tile Icon (Ultra-Luxurious Roasted Arabica & Gold Steam Badge) -->
                <div class="w-18 h-18 rounded-2xl bg-gradient-to-br from-[#612D08] via-[#8C430E] to-[#361703] border-2 border-[#FBBF24]/60 flex items-center justify-center shadow-[0_12px_28px_rgba(140,67,14,0.35)] group-hover:scale-110 group-hover:shadow-[0_16px_36px_rgba(251,191,36,0.3)] transition-all duration-300 p-3.5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(254,240,138,0.25),transparent)] pointer-events-none"></div>
                    <!-- Detailed Steaming Espresso Cup & Coffee Beans SVG -->
                    <svg class="w-10 h-10 drop-shadow-md relative z-10" viewBox="0 0 24 24" fill="none">
                        <!-- Aroma Steam Lines -->
                        <path d="M6.5 4C7 5 7 6 6.5 7" stroke="#FEF08A" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M11 2.5C11.8 3.8 11.8 5.2 11 6.5" stroke="#FDE68A" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M15.5 4C16 5 16 6 15.5 7" stroke="#FEF08A" stroke-width="1.5" stroke-linecap="round"/>
                        <!-- Cup Body -->
                        <path d="M4 9H18V14.5C18 17.5 15.5 20 11 20C6.5 20 4 17.5 4 14.5V9Z" fill="url(#coffee_gold_grad_2)" stroke="#FEF08A" stroke-width="1.2"/>
                        <!-- Cup Handle -->
                        <path d="M18 10.5H19.5C20.88 10.5 22 11.62 22 13C22 14.38 20.88 15.5 19.5 15.5H18" stroke="#FBBF24" stroke-width="1.8" stroke-linecap="round"/>
                        <!-- Saucer Base -->
                        <path d="M3 21H19" stroke="#FDE68A" stroke-width="1.5" stroke-linecap="round"/>
                        <!-- Coffee Bean Emblem on Cup -->
                        <ellipse cx="11" cy="14.5" rx="2.5" ry="3.5" transform="rotate(-25 11 14.5)" fill="#4A2105" stroke="#FEF08A" stroke-width="0.8"/>
                        <path d="M9.8 12.2C10.5 13.5 11.5 15.5 12.2 16.8" stroke="#FEF08A" stroke-width="0.8" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="coffee_gold_grad_2" x1="4" y1="9" x2="18" y2="20" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FBBF24"/>
                                <stop offset="0.6" stop-color="#D97706"/>
                                <stop offset="1" stop-color="#78350F"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <!-- Card Content -->
                <div class="space-y-3">
                    <h3 class="text-lg font-extrabold text-[#2C1810] group-hover:text-[#7A3B10] transition-colors tracking-wide font-serif-lao leading-snug">
                        <?php echo $current_lang === 'lo' ? 'ລາຄາສົມເຫດສົມຜົນ (HIGH AFFORDABILITY)' : 'HIGH AFFORDABILITY BEVERAGES'; ?>
                    </h3>
                    <p class="text-xs md:text-sm text-[#5C483E] font-normal leading-relaxed font-serif-lao">
                        <?php echo t('about_high_affordability_desc'); ?>
                    </p>
                </div>
            </div>

            <!-- Card 3: Craft Draft Beer & Signature Lounge Cocktails -->
            <div class="bg-white rounded-3xl p-8 space-y-6 border border-[#E6DEC9] shadow-[0_15px_40px_rgba(217,119,6,0.08)] hover:shadow-[0_25px_50px_rgba(217,119,6,0.18)] hover:border-amber-400/80 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden group">
                <!-- Top Header: Badge Pill + Number 03 -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-gradient-to-r from-amber-100 to-yellow-100 border border-amber-300/80 text-amber-950 text-[11px] font-extrabold uppercase tracking-wider font-serif-lao shadow-sm">
                        <span>🍺</span>
                        <span>CRAFT DRAFT BEER</span>
                    </span>
                    <span class="text-4xl font-black text-amber-900/20 group-hover:text-amber-900/40 transition-colors font-serif-lao">03</span>
                </div>

                <!-- Tile Icon (Ultra-Luxurious Amber Gold Beer Mug & Foam Head Badge) -->
                <div class="w-18 h-18 rounded-2xl bg-gradient-to-br from-[#D97706] via-[#F59E0B] to-[#92400E] border-2 border-[#FEF08A]/80 flex items-center justify-center shadow-[0_12px_28px_rgba(217,119,6,0.35)] group-hover:scale-110 group-hover:shadow-[0_16px_36px_rgba(254,240,138,0.4)] transition-all duration-300 p-3.5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,255,255,0.35),transparent)] pointer-events-none"></div>
                    <!-- Detailed Craft Beer Mug with Rich Foam SVG -->
                    <svg class="w-10 h-10 drop-shadow-md relative z-10" viewBox="0 0 24 24" fill="none">
                        <!-- Frothy Foam Head Top -->
                        <path d="M4.5 9.5C3.8 9.5 3 8.8 3 8C3 7.2 3.8 6.5 4.8 6.5C5.2 5.3 6.5 4.5 8 4.5C9.2 4.5 10.3 5.1 10.8 6C11.5 5.4 12.5 5 13.5 5C15.2 5 16.5 6.2 16.8 7.5C17.5 7.5 18.5 8.2 18.5 9C18.5 9.5 18 9.5 17.5 9.5H4.5Z" fill="#FFFFFF" stroke="#FEF08A" stroke-width="1"/>
                        <!-- Mug Glass Body -->
                        <path d="M5 9.5H17V18.5C17 19.6 16.1 20.5 15 20.5H7C5.9 20.5 5 19.6 5 18.5V9.5Z" fill="url(#beer_gold_grad_3)" stroke="#FEF08A" stroke-width="1.2"/>
                        <!-- Vertical Glass Facets -->
                        <line x1="8" y1="10" x2="8" y2="19" stroke="#FEF08A" stroke-width="1" stroke-linecap="round" opacity="0.8"/>
                        <line x1="11" y1="10" x2="11" y2="19" stroke="#FEF08A" stroke-width="1" stroke-linecap="round" opacity="0.8"/>
                        <line x1="14" y1="10" x2="14" y2="19" stroke="#FEF08A" stroke-width="1" stroke-linecap="round" opacity="0.8"/>
                        <!-- Mug Handle -->
                        <path d="M17 11.5H19.5C20.6 11.5 21.5 12.4 21.5 13.5V16C21.5 17.1 20.6 18 19.5 18H17" stroke="#FEF08A" stroke-width="1.8" stroke-linecap="round"/>
                        <!-- Sparkling Effervescent Bubbles -->
                        <circle cx="7" cy="13" r="0.8" fill="#FFF"/>
                        <circle cx="10" cy="16" r="0.9" fill="#FFF"/>
                        <circle cx="13" cy="12" r="0.7" fill="#FFF"/>
                        <circle cx="15" cy="17" r="0.8" fill="#FFF"/>
                        <defs>
                            <linearGradient id="beer_gold_grad_3" x1="5" y1="9.5" x2="17" y2="20.5" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FBBF24"/>
                                <stop offset="0.5" stop-color="#F59E0B"/>
                                <stop offset="1" stop-color="#B45309"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <!-- Card Content -->
                <div class="space-y-3">
                    <h3 class="text-lg font-extrabold text-[#2C1810] group-hover:text-[#D97706] transition-colors tracking-wide font-serif-lao leading-snug">
                        <?php echo $current_lang === 'lo' ? 'ຄວາມສະດວກສະບາຍ (HIGH CONVENIENCE)' : 'HIGH CONVENIENCE EVERYWHERE'; ?>
                    </h3>
                    <p class="text-xs md:text-sm text-[#5C483E] font-normal leading-relaxed font-serif-lao">
                        <?php echo t('about_high_convenience_desc'); ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>



<!-- Section 4: Spotlight 1 - HIGH QUALITY -->
<section class="py-20 bg-gradient-to-r from-[#1C050B] via-[#4D0F1E] to-[#1C050B] text-white px-6 md:px-12 relative overflow-hidden">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl xl:text-4xl font-bold font-serif-lao leading-snug tracking-normal text-amber-200 sm:whitespace-nowrap">
                <?php echo $current_lang === 'lo' ? 'ຄຸນນະພາບ ລະດັບສູງ' : 'HIGH QUALITY SELECTION'; ?>
            </h2>
            <div class="w-20 h-1 bg-amber-400/80 rounded-full"></div>
            <p class="text-amber-100/90 font-normal leading-relaxed text-base md:text-lg font-sans-lao">
                <?php echo $current_lang === 'lo'
                    ? 'ແກ່ນກາເຟຂອງພວກເຮົາສົ່ງກົງຈາກແຫຼ່ງປູກບໍລະເວນ ເຊິ່ງເປັນແຫຼ່ງປູກກາເຟທີ່ມີຊື່ສຽງລະດັບໂລກ. ທຸກໆ Batch ຂອງກາເຟໄດ້ຮັບການຄັດສັນ ແລະ ປຸງແຕ່ງຢ່າງພິຖີພິຖັນໂດຍທີມງານແຊັມບາຣິສຕ້າລະດັບຊາດ ແລະ Q-Grader ມືອາຊີບ ຈາກຫຼາຍກວ່າ 180 ສູດປະສົມ ເພື່ອໃຫ້ໄດ້ລົດຊາດທີ່ຖືກໃຈລູກຄ້າທີ່ສຸດ.'
                    : 'Our coffee beans come directly from the world-renowned Bolaven Plateau. Every batch of coffee is carefully selected and blended by our team of national champion baristas and professional Q-Graders, chosen from over 180 blending formulas to perfectly match your taste.';
                ?>
            </p>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-white/20 h-80 md:h-[400px]">
            <img src="assets/images/coffee.png" alt="High Quality Coffee" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
    </div>
</section>

<!-- Section 5: Spotlight 2 - HIGH AFFORDABILITY -->
<section class="py-20 bg-[#FAF7F2] px-6 md:px-12 border-t border-b border-[#EBE4D8]">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1 rounded-3xl overflow-hidden shadow-xl border border-[#E2D9CC] h-80 md:h-[400px]">
            <img src="assets/images/our_story.png" alt="High Affordability" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
        <div class="order-1 lg:order-2 space-y-6">
            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl xl:text-4xl font-bold text-[#3D0B16] font-serif-lao leading-snug tracking-normal sm:whitespace-nowrap">
                <?php echo $current_lang === 'lo' ? 'ລາຄາສົມເຫດສົມຜົນ ທີ່ຈ່າຍໄດ້ງ່າຍ' : 'HIGH AFFORDABILITY BEVERAGES'; ?>
            </h2>
            <div class="w-20 h-1 bg-[#6B1D2F] rounded-full"></div>
            <p class="text-[#4A3B34] font-normal leading-relaxed text-base md:text-lg font-sans-lao">
                <?php echo $current_lang === 'lo'
                    ? 'ພວກເຮົາສົ່ງເສີມຮູບແບບການບໍລິການທີ່ວ່ອງໄວ ແລະ ທັນສະໄໝ. ລູກຄ້າສາມາດເພີດເພີນກັບກາເຟພຣີມ່ຽມ ແລະ ເຄື່ອງດື່ມບາລາວປະຍຸກ ໃນລາຄາທີ່ຈ່າຍໄດ້ງ່າຍ ທຸກໆມື້ ໂດຍບໍ່ຕ້ອງກັງວົນກ່ຽວກັບຄ່າໃຊ້ຈ່າຍທີ່ສູງເກີນໄປ.'
                    : 'We advocate a fast, modern retail experience. Customers can enjoy premium coffee and Lao-adapted craft beverages at truly accessible prices every day without worrying about high markup costs.';
                ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 6: Spotlight 3 - HIGH CONVENIENCE -->
<section class="py-20 bg-gradient-to-r from-[#1C050B] via-[#4D0F1E] to-[#1C050B] text-white px-6 md:px-12 relative overflow-hidden">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-3xl xl:text-4xl font-bold font-serif-lao leading-snug tracking-normal text-amber-200 sm:whitespace-nowrap">
                <?php echo $current_lang === 'lo' ? 'ຄວາມສະດວກສະບາຍ ໃນທຸກໆສາຂາ' : 'HIGH CONVENIENCE EVERYWHERE'; ?>
            </h2>
            <div class="w-20 h-1 bg-amber-400/80 rounded-full"></div>
            <p class="text-amber-100/90 font-normal leading-relaxed text-base md:text-lg font-sans-lao">
                <?php echo $current_lang === 'lo'
                    ? 'ດ້ວຍສາຂາເລົາຈ໌ທີ່ທັນສະໄໝ 4 ສາຂາ ໃນວຽງຈັນ, ວັງວຽງ, ຫຼວງພະບາງ, ແລະ ປາກເຊ, LaoFe & Beer ພ້ອມຕອບໂຈດວິຖີຊີວິດຂອງທ່ານ ທັງຕອນກາງເວັນ (ກາເຟ) ແລະ ຕອນຄ່ຳ (ບາ) ດ້ວຍຄວາມສະດວກສະບາຍ ແລະ ບໍລິການທີ່ເປັນກັນເອງ.'
                    : 'With 4 modern lounge locations across Vientiane, Vang Vieng, Luang Prabang, and Pakse, LaoFe & Beer perfectly fits your lifestyle day (specialty coffee) and night (craft bar) with ultimate convenience and warm service.';
                ?>
            </p>
        </div>
        <div class="rounded-3xl overflow-hidden shadow-2xl border border-white/20 h-80 md:h-[400px]">
            <img src="assets/images/hero_banner.png" alt="High Convenience Lounge" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
    </div>
</section>

<!-- Section 7: Vision Section -->
<section class="py-20 bg-[#FAF7F2] px-6 md:px-12 border-b border-[#EBE4D8]">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="rounded-3xl overflow-hidden shadow-xl h-80 border border-[#E2D9CC]">
            <img src="assets/images/our_story.png" alt="LaoFe Vision" class="w-full h-full object-cover">
        </div>
        <div class="space-y-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-burgundy-700 border-l-4 border-burgundy-700 pl-4 font-serif-lao leading-snug">
                <?php echo t('story_vision'); ?>
            </h2>
            <p class="text-[#4A3B34] font-normal leading-relaxed text-base md:text-lg font-sans-lao">
                <?php echo t('story_vision_desc'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Section 8: Infinite Image Marquee Section (Based on Luckin style with 2 rows of sliding images moving in opposite directions) -->
<?php
$gallery_items = [];
try {
    $stmt_gal = $pdo->query("SELECT * FROM gallery WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
    $gallery_items = $stmt_gal->fetchAll();
} catch (\Exception $e) {
    $gallery_items = [];
}

// Fallback images if database table is empty
if (empty($gallery_items)) {
    $gallery_items = [
        ['image_path' => 'assets/images/hero_banner.png', 'title_lo' => 'LaoFe Gallery', 'title_en' => 'LaoFe Gallery'],
        ['image_path' => 'assets/images/our_story.png', 'title_lo' => 'LaoFe Gallery', 'title_en' => 'LaoFe Gallery'],
        ['image_path' => 'assets/images/coffee.png', 'title_lo' => 'LaoFe Gallery', 'title_en' => 'LaoFe Gallery'],
        ['image_path' => 'assets/images/beer_drink.png', 'title_lo' => 'LaoFe Gallery', 'title_en' => 'LaoFe Gallery']
    ];
}
$gallery_items_rev = array_reverse($gallery_items);
?>
<section class="py-24 bg-gray-50 overflow-hidden space-y-8 border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center mb-10 space-y-2">
        <h2 class="text-3xl md:text-4xl font-extrabold text-burgundy-700 font-serif-lao">
            <?php echo $current_lang === 'lo' ? 'ແກເລີຣີ ບັນຍາກາດ ແລະ ແນວຄິດການດີຊາຍ' : 'Atmosphere & Concept Gallery'; ?>
        </h2>
        <p class="text-gray-500 font-light font-serif-lao">
            <?php echo $current_lang === 'lo' ? 'ສຳຜັດກັບບັນຍາກາດການດີຊາຍ ແລະ ເຄື່ອງດື່ມຂອງ LaoFe & Beer' : 'Take a glimpse of our design and drinks'; ?>
        </p>
    </div>

    <!-- Row 1: Sliding Left -->
    <div class="marquee-container">
        <div class="animate-marquee-left">
            <!-- Slide Part 1 -->
            <div class="marquee-slide">
                <?php foreach ($gallery_items as $g_item): ?>
                    <img src="<?php echo htmlspecialchars($g_item['image_path']); ?>" alt="<?php echo htmlspecialchars(($current_lang === 'lo' ? $g_item['title_lo'] : $g_item['title_en']) ?: 'LaoFe Gallery'); ?>">
                <?php endforeach; ?>
            </div>
            <!-- Slide Part 2 (Duplicate for seamless loop) -->
            <div class="marquee-slide">
                <?php foreach ($gallery_items as $g_item): ?>
                    <img src="<?php echo htmlspecialchars($g_item['image_path']); ?>" alt="<?php echo htmlspecialchars(($current_lang === 'lo' ? $g_item['title_lo'] : $g_item['title_en']) ?: 'LaoFe Gallery'); ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Row 2: Sliding Right -->
    <div class="marquee-container">
        <div class="animate-marquee-right">
            <!-- Slide Part 1 -->
            <div class="marquee-slide">
                <?php foreach ($gallery_items_rev as $g_item): ?>
                    <img src="<?php echo htmlspecialchars($g_item['image_path']); ?>" alt="<?php echo htmlspecialchars(($current_lang === 'lo' ? $g_item['title_lo'] : $g_item['title_en']) ?: 'LaoFe Gallery'); ?>">
                <?php endforeach; ?>
            </div>
            <!-- Slide Part 2 (Duplicate for seamless loop) -->
            <div class="marquee-slide">
                <?php foreach ($gallery_items_rev as $g_item): ?>
                    <img src="<?php echo htmlspecialchars($g_item['image_path']); ?>" alt="<?php echo htmlspecialchars(($current_lang === 'lo' ? $g_item['title_lo'] : $g_item['title_en']) ?: 'LaoFe Gallery'); ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
