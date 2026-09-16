<?php
// includes/captcha.php - Anti-Spam Captcha Utility (Turnstile / reCAPTCHA / Math Fallback)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Render CAPTCHA Widget based on Environment Configuration
 */
function render_captcha_widget($lang = 'lo') {
    $turnstile_site = getenv('TURNSTILE_SITE_KEY') ?: ($_ENV['TURNSTILE_SITE_KEY'] ?? '');
    $recaptcha_site = getenv('RECAPTCHA_SITE_KEY') ?: ($_ENV['RECAPTCHA_SITE_KEY'] ?? '');

    if (!empty($turnstile_site)) {
        echo '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
        echo '<div class="cf-turnstile my-2" data-sitekey="' . htmlspecialchars($turnstile_site) . '"></div>';
        return;
    }

    if (!empty($recaptcha_site)) {
        echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
        echo '<div class="g-recaptcha my-2" data-sitekey="' . htmlspecialchars($recaptcha_site) . '"></div>';
        return;
    }

    // Built-in Session Math Captcha Fallback (Zero external dependencies)
    $num1 = rand(2, 9);
    $num2 = rand(1, 9);
    $_SESSION['captcha_answer'] = $num1 + $num2;

    $label = ($lang === 'lo')
        ? "🔒 ກວດສອບຄວາມປອດໄພ (ກັນ Spam Bot): {$num1} + {$num2} = ?"
        : "🔒 Security Check (Anti-Spam Bot): {$num1} + {$num2} = ?";
    $placeholder = ($lang === 'lo') ? "ປ້ອນຜົນລວມຢູ່ບ່ອນນີ້..." : "Enter sum answer here...";

    echo '<div class="p-4 bg-[#FAF7F2] border border-[#E2D9CC] rounded-xl space-y-2 my-3 font-sans-lao">';
    echo '  <label for="captcha_answer" class="block text-xs font-bold text-[#4A3B34] uppercase tracking-wider">' . htmlspecialchars($label) . ' *</label>';
    echo '  <input type="number" name="captcha_answer" id="captcha_answer" required placeholder="' . htmlspecialchars($placeholder) . '" class="w-full bg-white border border-[#E2D9CC] text-[#2C1810] rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 font-sans-lao text-sm shadow-sm" min="0" max="99">';
    echo '</div>';
}

/**
 * Verify CAPTCHA User Input
 */
function verify_captcha_response(&$error_message = '', $lang = 'lo') {
    $turnstile_secret = getenv('TURNSTILE_SECRET_KEY') ?: ($_ENV['TURNSTILE_SECRET_KEY'] ?? '');
    $recaptcha_secret = getenv('RECAPTCHA_SECRET_KEY') ?: ($_ENV['RECAPTCHA_SECRET_KEY'] ?? '');

    // 1. Cloudflare Turnstile Verification
    if (!empty($turnstile_secret)) {
        $token = $_POST['cf-turnstile-response'] ?? '';
        if (empty($token)) {
            $error_message = ($lang === 'lo') ? 'ກະລຸນາກວດສອບ Turnstile Captcha' : 'Please complete the Turnstile verification.';
            return false;
        }

        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret'   => $turnstile_secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5
            ]
        ];

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        if ($result !== false) {
            $json = json_decode($result, true);
            if (!empty($json['success'])) {
                return true;
            }
        }

        $error_message = ($lang === 'lo') ? 'ການຢັ້ງຢືນ Captcha ບໍ່ຖືກຕ້ອງ' : 'Turnstile verification failed.';
        return false;
    }

    // 2. Google reCAPTCHA Verification
    if (!empty($recaptcha_secret)) {
        $token = $_POST['g-recaptcha-response'] ?? '';
        if (empty($token)) {
            $error_message = ($lang === 'lo') ? 'ກະລຸນາກວດສອບ reCAPTCHA' : 'Please complete the reCAPTCHA verification.';
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret'   => $recaptcha_secret,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5
            ]
        ];

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        if ($result !== false) {
            $json = json_decode($result, true);
            if (!empty($json['success'])) {
                return true;
            }
        }

        $error_message = ($lang === 'lo') ? 'ການຢັ້ງຢືນ reCAPTCHA ບໍ່ຖືກຕ້ອງ' : 'reCAPTCHA verification failed.';
        return false;
    }

    // 3. Fallback Session Math Captcha Verification
    $user_answer = isset($_POST['captcha_answer']) ? intval($_POST['captcha_answer']) : null;
    $correct_answer = $_SESSION['captcha_answer'] ?? null;

    if ($correct_answer === null || $user_answer === null || $user_answer !== $correct_answer) {
        $error_message = ($lang === 'lo')
            ? 'ຄຳຕອບກວດສອບຄວາມປອດໄພ (Math Captcha) ບໍ່ຖືກຕ້ອງ. ກະລຸນາລອງໃໝ່.'
            : 'Incorrect security check answer (Math Captcha). Please try again.';
        return false;
    }

    // Clear captcha answer after successful verification to prevent replay
    unset($_SESSION['captcha_answer']);
    return true;
}
