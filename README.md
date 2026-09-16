# ☕ LaoFe & Beer - ລະບົບເວັບໄຊ ແລະ ຜູ້ດູແລ (Official System Documentation)

ເວັບໄຊຢ່າງເປັນທາງການຂອງ **LaoFe & Beer** - ກາເຟ ບໍລະເວນ ແລະ ບາເບຍສົດ ໃນຮູບແບບລາວປະຍຸກ. ລະບົບນີ້ພັດທະນາດ້ວຍ PHP, MySQL, ແລະ static minified Tailwind CSS ໂດຍຮອງຮັບ 2 ພາສາ (ລາວ & ອັງກິດ) ພ້ອມລະບົບ Admin ຈັດການຂໍ້ມູນ, ລະບົບສະມາຊິກ (Loyalty Points), ລະບົບຈອງໂຕະອອນໄລນ໌ ແລະ ຟອມສະໝັກ Franchise / ຕິດຕໍ່.

---

## 🛠️ 1. ຄວາມຕ້ອງການຂອງລະບົບ (System Requirements)
- **Web Server**: Apache / Nginx (ຮອງຮັບ mod_rewrite)
- **PHP Version**: 8.0, 8.1, 8.2+
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **PHP Extensions**: `pdo_mysql`, `gd` (ຫຼື `gmp` / `mbstring`), `curl`, `json`

---

## 🚀 2. ຄູ່ມືການຕິດຕັ້ງໃນ Local (XAMPP / Local Server)

1. **ກັອບປີໄຟລ໌ໂຄງການ**:
   - ຍ້າຍໂຟນເດີໂຄງການ `laofe-beer` ໄປໄວ້ທີ່ `c:\xampp\htdocs\laofe-beer` (ຫຼື `www/` ສຳລັບ WAMP/Laragon).

2. **ຕັ້ງຄ່າ Database**:
   - ເປີດ phpMyAdmin (`http://localhost/phpmyadmin`)
   - ສ້າງ Database ໃໝ່ ຊື່ `laofe_beer` (Collation: `utf8mb4_unicode_ci`)
   - Import ໄຟລ໌ `config/schema.sql` ເຂົ້າໄປໃນ Database `laofe_beer`.

3. **ຕັ້ງຄ່າ Environment Variables (`.env`)**:
   - ສ້າງໄຟລ໌ `.env` ຢູ່ Root Directory (ຫຼື ກັອບປີຈາກ `.env.example`):
     ```env
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=laofe_beer
     DB_USER=root
     DB_PASS=

     SMTP_HOST=smtp.gmail.com
     SMTP_PORT=587
     SMTP_USER=your_email@gmail.com
     SMTP_PASS=your_app_password
     MAIL_FROM=your_email@gmail.com
     MAIL_FROM_NAME="LaoFe & Beer"
     ADMIN_EMAIL=owner@laofebeer.com

     TURNSTILE_SITE_KEY=your_cloudflare_site_key
     TURNSTILE_SECRET_KEY=your_cloudflare_secret_key
     ```

4. **ເປີດໃຊ້ງານ**:
   - ເຂົ້າເວັບໄຊຜ່ານ: `http://localhost/laofe-beer/`
   - ເຂົ້າລະບົບ Admin ຜ່ານ: `http://localhost/laofe-beer/admin/`

---

## 🌐 3. ຄູ່ມືການ Deploy ឡើង Hostinger / cPanel (Production Deployment)

1. **Upload ໄຟລ໌ໂຄງການ**:
   - ແຕກໄຟລ໌ zip `laofe-beer-deploy.zip` ໄປໄວ້ທີ່ໂຟນເດີ `public_html` ເທິງ Hosting.

2. **ສ້າງ MySQL Database ເທິງ Hosting**:
   - ເຂົ້າໄປທີ່ cPanel / Hostinger hPanel ➔ **MySQL Databases**.
   - ສ້າງ Database ໃໝ່ ເຊັ່ນ: `laofe_db`.
   - ສ້າງ Database User ໃໝ່ (ກຳນົດລະຫັດຜ່ານທີ່ປອດໄພ) ແລະ ມອບສິດ `ALL PRIVILEGES` ໃຫ້ User ດັ່ງກ່າວ.

