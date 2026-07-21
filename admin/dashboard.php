<?php
// admin/dashboard.php
require_once __DIR__ . '/header.php';

// ດຶງຂໍ້ມູນສະຖິຕິ
$menu_count = 0;
$news_count = 0;
$franchise_count = 0;
$contact_count = 0;

try {
    $menu_count = $pdo->query("SELECT COUNT(*) FROM menus")->fetchColumn();
    $news_count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
    $franchise_count = $pdo->query("SELECT COUNT(*) FROM franchise_applications")->fetchColumn();
    $contact_count = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

    // ດຶງໃບສະໝັກແຟຣນໄຊສ໌ຫຼ້າສຸດ 3 ລາຍການ
    $recent_franchises = $pdo->query("SELECT * FROM franchise_applications ORDER BY id DESC LIMIT 3")->fetchAll();

    // ດຶງຂໍ້ຄວາມຕິດຕໍ່ຫຼ້າສຸດ 3 ລາຍການ
    $recent_contacts = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 3")->fetchAll();
} catch (\Exception $e) {
    echo "<div class='bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded text-red-700 text-sm'>ເກີດຂໍ້ຜິດພາດໃນການດຶງຂໍ້ມູນ: " . $e->getMessage() . "</div>";
}
?>

<div class="space-y-8">
    
    <!-- Dashboard Heading -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-2 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ແຜງຄວບຄຸມ (Dashboard)</h1>
            <p class="text-sm text-gray-500 mt-1">ພາບລວມລະບົບ ແລະ ຂໍ້ມູນສະຖິຕິຫຼ້າສຸດ</p>
        </div>
        <div class="text-sm text-gray-400">
            ເວລາເຊີເວີ: <strong><?php echo date('Y-m-d H:i:s'); ?></strong>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Menus -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-burgundy-700/10 text-burgundy-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ເມນູອາຫານ & ເຄື່ອງດື່ມ</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?php echo $menu_count; ?> ເມນູ</h3>
            </div>
        </div>

        <!-- Card 2: News -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-burgundy-700/10 text-burgundy-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ຂ່າວສານ & ໂປຣໂມຊັນ</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?php echo $news_count; ?> ບົດຄວາມ</h3>
            </div>
        </div>

        <!-- Card 3: Franchise -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-burgundy-700/10 text-burgundy-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ໃບສະໝັກແຟຣນໄຊສ໌</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?php echo $franchise_count; ?> ລາຍການ</h3>
            </div>
        </div>

        <!-- Card 4: Contacts -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-burgundy-700/10 text-burgundy-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ຂໍ້ຄວາມຕິດຕໍ່</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?php echo $contact_count; ?> ຂໍ້ຄວາມ</h3>
            </div>
        </div>

    </div>

    <!-- Tables for recent requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Franchise applications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ໃບສະໝັກແຟຣນໄຊສ໌ຫຼ້າສຸດ</h3>
                <a href="applications.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline">ເບິ່ງທັງໝົດ</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">ຊື່ຜູ້ສະໝັກ</th>
                            <th class="px-4 py-3">ເບີໂທ</th>
                            <th class="px-4 py-3">ສະຖານທີ່ສົນໃຈ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recent_franchises) > 0): ?>
                            <?php foreach ($recent_franchises as $row): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['location_preference']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400 font-light">ຍັງບໍ່ມີຂໍ້ມູນການສະໝັກ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Contact messages -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ຂໍ້ຄວາມຕິດຕໍ່ຫຼ້າສຸດ</h3>
                <a href="applications.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline">ເບິ່ງທັງໝົດ</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">ຊື່ຜູ້ຕິດຕໍ່</th>
                            <th class="px-4 py-3">ຫົວຂໍ້</th>
                            <th class="px-4 py-3">ວັນທີ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recent_contacts) > 0): ?>
                            <?php foreach ($recent_contacts as $row): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td class="px-4 py-3"><?php echo date("d-m-Y", strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400 font-light">ຍັງບໍ່ມີຂໍ້ຄວາມຕິດຕໍ່</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

</main>
</body>
</html>
