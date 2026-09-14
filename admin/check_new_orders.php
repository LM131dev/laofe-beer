<?php
// admin/check_new_orders.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

$last_id = intval($_GET['last_id'] ?? 0);

try {
    // ດຶງຈຳນວນອໍເດີ້ລໍຖ້າ (Pending & Paid)
    $stmt_p = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'");
    $pending_count = intval($stmt_p->fetchColumn());

    $stmt_pd = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Paid'");
    $paid_count = intval($stmt_pd->fetchColumn());

    // ດຶງ ID ສູງສຸດ
    $stmt_max = $pdo->query("SELECT MAX(id) FROM orders");
    $max_id = intval($stmt_max->fetchColumn() ?: 0);

    // ດຶງອໍເດີ້ໃໝ່ທີ່ເກີດขึ้นຫຼັງຈາກ last_id
    $new_orders = [];
    if ($last_id > 0 && $max_id > $last_id) {
        $stmt_new = $pdo->prepare("SELECT id, table_number, order_type, guest_name, total_amount, status, created_at FROM orders WHERE id > ? ORDER BY id DESC LIMIT 5");
        $stmt_new->execute([$last_id]);
        $rows = $stmt_new->fetchAll();

        foreach ($rows as $row) {
            $table_text = $row['table_number'] === 'Takeaway' ? '🥡 ສັ່ງກັບບ້ານ' : '📍 ໂຕະ ' . $row['table_number'];
            $new_orders[] = [
                'id' => $row['id'],
                'formatted_id' => '#' . str_pad($row['id'], 6, '0', STR_PAD_LEFT),
                'table_number' => $row['table_number'],
                'table_text' => $table_text,
                'guest_name' => $row['guest_name'] ?: 'ລູກຄ້າທົ່ວໄປ',
                'total_amount' => $row['total_amount'],
                'formatted_amount' => number_format($row['total_amount']) . ' ₭',
                'status' => $row['status'],
                'created_at' => date('H:i:s', strtotime($row['created_at'] ?? 'now'))
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'pending_count' => $pending_count,
        'paid_count' => $paid_count,
        'max_id' => $max_id,
        'has_new' => !empty($new_orders),
        'new_orders' => $new_orders
    ]);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
