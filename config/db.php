<?php
// config/db.php - Secure Database Connection Setup

// 1. Parse .env file if available
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0 || empty($line)) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// 2. Environment Configurations
$app_env = getenv('APP_ENV') ?: 'development';
if ($app_env === 'production') {
    ini_set('display_errors', 0);
    error_reporting(0);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// 3. Database Credentials from Environment (No Hardcoded Passwords in Git)
$host     = getenv('DB_HOST') ?: '127.0.0.1';
$port     = getenv('DB_PORT') ?: '3306';
$db_name  = getenv('DB_NAME') ?: 'laofe_beer';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$charset  = getenv('DB_CHARSET') ?: 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("
    <div style='font-family: Arial, sans-serif; background: #FFFDF2; padding: 30px; text-align: center; border-radius: 20px; border: 2px solid #DCAE6C; max-width: 550px; margin: 60px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); color: #3D0B16;'>
        <h2 style='font-size: 20px; margin: 0 0 10px 0; color: #6B1D2F;'>Database Connection Error</h2>
        <p style='font-size: 14px; color: #531321; line-height: 1.6;'>
            ບໍ່ສາມາດເຊື່ອມຕໍ່ລະບົບ Database ໄດ້ໃນຂະນະນີ້. ກະລຸນາລອງໃໝ່ອີກຄັ້ງໃນພາຍຫຼັງ.
        </p>
    </div>
    ");
}
?>
