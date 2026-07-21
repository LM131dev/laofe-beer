<?php
// config/db.php

$host = 'localhost';
$db_name = 'laofe_beer';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // ທຳອິດລອງເຊື່ອມຕໍ່ແບບບໍ່ລະບຸຖານຂໍ້ມູນ ເພື່ອສ້າງຖານຂໍ້ມູນຖ້າຫາກຍັງບໍ່ມີ
    $pdo_init = new PDO("mysql:host=$host;charset=$charset", $username, $password, $options);
    $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // ເຊື່ອມຕໍ່ຫາຖານຂໍ້ມູນແທ້
    $pdo = new PDO($dsn, $username, $password, $options);

    // ກວດສອບ ແລະ ສ້າງຕາຕະລາງພື້ນຖານ ຖ້າຫາກຍັງບໍ່ມີ (ເພື່ອຄວາມສະດວກໃນການຕິດຕັ້ງ)
    $sql_schema = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'admin',
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
    ";
    
    $pdo->exec($sql_schema);

    // ກວດສອບ ແລະ ເພີ່ມ Admin ເລີ່ມຕົ້ນ
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
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
        ('ສາຂາ ສີທອງ (ນະຄອນຫຼວງວຽງຈັນ)', 'Sithong Branch (Vientiane)', 'ຖະໜົນສີທອງ, ບ້ານສີທອງ, ເມືອງສີໂຄດຕະບອງ, ນະຄອນຫຼວງວຽງຈັນ', 'Sithong Road, Sithong Village, Sikhottabong District, Vientiane', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 1111', 'https://maps.google.com'),
        ('ສາຂາ ວັງວຽງ', 'Vangvieng Branch', 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ, ແຂວງວຽງຈັນ', 'Nam Song Riverside Rd, Savang Village, Vangvieng, Vientiane Province', '07:00 - 23:00', '07:00 AM - 11:00 PM', '+856 20 5555 2222', 'https://maps.google.com'),
        ('ສາຂາ ພູ 9 ຫຼັກ', 'Phu 9 Lak Branch', 'ບ້ານພູ 9 ຫຼັກ, ທາງຫຼວງເລກທີ 13, ແຂວງຫຼວງພະບາງ', 'Phu 9 Lak Village, Route 13, Luang Prabang Province', '08:00 - 20:00', '08:00 AM - 08:00 PM', '+856 20 5555 3333', 'https://maps.google.com'),
        ('ສາຂາ ຫຼວງພະບາງ', 'Luang Prabang Branch', 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ເມືອງຫຼວງພະບາງ, ແຂວງຫຼວງພະບາງ', 'Sisavangvong Road, Samsenthai Village, Luang Prabang', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 4444', 'https://maps.google.com');
        ";
        $pdo->exec($sql_branches);
    }

} catch (\PDOException $e) {
    die("ການເຊື່ອມຕໍ່ຖານຂໍ້ມູນຫຼົ້ມເຫຼວ: " . $e->getMessage());
}
?>
