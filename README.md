# ☕ LaoFe & Beer - ລະບົບເວັບໄຊ ແລະ ຜູ້ດູແລ (Official System Documentation)

ເວັບໄຊຢ່າງເປັນທາງການຂອງ **LaoFe & Beer** ໃນຮູບແບບລາວປະຍຸກ. ລະບົບນີ້ພັດທະນາດ້ວຍ PHP, MySQL, ແລະ static minified Tailwind CSS ໂດຍຮອງຮັບ 2 ພາສາ (ລາວ & ອັງກິດ) ພ້ອມລະບົບ Admin ຈັດການຂໍ້ມູນ, ລະບົບສະມາຊິກ (Loyalty Points), ລະບົບຈອງໂຕະອອນໄລນ໌ ແລະ ຟອມສະໝັກ Franchise / ຕິດຕໍ່.

---

## 🛠️ 1. ຄວາມຕ້ອງການຂອງລະບົບ (System Requirements)
- **Web Hosting**: Hostatom Web Hosting (DirectAdmin / cPanel Control Panel)
- **Web Server**: Apache / Nginx (ຮອງຮັບ mod_rewrite)
- **PHP Version**: PHP 8.0, 8.1, 8.2+
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **PHP Extensions**: `pdo_mysql`, `gd` (ຫຼື `gmp` / `mbstring`), `curl`, `json`

---

## 🚀 2. ຄູ່ມືການຕິດຕັ້ງ ແລະ Deploy ເທິງ Hostatom Web Hosting

1. **Upload ໄຟລ໌ໂຄງການ (Upload Project Package)**:
   - ເຂົ້າລະບົບຈັດການ **Hostatom Control Panel** (DirectAdmin / cPanel) ➔ ເປີດ **File Manager**.
   - ເຂົ້າໄປທີ່ໂຟນເດີ `public_html` (ຫຼື ໂຟນເດີ Root ຂອງ Domain).
   - ກົດ **Upload File** ເລືອກໄຟລ໌ `laofe-beer-deploy.zip` ແລ້ວກົດ **Extract (ແຕກໄຟລ໌ Zip)** ອອກມາໄວ້ທີ່ `public_html`.

2. **ສ້າງ Database ແລະ User ເທິງ Hostatom (Create MySQL Database)**:
   - ໃນ Hostatom Control Panel ➔ ເຂົ້າເມນູ **MySQL Management** (ຫຼື **MySQL Databases**).
   - ກົດ **Create New Database**:
     - ກຳນົດຊື່ Database ເຊັ່ນ: `hostatomuser_laofe`
     - ກຳນົດ Database Username ເຊັ່ນ: `hostatomuser_laofe`
     - ກຳນົດ Password (ລະຫັດຜ່ານ) ທີ່ປອດໄພ.

3. **Import Database Schema (Import config/schema.sql)**:
   - ເຂົ້າເມນູ **phpMyAdmin** ເທິງ Hostatom Control Panel.
   - ເລືອກ Database `hostatomuser_laofe` ທີ່ສ້າງໄວ້.
   - ກົດເມນູ **Import** ➔ ເລືອກໄຟລ໌ `config/schema.sql` ຈາກເຄື່ອງ ➔ ກົດ **Go / Exec** ເພື່ອສ້າງຕາຕະລາງທັງໝົດ.

4. **ຕັ້ງຄ່າ Environment Variables (`.env`) ເທິງ Hostatom**:
   - ເປີດ **File Manager** ໃນ Hostatom ➔ ສ້າງ ຫຼື ແກ້ໄຂໄຟລ໌ `.env` ຢູ່ Root Directory ຂອງ `public_html`:
     ```env
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=hostatomuser_laofe
     DB_USER=hostatomuser_laofe
     DB_PASS=YourStrongHostatomPassword123!

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

5. **ຕັ້ງຄ່າ PHP Version ເທິງ Hostatom**:
   - ເຂົ້າເມນູ **Select PHP Version** ຫຼື **PHP Selector** ເທິງ Hostatom ➔ ເລືອກ PHP Version ເປັນ **8.1** ຫຼື **8.2**.
   - ກວດສອບ Extension: ເປີດໃຊ້ `pdo_mysql`, `gd`, `mbstring`, `curl`, `json`.

6. **ເປີດໃຊ້ງານ (Access Website & Admin)**:
   - ເຂົ້າເວັບໄຊ: `https://your-domain.com/`
   - ເຂົ້າລະບົບ Admin: `https://your-domain.com/admin/`

