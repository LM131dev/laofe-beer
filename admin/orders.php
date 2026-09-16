<?php
// admin/orders.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// ຈັດການການປ່ຽນສະຖານະອໍເດີ້ (POST + CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token ບໍ່ຖືກຕ້ອງ!';
    } else {
        $order_id = intval($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? 'Pending';

        if ($order_id > 0) {
            try {
                $pdo->beginTransaction();

                // ດຶງສະຖານະເກົ່າ ແລະ ຂໍ້ມູນອໍເດີ້
                $stmt_old = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
                $stmt_old->execute([$order_id]);
                $old_order = $stmt_old->fetch();

                if ($old_order) {
                    // ອັບເດດສະຖານະ
                    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $order_id]);

                    // ຫາກປ່ຽນເປັນ Paid ຫຼື Completed ຈາກ Pending/Cancelled ແລະ ອໍເດີ້ມີ user_id -> ໃຫ້ຄະແນນສະສົມ
                    if (in_array($status, ['Paid', 'Completed']) && !in_array($old_order['status'], ['Paid', 'Completed']) && $old_order['user_id']) {
                        $points_earned = intval($old_order['points_earned']);
                        if ($points_earned > 0) {
                            $stmt_u = $pdo->prepare("SELECT points FROM users WHERE id = ?");
                            $stmt_u->execute([$old_order['user_id']]);
                            $current_p = intval($stmt_u->fetchColumn());
                            
                            $new_p = $current_p + $points_earned;
                            
                            // ຄຳນວນ Tier
                            $new_tier = 'Member';
                            if ($new_p >= 1000) $new_tier = 'Platinum';
                            elseif ($new_p >= 500) $new_tier = 'Gold';
                            elseif ($new_p >= 100) $new_tier = 'Silver';

                            $stmt_up = $pdo->prepare("UPDATE users SET points = ?, tier = ? WHERE id = ?");
                            $stmt_up->execute([$new_p, $new_tier, $old_order['user_id']]);
                        }
                    }
                }

                $pdo->commit();
                $success = 'ອັບເດດສະຖານະອໍເດີ້ສຳເລັດແລ້ວ! (ສົ່ງຄະແນນສະສົມໃຫ້ລູກຄ້າອັດຕະໂນມັດ)';
            } catch (\Exception $e) {
                $pdo->rollBack();
                $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
            }
        }
    }
}

// ດຶງຂໍ້ມູນອໍເດີ້ທັງໝົດ
$status_filter = $_GET['status'] ?? '';
$orders = [];

try {
    $query = "SELECT o.*, u.fullname as customer_name, u.phone as customer_phone 
              FROM orders o 
              LEFT JOIN users u ON o.user_id = u.id";
    
    if ($status_filter !== '') {
        $query .= " WHERE o.status = :status";
    }
    $query .= " ORDER BY o.id DESC";

    $stmt = $pdo->prepare($query);
    if ($status_filter !== '') {
        $stmt->execute(['status' => $status_filter]);
    } else {
        $stmt->execute();
    }
    $orders = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດໃນການດຶງຂໍ້ມູນ: ' . $e->getMessage();
}
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການອໍເດີ້ (Order Management)</h1>
        <p class="text-sm text-gray-500 mt-1">ຕິດຕາມສະຖານະການສັ່ງຊື້ ແລະ ຊຳລະເງິນທັງໝົດ</p>
    </div>

    <!-- Status Filter -->
    <div class="flex flex-wrap gap-3">
        <a href="orders.php" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === '' ? 'bg-burgundy-700 text-white border-burgundy-700' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">ທັງໝົດ</a>
        <a href="orders.php?status=Pending" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Pending</a>
        <a href="orders.php?status=Paid" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Paid' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Paid</a>
        <a href="orders.php?status=Completed" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Completed' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Completed</a>
        <a href="orders.php?status=Cancelled" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Cancelled' ? 'bg-gray-600 text-white border-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Cancelled</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded text-green-700 text-sm font-semibold">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm font-semibold">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Orders List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ລາຍການອໍເດີ້ທັງໝົດ</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">ເລກບິນ</th>
                        <th class="px-6 py-3">ລູກຄ້າ / ເບີໂທ</th>
                        <th class="px-6 py-3">ລາຍການສິນຄ້າ</th>
                        <th class="px-6 py-3">ຍອດລວມ</th>
                        <th class="px-6 py-3">ວິທີຊຳລະ</th>
                        <th class="px-6 py-3">ສະຖານະ</th>
                        <th class="px-6 py-3 text-right">ອັບເດດສະຖານະ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): 
                            // ດຶງລາຍການໃນອໍເດີ້
                            $items = [];
                            try {
                                $stmt_i = $pdo->prepare("SELECT oi.*, m.name_lo FROM order_items oi JOIN menus m ON oi.menu_id = m.id WHERE oi.order_id = ?");
                                $stmt_i->execute([$order['id']]);
                                $items = $stmt_i->fetchAll();
                            } catch (\Exception $e) {}
                        ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-950">
                                    <div class="font-bold text-gray-900">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                                    <div class="mt-1">
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-950 border border-amber-300 font-extrabold text-xs font-serif-lao inline-flex items-center gap-1 shadow-sm">
                                            📍 <?php echo !empty($order['table_number']) ? 'ໂຕະ ' . htmlspecialchars($order['table_number']) : 'Takeaway'; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($order['guest_name'] ?: ($order['customer_name'] ?: 'ລູກຄ້າ Scan QR')); ?></div>
                                    <div class="text-xs text-gray-400 font-light"><?php echo htmlspecialchars($order['customer_phone'] ?: '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <ul class="list-disc pl-4 space-y-1">
                                        <?php foreach ($items as $item): ?>
                                            <li>
                                                <span class="font-bold text-gray-900"><?php echo htmlspecialchars($item['name_lo']); ?></span> 
                                                <span class="text-amber-800 font-semibold">x <?php echo $item['quantity']; ?></span>
                                                <?php if (!empty($item['notes'])): ?>
                                                    <div class="text-[11px] text-gray-500 font-light italic pl-1">
                                                        👉 <?php echo htmlspecialchars($item['notes']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td class="px-6 py-4 font-bold text-burgundy-700"><?php echo number_format($order['total_amount']); ?> LAK</td>
                                <td class="px-6 py-4 text-xs font-semibold"><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        <?php 
                                            if ($order['status'] === 'Paid') echo 'bg-green-50 text-green-700';
                                            elseif ($order['status'] === 'Pending') echo 'bg-yellow-50 text-yellow-700';
                                            elseif ($order['status'] === 'Completed') echo 'bg-blue-50 text-blue-700';
                                            else echo 'bg-gray-100 text-gray-600';
                                        ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="orders.php?status=<?php echo $status_filter; ?>" method="POST" class="flex items-center justify-end space-x-2">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="px-2 py-1 text-xs border rounded bg-white focus:outline-none">
                                            <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Paid" <?php echo $order['status'] === 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                            <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="px-3 py-1 bg-burgundy-700 text-white rounded text-xs font-semibold hover:bg-burgundy-800 transition-colors">
                                            OK
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-light">ບໍ່ມີລາຍການອໍເດີ້ໃນລະບົບ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</main>
</body>
</html>
