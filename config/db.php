<?php
// config/db.php
ini_set('display_errors', 0);
error_reporting(E_ALL);

$host = 'sql312.infinityfree.com';
$db_name = 'if0_42919161_laofe_db';
$username = 'if0_42919161';
$password = 'HaTW8bVQ5E4xFq';
$charset = 'utf8mb4';

$is_localhost = isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($is_localhost) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
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
    $pdo = new PDO($dsn, $username, $password, $options);

    // ກວດສອບ ແລະ ສ້າງຕາຕະລາງພື້ນຖານ ຖ້າຫາກເປັນ Localhost (XAMPP)
    if ($is_localhost) {
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

        try {
            $cols_bn = $pdo->query("SHOW COLUMNS FROM banners");
            $col_names_bn = $cols_bn->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('target_page', $col_names_bn)) {
                $pdo->exec("ALTER TABLE banners ADD COLUMN target_page VARCHAR(50) DEFAULT 'all' AFTER is_active");
            }
        } catch (\Exception $e) {}
    }

} catch (\PDOException $e) {
    die("
    <div style='font-family: Arial, sans-serif; background: #FFFDF2; padding: 30px; text-align: center; border-radius: 20px; border: 2px solid #DCAE6C; max-width: 550px; margin: 60px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); color: #3D0B16;'>
        <h2 style='font-size: 20px; margin: 0 0 10px 0; color: #6B1D2F;'>Database Connection Error</h2>
        <p style='font-size: 14px; color: #531321; line-height: 1.6;'>
            ບໍ່ສາມາດເຊື່ອມຕໍ່ Database ໄດ້: <br>
            <code style='background: #F4EFE0; padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #C0392B;'>" . htmlspecialchars($e->getMessage()) . "</code>
        </p>
    </div>
    ");
}
?>
