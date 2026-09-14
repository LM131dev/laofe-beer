<?php
// admin/kitchen.php
require_once __DIR__ . '/../config/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ກວດສອບຄວາມປອດໄພ
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// ຈັດການປຸງແຕ່ງສຳເລັດ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_order'])) {
    $order_id = intval($_POST['order_id'] ?? 0);
    if ($order_id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
            $stmt->execute([$order_id]);
            $success = 'ອໍເດີ້ຖືກສົ່ງໃຫ້ລູກຄ້າສຳເລັດແລ້ວ!';
        } catch (\Exception $e) {
            $error = 'ເກີດຂໍ້ຜິດພາດ: ' . $e->getMessage();
        }
    }
}

// ດຶງອໍເດີ້ທີ່ຈ່າຍແລ້ວ (Paid) ແລະ ກຳລັງລໍຖ້າການປຸງແຕ່ງ
$kitchen_orders = [];
try {
    $stmt = $pdo->query("SELECT * FROM orders WHERE status = 'Paid' ORDER BY id ASC");
    $kitchen_orders = $stmt->fetchAll();
} catch (\Exception $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display System - LaoFe & Beer</title>
    <!-- Auto refresh every 10 seconds -->
    <meta http-equiv="refresh" content="10">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        burgundy: {
                            700: '#6b1d2f',
                            800: '#4a121e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- Kitchen Header -->
    <header class="bg-gray-950 border-b border-gray-800 px-6 py-4 flex justify-between items-center shrink-0">
        <div class="flex items-center space-x-3">
            <span class="w-3.5 h-3.5 rounded-full bg-red-600 animate-ping"></span>
            <h1 class="text-2xl font-bold font-mono tracking-wider">KITCHEN MONITOR</h1>
        </div>
        <div class="flex items-center space-x-6 text-sm text-gray-400">
            <span>ອໍເດີ້ລໍຖ້າປຸງແຕ່ງ: <strong class="text-white text-lg"><?php echo count($kitchen_orders); ?></strong></span>
            <a href="dashboard.php" class="px-4 py-2 bg-gray-850 hover:bg-gray-800 rounded-lg text-xs font-bold transition-all text-white">ກັບໄປ Dashboard</a>
        </div>
    </header>

    <!-- Orders Grid Area -->
    <main class="flex-grow p-6 overflow-y-auto">
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-600/20 border border-green-500/50 p-4 rounded-xl mb-6 text-green-400 text-sm font-semibold">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (count($kitchen_orders) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <?php foreach ($kitchen_orders as $order): 
                    // ດຶງລາຍການສິນຄ້າ ແລະ ໝາຍເຫດ
                    $items = [];
                    $notes = '';
                    try {
                        $stmt_i = $pdo->prepare("SELECT oi.*, m.name_lo 
                                                FROM order_items oi 
                                                JOIN menus m ON oi.menu_id = m.id 
                                                WHERE oi.order_id = ?");
                        $stmt_i->execute([$order['id']]);
                        $items = $stmt_i->fetchAll();
                        
                        // ດຶງໝາຍເຫດຫຼ້າສຸດຈາກລາຍການ
                        if (!empty($items)) {
                            $notes = $items[0]['notes'];
                        }
                    } catch (\Exception $e) {}

                    // ຄຳນວນເວລາຜ່ານໄປ (Elapsed minutes)
                    $created_time = strtotime($order['created_at']);
                    $elapsed_mins = floor((time() - $created_time) / 60);
                    $urgency_color = 'bg-green-500/20 text-green-400 border-green-500/30';
                    if ($elapsed_mins >= 20) {
                        $urgency_color = 'bg-red-500/30 text-red-400 border-red-500/50 animate-pulse';
                    } elseif ($elapsed_mins >= 10) {
                        $urgency_color = 'bg-amber-500/20 text-amber-400 border-amber-500/30';
                    }
                ?>
                    <!-- Order Ticket Card -->
                    <div class="bg-gray-950 border border-gray-800 rounded-2xl flex flex-col justify-between overflow-hidden shadow-2xl relative group">
                        <!-- Top header of ticket -->
                        <div class="p-4 bg-burgundy-700 flex justify-between items-center">
                            <div>
                                <span class="text-xs text-white/70 block uppercase font-mono tracking-wider">TICKET</span>
                                <span class="text-xl font-black font-mono">#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 rounded-full border text-xs font-mono font-bold <?php echo $urgency_color; ?>">
                                    ⏱️ <?php echo $elapsed_mins; ?>m ago
                                </span>
                                <div class="text-[10px] text-white/70 font-mono mt-1"><?php echo date("H:i:s", $created_time); ?></div>
                            </div>
                        </div>

                        <!-- Ticket Body -->
                        <div class="p-5 flex-grow space-y-4">
                            <!-- Items List -->
                            <ul class="divide-y divide-gray-800 text-base space-y-2 pb-2">
                                <?php foreach ($items as $item): ?>
                                    <li class="flex justify-between items-center py-2">
                                        <span class="font-bold text-lg text-yellow-400"><?php echo htmlspecialchars($item['name_lo']); ?></span>
                                        <span class="px-2.5 py-1 bg-gray-800 rounded-lg font-mono font-black text-white text-base">x <?php echo $item['quantity']; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <!-- Notes section -->
                            <?php if (!empty($notes)): ?>
                                <div class="bg-red-500/10 border border-red-500/20 p-3 rounded-xl">
                                    <span class="text-[10px] text-red-400 font-bold block uppercase tracking-wider font-mono">Kitchen Notes</span>
                                    <p class="text-sm text-red-200 mt-1 font-medium font-serif-lao"><?php echo htmlspecialchars($notes); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Ticket Actions -->
                        <div class="p-4 bg-gray-950 border-t border-gray-900 space-y-2">
                            <form action="kitchen.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" name="complete_order" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition-all duration-300 transform active:scale-95 flex items-center justify-center space-x-2 shadow-lg shadow-green-600/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>ປຸງແຕ່ງສຳເລັດ (Done)</span>
                                </button>
                            </form>
                            <button onclick="window.print()" class="w-full py-1.5 bg-gray-900 hover:bg-gray-800 text-gray-400 hover:text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.6 0-1.091-.462-1.12-1.06L5.88 18m11.78 0H5.88"/></svg>
                                <span>ພິມໃບບິນ</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <div class="h-[60vh] flex flex-col items-center justify-center space-y-4 text-gray-500">
                <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-lg font-light tracking-wider font-serif-lao">ຍັງບໍ່ມີອໍເດີ້ລໍຖ້າປຸງແຕ່ງໃນຂະນະນີ້</p>
                <span class="text-xs text-gray-600 font-mono">Auto refreshing every 10 seconds...</span>
            </div>
        <?php endif; ?>

    </main>

    <!-- Web Audio Synthetic Chime Sound Script -->
    <script>
        function playChime() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.3); // A5
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.5);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.5);
            } catch (e) {}
        }
        <?php if (count($kitchen_orders) > 0): ?>
            window.addEventListener('load', playChime);
        <?php endif; ?>
    </script>
</body>
</html>
