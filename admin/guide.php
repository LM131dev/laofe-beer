<?php
// admin/guide.php - System User Manual & Documentation Portal
require_once __DIR__ . '/header.php';
?>

<div class="space-y-8 pb-16 font-serif-lao">
    
    <!-- Title & Hero Banner -->
    <div class="bg-gradient-to-r from-burgundy-900 via-burgundy-800 to-gray-950 p-6 sm:p-8 rounded-3xl text-white shadow-xl border border-burgundy-700/30">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400/20 text-amber-300 border border-amber-400/30 rounded-full text-xs font-bold uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Official System Documentation
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">
                    📖 ຄູ່ມືການນຳໃຊ້ລະບົບ LaoFe & Beer
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    ຮວບຮວມວິທີການນຳໃຊ້ລະບົບທັງໝົດຢ່າງລະອຽດທຸກຂັ້ນຕອນ ທັງໜ້າເວັບໄຊອອນໄລນ໌ ແລະ ລະບົບຫຼັງບ້ານ (Admin Backoffice)
                </p>
            </div>
            <a href="../index.php" target="_blank" class="px-5 py-3 bg-amber-500 hover:bg-amber-400 text-burgundy-950 font-bold text-xs rounded-2xl shadow-lg transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>ເບິ່ງໜ້າເວັບໄຊອອນໄລນ໌</span>
            </a>
        </div>
    </div>

    <!-- Quick Jump Navigation Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="#section-frontend" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-burgundy-700/40 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2 group">
            <div class="w-10 h-10 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-800">1. ໜ້າເວັບໄຊອອນໄລນ໌</span>
        </a>
        <a href="#section-orders" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-emerald-700/40 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2 group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-800">2. ອໍເດີ & ຫ້ອງຄົວ</span>
        </a>
        <a href="#section-members" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-purple-700/40 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2 group">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-800">3. ສະມາຊິກ & ສິດທີມງານ</span>
        </a>
        <a href="#section-admin" class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-amber-700/40 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2 group">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-bold text-gray-800">4. ຈັດການເມນູ & ສາຂາ</span>
        </a>
    </div>

    <!-- DOCUMENTATION CONTENT SECTIONS -->
    
    <!-- SECTION 1: FRONTEND WEBSITE -->
    <div id="section-frontend" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex items-center gap-3 border-b pb-4">
            <div class="w-10 h-10 rounded-xl bg-burgundy-100 text-burgundy-800 flex items-center justify-center font-bold text-lg">1</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">🌐 1. ຄູ່ມືການໃຊ້ງານໜ້າເວັບໄຊອອນໄລນ໌ (Frontend Website Guide)</h2>
                <p class="text-xs text-gray-500">ວິທີການສັ່ງອາຫານ, ຈອງໂຕະ, ສະໝັກສະມາຊິກ ແລະ ສະໝັກ Franchise</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Step 1.1 -->
            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-burgundy-700 text-white text-xs flex items-center justify-center font-mono">A</span>
                    <span>ການສັ່ງອາຫານ & ເຄື່ອງດື່ມອອນໄລນ໌ (Online Order Process)</span>
                </h3>
                <ol class="list-decimal list-inside text-xs sm:text-sm text-gray-700 space-y-2 leading-relaxed pl-2">
                    <li><strong>ເລືອກໝວດໝູ່ສິນຄ້າ</strong>: ເຂົ້າໜ້າເວັບໄຊ ➔ ເລືອກໝວດໝູ່ ເຊັ່ນ: ກາເຟ, ເບຍ, ອາຫານ, ເຄື່ອງດື່ມ.</li>
                    <li><strong>ເພີ່ມສິນຄ້າເຂົ້າຕ່າ (Add to Cart)</strong>: ກົດປຸ່ມ <strong>"+"</strong> ຢູ່ຮູບສິນຄ້າ ລະບົບຈະເພີ່ມສິນຄ້າເຂົ້າຕ່າຊື້ ແລະ ສະແດງ ຈຳນວນສິນຄ້າຢູ່ມຸມຂວາລຸ່ມ.</li>
                    <li><strong>ກວດສອບຕ່າສິນຄ້າ (Review Cart)</strong>: ກົດປຸ່ມຕ່າຊື້ ➔ ປັບເພີ່ມ/ຫຼຸດ ຈຳນວນສິນຄ້າ ຫຼື ປ້ອນຄູປອງສ່ວນຫຼຸດ.</li>
                    <li><strong>ເລືອກຮູບແບບການຈັດສົ່ງ</strong>: 
                        <ul class="list-disc list-inside pl-5 mt-1 space-y-1 text-gray-600">
                            <li><strong>ຮັບຢູ່ຮ້ານ (Takeaway / Pickup)</strong>: ເລືອກສາຂາ ທີ່ຕ້ອງການໄປຮັບ.</li>
                            <li><strong>ຈັດສົ່ງເຖິງທີ່ (Delivery)</strong>: ປ້ອນທີ່ຢູ່ ແລະ ເບີໂທຕິດຕໍ່.</li>
                        </ul>
                    </li>
                    <li><strong>ຢືນຢັນການສັ່ງຊື້</strong>: ກົດປຸ່ມ <strong>"ຢືນຢັນການສັ່ງຊື້"</strong> ➔ ລະບົບຈະບັນທຶກອໍເດີເຂົ້າຖານຂໍ້ມູນ ແລະ ແຈ້ງເຕືອນໄປທີ່ <strong>ລະບົບຫຼັງບ້ານ (Admin Backoffice)</strong> ອັດໂນມັດ.</li>
                </ol>
            </div>

            <!-- Step 1.2 -->
            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-burgundy-700 text-white text-xs flex items-center justify-center font-mono">B</span>
                    <span>ລະບົບຈອງໂຕະອອນໄລນ໌ (Table Booking System)</span>
                </h3>
                <ol class="list-decimal list-inside text-xs sm:text-sm text-gray-700 space-y-2 leading-relaxed pl-2">
                    <li>ເຂົ້າໄປທີ່ເມນູ <strong>"ຈອງໂຕະ (Book Table)"</strong> ເທິງ Header.</li>
                    <li>ເລືອກສາຂາທີ່ຕ້ອງການຈອງ (ນະຄອນຫຼວງ, ວັງວຽງ, ຫຼວງພະບາງ, ປາກເຊ...).</li>
                    <li>ກຳນົດ <strong>ວັນທີ, ເວລາ ແລະ ຈຳນວນຄົນ</strong>.</li>
                    <li>ກອກຊື່ຜູ້ຈອງ, ເບີໂທ ແລະ ຂໍ້ຄວາມເພີ່ມເຕີມ (ຖ້າມີ).</li>
                    <li>ກົດ <strong>"ຢືນຢັນການຈອງໂຕະ"</strong> ➔ ເມື່ອສຳເລັດ ຂໍ້ມູນຈະໄປສະແດງຢູ່ໜ້າ <strong>Admin ➔ ຈັດການຈອງໂຕະ</strong>.</li>
                </ol>
            </div>

            <!-- Step 1.3 -->
            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-burgundy-700 text-white text-xs flex items-center justify-center font-mono">C</span>
                    <span>ລະບົບສະມາຊິກ & ສະສົມຄະແນນ (Loyalty Program)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-700 space-y-2 leading-relaxed pl-2">
                    <li><strong>ການສະໝັກສະມາຊິກ</strong>: ເຂົ້າໜ້າ `register.php` ➔ ປ້ອນຊື່ຜູ້ໃຊ້, ລະຫັດຜ່ານ, ເບີໂທ ແລະ ອີເມລ.</li>
                    <li><strong>ການສະສົມຄະແນນ (Earn Points)</strong>: ທຸກໆການສັ່ງຊື້ສິນຄ້າອອນໄລນ໌ ເມື່ອ Admin ກົດ <strong>"ຊຳລະແລ້ວ (Paid)"</strong> ລະບົບຈະຄິດໄລ່ຄະແນນເຂົ້າບັນຊີສະມາຊິກອັດໂນມັດ.</li>
                    <li><strong>ລະດັບ Tier ສະມາຊິກ</strong>: 
                        <span class="inline-flex gap-2 ml-2">
                            <span class="px-2 py-0.5 bg-gray-200 text-gray-800 text-[10px] font-bold rounded">Member</span>
                            <span class="px-2 py-0.5 bg-slate-300 text-slate-900 text-[10px] font-bold rounded">Silver</span>
                            <span class="px-2 py-0.5 bg-amber-200 text-amber-900 text-[10px] font-bold rounded">Gold</span>
                            <span class="px-2 py-0.5 bg-purple-200 text-purple-900 text-[10px] font-bold rounded">VIP</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- SECTION 2: ORDERS & KITCHEN -->
    <div id="section-orders" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex items-center gap-3 border-b pb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg">2</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">📦 2. ການຈັດການອໍເດີ & ໜ້າຈໍຫ້ອງຄົວ (Orders & Kitchen Display)</h2>
                <p class="text-xs text-gray-500">ວິທີຮັບອໍເດີ, ແຈ້ງເຕືອນສຽງ Realtime, ພິມໃບບິນ ແລະ ປ່ຽນສະຖານະອາຫານ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100 space-y-3">
                <h3 class="text-base font-bold text-emerald-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>ໜ້າຈັດການອໍເດີ (`admin/orders.php`)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-700 space-y-2 leading-relaxed">
                    <li><strong>ແຈ້ງເຕືອນອໍເດີໃໝ່</strong>: ລະບົບຈະມີສຽງ Chime ແຈ້ງເຕືອນອັດໂນມັດທຸກໆ 4 ວິນາທີ ເມື່ອມີອໍເດີໃໝ່ເຂົ້າមក.</li>
                    <li><strong>ປ່ຽນສະຖານະການຊຳລະ</strong>:
                        <ul class="list-circle list-inside pl-4 text-xs text-gray-600 mt-1 space-y-1">
                            <li><span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded font-bold">Pending</span>: ລໍຖ້າກວດສອບ / ຊຳລະ</li>
                            <li><span class="px-2 py-0.5 bg-green-100 text-green-800 rounded font-bold">Paid</span>: ຊຳລະແລ້ວ ➔ ສົ່ງເຂົ້າຫ້ອງຄົວ</li>
                            <li><span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-bold">Completed</span>: ເສີຟ/ຈັດສົ່ງຮຽບຮ້ອຍ</li>
                            <li><span class="px-2 py-0.5 bg-red-100 text-red-800 rounded font-bold">Cancelled</span>: ຍົກເລີກອໍເດີ</li>
                        </ul>
                    </li>
                    <li><strong>ພິມໃບບິນ/ໃບຮັບເງິນ</strong>: ກົດປຸ່ມ <strong>"🖨️ ພິມໃບບິນ"</strong> ເພື່ອພິມໃບສັ່ງຊື້ອອກເຄື່ອງພິມ Slip/POS.</li>
                </ul>
            </div>

            <div class="bg-amber-50/50 p-5 rounded-2xl border border-amber-100 space-y-3">
                <h3 class="text-base font-bold text-amber-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>ໜ້າຈໍຫ້ອງຄົວ/ບາຣ໌ (`admin/kitchen.php`)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-700 space-y-2 leading-relaxed">
                    <li>ໜ້າຈໍ Display ຂະໜາດໃຫຍ່ ສຳລັບຕິດໄວ້ຢູ່ <strong>ຫ້ອງຄົວ ຫຼື ເຄົາເຕີບາຣ໌</strong>.</li>
                    <li>ສະແດງອໍເດີທີ່ຊຳລະແລ້ວ (`Paid`) ແບບ Real-time.</li>
                    <li>ພະນັກງານຄົວ ກົດປຸ່ມ <strong>"ເລີ່ມເຮັດ (Preparing)"</strong> ເມື່ອກຳລັງປຸງແຕ່ງ.</li>
                    <li>ເມື່ອແຕ່ງອາຫານ/ເຄື່ອງດື່ມເສັດ ກົດ <strong>"ເສີຟແລ້ວ (Served)"</strong> ເພື່ອເຄຼຍອໍເດີອອກຈາກໜ້າຈໍ.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- SECTION 3: MEMBERS & STAFF ROLES -->
    <div id="section-members" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex items-center gap-3 border-b pb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-lg">3</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">🛡️ 3. ການຈັດການສະມາຊິກ & ສິດທີມງານ (Members & Staff Roles)</h2>
                <p class="text-xs text-gray-500">ການກຳນົດສິດເຂົ້າເຖິງ, ເພີ່ມທີມງານ, Reset ລະຫັດຜ່ານ ແລະ ປົດລັອກບັນຊີ</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Roles Matrix -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm text-left border border-gray-200 rounded-2xl overflow-hidden">
                    <thead class="bg-gray-100 text-gray-800 font-bold uppercase">
                        <tr>
                            <th class="p-3 border-b">ລະດັບສິດ (Role)</th>
                            <th class="p-3 border-b">ສິດການເຂົ້າເຖິງ (Permissions)</th>
                            <th class="p-3 border-b">ຜູ້ທີ່ສາມາດສ້າງ/ແກ້ໄຂໄດ້</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="p-3 font-bold text-purple-900 bg-purple-50/50">👑 Super Admin (`admin`)</td>
                            <td class="p-3">ສິດສູງສຸດ: ຈັດການທີມງານ, ເມນູ, ອໍເດີ, ສາຂາ, ສະມາຊິກ, Reset ລະຫັດຜ່ານ, ປົດລັອກບັນຊີ.</td>
                            <td class="p-3">Super Admin ເທົ່ານັ້ນ</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-blue-900 bg-blue-50/50">💼 Manager (`manager`)</td>
                            <td class="p-3">ຈັດການເມນູ, ອໍເດີ, ຈອງໂຕະ, ສາຂາ, ຂ່າວສານ, ເບິ່ງທີມງານ (ບໍ່ສາມາດລົບ Super Admin ໄດ້).</td>
                            <td class="p-3">Super Admin & Manager</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-amber-900 bg-amber-50/50">🧑‍🍳 Staff (`staff`)</td>
                            <td class="p-3">ເຂົ້າເຖິງ Dashboard, ໜ້າຈັດການອໍເດີ (`orders.php`) ແລະ ໜ້າຈໍຫ້ອງຄົວ (`kitchen.php`).</td>
                            <td class="p-3">Super Admin & Manager</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-gray-700 bg-gray-50">👤 Customer (`customer`)</td>
                            <td class="p-3">ບັນຊີລູກຄ້າສຳລັບເຂົ້າສູ່ລະບົບໜ້າເວັບ, ຕິດຕາມອໍເດີ ແລະ ສະສົມຄະແນນ.</td>
                            <td class="p-3">ສະໝັກຜ່ານໜ້າເວັບ</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Lockout explanation -->
            <div class="bg-red-50 p-5 rounded-2xl border border-red-200 space-y-2">
                <h4 class="text-sm font-bold text-red-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>ລະບົບປ້ອງກັນການສຸ່ມລະຫັດຜ່ານ 3 ຂັ້ນ (Progressive Lockout System)</span>
                </h4>
                <p class="text-xs text-red-800 leading-relaxed">
                    ກໍລະນີມີຜູ້ປ້ອນລະຫັດຜ່ານຜິດ 3 ຄັ້ງ ລະບົບຈະລັອກບັນຊີອັດໂນມັດຕາມຂັ້ນຕອນ:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                    <div class="bg-white p-3 rounded-xl border border-red-200">
                        <span class="font-bold text-red-900 block">ຂັ້ນທີ 1 (Stage 1)</span>
                        <span class="text-gray-600">ປ້ອນຜິດ 3 ຄັ້ງ ➔ ລັອກ 15 ນາທີ</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-red-200">
                        <span class="font-bold text-red-900 block">ຂັ້ນທີ 2 (Stage 2)</span>
                        <span class="text-gray-600">ປ້ອນຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ 1 ຊົ່ວໂມງ</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-red-200">
                        <span class="font-bold text-red-900 block">ຂັ້ນທີ 3 (Stage 3)</span>
                        <span class="text-gray-600">ປ້ອນຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ 24 ຊົ່ວໂມງ ຫຼື ຈົນກວ່າ Admin ຈະກົດ <strong>"🔓 ປົດລັອກ"</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: MENU & BRANCH MANAGEMENT -->
    <div id="section-admin" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex items-center gap-3 border-b pb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg">4</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">⚙️ 4. ການຈັດການຂໍ້ມູນເມນູ, ສາຂາ & ເນື້ອຫາ (Content Management)</h2>
                <p class="text-xs text-gray-500">ວິທີເພີ່ມ/ແກ້ໄຂ ເມນູອາຫານ, ສາຂາຮ້ານ, ຂ່າວສານ, Banner ແລະ ຮູບພາບ Gallery</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-burgundy-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>ຈັດການເມນູ (`admin/menu_manage.php`)</span>
                </h4>
                <p class="text-gray-600 leading-relaxed">
                    ເພີ່ມ/ແກ້ໄຂ ລາຍການອາຫານ ແລະ ເຄື່ອງດື່ມ, ອັບໂຫຼດຮູບພາບ, ຕັ້ງຄ່າລາຄາ, ກຳນົດໝວດໝູ່ (ກາເຟ, ເບຍ, ອາຫານ...) ແລະ ສະຖານະ <strong>ມີ/ໝົດ</strong>.
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>ຈັດການສາຂາ (`admin/branch_manage.php`)</span>
                </h4>
                <p class="text-gray-600 leading-relaxed">
                    ເພີ່ມ/ແກ້ໄຂ ສາຂາຮ້ານ LaoFe & Beer ທົ່ວປະເທດ (ຊື່ສາຂາ, ທີ່ຢູ່, ເບີໂທ, ເວລາເປີດ-ປິດ, ລິ້ງ Google Maps ແລະ ຮູບພາບ).
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>ບົດຄວາມ & ກິດຈະກຳ (`admin/news_manage.php`)</span>
                </h4>
                <p class="text-gray-600 leading-relaxed">
                    ໂພສຂ່າວສານ, ໂປຣໂມຊັນ ແລະ ກິດຈະກຳໃໝ່ໆ ຂອງຮ້ານ ພ້ອມຮອງຮັບການສະແດງຜົນ 2 ພາສາ (ລາວ & ອັງກິດ).
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Banner & Gallery (`admin/banner_manage.php`)</span>
                </h4>
                <p class="text-gray-600 leading-relaxed">
                    ຈັດການ Banner Slider ຢູ່ໜ້າເວັບຫຼັກ ແລະ ຮູບພາບ Gallery ບັນຍາກາດຮ້ານ ເພື່ອຄວາມສວຍງາມ ແລະ ທັນສະໄໝ.
                </p>
            </div>
        </div>
    </div>

</div>

</main>
</body>
</html>
