<?php
// admin/bookings.php
require_once __DIR__ . '/header.php';

$success = '';
$error = '';

// ຈັດການການອັບເດດສະຖານະການຈອງ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking_status'])) {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $status = $_POST['status'] ?? 'Pending';

    if ($booking_id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $booking_id]);
            $success = 'ອັບເດດສະຖານະການຈອງໂຕະສຳເລັດແລ້ວ!';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
        }
    }
}

// ດຶງຂໍ້ມູນການຈອງທັງໝົດ
$status_filter = $_GET['status'] ?? '';
$bookings = [];

try {
    $query = "SELECT b.*, br.name_lo as branch_lo, br.name_en as branch_en 
              FROM bookings b 
              JOIN branches br ON b.branch_id = br.id";
    
    if ($status_filter !== '') {
        $query .= " WHERE b.status = :status";
    }
    $query .= " ORDER BY b.booking_date DESC, b.booking_time DESC";

    $stmt = $pdo->prepare($query);
    if ($status_filter !== '') {
        $stmt->execute(['status' => $status_filter]);
    } else {
        $stmt->execute();
    }
    $bookings = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'ເກີດຂໍ້ຜິດພາດໃນການດຶງຂໍ້ມູນ: ' . $e->getMessage();
}
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 font-serif-lao">ຈັດການການຈອງໂຕະ (Table Bookings)</h1>
        <p class="text-sm text-gray-500 mt-1">ກວດສອບ ແລະ ຢັ້ງຢືນການຈອງໂຕະ/ເລົາຈ໌ ຂອງລູກຄ້າ</p>
    </div>

    <!-- Status Filter -->
    <div class="flex flex-wrap gap-3">
        <a href="bookings.php" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === '' ? 'bg-burgundy-700 text-white border-burgundy-700' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">ທັງໝົດ</a>
        <a href="bookings.php?status=Pending" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Pending</a>
        <a href="bookings.php?status=Confirmed" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Confirmed' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Confirmed</a>
        <a href="bookings.php?status=Cancelled" class="px-4 py-2 text-xs font-bold rounded-lg border <?php echo $status_filter === 'Cancelled' ? 'bg-gray-600 text-white border-gray-600' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">Cancelled</a>
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

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ລາຍຊື່ການຈອງໂຕະທັງໝົດ</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">ສາຂາ</th>
                        <th class="px-6 py-3">ວັນທີ / ເວລາ</th>
                        <th class="px-6 py-3">ຊື່ລູກຄ້າ / ເບີໂທ</th>
                        <th class="px-6 py-3">ຈຳນວນຄົນ / ໂຊນ</th>
                        <th class="px-6 py-3">ເງິນມັດຈຳ</th>
                        <th class="px-6 py-3">ສະຖານະການຈອງ</th>
                        <th class="px-6 py-3 text-right">ອັບເດດສະຖານະ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php foreach ($bookings as $book): ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900"><?php echo htmlspecialchars($book['branch_lo']); ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 font-mono"><?php echo htmlspecialchars($book['booking_date']); ?></div>
                                    <div class="text-xs text-gray-400 font-mono mt-0.5"><?php echo date("H:i", strtotime($book['booking_time'])); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($book['booking_name']); ?></div>
                                    <div class="text-xs text-gray-400 font-light"><?php echo htmlspecialchars($book['booking_phone']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800"><?php echo htmlspecialchars($book['table_zone']); ?></div>
                                    <div class="text-xs text-gray-400 mt-0.5"><?php echo $book['guest_count']; ?> ຄົນ</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 font-mono"><?php echo number_format($book['deposit_amount']); ?> LAK</div>
                                    <div class="text-[10px] mt-0.5">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase tracking-wider
                                            <?php echo $book['payment_status'] === 'Paid' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'; ?>">
                                            <?php echo htmlspecialchars($book['payment_status']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        <?php 
                                            if ($book['status'] === 'Confirmed') echo 'bg-green-50 text-green-700';
                                            elseif ($book['status'] === 'Pending') echo 'bg-yellow-50 text-yellow-700';
                                            else echo 'bg-gray-100 text-gray-600';
                                        ?>">
                                        <?php echo htmlspecialchars($book['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <?php if ($book['status'] === 'Pending'): ?>
                                            <form action="bookings.php?status=<?php echo $status_filter; ?>" method="POST" class="inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $book['id']; ?>">
                                                <input type="hidden" name="status" value="Confirmed">
                                                <button type="submit" name="update_booking_status" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition-all shadow-sm flex items-center space-x-1" title="Confirm Booking">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                    <span>Confirm</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form action="bookings.php?status=<?php echo $status_filter; ?>" method="POST" class="flex items-center space-x-1.5">
                                            <input type="hidden" name="booking_id" value="<?php echo $book['id']; ?>">
                                            <select name="status" class="px-2 py-1 text-xs border rounded bg-white focus:outline-none text-gray-700">
                                                <option value="Pending" <?php echo $book['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Confirmed" <?php echo $book['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="Cancelled" <?php echo $book['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_booking_status" class="px-2.5 py-1 bg-burgundy-700 text-white rounded text-xs font-semibold hover:bg-burgundy-800 transition-colors">
                                                Save
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-light">ບໍ່ມີລາຍການຈອງໂຕະໃນລະບົບ</td>
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
