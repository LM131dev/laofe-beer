# ແຜນຜັງການເຮັດວຽກ ເຟສ 2 (Phase 2 Detailed Workflows)

ເອກະສານນີ້ອະທິບາຍກ່ຽວກັບ **Flow (ຂັ້ນຕອນການເຮັດວຽກ)** ຂອງແຕ່ລະລະບົບໃນ ເຟສ 2 (Phase 2) ຂອງ LaoFe & Beer ຢ່າງລະອຽດ.

---

## 1. ລະບົບສັ່ງຊື້ ແລະ ຊຳລະເງິນອອນໄລນ໌ (Ordering & Payment Flow)

ລະບົບນີ້ເຊື່ອມໂຍງລະຫວ່າງລູກຄ້າ, Backend API, ລະບົບທະນາຄານ (BCEL OnePay) ແລະ ໜ້າຮ້ານ (Kitchen/Bar).

```mermaid
sequenceDiagram
    autonumber
    actor Customer as ລູກຄ້າ (App/Web)
    participant App as ລະບົບ Frontend
    participant Server as Backend API (PHP)
    participant DB as Database (MySQL)
    participant BCEL as BCEL OnePay API
    participant Kitchen as ລະບົບຫ້ອງຄົວ (Kitchen Monitor)

    Customer->>App: ເລືອກເມນູກາເຟ/ເຄື່ອງດື່ມໃສ່ຕະກ້າ
    Customer->>App: ກົດສັ່ງຊື້ (Checkout)
    App->>Server: ສົ່ງຂໍ້ມູນອໍເດີ້ (Cart details & Customer ID)
    Server->>DB: ບັນທຶກອໍເດີ້ສະຖານະ 'Pending'
    Server->>BCEL: ຮ້ອງຂໍສ້າງ QR Code ຊຳລະເງິນ (Generate Bill)
    BCEL-->>Server: ສົ່ງລິ້ງຮູບພາບ QR Code
    Server-->>App: ສະແດງ QR Code ໃຫ້ລູກຄ້າເທິງໜ້າຈໍ
    Customer->>BCEL: ສະແກນຈ່າຍເງິນຜ່ານແອັບ BCEL One
    BCEL->>Server: ສົ່ງຂໍ້ມູນການຈ່າຍເງິນສຳເລັດ (Webhook Callback)
    Server->>DB: ອັບເດດສະຖານະອໍເດີ້ເປັນ 'Paid' & ບັນທຶກທຸລະກຳ
    Server->>Kitchen: ສົ່ງລາຍການອໍເດີ້ໄປຫ້ອງຄົວທັນທີ (Print/Display Ticket)
    Server-->>App: ສະແດງໜ້າຈໍ 'ຊຳລະເງິນສຳເລັດ' ພ້ອມເລກບິນ
    Kitchen->>Customer: ປຸງແຕ່ງເຄື່ອງດື່ມ ແລະ ເອີ້ນຮັບເຄື່ອງຕາມເລກບິນ
```

---

## 2. ລະບົບສະມາຊິກ ແລະ ສະສົມຄະແນນ (Membership & Loyalty Points Flow)

ທຸກໆການສັ່ງຊື້ທີ່ຊຳລະເງິນສຳເລັດ ລະບົບຈະຄຳນວນຄະແນນໃຫ້ສະມາຊິກໂດຍອັດຕະໂນມັດ (ອັດຕາແລກປ່ຽນ: **10,000 ກີບ = 1 ຄະແນນ**).