3. **Import Database Schema**:
   - ເປີດ phpMyAdmin ເທິງ Hosting.
   - ເລືອກ Database `laofe_db` ແລ້ວກົດ **Import** ➔ ເລືອກໄຟລ໌ `config/schema.sql`.

4. **ອັບເດດ `.env` ເທິງ Hosting**:
   - ແກ້ໄຂໄຟລ໌ `.env` ໃນ Server ໃຫ້ກົງກັບ Database ແລະ ອີເມລຂອງ LaoFe:
     ```env
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=laofe_db
     DB_USER=laofe_dbuser
     DB_PASS=YourStrongDbPassword123!
     ```

---

## 🔐 4. ຂໍ້ມູນສິດເຂົ້າເຖິງລະບົບ Admin (Initial Admin Credentials)

- **URL ເຂົ້າລະບົບ ແອດມິນ**: `https://your-domain.com/admin/`
- **Username**: `admin`
- **Password (ເລີ່ມຕົ້ນ)**: `LaoFe2026#Secure`

> ⚠️ **ຄຳເຕືອນ**: ກະລຸນາປ່ຽນລະຫັດຜ່ານໃໝ່ທັນທີຫຼັງຈາກຮັບມອບລະບົບ ໂດຍເຂົ້າໄປທີ່ **Admin Panel ➔ ຕັ້ງຄ່າລະຫັດຜ່ານ / Manage Users**.

---

## 💾 5. ວິທີ Backup ຂໍ້ມູນ (Data Backup Procedure)

### 1. Backup ຂໍ້ມູນ Database (ຂໍ້ຄວາມ, ເມນູ, ຂ່າວ, ອໍເດີ້, ສາຂາ, ຜູ້ໃຊ້)
- **ຜ່ານ phpMyAdmin**:
  1. ເຂົ້າ phpMyAdmin ➔ ເລືອກ Database `laofe_db`.
  2. ກົດເມນູ **Export** ➔ ເລືອກ **Quick Export** (Format: `SQL`).
  3. ກົດ **Go** ເພື່ອດາວໂຫຼດໄຟລ໌ `.sql` ເກັບໄວ້ໃນເຄື່ອງ.
- **ຜ່ານ Hostinger/cPanel Automated Backup**:
  - ໃນ hPanel ➔ **Files** ➔ **Backups** ➔ ເລືອກ **Database Backups** ➔ ດາວໂຫຼດ backup ປະຈຳອາທິດ.

### 2. Backup ໄຟລ໌ຮູບພາບ ແລະ ຊອດໂຄ້ດ (Files & Images)
- ເຂົ້າ File Manager ➔ ເລືອກໂຟນເດີ `public_html` (ຫຼື `assets/images/`) ➔ ກົດ **Compress** ➔ ດາວໂຫຼດໄຟລ໌ `.zip` ເກັບໄວ້.

---

## 🛡️ 6. ເງື່ອນໄຂການຮັບປະກັນ (6-Month Warranty & Support Policy)

- **ໄລຍະເວລາຮັບປະກັນ**: **6 ເດືອນ** (ນັບຈາກວັນທີສົ່ງມອບ).
- **ຂອບເຂດການຮັບປະກັນ**:
  - ແກ້ໄຂ **Bug**, ຂໍ້ຜິດພາດຂອງລະບົບ (System Errors), ຫຼື Error ທີ່ເກີດຈາກໂຄ້ດ ໂດຍບໍ່ມີຄ່າໃຊ້ຈ່າຍເພີ່ມເຕີມ.
  - ໃຫ້ຄຳປຶກສາ ແລະ ຊ່ວຍເຫຼືອດ້ານເຕັກນິກ, ການຕັ້ງຄ່າ Domain/Hosting ແລະ ການສຳຮອງຂໍ້ມູນ.
  - ອັບເດດຄວາມປອດໄພຂອງໂຄ້ດ ແລະ ລະບົບ.

---
*ຈັດທຳໂດຍ ທີມງານພັດທະນາ LaoFe & Beer System (2026)*
