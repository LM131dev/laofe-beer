<?php
// admin/dashboard.php
require_once __DIR__ . '/header.php';

// ດຶງຂໍ້ມູນສະຖິຕິ
$menu_count = 0;
$news_count = 0;
$franchise_count = 0;
$contact_count = 0;
$orders_count = 0;
$total_revenue = 0;
$pending_bookings_count = 0;
$members_count = 0;

$recent_orders = [];
$recent_bookings = [];
$recent_franchises = [];

try {
    $menu_count = $pdo->query("SELECT COUNT(*) FROM menus")->fetchColumn();
    $news_count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
    $franchise_count = $pdo->query("SELECT COUNT(*) FROM franchise_applications")->fetchColumn();
    $contact_count = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

    // ດຶງຂໍ້ມູນອໍເດີ້ ແລະ ຍອດຂາຍ
    $orders_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $total_revenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status IN ('Paid', 'Completed')")->fetchColumn();

    // ດຶງຂໍ້ມູນການຈອງໂຕະ ແລະ ສະມາຊິກ
    $pending_bookings_count = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'Pending'")->fetchColumn();
    $members_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();

    // ດຶງອໍເດີ້ຫຼ້າສຸດ 5 ລາຍການ
    $stmt_ro = $pdo->query("SELECT o.*, u.fullname as customer_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5");
    $recent_orders = $stmt_ro->fetchAll();

    // ດຶງການຈອງໂຕະຫຼ້າສຸດ 5 ລາຍການ
    $stmt_rb = $pdo->query("SELECT b.*, br.name_lo as branch_name FROM bookings b JOIN branches br ON b.branch_id = br.id ORDER BY b.id DESC LIMIT 5");
    $recent_bookings = $stmt_rb->fetchAll();

    // ດຶງໃບສະໝັກແຟຣນໄຊສ໌ຫຼ້າສຸດ 3 ລາຍການ
    $recent_franchises = $pdo->query("SELECT * FROM franchise_applications ORDER BY id DESC LIMIT 3")->fetchAll();

    // ດຶງຂໍ້ມູນວິເຄາະ 7 ວັນຍ້ອນຫຼັງ ສຳລັບ Line Chart
    $chart_days = [];
    $chart_revenues = [];
    $chart_order_counts = [];

    for ($i = 6; $i >= 0; $i--) {
        $d_str = date('Y-m-d', strtotime("-$i days"));
        $d_label = date('d/m', strtotime("-$i days"));
        $chart_days[] = $d_label;

        // ຍອດຂາຍ
        $stmt_c = $pdo->prepare("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE DATE(created_at) = ? AND status IN ('Paid', 'Completed')");
        $stmt_c->execute([$d_str]);
        $chart_revenues[] = floatval($stmt_c->fetchColumn());

        // ອໍເດີ້
        $stmt_oc = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = ?");
        $stmt_oc->execute([$d_str]);
        $chart_order_counts[] = intval($stmt_oc->fetchColumn());
    }

} catch (\Exception $e) {
    echo "<div class='bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded text-red-700 text-sm'>ເກີດຂໍ້ຜິດພາດໃນການດຶງຂໍ້ມູນ: " . $e->getMessage() . "</div>";
}
?>

