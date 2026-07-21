CREATE DATABASE IF NOT EXISTS laofe_beer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laofe_beer;

-- 1. ຕາຕະລາງຜູ້ດູແລລະບົບ (Admin Users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ຕາຕະລາງເມນູອາຫານ ແລະ ເຄື່ອງດື່ມ (Menus)
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_lo VARCHAR(100) NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL, -- 'coffee', 'drinks', 'bar', 'food'
    price DECIMAL(12, 0) NOT NULL, -- ລາຄາເປັນກີບ LAK
    description_lo TEXT,
    description_en TEXT,
    image_path VARCHAR(255),
    is_popular TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ຕາຕະລາງຂ່າວສານ ແລະ ໂໂປຣໂມຊັນ (News & Promotions)
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_lo VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    content_lo TEXT NOT NULL,
    content_en TEXT NOT NULL,
    image_path VARCHAR(255),
    is_promo TINYINT(1) DEFAULT 0, -- 1 = ໂປຣໂມຊັນ, 0 = ຂ່າວສານ
    publish_date DATE DEFAULT (CURRENT_DATE),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ຕາຕະລາງຂໍ້ມູນສາຂາ (Branches)
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

-- 5. ຕາຕະລາງລົງທະບຽນແຟຣນໄຊສ໌ (Franchise Applications)
CREATE TABLE IF NOT EXISTS franchise_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    location_preference TEXT NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ຕາຕະລາງຂໍ້ຄວາມຕິດຕໍ່ (Contact Messages)
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ແຊັກຂໍ້ມູນສາຂາເລີ່ມຕົ້ນ (Branch Seeds)
INSERT INTO branches (name_lo, name_en, address_lo, address_en, hours_lo, hours_en, phone, map_link) VALUES
('ສາຂາ ສີທອງ (ນະຄອນຫຼວງວຽງຈັນ)', 'Sithong Branch (Vientiane)', 'ຖະໜົນສີທອງ, ບ້ານສີທອງ, ເມືອງສີໂຄດຕະບອງ, ນະຄອນຫຼວງວຽງຈັນ', 'Sithong Road, Sithong Village, Sikhottabong District, Vientiane', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 1111', 'https://maps.google.com'),
('ສາຂາ ວັງວຽງ', 'Vangvieng Branch', 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ, ແຂວງວຽງຈັນ', 'Nam Song Riverside Rd, Savang Village, Vangvieng, Vientiane Province', '07:00 - 23:00', '07:00 AM - 11:00 PM', '+856 20 5555 2222', 'https://maps.google.com'),
('ສາຂາ ພູ 9 ຫຼັກ', 'Phu 9 Lak Branch', 'ບ້ານພູ 9 ຫຼັກ, ທາງຫຼວງເລກທີ 13, ແຂວງຫຼວງພະບາງ', 'Phu 9 Lak Village, Route 13, Luang Prabang Province', '08:00 - 20:00', '08:00 AM - 08:00 PM', '+856 20 5555 3333', 'https://maps.google.com'),
('ສາຂາ ຫຼວງພະບາງ', 'Luang Prabang Branch', 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ເມືອງຫຼວງພະບາງ, ແຂວງຫຼວງພະບາງ', 'Sisavangvong Road, Samsenthai Village, Luang Prabang', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 5555 4444', 'https://maps.google.com');
