<?php
// admin/guide.php - System User Manual & Documentation Portal
require_once __DIR__ . '/header.php';
?>

<div class="space-y-8 pb-20 font-serif-lao">
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-burgundy-950 via-burgundy-900 to-amber-950 p-6 sm:p-10 rounded-3xl text-white shadow-xl border border-amber-400/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-3 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-amber-400/20 text-amber-300 border border-amber-400/30 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Official System Documentation</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    ຄູ່ມືການນຳໃຊ້ລະບົບ LaoFe & Beer (User Manual)
                </h1>
                <p class="text-xs sm:text-sm text-amber-100/90 leading-relaxed">
                    ຄູ່ມືແນະນຳການນຳໃຊ້ລະບົບຢ່າງລະອຽດທຸກຂັ້ນຕອນ ທັງລະບົບໜ້າເວັບໄຊອອນໄລນ໌ ສຳລັບລູກຄ້າ ແລະ ລະບົບຫຼັງບ້ານ (Admin Backoffice) ສຳລັບຜູ້ດູແລ ແລະ ທີມງານ
                </p>
            </div>
            
            <a href="../index.php" target="_blank" class="px-5 py-3 bg-amber-500 hover:bg-amber-400 text-burgundy-950 font-extrabold text-xs rounded-2xl shadow-lg hover:shadow-amber-500/20 hover:scale-105 transition-all flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>ເບິ່ງໜ້າເວັບໄຊອອນໄລນ໌</span>
            </a>
        </div>
    </div>

    <!-- Quick Navigation Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="#section-frontend" class="p-5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-burgundy-600 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2.5 group">
            <div class="w-12 h-12 rounded-xl bg-burgundy-50 text-burgundy-700 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <span class="text-xs sm:text-sm font-bold text-gray-900 group-hover:text-burgundy-700">1. ໜ້າເວັບໄຊອອນໄລນ໌</span>
        </a>

        <a href="#section-orders" class="p-5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-emerald-600 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2.5 group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <span class="text-xs sm:text-sm font-bold text-gray-900 group-hover:text-emerald-700">2. ອໍເດີ & ຫ້ອງຄົວ</span>
        </a>

        <a href="#section-members" class="p-5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-purple-600 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2.5 group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-xs sm:text-sm font-bold text-gray-900 group-hover:text-purple-700">3. ສະມາຊິກ & ສິດທີມງານ</span>
        </a>

        <a href="#section-admin" class="p-5 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:border-amber-600 hover:shadow-md transition-all flex flex-col items-center text-center space-y-2.5 group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs sm:text-sm font-bold text-gray-900 group-hover:text-amber-700">4. ຈັດການເມນູ & ສາຂາ</span>
        </a>
    </div>

    <!-- DOCUMENTATION CONTENT SECTIONS -->
    
    <!-- SECTION 1: FRONTEND WEBSITE -->
    <div id="section-frontend" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 space-y-6">
        <div class="flex items-center gap-3.5 border-b border-gray-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-burgundy-700 text-white flex items-center justify-center font-bold text-base shadow-sm">1</div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">1. ຄູ່ມືການໃຊ້ງານໜ້າເວັບໄຊອອນໄລນ໌ (Frontend Website Guide)</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">ວິທີການສັ່ງອາຫານ, ຈອງໂຕະ, ສະໝັກສະມາຊິກ ແລະ ສະໝັກ Franchise</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Step 1.1 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-burgundy-100 text-burgundy-800 text-xs flex items-center justify-center font-mono font-bold">A</span>
                    <span>ການສັ່ງອາຫານ & ເຄື່ອງດື່ມອອນໄລນ໌ (Online Order Process)</span>
                </h3>
                <ol class="list-decimal list-inside text-xs sm:text-sm text-gray-800 space-y-3 leading-relaxed pl-1">
                    <li class="pl-2"><strong class="text-gray-900">ເລືອກໝວດໝູ່ສິນຄ້າ</strong>: ເຂົ້າໜ້າເວັບໄຊ ➔ ເລືອກໝວດໝູ່ ເຊັ່ນ: <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-800 font-semibold">ກາເຟ</span>, <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-800 font-semibold">ເບຍ</span>, <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-800 font-semibold">ອາຫານ</span>, <span class="px-2 py-0.5 bg-gray-100 rounded text-gray-800 font-semibold">ເຄື່ອງດື່ມ</span>.</li>
                    <li class="pl-2"><strong class="text-gray-900">ເພີ່ມສິນຄ້າເຂົ້າຕ່າ (Add to Cart)</strong>: ກົດປຸ່ມ <span class="px-2 py-0.5 bg-burgundy-50 text-burgundy-700 font-bold rounded">+</span> ຢູ່ຮູບສິນຄ້າ ລະບົບຈະເພີ່ມສິນຄ້າເຂົ້າຕ່າຊື້ ແລະ ສະແດງຈຳນວນສິນຄ້າຢູ່ມຸມຂວາລຸ່ມ.</li>
                    <li class="pl-2"><strong class="text-gray-900">ກວດສອບຕ່າສິນຄ້າ (Review Cart)</strong>: ກົດປຸ່ມຕ່າຊື້ ➔ ປັບເພີ່ມ/ຫຼຸດ ຈຳນວນສິນຄ້າ ຫຼື ປ້ອນຄູປອງສ່ວນຫຼຸດ.</li>
                    <li class="pl-2"><strong class="text-gray-900">ເລືອກຮູບແບບການຈັດສົ່ງ</strong>: 
                        <ul class="list-disc list-inside pl-6 mt-2 space-y-1.5 text-gray-700">
                            <li><strong class="text-gray-900">ຮັບຢູ່ຮ້ານ (Takeaway / Pickup)</strong>: ເລືອກສາຂາ ທີ່ຕ້ອງການໄປຮັບ.</li>
                            <li><strong class="text-gray-900">ຈັດສົ່ງເຖິງທີ່ (Delivery)</strong>: ປ້ອນທີ່ຢູ່ ແລະ ເບີໂທຕິດຕໍ່.</li>
                        </ul>
                    </li>
                    <li class="pl-2"><strong class="text-gray-900">ຢືນຢັນການສັ່ງຊື້</strong>: ກົດປຸ່ມ <span class="px-3 py-1 bg-burgundy-700 text-white font-bold text-xs rounded-lg">"ຢືນຢັນການສັ່ງຊື້"</span> ➔ ລະບົບຈະບັນທຶກອໍເດີເຂົ້າຖານຂໍ້ມູນ ແລະ ແຈ້ງເຕືອນໄປທີ່ <strong>ລະບົບຫຼັງບ້ານ (Admin Backoffice)</strong> ອັດໂນມັດ.</li>
                </ol>
            </div>

            <!-- Step 1.2 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-burgundy-100 text-burgundy-800 text-xs flex items-center justify-center font-mono font-bold">B</span>
                    <span>ລະບົບຈອງໂຕະອອນໄລນ໌ (Table Booking System)</span>
                </h3>
                <ol class="list-decimal list-inside text-xs sm:text-sm text-gray-800 space-y-2.5 leading-relaxed pl-1">
                    <li class="pl-2">ເຂົ້າໄປທີ່ເມນູ <strong class="text-gray-900 font-bold">"ຈອງໂຕະ (Book Table)"</strong> ເທິງ Header.</li>
                    <li class="pl-2">ເລືອກສາຂາທີ່ຕ້ອງການຈອງ (ນະຄອນຫຼວງ, ວັງວຽງ, ຫຼວງພະບາງ, ປາກເຊ...).</li>
                    <li class="pl-2">ກຳນົດ <strong class="text-gray-900">ວັນທີ, ເວລາ ແລະ ຈຳນວນຄົນ</strong>.</li>
                    <li class="pl-2">ກອກຊື່ຜູ້ຈອງ, ເບີໂທ ແລະ ຂໍ້ຄວາມເພີ່ມເຕີມ.</li>
                    <li class="pl-2">ກົດ <span class="px-3 py-1 bg-burgundy-700 text-white font-bold text-xs rounded-lg">"ຢືນຢັນການຈອງໂຕະ"</span> ➔ ເມື່ອສຳເລັດ ຂໍ້ມູນຈະໄປສະແດງຢູ່ໜ້າ <strong class="text-gray-900">Admin ➔ ຈັດການຈອງໂຕະ</strong>.</li>
                </ol>
            </div>

            <!-- Step 1.3 -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-burgundy-900 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-burgundy-100 text-burgundy-800 text-xs flex items-center justify-center font-mono font-bold">C</span>
                    <span>ລະບົບສະມາຊິກ & ສະສົມຄະແນນ (Loyalty Program)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-800 space-y-2.5 leading-relaxed pl-1">
                    <li class="pl-2"><strong class="text-gray-900">ການສະໝັກສະມາຊິກ</strong>: ເຂົ້າໜ້າ `register.php` ➔ ປ້ອນຊື່ຜູ້ໃຊ້, ລະຫັດຜ່ານ, ເບີໂທ ແລະ ອີເມລ.</li>
                    <li class="pl-2"><strong class="text-gray-900">ການສະສົມຄະແນນ (Earn Points)</strong>: ທຸກໆການສັ່ງຊື້ສິນຄ້າອອນໄລນ໌ ເມື່ອ Admin ກົດ <span class="px-2 py-0.5 bg-green-100 text-green-800 font-bold rounded">"ຊຳລະແລ້ວ (Paid)"</span> ລະບົບຈະຄິດໄລ່ຄະແນນເຂົ້າບັນຊີສະມາຊິກອັດໂນມັດ.</li>
                    <li class="pl-2"><strong class="text-gray-900">ລະດັບ Tier ສະມາຊິກ</strong>: 
                        <span class="inline-flex flex-wrap gap-2 ml-2 mt-1">
                            <span class="px-2.5 py-0.5 bg-gray-100 text-gray-800 text-xs font-bold rounded border border-gray-200">Member</span>
                            <span class="px-2.5 py-0.5 bg-slate-200 text-slate-900 text-xs font-bold rounded border border-slate-300">Silver</span>
                            <span class="px-2.5 py-0.5 bg-amber-200 text-amber-900 text-xs font-bold rounded border border-amber-300">Gold</span>
                            <span class="px-2.5 py-0.5 bg-purple-200 text-purple-900 text-xs font-bold rounded border border-purple-300">VIP</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- SECTION 2: ORDERS & KITCHEN -->
    <div id="section-orders" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 space-y-6">
        <div class="flex items-center gap-3.5 border-b border-gray-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-bold text-base shadow-sm">2</div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">2. ການຈັດການອໍເດີ & ໜ້າຈໍຫ້ອງຄົວ (Orders & Kitchen Display)</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">ວິທີຮັບອໍເດີ, ແຈ້ງເຕືອນສຽງ Realtime, ພິມໃບບິນ ແລະ ປ່ຽນສະຖານະອາຫານ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-emerald-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>ໜ້າຈັດການອໍເດີ (`admin/orders.php`)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-800 space-y-3 leading-relaxed">
                    <li><strong class="text-gray-900">ແຈ້ງເຕືອນອໍເດີໃໝ່ Real-time</strong>: ລະບົບຈະມີສຽງ Chime ແຈ້ງເຕືອນອັດໂນມັດທຸກໆ 4 ວິນາທີ ເມື່ອມີອໍເດີໃໝ່ເຂົ້າមក.</li>
                    <li><strong class="text-gray-900">ປ່ຽນສະຖານະການຊຳລະ</strong>:
                        <ul class="list-circle list-inside pl-4 text-xs text-gray-700 mt-2 space-y-2">
                            <li><span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded font-bold border border-yellow-300">Pending</span>: ລໍຖ້າກວດສອບ / ຊຳລະ</li>
                            <li><span class="px-2.5 py-1 bg-green-100 text-green-800 rounded font-bold border border-green-300">Paid</span>: ຊຳລະແລ້ວ ➔ ສົ່ງເຂົ້າຄົວອັດໂນມັດ</li>
                            <li><span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded font-bold border border-blue-300">Completed</span>: ເສີຟ/ຈັດສົ່ງຮຽບຮ້ອຍ</li>
                            <li><span class="px-2.5 py-1 bg-red-100 text-red-800 rounded font-bold border border-red-300">Cancelled</span>: ຍົກເລີກອໍເດີ</li>
                        </ul>
                    </li>
                    <li><strong class="text-gray-900">ພິມໃບບິນ/ໃບຮັບເງິນ</strong>: ກົດປຸ່ມ <span class="px-2.5 py-1 bg-gray-100 border rounded text-xs font-bold text-gray-800">🖨️ ພິມໃບບິນ</span> ເພື່ອພິມໃບສັ່ງຊື້ອອກເຄື່ອງພິມ Slip/POS.</li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-amber-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>ໜ້າຈໍຫ້ອງຄົວ/ບາຣ໌ (`admin/kitchen.php`)</span>
                </h3>
                <ul class="list-disc list-inside text-xs sm:text-sm text-gray-800 space-y-3 leading-relaxed">
                    <li>ໜ້າຈໍ Display ຂະໜາດໃຫຍ່ ສຳລັບຕິດໄວ້ຢູ່ <strong class="text-gray-900">ຫ້ອງຄົວ ຫຼື ເຄົາເຕີບາຣ໌</strong>.</li>
                    <li>ສະແດງອໍເດີທີ່ຊຳລະແລ້ວ (`Paid`) ແບບ Real-time.</li>
                    <li>ພະນັກງານຄົວ ກົດປຸ່ມ <span class="px-2.5 py-1 bg-amber-500 text-white font-bold text-xs rounded">"ເລີ່ມເຮັດ (Preparing)"</span> ເມື່ອກຳລັງປຸງແຕ່ງ.</li>
                    <li>ເມື່ອແຕ່ງອາຫານ/ເຄື່ອງດື່ມເສັດ ກົດ <span class="px-2.5 py-1 bg-green-600 text-white font-bold text-xs rounded">"ເສີຟແລ້ວ (Served)"</span> ເພື່ອເຄຼຍອໍເດີອອກຈາກໜ້າຈໍ.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- SECTION 3: MEMBERS & STAFF ROLES -->
    <div id="section-members" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 space-y-6">
        <div class="flex items-center gap-3.5 border-b border-gray-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-base shadow-sm">3</div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">3. ການຈັດການສະມາຊິກ & ສິດທີມງານ (Members & Staff Roles)</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">ການກຳນົດສິດເຂົ້າເຖິງ, ເພີ່ມທີມງານ, Reset ລະຫັດຜ່ານ ແລະ ປົດລັອກບັນຊີ</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Roles Matrix Table -->
            <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-gray-100 text-gray-900 font-bold uppercase border-b border-gray-200">
                        <tr>
                            <th class="p-3.5">ລະດັບສິດ (Role)</th>
                            <th class="p-3.5">ສິດການເຂົ້າເຖິງ (Permissions)</th>
                            <th class="p-3.5">ຜູ້ທີ່ສາມາດສ້າງ/ແກ້ໄຂໄດ້</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-800">
                        <tr class="hover:bg-purple-50/30">
                            <td class="p-3.5 font-bold text-purple-900 bg-purple-50/50">
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-900 border border-purple-300 rounded-full font-bold inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Super Admin (`admin`)</span>
                                </span>
                            </td>
                            <td class="p-3.5">ສິດສູງສຸດ: ຈັດການທີມງານ, ເມນູ, ອໍເດີ, ສາຂາ, ສະມາຊິກ, Reset ລະຫັດຜ່ານ, ປົດລັອກບັນຊີ.</td>
                            <td class="p-3.5 font-semibold text-purple-900">Super Admin ເທົ່ານັ້ນ</td>
                        </tr>
                        <tr class="hover:bg-blue-50/30">
                            <td class="p-3.5 font-bold text-blue-900 bg-blue-50/50">
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-900 border border-blue-300 rounded-full font-bold inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Manager (`manager`)</span>
                                </span>
                            </td>
                            <td class="p-3.5">ຈັດການເມນູ, ອໍເດີ, ຈອງໂຕະ, ສາຂາ, ຂ່າວສານ, ເບິ່ງທີມງານ (ບໍ່ສາມາດລົບ Super Admin ໄດ້).</td>
                            <td class="p-3.5 font-semibold text-blue-900">Super Admin & Manager</td>
                        </tr>
                        <tr class="hover:bg-amber-50/30">
                            <td class="p-3.5 font-bold text-amber-900 bg-amber-50/50">
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-900 border border-amber-300 rounded-full font-bold inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Staff (`staff`)</span>
                                </span>
                            </td>
                            <td class="p-3.5">ເຂົ້າເຖິງ Dashboard, ໜ້າຈັດການອໍເດີ (`orders.php`) ແລະ ໜ້າຈໍຫ້ອງຄົວ (`kitchen.php`).</td>
                            <td class="p-3.5 font-semibold text-amber-900">Super Admin & Manager</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3.5 font-bold text-gray-700 bg-gray-50">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-300 rounded-full font-bold inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Customer (`customer`)</span>
                                </span>
                            </td>
                            <td class="p-3.5">ບັນຊີລູກຄ້າສຳລັບເຂົ້າສູ່ລະບົບໜ້າເວັບ, ຕິດຕາມອໍເດີ ແລະ ສະສົມຄະແນນ.</td>
                            <td class="p-3.5 font-semibold text-gray-700">ສະໝັກຜ່ານໜ້າເວັບ</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Lockout explanation -->
            <div class="bg-red-50/80 p-6 rounded-2xl border border-red-200 space-y-3">
                <h4 class="text-sm font-bold text-red-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>ລະບົບປ້ອງກັນການສຸ່ມລະຫັດຜ່ານ 3 ຂັ້ນ (Progressive Lockout System)</span>
                </h4>
                <p class="text-xs sm:text-sm text-red-900 leading-relaxed">
                    ກໍລະນີມີຜູ້ປ້ອນລະຫັດຜ່ານຜິດ 3 ຄັ້ງ ລະບົບຈະລັອກບັນຊີອັດໂນມັດຕາມຂັ້ນຕອນ:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                    <div class="bg-white p-4 rounded-xl border border-red-200 shadow-sm space-y-1">
                        <span class="font-bold text-red-900 block text-sm">ຂັ້ນທີ 1 (Stage 1)</span>
                        <span class="text-gray-700">ປ້ອນຜິດ 3 ຄັ້ງ ➔ ລັອກ <strong>15 ນາທີ</strong></span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-red-200 shadow-sm space-y-1">
                        <span class="font-bold text-red-900 block text-sm">ຂັ້ນທີ 2 (Stage 2)</span>
                        <span class="text-gray-700">ປ້ອນຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ <strong>1 ຊົ່ວໂມງ</strong></span>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-red-200 shadow-sm space-y-1">
                        <span class="font-bold text-red-900 block text-sm">ຂັ້ນທີ 3 (Stage 3)</span>
                        <span class="text-gray-700">ປ້ອນຜິດອີກ 3 ຄັ້ງ ➔ ລັອກ <strong>24 ຊົ່ວໂມງ</strong> ຫຼື ຈົນກວ່າ Admin ຈະກົດ <span class="px-1.5 py-0.5 bg-green-100 text-green-800 rounded font-bold">"🔓 ປົດລັອກ"</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: MENU & BRANCH MANAGEMENT -->
    <div id="section-admin" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 space-y-6">
        <div class="flex items-center gap-3.5 border-b border-gray-100 pb-5">
            <div class="w-10 h-10 rounded-xl bg-amber-700 text-white flex items-center justify-center font-bold text-base shadow-sm">4</div>
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">4. ການຈັດການຂໍ້ມູນເມນູ, ສາຂາ & ເນື້ອຫາ (Content Management)</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">ວິທີເພີ່ມ/ແກ້ໄຂ ເມນູອາຫານ, ສາຂາຮ້ານ, ຂ່າວສານ, Banner ແລະ ຮູບພາບ Gallery</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs sm:text-sm">
            <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-2.5">
                <h4 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-burgundy-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>ຈັດການເມນູ (`admin/menu_manage.php`)</span>
                </h4>
                <p class="text-gray-700 leading-relaxed">
                    ເພີ່ມ/ແກ້ໄຂ ລາຍການອາຫານ ແລະ ເຄື່ອງດື່ມ, ອັບໂຫຼດຮູບພາບ, ຕັ້ງຄ່າລາຄາ, ກຳນົດໝວດໝູ່ (ກາເຟ, ເບຍ, ອາຫານ...) ແລະ ສະຖານະ <strong class="text-gray-900">ມີ/ໝົດ</strong>.
                </p>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-2.5">
                <h4 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>ຈັດການສາຂາ (`admin/branch_manage.php`)</span>
                </h4>
                <p class="text-gray-700 leading-relaxed">
                    ເພີ່ມ/ແກ້ໄຂ ສາຂາຮ້ານ LaoFe & Beer ທົ່ວປະເທດ (ຊື່ສາຂາ, ທີ່ຢູ່, ເບີໂທ, ເວລາເປີດ-ປິດ, ລິ້ງ Google Maps ແລະ ຮູບພາບ).
                </p>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-2.5">
                <h4 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>ບົດຄວາມ & ກິດຈະກຳ (`admin/news_manage.php`)</span>
                </h4>
                <p class="text-gray-700 leading-relaxed">
                    ໂພສຂ່າວສານ, ໂປຣໂມຊັນ ແລະ ກິດຈະກຳໃໝ່ໆ ຂອງຮ້ານ ພ້ອມຮອງຮັບການສະແດງຜົນ 2 ພາສາ (ລາວ & ອັງກິດ).
                </p>
            </div>

            <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-2.5">
                <h4 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Banner & Gallery (`admin/banner_manage.php`)</span>
                </h4>
                <p class="text-gray-700 leading-relaxed">
                    ຈັດການ Banner Slider ຢູ່ໜ້າເວັບຫຼັກ ແລະ ຮູບພາບ Gallery ບັນຍາກາດຮ້ານ ເພື່ອຄວາມສວຍງາມ ແລະ ທັນສະໄໝ.
                </p>
            </div>
        </div>
    </div>

</div>

</main>
</body>
</html>