<div class="space-y-8 pb-12">
    
    <!-- Dashboard Heading & Action Quicklinks -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-serif-lao flex items-center gap-2">
                <span>ແຜງຄວບຄຸມ</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">ພາບລວມລະບົບ, ຍອດຂາຍອອນໄລນ໌ ແລະ ການຈອງໂຕະ LaoFe & Beer</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="kitchen.php" target="_blank" class="px-4 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-gray-950 font-bold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-yellow-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>ເປີດ Kitchen Monitor</span>
            </a>
            <a href="orders.php" class="px-4 py-2.5 bg-burgundy-700 hover:bg-burgundy-800 text-white font-bold rounded-xl text-xs flex items-center space-x-2 shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>ຈັດການອໍເດີ້</span>
            </a>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric 1: Total Revenue -->
        <div class="bg-gradient-to-br from-burgundy-700 to-burgundy-900 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <p class="text-xs font-semibold text-white/70 uppercase tracking-wider font-serif-lao">ຍອດຂາຍລວມ (Revenue)</p>
            <h3 class="text-2xl lg:text-3xl font-black mt-2 font-mono tracking-tight"><?php echo number_format($total_revenue); ?> <span class="text-xs font-normal">LAK</span></h3>
            <div class="mt-4 flex items-center text-[11px] text-white/80 space-x-1 font-serif-lao">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                <span>ຈາກອໍເດີ້ທີ່ຊຳລະແລ້ວ</span>
            </div>
        </div>

        <!-- Metric 2: Total Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 bg-amber-50 text-amber-600 rounded-2xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-serif-lao">ອໍເດີ້ທັງໝົດ</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-mono"><?php echo number_format($orders_count); ?> <span class="text-xs text-gray-500 font-sans">ບິນ</span></h3>
            </div>
        </div>

        <!-- Metric 3: Pending Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-serif-lao">ຈອງໂຕະ (Pending)</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-mono"><?php echo number_format($pending_bookings_count); ?> <span class="text-xs text-gray-500 font-sans">ລາຍການ</span></h3>
            </div>
        </div>

        <!-- Metric 4: Registered Members -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 bg-purple-50 text-purple-600 rounded-2xl">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider font-serif-lao">ສະມາຊິກ</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-mono"><?php echo number_format($members_count); ?> <span class="text-xs text-gray-500 font-sans">ຄົນ</span></h3>
            </div>
        </div>

    </div>

    <!-- Analytics Line Chart Card (Sales & Orders Trend 7 Days) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 font-serif-lao flex items-center gap-2">
                    <span>📈 ວິເຄາະແນວໂນ້ມຍອດຂາຍ & ອໍເດີ້ (Sales & Orders Trend - 7 Days)</span>
                </h3>
                <p class="text-xs text-gray-500 font-serif-lao mt-0.5">ສະຖິຕິການເຕີບໂຕ ແລະ ການເຄື່ອນໄຫວຍອດຂາຍປະຈຳ 7 ວັນຍ້ອນຫຼັງ</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-serif-lao">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-burgundy-50 text-burgundy-700 font-bold rounded-full border border-burgundy-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-burgundy-700"></span>
                    ຍອດຂາຍ (LAK)
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 font-bold rounded-full border border-amber-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    ຈຳນວນອໍເດີ້ (ບິນ)
                </span>
            </div>
        </div>

        <div class="relative w-full h-72 sm:h-80">
            <canvas id="salesAnalyticsChart"></canvas>
        </div>
    </div>

    <!-- Chart.js CDN & Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('salesAnalyticsChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        // Custom Gradient for Revenue Line Fill
        const gradientRevenue = ctx.createLinearGradient(0, 0, 0, 300);
        gradientRevenue.addColorStop(0, 'rgba(107, 29, 47, 0.35)');
        gradientRevenue.addColorStop(1, 'rgba(107, 29, 47, 0.0)');

        const gradientOrders = ctx.createLinearGradient(0, 0, 0, 300);
        gradientOrders.addColorStop(0, 'rgba(234, 179, 8, 0.25)');
        gradientOrders.addColorStop(1, 'rgba(234, 179, 8, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_days); ?>,
                datasets: [
                    {
                        label: 'ຍອດຂາຍ (LAK)',
                        data: <?php echo json_encode($chart_revenues); ?>,
                        borderColor: '#6b1d2f',
                        backgroundColor: gradientRevenue,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6b1d2f',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        yAxisID: 'y'
                    },
                    {
                        label: 'ຈຳນວນອໍເດີ້ (ບິນ)',
                        data: <?php echo json_encode($chart_order_counts); ?>,
                        borderColor: '#eab308',
                        backgroundColor: gradientOrders,
                        borderWidth: 2.5,
                        borderDash: [5, 5],
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#eab308',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#26050E',
                        titleFont: { family: 'Noto Serif Lao', size: 13, weight: 'bold' },
                        bodyFont: { family: 'Noto Serif Lao', size: 12 },
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat().format(context.raw) + ' LAK';
                                } else {
                                    label += context.raw + ' ບິນ';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Noto Serif Lao', size: 11 }, color: '#6b7280' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            font: { family: 'Noto Serif Lao', size: 11 },
                            color: '#6b1d2f',
                            callback: function(val) {
                                if (val >= 1000000) return (val / 1000000) + 'M';
                                if (val >= 1000) return (val / 1000) + 'K';
                                return val;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            font: { family: 'Noto Serif Lao', size: 11 },
                            color: '#d97706',
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
    </script>

    <!-- Active Orders & Table Bookings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao flex items-center gap-2">
                    <span>🛒 ອໍເດີ້ສັ່ງຊື້ຫຼ້າສຸດ</span>
                </h3>
                <a href="orders.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline font-serif-lao">ເບິ່ງທັງໝົດ</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ເລກບິນ</th>
                            <th class="px-4 py-3">ລູກຄ້າ</th>
                            <th class="px-4 py-3">ຍອດລວມ</th>
                            <th class="px-4 py-3">ສະຖານະ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recent_orders) > 0): ?>
                            <?php foreach ($recent_orders as $ord): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-950 font-mono">#<?php echo str_pad($ord['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td class="px-4 py-3 font-semibold text-gray-800"><?php echo htmlspecialchars($ord['customer_name'] ?: 'Guest (ລູກຄ້າທົ່ວໄປ)'); ?></td>
                                    <td class="px-4 py-3 font-bold text-burgundy-700 font-mono"><?php echo number_format($ord['total_amount']); ?> LAK</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            <?php 
                                                if ($ord['status'] === 'Paid') echo 'bg-green-50 text-green-700';
                                                elseif ($ord['status'] === 'Pending') echo 'bg-yellow-50 text-yellow-700';
                                                elseif ($ord['status'] === 'Completed') echo 'bg-blue-50 text-blue-700';
                                                else echo 'bg-gray-100 text-gray-600';
                                            ?>">
                                            <?php echo htmlspecialchars($ord['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400 font-light font-serif-lao">ຍັງບໍ່ມີອໍເດີ້ໃນລະບົບ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Table Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao flex items-center gap-2">
                    <span>📅 ການຈອງໂຕະຫຼ້າສຸດ</span>
                </h3>
                <a href="bookings.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline font-serif-lao">ເບິ່ງທັງໝົດ</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">ສາຂາ</th>
                            <th class="px-4 py-3">ລູກຄ້າ</th>
                            <th class="px-4 py-3">ວັນທີ / ເວລາ</th>
                            <th class="px-4 py-3">ສະຖານະ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($recent_bookings) > 0): ?>
                            <?php foreach ($recent_bookings as $bk): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo htmlspecialchars($bk['branch_name']); ?></td>
                                    <td class="px-4 py-3 font-semibold text-gray-800"><?php echo htmlspecialchars($bk['booking_name']); ?></td>
                                    <td class="px-4 py-3 font-mono"><?php echo htmlspecialchars($bk['booking_date']); ?> (<?php echo date("H:i", strtotime($bk['booking_time'])); ?>)</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            <?php 
                                                if ($bk['status'] === 'Confirmed') echo 'bg-green-50 text-green-700';
                                                elseif ($bk['status'] === 'Pending') echo 'bg-yellow-50 text-yellow-700';
                                                else echo 'bg-gray-100 text-gray-600';
                                            ?>">
                                            <?php echo htmlspecialchars($bk['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400 font-light font-serif-lao">ຍັງບໍ່ມີຂໍ້ມູນການຈອງໂຕະ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Secondary Management Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Franchise applications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ໃບສະໝັກແຟຣນໄຊສ໌ (<?php echo $franchise_count; ?>)</h3>
                <a href="applications.php" class="text-xs text-burgundy-700 hover:text-burgundy-800 font-bold underline font-serif-lao">ເບິ່ງທັງໝົດ</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-500">
                    <thead class="text-[11px] text-gray-700 uppercase bg-gray-50">
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
                                    <td class="px-4 py-3 font-mono"><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($row['location_preference']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-400 font-light font-serif-lao">ຍັງບໍ່ມີຂໍ້ມູນການສະໝັກ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Shortcuts Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-950 font-serif-lao">ທາງລັດຈັດການລະບົບ (Quick Actions)</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="menu_manage.php" class="p-4 rounded-xl border border-gray-100 hover:border-burgundy-700/30 hover:bg-burgundy-50/20 transition-all flex flex-col items-center text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800 font-serif-lao">ເພີ່ມ/ແກ້ໄຂ ເມນູ (<?php echo $menu_count; ?>)</span>
                </a>
                <a href="members.php" class="p-4 rounded-xl border border-gray-100 hover:border-burgundy-700/30 hover:bg-burgundy-50/20 transition-all flex flex-col items-center text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800 font-serif-lao">ຈັດການສະມາຊິກ</span>
                </a>
                <a href="banner_manage.php" class="p-4 rounded-xl border border-gray-100 hover:border-burgundy-700/30 hover:bg-burgundy-50/20 transition-all flex flex-col items-center text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800 font-serif-lao">ຈັດການ Banner ຮູບພາບ</span>
                </a>
                <a href="news_manage.php" class="p-4 rounded-xl border border-gray-100 hover:border-burgundy-700/30 hover:bg-burgundy-50/20 transition-all flex flex-col items-center text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-lg bg-green-100 text-green-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800 font-serif-lao">ຈັດການບົດຄວາມ & ກິດຈະກຳ (<?php echo $news_count; ?>)</span>
                </a>
            </div>
        </div>

    </div>

</div>

</main>
</body>
</html>