```mermaid
flowchart TD
    A[ເລີ່ມຕົ້ນ: ອໍເດີ້ຊຳລະເງິນສຳເລັດ - Paid] --> B{ລູກຄ້າໄດ້ເຂົ້າສູ່ລະບົບສະມາຊິກ?}
    B -- ບໍ່ໄດ້ເຂົ້າ --> C[ຈົບການເຮັດວຽກ: ບໍ່ມີການສະສົມຄະແນນ]
    B -- ເຂົ້າສູ່ລະບົບແລ້ວ --> D[ດຶງຍອດລວມຂອງອໍເດີ້ - Total Amount]
    D --> E[ຄຳນວນຄະແນນ: Points = Total / 10,000]
    E --> F[ອັບເດດຄະແນນໃໝ່ໃສ່ Database ຂອງລູກຄ້າ]
    F --> G[ບັນທຶກປະຫວັດການຮັບຄະແນນ - Points History]
    G --> H{ຄະແນນສະສົມຮ່ວມຮອດເກນ Tier ໃໝ່?}
    H -- ຮອດເກນ --> I[ອັບເກຣດລະດັບສະມາຊິກ: Silver / Gold / Platinum]
    H -- ບໍ່ຮອດເກນ --> J[ສົ່ງແຈ້ງເຕືອນຄະແນນສະສົມໃໝ່ຫາລູກຄ້າ]
    I --> J
    J --> K[ຈົບການເຮັດວຽກ]
```

---

## 3. ລະບົບຈອງໂຕະອອນໄລນ໌ (Table & Lounge Booking Flow)

ລູກຄ້າສາມາດຈອງໂຕະ ຫຼື ໂຊນເລົາຈ໌ VIP ໄວ້ລ່ວງໜ້າເພື່ອຄວາມສະດວກໃນການຈັດງານ ຫຼື ພົບປະສັງສັນ.

```mermaid
sequenceDiagram
    autonumber
    actor Customer as ລູກຄ້າ (App/Web)
    participant App as ລະບົບ Frontend
    participant Server as Backend API (PHP)
    participant DB as Database (MySQL)
    participant Notify as ລະບົບແຈ້ງເຕືອນ (Email/SMS)

    Customer->>App: ເລືອກສາຂາ, ວັນທີ, ເວລາ ແລະ ຈຳນວນຄົນ
    App->>Server: ກວດສອບໂຕະຫວ່າງ (Check Availability)
    Server->>DB: ຄົ້ນຫາຂໍ້ມູນໂຕະຫວ່າງໃນຊ່ວງເວລານັ້ນ
    DB-->>Server: ສົ່ງລາຍຊື່ໂຕະຫວ່າງ
    Server-->>App: ສະແດງຜັງໂຕະຫວ່າງໃຫ້ລູກຄ້າເລືອກ
    Customer->>App: ເລືອກໂຕະ ແລະ ກົດຢືນຢັນຈອງ
    App->>Server: ສົ່ງຂໍ້ມູນການຈອງ
    Server->>DB: ບັນທຶກການຈອງສະຖານະ 'Confirmed'
    Server->>Notify: ສົ່ງບັດຢືນຢັນການຈອງ (Booking Voucher) ພ້ອມ QR Code
    Notify-->>Customer: ສົ່ງ Email ຫຼື SMS ຢືນຢັນ
    Customer->>App: ສະແດງບັດຈອງໃຫ້ພະນັກງານໜ້າຮ້ານສະແກນເມື່ອມາຮອດ
```

---

## 4. ຂັ້ນຕອນການເດີນວຽກຂອງທີມພັດທະນາ (Development & Release Pipeline)

ຂັ້ນຕອນການເດີນວຽກໃນການຂຽນໂຄ້ດ ແລະ ຂຶ້ນລະບົບແອັບມືຖື ເຂົ້າສູ່ App Store.

```mermaid
graph LR
    A[1. ອອກແບບ UI/UX & Figma] --> B[2. ຂຽນໂຄ້ດ Backend API]
    B --> C[3. ຂຽນໂຄ້ດ Mobile App]
    C --> D[4. ເຊື່ອມຕໍ່ API & App]
    D --> E[5. ທົດສອບພາຍໃນ QA/Beta]
    E --> F[6. ແກ້ໄຂບັກ & ປັບປຸງ]
    F --> G[7. ສົ່ງຂຶ້ນ App Store / Play Store]
    G --> H[8. ເປີດໃຫ້ບໍລິການຢ່າງເປັນທາງການ]
```