---

## 🔐 3. ຂໍ້ມູນສິດເຂົ້າເຖິງລະບົບ Admin (Initial Admin Credentials)

- **URL ເຂົ້າລະບົບ ແອດມິນ**: `https://your-domain.com/admin/`
- **Username**: `admin`
- **Password (ເລີ່ມຕົ້ນ)**: `LaoFe2026#Secure`

> ⚠️ **ຄຳເຕືອນ**: ກະລຸນາປ່ຽນລະຫັດຜ່ານໃໝ່ທັນທີຫຼັງຈາກຮັບມອບລະບົບ ໂດຍເຂົ້າໄປທີ່ **Admin Panel ➔ ຕັ້ງຄ່າລະຫັດຜ່ານ / Manage Users**.

---

## 💾 4. ວິທີ Backup ຂໍ້ມູນເທິງ Hostatom (Data Backup Procedure)

### 1. Backup ຂໍ້ມູນ Database (ຂໍ້ຄວາມ, ເມນູ, ຂ່າວ, ອໍເດີ້, ສາຂາ, ຜູ້ໃຊ້)
- **ຜ່ານ Hostatom phpMyAdmin**:
  1. ເຂົ້າ Hostatom Control Panel ➔ ເປີດ **phpMyAdmin** ➔ ເລືອກ Database `hostatomuser_laofe`.
  2. ກົດເມນູ **Export** ➔ ເລືອກ **Quick Export** (Format: `SQL`).
  3. ກົດ **Go** ເພື່ອດາວໂຫຼດໄຟລ໌ `.sql` ເກັບໄວ້ໃນເຄື່ອງ.
- **ຜ່ານ Hostatom Create/Restore Backups**:
  - ໃນ DirectAdmin / cPanel Control Panel ➔ ເຂົ້າເມນູ **Create/Restore Backups** ➔ ເລືອກ **Database Backups** ➔ ກົດດາວໂຫຼດ backup.

### 2. Backup ໄຟລ໌ຮູບພາບ ແລະ ຊອດໂຄ້ດ (Files & Images)
- ເຂົ້າ File Manager ເທິງ Hostatom ➔ ເລືອກໂຟນເດີ `public_html` ➔ ກົດ **Compress** ➔ ດາວໂຫຼດໄຟລ໌ `.zip` ເກັບໄວ້.

---

## 🛡️ 5. ເງື່ອນໄຂການຮັບປະກັນ (3-Month Warranty & Support Policy)

- **ໄລຍະເວລາຮັບປະກັນ**: **3 ເດືອນ** (ນັບຈາກວັນທີສົ່ງມອບ).
- **ຂອບເຂດການຮັບປະກັນ**:
  - ແກ້ໄຂ **Bug**, ຂໍ້ຜິດພາດຂອງລະບົບ (System Errors), ຫຼື Error ທີ່ເກີດຈາກໂຄ້ດ ໂດຍບໍ່ມີຄ່າໃຊ້ຈ່າຍເພີ່ມເຕີມ.
  - ໃຫ້ຄຳປຶກສາ ແລະ ຊ່ວຍເຫຼືອດ້ານເຕັກນິກ, ການຕັ້ງຄ່າ Domain/Hostatom Hosting ແລະ ການສຳຮອງຂໍ້ມູນ.
  - ອັບເດດຄວາມປອດໄພຂອງໂຄ້ດ ແລະ ລະບົບ.

---

## 📖 6. ຄູ່ມືການນຳໃຊ້ລະບົບຢ່າງລະອຽດທຸກຂັ້ນຕອນ (Full System User Manual)

### 1. 🌐 ໜ້າເວັບໄຊອອນໄລນ໌ (Frontend Website)
1. **ການສັ່ງອາຫານ & ເຄື່ອງດື່ມ**:
   - ເຂົ້າໜ້າເວັບໄຊ ➔ ເລືອກໝວດໝູ່ (ກາເຟ, ເບຍ, ອາຫານ, ເຄື່ອງດື່ມ...).
   - ກົດ **"+"** ເພື່ອເພີ່ມສິນຄ້າເຂົ້າຕ່າຊື້ (Cart).
   - ກົດເບິ່ງຕ່າຊື້ ➔ ເລືອກຮູບແບບການຈັດສົ່ງ (ຮັບຢູ່ຮ້ານ / ຈັດສົ່ງ) ➔ ປ້ອນທີ່ຢູ່ & ເບີໂທ ➔ ກົດຢືນຢັນການສັ່ງຊື້.
