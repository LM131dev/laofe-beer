<?php
// includes/auth_lockout.php - Multi-Tier Login Protection & Lockout Manager

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ensure login protection columns exist in database table `users`
 */
function ensure_lockout_columns($pdo) {
    static $checked = false;
    if ($checked) return;
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'failed_attempts'");
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE users 
                ADD COLUMN failed_attempts INT DEFAULT 0,
                ADD COLUMN lockout_tier INT DEFAULT 0,
                ADD COLUMN locked_until DATETIME NULL");
        }
    } catch (\Exception $e) {
        error_log("Lockout table column check error: " . $e->getMessage());
    }
    $checked = true;
}

/**
 * Check if a username is currently locked out
 * @return array ['locked' => bool, 'message' => string]
 */
function check_user_lockout($pdo, $username, $lang = 'lo') {
    ensure_lockout_columns($pdo);

    if (empty($username)) {
        return ['locked' => false, 'message' => ''];
    }

    try {
        $stmt = $pdo->prepare("SELECT id, failed_attempts, lockout_tier, locked_until FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || empty($user['locked_until'])) {
            return ['locked' => false, 'message' => ''];
        }

        $locked_until = strtotime($user['locked_until']);
        $now = time();

        // Check if lockout duration has passed
        if ($now >= $locked_until) {
            // Lockout expired - clear locked_until & reset failed attempts (keep lockout_tier for next escalation)
            $stmt_reset = $pdo->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
            $stmt_reset->execute([$user['id']]);
            return ['locked' => false, 'message' => ''];
        }

        // Account is currently locked
        $diff_sec = $locked_until - $now;
        $mins_left = ceil($diff_sec / 60);
        $hours_left = ceil($diff_sec / 3600);
        $tier = intval($user['lockout_tier']);

        if ($tier === 1) {
            $msg = ($lang === 'lo')
                ? "ທ່ານໃສ່ລະຫັດຜິດ 3 ຄັ້ງ. ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານ 15 ນາທີ (ເຫຼືອເວລາ $mins_left ນາທີ)."
                : "3 failed login attempts. Your account is locked for 15 minutes ($mins_left mins remaining).";
        } elseif ($tier === 2) {
            $msg = ($lang === 'lo')
                ? "ທ່ານໃສ່ລະຫັດຜິດຊ້ຳອີກ. ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານ 1 ຊົ່ວໂມງ (ເຫຼືອເວລາ $mins_left ນາທີ)."
                : "Repeated failed login attempts. Your account is locked for 1 hour ($mins_left mins remaining).";
        } else {
            $msg = ($lang === 'lo')
                ? "ທ່ານໃສ່ລະຫັດຜິດຫຼາຍເກີນໄປ. ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານຈົນເຖິງມື້ຖັດໄປ (ຫຼື ຈົນກວ່າ Admin ຈະປົດລ໋ອກໃຫ້). (ເຫຼືອເວລາ $hours_left ຊົ່ວໂມງ)"
                : "Multiple failed attempts. Your account is locked until tomorrow or until Admin unlocks it ($hours_left hours remaining).";
        }

        return ['locked' => true, 'message' => $msg];

    } catch (\Exception $e) {
        error_log("Lockout check exception: " . $e->getMessage());
        return ['locked' => false, 'message' => ''];
    }
}

/**
 * Record a failed login attempt and apply tiered lockout
 * @return string Error message to display to user
 */
function record_failed_login_attempt($pdo, $username, $lang = 'lo') {
    ensure_lockout_columns($pdo);

    $default_err = ($lang === 'lo') ? 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ!' : 'Invalid username or password.';

    if (empty($username)) {
        return $default_err;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, failed_attempts, lockout_tier FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            return $default_err;
        }

        $attempts = intval($user['failed_attempts']) + 1;
        $current_tier = intval($user['lockout_tier']);

        if ($attempts >= 3) {
            $next_tier = $current_tier + 1;
            
            if ($next_tier === 1) {
                // Tier 1: 15 minutes lockout
                $lockout_seconds = 15 * 60;
                $msg = ($lang === 'lo')
                    ? "ທ່ານໃສ່ລະຫັດຜິດ 3 ຄັ້ງ! ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານ 15 ນາທີ."
                    : "Entered incorrect password 3 times! Account locked for 15 minutes.";
            } elseif ($next_tier === 2) {
                // Tier 2: 1 hour lockout
                $lockout_seconds = 60 * 60;
                $msg = ($lang === 'lo')
                    ? "ທ່ານໃສ່ລະຫັດຜິດຊ້ຳອີກ 3 ຄັ້ງ! ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານ 1 ຊົ່ວໂມງ."
                    : "Entered incorrect password 3 times again! Account locked for 1 hour.";
            } else {
                // Tier 3+: 24 hours lockout (until next day or Admin unlocks)
                $lockout_seconds = 24 * 3600;
                $msg = ($lang === 'lo')
                    ? "ທ່ານໃສ່ລະຫັດຜິດ 3 ຄັ້ງຊ້ຳອີກ! ລະບົບໄດ້ລ໋ອກການເຂົ້າໃຊ້ງານຈົນເຖິງມື້ຖັດໄປ (ຫຼື ຈົນກວ່າ Admin ຈະປົດລ໋ອກໃຫ້)."
                    : "Multiple wrong attempts! Account locked until tomorrow or until Admin unlocks it.";
            }

            $locked_until = date('Y-m-d H:i:s', time() + $lockout_seconds);
            $stmt_upd = $pdo->prepare("UPDATE users SET failed_attempts = 0, lockout_tier = ?, locked_until = ? WHERE id = ?");
            $stmt_upd->execute([$next_tier, $locked_until, $user['id']]);

            return $msg;
        } else {
            // Under 3 attempts: record attempt count
            $stmt_upd = $pdo->prepare("UPDATE users SET failed_attempts = ? WHERE id = ?");
            $stmt_upd->execute([$attempts, $user['id']]);

            $remaining = 3 - $attempts;
            $warning = ($lang === 'lo')
                ? "ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ! (ປ້ອນຜິດ $attempts/3 ຄັ້ງ, ຫາກຜິດອີກ $remaining ຄັ້ງຈະຖືກລ໋ອກລະບົບ)"
                : "Invalid username or password! (Attempt $attempts/3, account will be locked after $remaining more failed attempt)";

            return $warning;
        }

    } catch (\Exception $e) {
        error_log("Record failed attempt error: " . $e->getMessage());
        return $default_err;
    }
}

/**
 * Reset lockout state on successful login
 */
function reset_user_lockout($pdo, $user_id) {
    ensure_lockout_columns($pdo);
    try {
        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, lockout_tier = 0, locked_until = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
    } catch (\Exception $e) {
        error_log("Reset lockout error: " . $e->getMessage());
    }
}

/**
 * Manually unlock a user account (Admin action)
 */
function admin_unlock_user($pdo, $user_id) {
    ensure_lockout_columns($pdo);
    try {
        $stmt = $pdo->prepare("UPDATE users SET failed_attempts = 0, lockout_tier = 0, locked_until = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
        return true;
    } catch (\Exception $e) {
        error_log("Admin unlock error: " . $e->getMessage());
        return false;
    }
}
