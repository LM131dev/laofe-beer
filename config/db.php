<?php
// config/db.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'sql312.infinityfree.com';
$db_name = 'if0_42919161_laofe_db';
$username = 'if0_42919161';
$password = 'HaTW8bVQ5E4xFq';
$charset = 'utf8mb4';

// ອັດຕະໂນມັດສະຫຼັບໃຊ້ Local Server ຖ້າຢູ່ເທິງເຄື່ອງ XAMPP (Localhost)
if (isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1')) {
    $host = 'localhost';
    $db_name = 'laofe_beer';
    $username = 'root';
    $password = '';
}

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    try {
        $pdo = new PDO($dsn, $username, $password, $options);
    } catch (\PDOException $e_connect) {
        if ($host === 'localhost' || $host === '127.0.0.1') {
            $pdo_init = new PDO("mysql:host=$host;charset=$charset", $username, $password, $options);
            $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo = new PDO($dsn, $username, $password, $options);
        } else {
            throw $e_connect;
        }
    }

    // ກວດສອບ ແລະ ສ້າງຕາຕະລາງພື້ນຖານ ຖ້າຫາກເປັນ Local Server
    if ($host === 'localhost' || $host === '127.0.0.1') {
    $sql_schema = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        fullname VARCHAR(100) NULL,
        phone VARCHAR(50) NULL,
        email VARCHAR(100) NULL,
        role VARCHAR(20) DEFAULT 'admin',
        points INT DEFAULT 0,
        tier VARCHAR(50) DEFAULT 'Member',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS menus (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_lo VARCHAR(100) NOT NULL,
        name_en VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(12, 0) NOT NULL,
        description_lo TEXT,
        description_en TEXT,
        image_path VARCHAR(255),
        is_popular TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title_lo VARCHAR(255) NOT NULL,
        title_en VARCHAR(255) NOT NULL,
        content_lo TEXT NOT NULL,
        content_en TEXT NOT NULL,
        image_path VARCHAR(255),
        is_promo TINYINT(1) DEFAULT 0,
        publish_date DATE DEFAULT (CURRENT_DATE),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS branches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_lo VARCHAR(100) NOT NULL,
        name_en VARCHAR(100) NOT NULL,
        address_lo TEXT NOT NULL,
        address_en TEXT NOT NULL,
        hours_lo VARCHAR(100) NOT NULL,
        hours_en VARCHAR(100) NOT NULL,
        phone VARCHAR(50),
        map_link TEXT,
        image_path VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS franchise_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        location_preference TEXT NOT NULL,
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(50),
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(150),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        total_amount DECIMAL(12, 0) NOT NULL,
        points_earned INT DEFAULT 0,
        status VARCHAR(50) DEFAULT 'Pending',
        payment_method VARCHAR(50) DEFAULT 'Cash',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        menu_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(12, 0) NOT NULL,
        notes TEXT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        branch_id INT NOT NULL,
        booking_name VARCHAR(100) NOT NULL,
        booking_phone VARCHAR(50) NOT NULL,
        booking_email VARCHAR(100) NOT NULL,
        booking_date DATE NOT NULL,
        booking_time TIME NOT NULL,
        guest_count INT NOT NULL,
        table_zone VARCHAR(50) DEFAULT 'Standard',
        deposit_amount DECIMAL(12, 0) DEFAULT 0,
        payment_status VARCHAR(50) DEFAULT 'Unpaid',
        status VARCHAR(50) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        badge_lo VARCHAR(255) NULL,
        badge_en VARCHAR(255) NULL,
        title_lo VARCHAR(255) NOT NULL,
        title_en VARCHAR(255) NOT NULL,
        subtitle_lo TEXT NULL,
        subtitle_en TEXT NULL,
        image_path VARCHAR(255) NOT NULL,
        btn1_text_lo VARCHAR(100) NULL,
        btn1_text_en VARCHAR(100) NULL,
        btn1_link VARCHAR(255) NULL,
        btn2_text_lo VARCHAR(100) NULL,
        btn2_text_en VARCHAR(100) NULL,
        btn2_link VARCHAR(255) NULL,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title_lo VARCHAR(255) NULL,
        title_en VARCHAR(255) NULL,
        image_path VARCHAR(255) NOT NULL,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql_schema);

    // ກວດສອບ ແລະ ປັບປຸງຕາຕະລາງ users ທີ່ເຄີຍມີຢູ່ແລ້ວໃຫ້ມີຟິວສະມາຊິກ (ສຳລັບຖານຂໍ້ມູນເກົ່າ)
        // ກວດສອບ ແລະ ປັບປຸງຕາຕະລາງ banners (ສຳລັບຟິວ target_page)
    try {
        $cols_bn = $pdo->query("SHOW COLUMNS FROM banners");
        $col_names_bn = $cols_bn->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('target_page', $col_names_bn)) {
            $pdo->exec("ALTER TABLE banners ADD COLUMN target_page VARCHAR(50) DEFAULT 'all' AFTER is_active");
        }
    } catch (\Exception $e) {}

    $cols = $pdo->query("SHOW COLUMNS FROM users");
    $col_names = $cols->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('fullname', $col_names)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN fullname VARCHAR(100) NULL AFTER username");
    }
    if (!in_array('phone', $col_names)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(50) NULL AFTER fullname");
    }
    if (!in_array('email', $col_names)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL AFTER phone");
    }
    if (!in_array('points', $col_names)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN points INT DEFAULT 0 AFTER role");
    }
    if (!in_array('tier', $col_names)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN tier VARCHAR(50) DEFAULT 'Member' AFTER points");
    }

    // ກວດສອບ ແລະ ປັບປຸງຕາຕະລາງ bookings (ສຳລັບຖານຂໍ້ມູນເກົ່າ)
    $cols_b = $pdo->query("SHOW COLUMNS FROM bookings");
    $col_names_b = $cols_b->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('deposit_amount', $col_names_b)) {
        $pdo->exec("ALTER TABLE bookings ADD COLUMN deposit_amount DECIMAL(12, 0) DEFAULT 0 AFTER table_zone");
    }
    if (!in_array('payment_status', $col_names_b)) {
        $pdo->exec("ALTER TABLE bookings ADD COLUMN payment_status VARCHAR(50) DEFAULT 'Unpaid' AFTER deposit_amount");
    }

    // ກວດສອບ ແລະ ປັບປຸງຕາຕະລາງ news (ສຳລັບບົດຄວາມ & ກິດຈະກຳ)
    $cols_n = $pdo->query("SHOW COLUMNS FROM news");
    $col_names_n = $cols_n->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('is_featured', $col_names_n)) {
        $pdo->exec("ALTER TABLE news ADD COLUMN is_featured TINYINT(1) DEFAULT 0 AFTER is_promo");
    }
    if (!in_array('category', $col_names_n)) {
        $pdo->exec("ALTER TABLE news ADD COLUMN category VARCHAR(50) DEFAULT 'article_news' AFTER is_featured");
    }
    if (!in_array('event_location', $col_names_n)) {
        $pdo->exec("ALTER TABLE news ADD COLUMN event_location VARCHAR(255) DEFAULT NULL AFTER category");
    }
    if (!in_array('event_date', $col_names_n)) {
        $pdo->exec("ALTER TABLE news ADD COLUMN event_date VARCHAR(100) DEFAULT NULL AFTER event_location");
    }
    if (!in_array('status', $col_names_n)) {
        $pdo->exec("ALTER TABLE news ADD COLUMN status VARCHAR(20) DEFAULT 'published' AFTER event_date");
    }

    // ກວດສອບ ແລະ ປັບປຸງຕາຕະລາງ orders (ສຳລັບ Scan QR Code ຢູ່ໂຕະ)
    $cols_o = $pdo->query("SHOW COLUMNS FROM orders");
    $col_names_o = $cols_o->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('table_number', $col_names_o)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN table_number VARCHAR(50) DEFAULT 'Takeaway' AFTER user_id");
    }
    if (!in_array('order_type', $col_names_o)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN order_type VARCHAR(50) DEFAULT 'dine_in_qr' AFTER table_number");
    }
    if (!in_array('guest_name', $col_names_o)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN guest_name VARCHAR(100) NULL AFTER order_type");
    }
    if (!in_array('notes', $col_names_o)) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN notes TEXT NULL AFTER guest_name");
    }

    // ກວດສອບ ແລະ ເພີ່ມ Admin ເລີ່ມຕົ້ນ
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $user_count = $stmt->fetchColumn();
    if ($user_count == 0) {
        $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt_insert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt_insert->execute(['admin', $admin_pass, 'admin']);
    }

    // ກວດສອບ ແລະ ເພີ່ມຂໍ້ມູນສາຂາເລີ່ມຕົ້ນ
    $stmt_b = $pdo->query("SELECT COUNT(*) FROM branches");
    $branch_count = $stmt_b->fetchColumn();
    if ($branch_count == 0) {
        $sql_branches = "
        INSERT INTO branches (name_lo, name_en, address_lo, address_en, hours_lo, hours_en, phone, map_link) VALUES
        ('ສາຂາ ສີທອງ (ນະຄອນຫຼວງວຽງຈັນ)', 'Sithong Branch (Vientiane)', 'ຖະໜົນສີທອງ, ບ້ານສີທອງ, ເມືອງສີໂຄດຕະບອງ, ນະຄອນຫຼວງວຽງຈັນ', 'Sithong Road, Sithong Village, Sikhottabong District, Vientiane', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 1111', 'https://www.google.com/maps?q=17.966801,102.606311'),
        ('ສາຂາ ວັງວຽງ', 'Vangvieng Branch', 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ, ແຂວງວຽງຈັນ', 'Nam Song Riverside Rd, Savang Village, Vangvieng, Vientiane Province', '07:00 - 23:00', '07:00 AM - 11:00 PM', '+856 20 5555 2222', 'https://maps.google.com'),
        ('ສາຂາ ພູ 9 ຫຼັກ', 'Phu 9 Lak Branch', 'ບ້ານພູ 9 ຫຼັກ, ທາງຫຼວງເລກທີ 13, ແຂວງຫຼວງພະບາງ', 'Phu 9 Lak Village, Route 13, Luang Prabang Province', '08:00 - 20:00', '08:00 AM - 08:00 PM', '+856 20 5555 3333', 'https://maps.google.com'),
        ('ສາຂາ ຫຼວງພະບາງ', 'Luang Prabang Branch', 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ເມືອງຫຼວງພະບາງ, ແຂວງຫຼວງພະບາງ', 'Sisavangvong Road, Samsenthai Village, Luang Prabang', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 4444', 'https://maps.google.com');
        ";
        $pdo->exec($sql_branches);
    }

    // ກວດສອບ ແລະ ເພີ່ມຂໍ້ມູນ Banner ເລີ່ມຕົ້ນ
    $stmt_banner_cnt = $pdo->query("SELECT COUNT(*) FROM banners");
    $banner_count = $stmt_banner_cnt->fetchColumn();
    if ($banner_count == 0) {
        $sql_banners = "
        INSERT INTO banners (badge_lo, badge_en, title_lo, title_en, subtitle_lo, subtitle_en, btn1_text_lo, btn1_text_en, btn1_link, btn2_text_lo, btn2_text_en, btn2_link, sort_order, is_active, image_path) VALUES
        (
            'ກາເຟ ແລະ ບາ ແບບລາວປະຍຸກ', 
            'Lao-Adapted Coffee & Bar', 
            'ລົດຊາດລາວປະຍຸກ ແທ້ໆ', 
            'Authentic Lao-Adapted Taste', 
            'ຄົ້ນພົບຄວາມລົງຕົວຂອງກາເຟຊັ້ນດີໃນຍາມກາງເວັນ ແລະ ເຄື່ອງດື່ມສຸດພິເສດໃນຍາມຄ່ຳຄືນ ທີ່ LaoFe & Beer.', 
            'Discover the perfect blend of premium coffee by day and craft beverages by night at LaoFe & Beer.', 
            'ເບິ່ງເມນູຂອງພວກເຮົາ', 
            'Explore Our Menu', 
            'menu.php', 
            'ເລື່ອງລາວຂອງເຮົາ', 
            'Our Story', 
            'our-story.php', 
            0, 
            1, 
            'assets/images/hero_banner.png'
        ),
        (
            'ແນວຄິດການພັກຜ່ອນຍາມຄ່ຳຄືນ', 
            'Premium Nightlife Concept', 
            'ກາງເວັນກາເຟ ກາງຄືນເບຍສົດ', 
            'Coffee By Day, Beer By Night', 
            'ດື່ມດ່ຳກັບເບຍຄຣາບລາວ ແລະ ຄັອກເທວສະໝຸນໄພສູດພິເສດ ໃນບັນຍາກາດຊິລໆຍາມຄ່ຳຄືນ', 
            'Savor the craft Lao beer and herbal signature cocktails in a relaxed premium lounge evening.', 
            'ເບິ່ງເມນູຂອງພວກເຮົາ', 
            'Explore Our Menu', 
            'menu.php', 
            'ສາຂາ', 
            'Locations', 
            'locations.php', 
            1, 
            1, 
            'assets/images/beer_drink.png'
        );
        ";
        $pdo->exec($sql_banners);
    }

    // ກວດສອບ ແລະ ເພີ່ມຂໍ້ມູນ Gallery ເລີ່ມຕົ້ນ
    $stmt_gal_cnt = $pdo->query("SELECT COUNT(*) FROM gallery");
    $gal_count = $stmt_gal_cnt->fetchColumn();
    if ($gal_count == 0) {
        $sql_gallery = "
        INSERT INTO gallery (title_lo, title_en, image_path, sort_order, is_active) VALUES
        ('ບັນຍາກາດຮ້ານ LaoFe', 'LaoFe Atmosphere', 'assets/images/hero_banner.png', 1, 1),
        ('ເລື່ອງລາວ LaoFe & Beer', 'Our Story LaoFe & Beer', 'assets/images/our_story.png', 2, 1),
        ('ກາເຟສົດ ຄຸນນະພາບ', 'Quality Fresh Coffee', 'assets/images/coffee.png', 3, 1),
        ('ເບຍສົດ ແລະ ເຄື່ອງດື່ມ', 'Draft Beer & Craft Drinks', 'assets/images/beer_drink.png', 4, 1);
        ";
        $pdo->exec($sql_gallery);
    }

    // ກວດສອບ ແລະ ເພີ່ມຂໍ້ມູນ News / Articles / Events ເລີ່ມຕົ້ນ
    $stmt_news_cnt = $pdo->query("SELECT COUNT(*) FROM news");
    $news_count = $stmt_news_cnt->fetchColumn();
    if ($news_count == 0) {
        $sql_news_init = "
        INSERT INTO news (title_lo, title_en, content_lo, content_en, image_path, is_promo, category, event_location, event_date, status, publish_date) VALUES
        (
            'ເທດສະການກາເຟດອຍບໍລະເວນ ແລະ ງານແຂ່ງຂັນບາຣິສຕ້າ 2026',
            'Bolaven Coffee Festival & Barista Championship 2026',
            'ຂໍເຊີນຊວນຄົນຮັກກາເຟທຸກທ່ານ ເຂົ້າຮ່ວມງານເທດສະການກາເຟດອຍບໍລະເວນ ພົບກັບການແຂ່ງຂັນຊົງກາເຟດຣິບ Drip Coffee, ງານຊິມກາເຟ Cupping Session, ແລະ ເວີກຊອບຈາກແຊັມບາຣິສຕ້າລະດັບປະເທດ.',
            'Join us for the annual Bolaven Coffee Festival featuring Drip Coffee Competitions, Coffee Cupping Sessions, and Masterclasses by National Barista Champions.',
            'assets/images/hero_banner.png',
            2,
            'event_workshop',
            'LaoFe & Beer ສາຂາ ນ້ຳພຸ (Vientiane)',
            '15 ພະຈິກ 2026 | 09:00 - 17:00',
            'published',
            '2026-11-01'
        ),
        (
            'ດົນຕີສົດ Jazz & Acoustic Night ຍາມຄ່ຳຄືນ ຢູ່ LaoFe Lounge',
            'Live Jazz & Acoustic Night at LaoFe Evening Lounge',
            'ເພີ່ມຄວາມຊິລໃນຄ່ຳຄືນວັນເສົາ ດ້ວຍສຽງດົນຕີ Jazz & Acoustic ສົດໆ ພ້ອມໂປຣໂມຊັນ Craft Draft Beer ຊື້ 2 ແຖມ 1 ແລະ Signature Lao Cocktail.',
            'Chill out your Saturday night with live Jazz & Acoustic performances paired with Buy 2 Get 1 Free Craft Draft Beer & Signature Lao Cocktails.',
            'assets/images/beer_drink.png',
            2,
            'event_music',
            'LaoFe & Beer ສາຂາ ວັງວຽງ & ຫຼວງພະບາງ',
            'ທຸກໆ ວັນເສົາ | 19:30 - 22:30',
            'published',
            '2026-10-25'
        ),
        (
            'ສິລະປະການຊົງກາເຟ Manual Pour Over & Cupping Masterclass',
            'Manual Pour Over & Cupping Masterclass',
            'ຮຽນຮູ້ສິລະປະການຊົງກາເຟດຣິບ Pour Over ຈາກແຊັມບາຣິສຕ້າ LaoFe ພ້ອມຊີມກາເຟ Specialty ເມັດດ່ຽວດອຍບໍລະເວນ ຂົ້ວພິເສດ.',
            'Master the art of manual pour-over coffee brewing and taste single-origin Bolaven specialty coffee beans with LaoFe master baristas.',
            'assets/images/coffee.png',
            2,
            'event_workshop',
            'LaoFe & Beer ສາຂາ ຫຼວງພະບາງ & ວຽງຈັນ',
            '22 ພະຈິກ 2026 | 13:30 - 16:30',
            'published',
            '2026-11-05'
        ),
        (
            'ຄວາມລັບຂອງເມັດກາເຟ ອາຣາບິກ້າ ບໍລະເວນ (Bolaven Specialty)',
            'The Secret Behind Bolaven Plateau Arabica Coffee Beans',
            'ເຈາະເລິກຄວາມພິເສດຂອງດິນພູເຂົາໄຟເກົ່າ ດອຍບໍລະເວນ ທີ່ເຮັດໃຫ້ເມັດກາເຟ LaoFe ມີກິ່ນຫອມອະລົມມ່າ ຫອມຫວານຄືດອກໄມ້ປ່າ ແລະ ມີລົດຊາດກົມກ່ອມເປັນເອກະລັກ.',
            'Explore how the fertile volcanic soil of Bolaven Plateau creates LaoFe’s distinctive aroma, floral sweetness, and smooth balanced flavor profile.',
            'assets/images/our_story.png',
            0,
            'article_coffee',
            NULL,
            NULL,
            'published',
            '2026-10-10'
        ),
        (
            'ເລື່ອງລາວຈາກ Brewmaster: ຄວາມເປັນມາຂອງ Amber Grain Ale & ຄັອກເທວສະໝຸນໄພ',
            'Meet the Brewmaster: Crafting Amber Grain Ale & Herbal Cocktails',
            'ບົດສຳພາດຜູ້ຢູ່ເບື້ອງຫຼັງຄວາມຮົ່ມເຢັນ ແລະ ລົດຊາດອັນເປັນເອກະລັກຂອງ Amber Grain Ale ພ້ອມກັບການປະສົມປະສານສະໝຸນໄພທ້ອງຖິ່ນລາວ ໃນຄັອກເທວສູດພິເສດ.',
            'An exclusive interview with our head brewmaster discussing the craftsmanship behind our Amber Grain Ale and locally-infused herbal signature cocktails.',
            'assets/images/beer_drink.png',
            0,
            'article_culture',
            NULL,
            NULL,
            'published',
            '2026-10-18'
        );
        ";
        $pdo->exec($sql_news_init);
    }

    // ກວດສອບ ແລະ ເພີ່ມກິດຈະກຳທີ 3 ຖ້າກິດຈະກຳມີໜ້ອຍກວ່າ 3 ລາຍການ
    $stmt_ev_cnt = $pdo->query("SELECT COUNT(*) FROM news WHERE is_promo = 2 OR category LIKE 'event_%'");
    $ev_count = $stmt_ev_cnt->fetchColumn();
    if ($ev_count < 3) {
        $sql_add_3rd_ev = "
        INSERT INTO news (title_lo, title_en, content_lo, content_en, image_path, is_promo, category, event_location, event_date, status, publish_date) VALUES
        (
            'ສິລະປະການຊົງກາເຟ Manual Pour Over & Cupping Masterclass',
            'Manual Pour Over & Cupping Masterclass',
            'ຮຽນຮູ້ສິລະປະການຊົງກາເຟດຣິບ Pour Over ຈາກແຊັມບາຣິສຕ້າ LaoFe ພ້ອມຊີມກາເຟ Specialty ເມັດດ່ຽວດອຍບໍລະເວນ ຂົ້ວພິເສດ.',
            'Master the art of manual pour-over coffee brewing and taste single-origin Bolaven specialty coffee beans with LaoFe master baristas.',
            'assets/images/coffee.png',
            2,
            'event_workshop',
            'LaoFe & Beer ສາຂາ ຫຼວງພະບາງ & ວຽງຈັນ',
            '22 ພະຈິກ 2026 | 13:30 - 16:30',
            'published',
            '2026-11-05'
        );
        ";
        $pdo->exec($sql_add_3rd_ev);
    }

    // ກວດສອບ ແລະ ເພີ່ມບົດຄວາມທີ 2 ຖ້າບົດຄວາມມີໜ້ອຍກວ່າ 2 ລາຍການ
    $stmt_art_cnt = $pdo->query("SELECT COUNT(*) FROM news WHERE is_promo = 0 OR category LIKE 'article_%'");
    $art_count = $stmt_art_cnt->fetchColumn();
    if ($art_count < 2) {
        $sql_add_2nd_art = "
        INSERT INTO news (title_lo, title_en, content_lo, content_en, image_path, is_promo, category, event_location, event_date, status, publish_date) VALUES
        (
            'ເລື່ອງລາວຈາກ Brewmaster: ຄວາມເປັນມາຂອງ Amber Grain Ale & ຄັອກເທວສະໝຸນໄພ',
            'Meet the Brewmaster: Crafting Amber Grain Ale & Herbal Cocktails',
            'ບົດສຳພາດຜູ້ຢູ່ເບື້ອງຫຼັງຄວາມຮົ່ມເຢັນ ແລະ ລົດຊາດອັນເປັນເອກະລັກຂອງ Amber Grain Ale ພ້ອມກັບການປະສົມປະສານສະໝຸນໄພທ້ອງຖິ່ນລາວ ໃນຄັອກເທວສູດພິເສດ.',
            'An exclusive interview with our head brewmaster discussing the craftsmanship behind our Amber Grain Ale and locally-infused herbal signature cocktails.',
            'assets/images/beer_drink.png',
            0,
            'article_culture',
            NULL,
            NULL,
            'published',
            '2026-10-18'
        );
        ";
        $pdo->exec($sql_add_2nd_art);
    }
    }

} catch (\PDOException $e) {
    die("
    <div style='font-family: Arial, sans-serif; background: #FFFDF2; padding: 30px; text-align: center; border-radius: 20px; border: 2px solid #DCAE6C; max-width: 550px; margin: 60px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); color: #3D0B16;'>
        <div style='font-size: 40px; margin-bottom: 10px;'>⚠️</div>
        <h2 style='font-size: 20px; margin: 0 0 10px 0; color: #6B1D2F;'>Database Connection Error</h2>
        <p style='font-size: 14px; color: #531321; line-height: 1.6;'>
            ບໍ່ສາມາດເຊື່ອມຕໍ່ Database ໄດ້: <br>
            <code style='background: #F4EFE0; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #C0392B;'>" . htmlspecialchars($e->getMessage()) . "</code>
        </p>
        <hr style='border: 0; border-top: 1px solid #E7DFCC; margin: 20px 0;'>
        <p style='font-size: 13px; color: #666; margin: 0;'>
            💡 <b>ວິທີແກ້ໄຂ:</b> ກະລຸນາກວດສອບ <b>vPanel Password</b> ໃນໄຟລ໌ <code>config/db.php</code> (ບັນທັດທີ 7) ໃຫ້ຖືກຕ້ອງ.
        </p>
    </div>
    ");
}
?>