2. **ການຈອງໂຕະອອນໄລນ໌**:
   - ເຂົ້າໜ້າ **"ຈອງໂຕະ (Book Table)"** ➔ ເລືອກສາຂາ, ວັນທີ, ເວລາ, ຈຳນວນຄົນ ➔ ກົດຢືນຢັນການຈອງ.
3. **ລະບົບສະມາຊິກ & ສະສົມຄະແນນ (Loyalty Program)**:
   - ສະໝັກ/ເຂົ້າສູ່ລະບົບ ➔ ທຸກໆການສັ່ງຊື້ສິນຄ້າຈະໄດ້ຮັບຄະແນນອັດໂນມັດເມື່ອ Admin ກົດ "Paid".
   - ລະດັບ Tier ສະມາຊິກ: `Member` ➔ `Silver` ➔ `Gold` ➔ `VIP`.

### 2. 🛡️ ລະບົບຫຼັງບ້ານ (Admin Backoffice - `admin/`)
1. **ການເຂົ້າສູ່ລະບົບ & ຄວາມປອດໄພ**:
   - ເຂົ້າ URL `admin/login.php`.
   - ລະບົບປ້ອງກັນການສຸ່ມລະຫັດຜ່ານ 3 ຂັ້ນ (Progressive Lockout):
     - ຜິດ 3 ຄັ້ງ ➔ ລັອກ 15 ນາທີ.
     - ຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ 1 ຊົ່ວໂມງ.
     - ຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ 24 ຊົ່ວໂມງ ຫຼື ຈົນກວ່າ Admin ຈະກົດ **"🔓 ປົດລັອກ"**.
2. **ການຈັດການອໍເດີ (`admin/orders.php`)**:
   - ລະບົບມີສຽງ Chime ແຈ້ງເຕືອນອໍເດີໃໝ່ Real-time ທຸກໆ 4 ວິນາທີ.
   - ປ່ຽນສະຖານະ: `Pending` ➔ `Paid` ➔ `Completed` ຫຼື `Cancelled`.
   - ພິມໃບບິນ/ໃບຮັບເງິນ (Slip Receipt) ໄດ້ທັນທີ.
3. **ໜ້າຈໍຫ້ອງຄົວ (`admin/kitchen.php`)**:
   - Display ສຳລັບຫ້ອງຄົວ / ບາຣ໌: ເບິ່ງອໍເດີໃໝ່ ➔ ກົດ "ເລີ່ມເຮັດ" ➔ ກົດ "ເສີຟແລ້ວ".
4. **ຈັດການສິດ & ທີມງານ (`admin/staff.php`)**:
   - **Super Admin (`admin`)**: ສິດສູງສຸດ.
   - **Manager (`manager`)**: ຈັດການເມນູ, ອໍເດີ, ສາຂາ, ຈອງໂຕະ.
   - **Staff (`staff`)**: ເບິ່ງ Dashboard, ອໍເດີ ແລະ ຫ້ອງຄົວ.
   - ຟັງຊັນ: ສ້າງບັນຊີທີມງານໃໝ່, ແກ້ໄຂ Role, Reset Password, ປົດລັອກບັນຊີ.
5. **ຈັດການເມນູ, ສາຂາ, ບົດຄວາມ, Banner & Gallery**:
   - ເມນູ `admin/menu_manage.php`: ເພີ່ມ/ແກ້ໄຂ ເມນູ ແລະ ຮູບພາບ.
   - ເມນູ `admin/branch_manage.php`: ເພີ່ມ/ແກ້ໄຂ ສາຂາຮ້ານ LaoFe & Beer.
   - ເມນູ `admin/guide.php`: ດູຄູ່ມືການໃຊ້ງານລະບົບແບບ Interactive ພາຍໃນລະບົບຫຼັງບ້ານ.

---
*ຈັດທຳໂດຍ ທີມງານພັດທະນາ LM Service (2026)*
