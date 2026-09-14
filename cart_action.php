<?php
// cart_action.php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ປະກາດຕະກ້າສິນຄ້າຖ້າຍັງບໍ່ມີ
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';
$menu_id = intval($_GET['id'] ?? 0);
$quantity = intval($_GET['qty'] ?? 1);
$user_notes = trim($_GET['notes'] ?? '');
$temp = trim($_GET['temp'] ?? '');
$sweet = trim($_GET['sweet'] ?? '');

$note_parts = [];
if (!empty($temp)) {
    $note_parts[] = ($temp === 'iced' ? '❄️ Iced (ເຢັນ)' : ($temp === 'hot' ? '🔥 Hot (ຮ້ອນ)' : '🧊 Extra Cold'));
}
if ($sweet !== '') {
    $note_parts[] = "ຫວານ " . $sweet . "%";
}
if (!empty($user_notes)) {
    $note_parts[] = $user_notes;
}
$notes = implode(" | ", $note_parts);

$redirect = $_SERVER['HTTP_REFERER'] ?? 'menu.php';

if ($action === 'add' && $menu_id > 0) {
    // ດຶງຂໍ້ມູນເມນູຈາກ DB ເພື່ອກວດສອບວ່າມີຈິງ
    try {
        $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$menu_id]);
        $menu_item = $stmt->fetch();

        if ($menu_item) {
            $cart_key = $menu_id . '_' . md5($notes);
            // ກວດສອບວ່າມີໃນຕະກ້າແລ້ວບໍ່
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cart_key] = [
                    'id' => $menu_item['id'],
                    'name_lo' => $menu_item['name_lo'],
                    'name_en' => $menu_item['name_en'],
                    'price' => $menu_item['price'],
                    'image_path' => $menu_item['image_path'],
                    'quantity' => $quantity,
                    'notes' => $notes
                ];
            }
        }
    } catch (\Exception $e) {
        // error
    }
}

$item_key = $_GET['key'] ?? $menu_id;

if ($action === 'update') {
    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$item_key])) {
            $_SESSION['cart'][$item_key]['quantity'] = $quantity;
        }
    } else {
        unset($_SESSION['cart'][$item_key]);
    }
}

if ($action === 'remove') {
    if (isset($_SESSION['cart'][$item_key])) {
        unset($_SESSION['cart'][$item_key]);
    }
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
}

header("Location: " . $redirect);
exit;
?>
