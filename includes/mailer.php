<?php
// includes/mailer.php - Email Notification Helper for Contact & Franchise Submissions

/**
 * Send an HTML email notification to the site administrator
 *
 * @param string $subject Subject line of the notification email
 * @param string $html_body HTML formatted body of the message
 * @return bool True if mail dispatch attempted successfully, false otherwise
 */
function send_admin_notification($subject, $html_body) {
    $to = getenv('NOTIFICATION_EMAIL') ?: ($_ENV['NOTIFICATION_EMAIL'] ?? 'info@laofecafe.com');
    if (empty($to)) {
        return false;
    }

    $from_email = getenv('MAIL_FROM') ?: ($_ENV['MAIL_FROM'] ?? 'no-reply@laofecafe.com');
    $from_name  = getenv('MAIL_FROM_NAME') ?: ($_ENV['MAIL_FROM_NAME'] ?? 'LaoFe & Beer Website');

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($from_name) . "?= <{$from_email}>\r\n";
    $headers .= "Reply-To: {$from_email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    // Standard HTML email wrapper for crisp rendering across mail clients
    $full_html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>" . htmlspecialchars($subject) . "</title>
    </head>
    <body style='font-family: Arial, sans-serif; background-color: #F8F5EE; margin: 0; padding: 20px; color: #2C1810;'>
        <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #EBE4D8; box-shadow: 0 4px 15px rgba(0,0,0,0.05);'>
            <div style='background: linear-gradient(135deg, #1C050B 0%, #531321 100%); padding: 25px; text-align: center; color: #ffffff;'>
                <h2 style='margin: 0; font-size: 22px; color: #F59E0B;'>☕ LaoFe & Beer Notification</h2>
                <p style='margin: 5px 0 0 0; font-size: 13px; color: #FDE68A;'>ແຈ້ງເຕືອນການສົ່ງຟອມໃໝ່ຈາກໜ້າເວັບໄຊ</p>
            </div>
            <div style='padding: 30px; font-size: 14px; line-height: 1.6;'>
                {$html_body}
            </div>
            <div style='background: #FAF7F2; padding: 15px; text-align: center; font-size: 12px; color: #9E8C82; border-t: 1px solid #EBE4D8;'>
                &copy; " . date('Y') . " LaoFe & Beer. System Automated Email.
            </div>
        </div>
    </body>
    </html>
    ";

    try {
        // Attempt native PHP mail dispatch
        return @mail($to, $encoded_subject, $full_html, $headers);
    } catch (\Throwable $e) {
        error_log("Failed to send admin notification email: " . $e->getMessage());
        return false;
    }
}
