<?php
// includes/lang.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ບັງຄັບໃຫ້ Browser ບໍ່ຈື່ຈຳແຄຊ໌ ເພື່ອໃຫ້ສະແດງຜົນການອັບເດດສີຫຼ້າສຸດທັນທີ
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ກວດສອບການປ່ຽນພາສາຜ່ານ URL
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['lo', 'en'])) {
        $_SESSION['lang'] = $lang;
    }
    // ກັບຄືນໜ້າເກົ່າ ໂດຍບໍ່ມີ parameter ?lang
    $redirect = strtok($_SERVER['REQUEST_URI'], '?');
    // ຮັກສາ query parameters ອື່ນໆ ຖ້າມີ
    $queryParams = $_GET;
    unset($queryParams['lang']);
    if (!empty($queryParams)) {
        $redirect .= '?' . http_build_query($queryParams);
    }
    header("Location: " . $redirect);
    exit;
}

// ພາສາເລີ່ມຕົ້ນແມ່ນ ພາສາລາວ 'lo'
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'lo';
}

$current_lang = $_SESSION['lang'];

// ອາເຣແປພາສາ (Translation Dictionary)
$translations = [
    'lo' => [
        'brand_name' => 'LaoFe & Beer',
        'nav_home' => 'ໜ້າຫຼັກ',
        'nav_story' => 'ເລື່ອງລາວຂອງເຮົາ',
        'nav_about' => 'ກ່ຽວກັບເຮົາ',
        'nav_menu' => 'ເມນູ',
        'nav_locations' => 'ສາຂາ',
        'nav_news' => 'ຂ່າວສານ & ໂປຣ',
        'nav_franchise' => 'ຮ່ວມທຸລະກິດ',
        'nav_contact' => 'ຕິດຕໍ່',
        'lang_toggle' => 'English',
        'lang_code' => 'en',
        
        // footer
        'footer_contact' => 'ຕິດຕໍ່ພວກເຮົາ',
        'footer_desc' => 'ກາເຟ ແລະ ບາ ໃນຮູບແບບລາວປະຍຸກ ທີ່ຜສານຄວາມເປັນລາວເຂົ້າກັບຄວາມທັນສະໄໝຢ່າງລົງຕົວ.',
        'footer_rights' => 'ສະຫງວນລິຂະສິດທັງໝົດ.',
        'footer_links' => 'ລິ້ງດ່ວນ',
        'footer_policy' => 'ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ',
        'footer_terms' => 'ເງື່ອນໄຂການນຳໃຊ້',
        
        // Index / Home page
        'hero_title' => 'ລົດຊາດລາວປະຍຸກ ແທ້ໆ',
        'hero_subtitle' => 'ຄົ້ນພົບຄວາມລົງຕົວຂອງກາເຟຊັ້ນດີໃນຍາມກາງເວັນ ແລະ ເຄື່ອງດື່ມສຸດພິເສດໃນຍາມຄ່ຳຄືນ ທີ່ LaoFe & Beer.',
        'hero_cta' => 'ເບິ່ງເມນູຂອງພວກເຮົາ',
        'promo_title' => 'ໂປຣໂມຊັນພິເສດປະຈຳເດືອນ',
        'promo_sub' => 'ຫ້າມພາດ! ຂໍ້ສະເໜີສຸດຄຸ້ມສຳລັບລູກຄ້າຄົນພິເສດ.',
        'popular_title' => 'ເມນູຍອດນິຍົມ',
        'popular_sub' => 'ລອງເລີຍ! ເມນູທີ່ໄດ້ຮັບຄວາມນິຍົມສູງສຸດຈາກລູກຄ້າຂອງພວກເຮົາ.',
        'view_all' => 'ເບິ່ງທັງໝົດ',
        'learn_more' => 'ອ່ານເພີ່ມເຕີມ',
        'explore_title' => 'ຄົ້ນພົບໂລກແຫ່ງກາເຟ & ບາ ຂອງພວກເຮົາ',
        'high_quality_beans' => 'ແກ່ນກາເຟຄຸນນະພາບສູງ ຈາກບໍລະເວນ',
        'blended_by_champions' => 'ປຸງແຕ່ງໂດຍຜູ້ຊ່ຽວຊານມືອາຊີບ',
        'freshly_roasted_poured' => 'ຂົ້ວສົດໃໝ່ & ດຶງສົດຈາກແທັບ',
        'special_offer' => 'ຂໍ້ສະເໜີສຸດພິເສດ',
        'premium_selection_badge' => 'ກາເຟພຣີມ່ຽມບໍລະເວນທີ່ຄັດສັນເປັນພິເສດ',
        'blended_by_masters' => 'ປຸງແຕ່ງໂດຍແຊັມບາຣິສຕ້າ & ມິກໂຊໂລຈິສ',
        'meet_experts' => 'ພົບກັບຜູ້ຊ່ຽວຊານດ້ານລົດຊາດຂອງພວກເຮົາ',
        'master_barista' => 'ແຊັມບາຣິສຕ້າ',
        'sengdavone_title' => 'ຜູ້ຊະນະເລີດການແຂ່ງຂັນບາຣິສຕ້າລະດັບຊາດລາວ 2024',
        'master_mixologist' => 'ມິກໂຊໂລຈິສມືອາຊີບ',
        'bounmy_title' => 'ຜູ້ຊ່ຽວຊານດ້ານຄັອກເທວ, ປະສົບການບາ 8 ປີ',
        'master_roaster' => 'ນັກຂົ້ວກາເຟມືອາຊີບ',
        'aloun_title' => 'ຫົວໜ້າຝ່າຍຂົ້ວກາເຟ ແລະ ຄວບຄຸມຄຸນນະພາບ, ໃບຢັ້ງຢືນ Q-Grader',
        'equipment_badge' => 'ອຸປະກອນກາເຟ ແລະ ບາເບຍສົດລະດັບພຣີມ່ຽມ',
        'equipment_title' => 'ຂົ້ວສົດ, ບົດໃໝ່ ແລະ ດຶງສົດຈາກແທັບ',
        'outlets_title' => 'ສາຂາ ແລະ ເລົາຈ໌ ຂອງ LaoFe',
        'outlets_sub' => 'ຊອກຫາສະຖານທີ່ສະດວກສະບາຍໃກ້ທ່ານເພື່ອຜ່ອນຄາຍ ແລະ ເພີດເພີນ',
        'view_map_phone' => 'ເບິ່ງແຜນທີ່ & ເບີໂທ',
        'lounge_sithong' => 'ສາຂາສີທອງ ເລົາຈ໌',
        'lounge_vangvieng' => 'ສາຂາວັງວຽງ ຣິເວີໄຊ້',
        'lounge_luangprabang' => 'ສາຂາຫຼວງພະບາງ ມໍລະດົກ',
        'lounge_pakse' => 'ສາຂາປາກເຊ ບໍລະເວນເລົາຈ໌',
        'sithong_hours' => 'ຖະໜົນສີທອງ, ນະຄອນຫຼວງວຽງຈັນ. ເປີດທຸກວັນ: 07:00 - 23:00',
        'vangvieng_hours' => 'ຖະໜົນກາງ, ເມືອງວັງວຽງ. ເປີດທຸກວັນ: 07:00 - 24:00',
        'luangprabang_hours' => 'ຖະໜົນສີສະຫວ່າງວົງ, ຫຼວງພະບາງ. ເປີດທຸກວັນ: 06:30 - 22:00',
        'pakse_hours' => 'ເສັ້ນທາງເລກທີ 13, ປາກເຊ. ເປີດທຸກວັນ: 07:00 - 22:00',
        
        // Our Story
        'story_title' => 'ເລື່ອງລາວຂອງ LaoFe & Beer',
        'story_subtitle' => 'ຄວາມເປັນມາ ແລະ ແຮງບັນດານໃຈ',
        'story_hero_title1' => 'ການປ່ຽນແປງຄັ້ງໃຫຍ່',
        'story_hero_title2' => 'ກຳລັງມາຮອດ',
        'story_hero_desc' => 'ຄົ້ນພົບລົດຊາດລະດັບແຊັມປ້ຽນຂອງ LaoFe & Beer ທີ່ພ້ອມສົ່ງມອບຄວາມສຸກໃຫ້ທຸກຄົນ. ສັມຜັດລົດຊາດກາເຟ ແລະ ເຄື່ອງດື່ມປະຍຸກສູດພິເສດ ເອກະລັກພຽງແຫ່ງດຽວ.',
        'story_hero_cta' => 'ຄົ້ນພົບເມນູພິເສດ',
        'story_p1' => 'LaoFe & Beer ເລີ່ມຕົ້ນດ້ວຍແນວຄິດທີ່ຢາກຈະນຳສະເໜີ "ກາເຟ ແລະ ບາ" ໃນຮູບແບບລາວປະຍຸກ (Lao-Adapted Coffee & Bar) ທີ່ບໍ່ຄືໃຜ. ພວກເຮົາຕັ້ງໃຈສ້າງພື້ນທີ່ທີ່ຜສົມຜະສານວັດທະນະທຳທ້ອງຖິ່ນອັນດີງາມ ເຂົ້າກັບວິຖີຊີວິດທີ່ທັນສະໄໝ.',
        'story_p2' => 'ພວກເຮົາຄັດສັນແກ່ນກາເຟຄຸນນະພາບສູງ ຈາກແຫຼ່ງປູກທີ່ດີທີ່ສຸດໃນລາວ ເພື່ອມອບລົດຊາດກາເຟທີ່ເຂັ້ມຂຸ້ນ ແລະ ກິ່ນຫອມລະມຸນໃນຕອນກາງເວັນ. ແລະ ເມື່ອຕອນແລງມາຮອດ, ຮ້ານຂອງພວກເຮົາຈະປ່ຽນເປັນພື້ນທີ່ແຫ່ງການພັກຜ່ອນຢ່ອນໃຈ ພ້ອມເຄື່ອງດື່ມ ແລະ ອາຫານລາວປະຍຸກຫຼາກຫຼາຍເມນູ.',
        'story_vision' => 'ວິໄສທັດຂອງພວກເຮົາ',
        'story_vision_desc' => 'ພວກເຮົາຕ້ອງການເປັນແບຣນກາເຟ ແລະ ບາ ລາວປະຍຸກອັນດັບໜຶ່ງ ທີ່ມີມາດຕະຖານລະດັບສາກົນ ແລະ ພ້ອມທີ່ຈະຂະຫຍາຍສາຂາໄປທົ່ວປະເທດ ແລະ ຕ່າງປະເທດ ດ້ວຍລະບົບແຟຣນໄຊສ໌ທີ່ເຂັ້ມແຂງ.',
        
        // About Us (Luckin Style)
        'about_us_title' => 'ກ່ຽວກັບ LaoFe & Beer',
        'about_us_sub' => 'ແບຣນກາເຟ ແລະ ບາ ລາວປະຍຸກ ທີ່ຜ່ານການຄິດຄົ້ນ ແລະ ພັດທະນາຢ່າງບໍ່ຢຸດຢັ້ງ',
        'about_value_prop_title' => 'ຄຸນຄ່າຫຼັກຂອງ LaoFe & Beer (Value Proposition)',
        'about_high_quality' => 'ຄຸນນະພາບສູງ (HIGH QUALITY)',
        'about_high_quality_desc' => 'ແກ່ນກາເຟຄຸນນະພາບສູງຈາກແຫຼ່ງປູກບໍລະເວນ ຜ່ານການປຸງແຕ່ງ ແລະ ຂົ້ວໂດຍທີມງານແຊັມບາຣິສຕ້າ. ຄັດສັນຫຼາຍກວ່າ 180 ສູດປະສົມ ທີ່ຕອບໂຈດລົດຊາດຂອງລູກຄ້າໄດ້ຢ່າງສົມບູນແບບ.',
        'about_high_affordability' => 'ລາຄາເຂົ້າເຖິງງ່າຍ (HIGH AFFORDABILITY)',
        'about_high_affordability_desc' => 'ມອບປະສົບການເຄື່ອງດື່ມພຣີມ່ຽມ ໃນລາຄາທີ່ທຸກຄົນສາມາດເພີດເພີນໄດ້ທຸກໆມື້ ໂດຍບໍ່ມີລາຄາບວກເພີ່ມທີ່ເກີນຄວາມເປັນຈິງ.',
        'about_high_convenience' => 'ຄວາມສະດວກສະບາຍສູງ (HIGH CONVENIENCE)',
        'about_high_convenience_desc' => 'ສະດວກສະບາຍໃນການສັ່ງເຄື່ອງດື່ມ ແລະ ພັກຜ່ອນ ໃນບັນຍາກາດເລົາຈ໌ທີ່ທັນສະໄໝ 4 ສາຂາ ພ້ອມບໍລິການທີ່ວ່ອງໄວ ແລະ ເປັນກັນເອງ.',
        'about_company_title' => 'ບໍລິສັດ',
        'about_company_val' => 'LAOFE & BEER CO., LTD.',
        'about_email_title' => 'ອີເມລຕິດຕໍ່',
        'about_enquiries_title' => 'ສອບຖາມຂໍ້ມູນ & ຂໍ້ສະເໜີແນະ',
        'about_enquiries_hours' => 'ຈັນ - ອາທິດ: 07:00 - 23:00',
        'about_join_title' => 'ຮ່ວມງານກັບເຮົາ / ແຟຣນໄຊສ໌',
        'about_join_sub' => 'ເບິ່ງໂອກາດທາງທຸລະກິດ',
        
        // Menu page
        'menu_all' => 'ທັງໝົດ',
        'menu_coffee' => 'ກາເຟ',
        'menu_drinks' => 'ເຄື່ອງດື່ມ',
        'menu_bar' => 'ບາ / ຄັອກເທວ',
        'menu_food' => 'ອາຫານລາວປະຍຸກ',
        'currency' => 'ກີບ',
        
        // Locations page
        'loc_title' => 'ສາຂາຂອງພວກເຮົາ',
        'loc_sub' => 'LaoFe & Beer ພ້ອມຕ້ອນຮັບທ່ານໃນ 4 ສາຂາທົ່ວປະເທດ',
        'loc_open' => 'ເວລາເປີດ-ປິດ:',
        'loc_phone' => 'ເບີໂທລະສັບ:',
        'loc_map' => 'ແຜນທີ່ Google Maps',
        
        // Franchise page
        'fran_title' => 'ຮ່ວມທຸລະກິດແຟຣນໄຊສ໌',
        'fran_sub' => 'ເຕີບໃຫຍ່ໄປພ້ອມກັບພວກເຮົາ ດ້ວຍໂຄງສ້າງແຟຣນໄຊສ໌ທີ່ເປັນມືອາຊີບ',
        'fran_desc' => 'ຫາກທ່ານມີຄວາມສົນໃຈ ແລະ ຢາກເປັນສ່ວນໜຶ່ງຂອງຄອບຄົວ LaoFe & Beer ໃນການດຳເນີນທຸລະກິດຮ້ານກາເຟ ແລະ ບາ ທີ່ທັນສະໄໝ, ກະລຸນາກອກຟອມຂໍ້ມູນຕິດຕໍ່ດ້ານລຸ່ມນີ້. ທີມງານຂອງພວກເຮົາຈະຕິດຕໍ່ກັບຫາທ່ານໂດຍໄວທີ່ສຸດ.',
        'fran_form_title' => 'ຟອມສະແດງຄວາມຈຳນົງ',
        'fran_name' => 'ຊື່ ແລະ ນາມສະກຸນ',
        'fran_phone' => 'ເບີໂທລະສັບຕິດຕໍ່',
        'fran_email' => 'ອີເມລ',
        'fran_loc' => 'ສາຂາ/ສະຖານທີ່ ທີ່ທ່ານສົນໃຈຢາກເປີດ',
        'fran_msg' => 'ຂໍ້ຄວາມເພີ່ມເຕີມ/ໝາຍເຫດ',
        'fran_submit' => 'ສົ່ງຂໍ້ມູນສະໝັກ',
        'fran_success' => 'ສົ່ງຂໍ້ມູນສະໝັກແຟຣນໄຊສ໌ສຳເລັດແລ້ວ! ທີມງານຈະຕິດຕໍ່ກັບຫາທ່ານໂດຍໄວ.',
        
        // Contact page
        'contact_title' => 'ຕິດຕໍ່ພວກເຮົາ',
        'contact_sub' => 'ມີຄຳຖາມ ຫຼື ຂໍ້ສະເໜີແນະໃດໆ? ສາມາດສົ່ງຫາພວກເຮົາໄດ້ທັນທີ',
        'contact_info' => 'ຂໍ້ມູນຕິດຕໍ່',
        'contact_form_title' => 'ສົ່ງຂໍ້ຄວາມຫາພວກເຮົາ',
        'contact_name' => 'ຊື່ຂອງທ່ານ',
        'contact_phone' => 'ເບີໂທລະສັບ (ຖ້າມີ)',
        'contact_email' => 'ອີເມລ',
        'contact_subject' => 'ຫົວຂໍ້',
        'contact_msg' => 'ຂໍ້ຄວາມຂອງທ່ານ',
        'contact_submit' => 'ສົ່ງຂໍ້ຄວາມ',
        'contact_success' => 'ສົ່ງຂໍ້ຄວາມສຳເລັດແລ້ວ! ຂອບໃຈສຳລັບການຕິດຕໍ່ຫາພວກເຮົາ.',
    ],
    'en' => [
        'brand_name' => 'LaoFe & Beer',
        'nav_home' => 'Home',
        'nav_story' => 'Our Story',
        'nav_about' => 'About Us',
        'nav_menu' => 'Menu',
        'nav_locations' => 'Locations',
        'nav_news' => 'News & Promos',
        'nav_franchise' => 'Franchise',
        'nav_contact' => 'Contact Us',
        'lang_toggle' => 'ພາສາລາວ',
        'lang_code' => 'lo',
        
        // footer
        'footer_contact' => 'Contact Us',
        'footer_desc' => 'A Lao-adapted coffee & bar concept that blends traditional Lao culture with modern lifestyle.',
        'footer_rights' => 'All rights reserved.',
        'footer_links' => 'Quick Links',
        'footer_policy' => 'Privacy Policy',
        'footer_terms' => 'Terms of Use',
        
        // Index / Home page
        'hero_title' => 'Authentic Lao-Adapted Taste',
        'hero_subtitle' => 'Discover the perfect blend of premium coffee by day and craft beverages by night at LaoFe & Beer.',
        'hero_cta' => 'Explore Our Menu',
        'promo_title' => 'Special Monthly Promotions',
        'promo_sub' => 'Do not miss out on exclusive offers tailored just for you.',
        'popular_title' => 'Popular Items',
        'popular_sub' => 'Try our most-ordered coffee, drinks, and food items.',
        'view_all' => 'View All',
        'learn_more' => 'Read More',
        'explore_title' => 'Explore Our Coffee & Bar World',
        'high_quality_beans' => 'HIGH QUALITY BOLAVEN BEANS',
        'blended_by_champions' => 'BLENDED BY MASTER CRAFTSMEN',
        'freshly_roasted_poured' => 'FRESHLY ROASTED & DRAFT POURED',
        'special_offer' => 'Special Offer',
        'premium_selection_badge' => 'Premium Bolaven Coffee Selection',
        'blended_by_masters' => 'Blended by Master Baristas & Mixologists',
        'meet_experts' => 'Meet our experts behind the perfect taste',
        'master_barista' => 'Master Barista',
        'sengdavone_title' => 'Winner of the 2024 Lao National Barista Championship',
        'master_mixologist' => 'Master Mixologist',
        'bounmy_title' => 'Signature Cocktails Specialist, 8 Years Lounge Experience',
        'master_roaster' => 'Master Roaster',
        'aloun_title' => 'Head of Coffee Roastery & Quality Control, Q-Grader Certified',
        'equipment_badge' => 'Premium Coffee & Draft Bar Equipment',
        'equipment_title' => 'Freshly Roasted, Freshly Grounded & Drafted',
        'outlets_title' => 'LaoFe Outlets & Lounges',
        'outlets_sub' => 'Find a comfortable spot near you to relax and enjoy',
        'view_map_phone' => 'View Map & Phone',
        'lounge_sithong' => 'Sithong Lounge',
        'lounge_vangvieng' => 'Vangvieng Riverside',
        'lounge_luangprabang' => 'Luang Prabang Heritage',
        'lounge_pakse' => 'Pakse Bolaven Lounge',
        'sithong_hours' => 'Sithong Road, Vientiane Capital. Open daily: 07:00 - 23:00',
        'vangvieng_hours' => 'Kang Road, Vangvieng Town. Open daily: 07:00 - 24:00',
        'luangprabang_hours' => 'Sisavangvong Road, Luang Prabang. Open daily: 06:30 - 22:00',
        'pakse_hours' => 'Route 13, Pakse City. Open daily: 07:00 - 22:00',
        
        // Our Story
        'story_title' => 'Our Story - LaoFe & Beer',
        'story_subtitle' => 'Origin and Inspiration',
        'story_hero_title1' => 'The Real Game Changer',
        'story_hero_title2' => 'is Coming',
        'story_hero_desc' => 'Discover the champion taste of LaoFe & Beer, meant to be shared with all. Taste the original Lao-adapted coffee & craft beverages only at LaoFe & Beer.',
        'story_hero_cta' => 'Explore Exclusive Menu',
        'story_p1' => 'LaoFe & Beer was born from the desire to present a unique "Lao-adapted Coffee & Bar" concept. We are dedicated to creating a space that blends the richness of local Lao culture with the modern urban lifestyle.',
        'story_p2' => 'We source high-quality coffee beans from the best plantations in Laos to deliver rich, aromatic coffee during the day. As evening approaches, our space transforms into a relaxing hangout featuring craft beverages and modern Lao food.',
        'story_vision' => 'Our Vision',
        'story_vision_desc' => 'To be the leading Lao-adapted coffee and bar brand with international standards, ready to expand nationwide and globally through a robust franchise system.',
        
        // Menu page
        'menu_all' => 'All',
        'menu_coffee' => 'Coffee',
        'menu_drinks' => 'Drinks',
        'menu_bar' => 'Bar / Cocktails',
        'menu_food' => 'Lao-Adapted Food',
        'currency' => 'LAK',
        
        // Locations page
        'loc_title' => 'Our Branches',
        'loc_sub' => 'LaoFe & Beer is ready to welcome you in 4 branches nationwide',
        'loc_open' => 'Opening Hours:',
        'loc_phone' => 'Phone:',
        'loc_map' => 'View Google Maps',
        
        // Franchise page
        'fran_title' => 'Join Our Franchise',
        'fran_sub' => 'Grow with us through a highly professional franchise structure',
        'fran_desc' => 'If you are interested in joining the LaoFe & Beer family and operating a modern coffee & bar outlet, please fill out the contact form below. Our team will contact you shortly.',
        'fran_form_title' => 'Franchise Inquiry Form',
        'fran_name' => 'Full Name',
        'fran_phone' => 'Contact Phone',
        'fran_email' => 'Email Address',
        'fran_loc' => 'Preferred Location / Branch',
        'fran_msg' => 'Additional Message / Notes',
        'fran_submit' => 'Submit Application',
        'fran_success' => 'Franchise inquiry submitted successfully! Our team will contact you soon.',
        
        // Contact page
        'contact_title' => 'Contact Us',
        'contact_sub' => 'Have questions or suggestions? Reach out to us anytime',
        'contact_info' => 'Contact Information',
        'contact_form_title' => 'Send Us a Message',
        'contact_name' => 'Your Name',
        'contact_phone' => 'Phone Number (Optional)',
        'contact_email' => 'Email Address',
        'contact_subject' => 'Subject',
        'contact_msg' => 'Your Message',
        'contact_submit' => 'Send Message',
        'contact_success' => 'Message sent successfully! Thank you for contacting us.',
        
        // About Us (Luckin Style)
        'about_us_title' => 'About LaoFe & Beer',
        'about_us_sub' => 'A leading Lao-adapted coffee & bar brand driven by quality, value, and convenience',
        'about_value_prop_title' => "LaoFe & Beer's Value Proposition",
        'about_high_quality' => 'HIGH QUALITY',
        'about_high_quality_desc' => 'Top Bolaven Plateau coffee beans blended by champion baristas and roasted to perfection. Crafted from over 180 blending formulas to perfectly suit your taste.',
        'about_high_affordability' => 'HIGH AFFORDABILITY',
        'about_high_affordability_desc' => 'Delivering high-end specialty coffee and craft beverages at truly accessible prices for everyday enjoyment.',
        'about_high_convenience' => 'HIGH CONVENIENCE',
        'about_high_convenience_desc' => 'Seamless ordering and modern lounge atmospheres across 4 prime locations with warm, friendly service.',
        'about_company_title' => 'Company',
        'about_company_val' => 'LAOFE & BEER CO., LTD.',
        'about_email_title' => 'E-mail',
        'about_enquiries_title' => 'General Enquiries & Feedback',
        'about_enquiries_hours' => 'Monday to Sunday: 7:00 AM - 11:00 PM',
        'about_join_title' => 'Join Us / Franchise',
        'about_join_sub' => 'See business opportunities',
    ]
];

// ຟັງຊັນແປຄຳສັບ (Translation helper)
function t($key) {
    global $translations, $current_lang;
    if (isset($translations[$current_lang][$key])) {
        return $translations[$current_lang][$key];
    }
    return $key;
}

// ຟັງຊັນດຶງຂໍ້ມູນຈາກ Database ແປພາສາຕາມຟິວ (e.g. name_lo vs name_en)
function td($row, $field) {
    global $current_lang;
    $field_lang = $field . '_' . $current_lang;
    if (isset($row[$field_lang]) && $row[$field_lang] !== '') {
        return $row[$field_lang];
    }
    // ຖ້າບໍ່ມີ ໃຫ້ກັບໄປໃຊ້ ພາສາລາວ (lo)
    $field_default = $field . '_lo';
    return isset($row[$field_default]) ? $row[$field_default] : '';
}
?>
