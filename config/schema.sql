-- Database Schema for LaoFe & Beer
-- Note: On shared hosting, select your database in phpMyAdmin before importing.

-- 1. ຕາຕະລາງຜູ້ໃຊ້ (Users - ທັງ Admin ແລະ ລູກຄ້າສະມາຊິກ)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NULL,
    phone VARCHAR(50) NULL,
    email VARCHAR(100) NULL,
    role VARCHAR(20) DEFAULT 'admin', -- 'admin', 'customer'
    points INT DEFAULT 0,
    tier VARCHAR(50) DEFAULT 'Member',
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

-- 3. ຕາຕະລາງຂ່າວສານ ແລະ ໂປຣໂມຊັນ (News & Promotions)
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

-- 7. ຕາຕະລາງອໍເດີ້ສັ່ງຊື້ (Orders)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    total_amount DECIMAL(12, 0) NOT NULL,
    points_earned INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'Pending', -- 'Pending', 'Paid', 'Completed', 'Cancelled'
    payment_method VARCHAR(50) DEFAULT 'Cash', -- 'Cash', 'BCEL OnePay'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. ຕາຕະລາງລາຍການໃນອໍເດີ້ (Order Items)
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

-- 9. ຕາຕະລາງຈອງໂຕະ (Bookings)
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
    table_zone VARCHAR(50) DEFAULT 'Standard', -- 'Standard', 'Lounge', 'VIP'
    status VARCHAR(50) DEFAULT 'Pending', -- 'Pending', 'Confirmed', 'Cancelled'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. ຕາຕະລາງ Banner (Banners)
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


-- ແຊັກຂໍ້ມູນສາຂາເລີ່ມຕົ້ນ (Branch Seeds)
INSERT INTO branches (name_lo, name_en, address_lo, address_en, hours_lo, hours_en, phone, map_link) VALUES
('ສາຂາ ສີທອງ (ນະຄອນຫຼວງວຽງຈັນ)', 'Sithong Branch (Vientiane)', 'ຖະໜົນສີທອງ, ບ້ານສີທອງ, ເມືອງສີໂຄດຕະບອງ, ນະຄອນຫຼວງວຽງຈັນ', 'Sithong Road, Sithong Village, Sikhottabong District, Vientiane', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 91 111 104', 'https://www.google.com/maps?q=17.966801,102.606311'),
('ສາຂາ ວັງວຽງ', 'Vangvieng Branch', 'ຖະໜົນຄັງແຄມນ້ຳຊອງ, ບ້ານສະຫວ່າງ, ເມືອງວັງວຽງ, ແຂວງວຽງຈັນ', 'Nam Song Riverside Rd, Savang Village, Vangvieng, Vientiane Province', '07:00 - 23:00', '07:00 AM - 11:00 PM', '+856 20 91 111 104', 'https://www.google.com/maps/search/?api=1&query=Vang+Vieng+Nam+Song'),
('ສາຂາ ພູ 9 ຫຼັກ', 'Phu 9 Lak Branch', 'ບ້ານພູ 9 ຫຼັກ, ທາງຫຼວງເລກທີ 13, ແຂວງຫຼວງພະບາງ', 'Phu 9 Lak Village, Route 13, Luang Prabang Province', '08:00 - 20:00', '08:00 AM - 08:00 PM', '+856 20 91 111 104', 'https://www.google.com/maps/search/?api=1&query=Phou+Kao+Lak+Luang+Prabang'),
('ສາຂາ ຫຼວງພະບາງ', 'Luang Prabang Branch', 'ຖະໜົນສີສະຫວ່າງວົງ, ບ້ານສາມແສນໄທ, ເມືອງຫຼວງພະບາງ, ແຂວງຫຼວງພະບາງ', 'Sisavangvong Road, Samsenthai Village, Luang Prabang', '07:00 - 22:00', '07:00 AM - 10:00 PM', '+856 20 91 111 104', 'https://www.google.com/maps/search/?api=1&query=Luang+Prabang+Heritage+Town');
