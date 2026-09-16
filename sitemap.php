<?php
// sitemap.php - Dynamic XML Sitemap Generator
header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/config/db.php';

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base_url = $protocol . "://" . $host . ($base_dir ? $base_dir : '');

$pages = [
    ['path' => '/', 'priority' => '1.0', 'freq' => 'daily'],
    ['path' => '/index.php', 'priority' => '1.0', 'freq' => 'daily'],
    ['path' => '/our-story.php', 'priority' => '0.8', 'freq' => 'monthly'],
    ['path' => '/menu.php', 'priority' => '0.9', 'freq' => 'weekly'],
    ['path' => '/locations.php', 'priority' => '0.8', 'freq' => 'monthly'],
    ['path' => '/news.php', 'priority' => '0.8', 'freq' => 'weekly'],
    ['path' => '/franchise.php', 'priority' => '0.7', 'freq' => 'monthly'],
    ['path' => '/contact.php', 'priority' => '0.7', 'freq' => 'monthly'],
    ['path' => '/booking.php', 'priority' => '0.8', 'freq' => 'weekly'],
    ['path' => '/app.php', 'priority' => '0.6', 'freq' => 'monthly'],
    ['path' => '/login.php', 'priority' => '0.5', 'freq' => 'monthly'],
    ['path' => '/register.php', 'priority' => '0.5', 'freq' => 'monthly'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $p) {
    echo '  <url>' . "\n";
    echo '    <loc>' . htmlspecialchars($base_url . $p['path']) . '</loc>' . "\n";
    echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    echo '    <changefreq>' . $p['freq'] . '</changefreq>' . "\n";
    echo '    <priority>' . $p['priority'] . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

// Fetch Dynamic News Articles
try {
    $stmt = $pdo->query("SELECT id, created_at FROM news ORDER BY id DESC");
    while ($row = $stmt->fetch()) {
        $lastmod = !empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : date('Y-m-d');
        echo '  <url>' . "\n";
        echo '    <loc>' . htmlspecialchars($base_url . '/news-detail.php?id=' . $row['id']) . '</loc>' . "\n";
        echo '    <lastmod>' . $lastmod . '</lastmod>' . "\n";
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
} catch (Exception $e) {
    // Fallback gracefully if database table is empty
}

echo '</urlset>' . "\n";
