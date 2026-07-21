<?php
// admin/applications.php
require_once __DIR__ . '/header.php';

$franchises = [];
$contacts = [];
$error = '';

try {
    // ດຶງຂໍ້ມູນໃບສະໝັກແຟຣນໄຊສ໌ທັງໝົດ
    $franchises = $pdo->query("SELECT * FROM franchise_applications ORDER BY id DESC")->fetchAll();

    // ດຶງຂໍ້ມູນຂໍ້ຄວາມຕິດຕໍ່ທັງໝົດ
    $contacts = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
}
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ໃບສະໝັກແຟຣນໄຊສ໌ & ຂໍ້ຄວາມຕິດຕໍ່</h1>
        <p class="text-sm text-gray-500 mt-1">ຕິດຕາມການສະໝັກຮ່ວມທຸລະກິດ ແລະ ຄຳຕິຊົມຂອງລູກຄ້າ</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Tabs or Two sections layout -->
    <div class="space-y-12">
        
        <!-- Section 1: Franchise Inquiries -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h3 class="text-xl font-bold text-gray-950 border-b pb-3 font-serif-lao flex items-center space-x-2">
                <span class="w-2.5 h-6 bg-burgundy-700 rounded-full"></span>
                <span>ລາຍການສະໝັກຮ່ວມທຸລະກິດແຟຣນໄຊສ໌ (Franchise Applications)</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ຊື່ຜູ້ສະໝັກ</th>
                            <th class="px-4 py-3">ຂໍ້ມູນຕິດຕໍ່ (ເບີໂທ / ອີເມລ)</th>
                            <th class="px-4 py-3">ສາຂາ/ສະຖານທີ່ສົນໃຈ</th>
                            <th class="px-4 py-3">ຂໍ້ຄວາມ/ໝາຍເຫດ</th>
                            <th class="px-4 py-3">ວັນທີສະໝັກ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($franchises) > 0): ?>
                            <?php foreach ($franchises as $fran): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($fran['full_name']); ?></td>
                                    <td class="px-4 py-3">
                                        <div><?php echo htmlspecialchars($fran['phone']); ?></div>
                                        <div class="text-xs text-gray-400 font-light"><?php echo htmlspecialchars($fran['email']); ?></div>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-burgundy-700"><?php echo htmlspecialchars($fran['location_preference']); ?></td>
                                    <td class="px-4 py-3 font-light text-xs max-w-xs leading-relaxed"><?php echo nl2br(htmlspecialchars($fran['message'])); ?></td>
                                    <td class="px-4 py-3 text-xs"><?php echo date("d-m-Y H:i", strtotime($fran['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-400 font-light">ຍັງບໍ່ມີຂໍ້ມູນການສະໝັກຮ່ວມທຸລະກິດ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Contact Messages -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h3 class="text-xl font-bold text-gray-950 border-b pb-3 font-serif-lao flex items-center space-x-2">
                <span class="w-2.5 h-6 bg-burgundy-700 rounded-full"></span>
                <span>ຂໍ້ຄວາມຕິດຕໍ່ສອບຖາມຈາກລູກຄ້າ (Contact Messages)</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ຊື່ຜູ້ຕິດຕໍ່</th>
                            <th class="px-4 py-3">ຂໍ້ມູນຕິດຕໍ່ (ເບີໂທ / ອີເມລ)</th>
                            <th class="px-4 py-3">ຫົວຂໍ້</th>
                            <th class="px-4 py-3">ເນື້ອຫາຂໍ້ຄວາມ</th>
                            <th class="px-4 py-3">ວັນທີສົ່ງ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($contacts) > 0): ?>
                            <?php foreach ($contacts as $con): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($con['name']); ?></td>
                                    <td class="px-4 py-3">
                                        <div><?php echo htmlspecialchars($con['phone'] ?: '-'); ?></div>
                                        <div class="text-xs text-gray-400 font-light"><?php echo htmlspecialchars($con['email']); ?></div>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($con['subject'] ?: '-'); ?></td>
                                    <td class="px-4 py-3 font-light text-xs max-w-xs leading-relaxed"><?php echo nl2br(htmlspecialchars($con['message'])); ?></td>
                                    <td class="px-4 py-3 text-xs"><?php echo date("d-m-Y H:i", strtotime($con['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-400 font-light">ຍັງບໍ່ມີຂໍ້ຄວາມຕິດຕໍ່ຈາກລູກຄ້າ</td>
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
