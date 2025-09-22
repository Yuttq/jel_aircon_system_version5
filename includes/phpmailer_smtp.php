<?php
/**
 * PHPMailer SMTP Email Function
 * A more reliable alternative to the custom SMTP implementation
 */

// Load PHPMailer autoloader
require_once __DIR__ . '/../vendor/autoload.php';
// Ensure our custom SMTP helper is available for the PHPMailer stub
require_once __DIR__ . '/simple_smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendPHPMailerEmail($to, $subject, $message, $isHtml = true) {
    if (!defined('SMTP_HOST') || !defined('SMTP_USERNAME') || !defined('SMTP_PASSWORD')) {
        error_log("SMTP configuration not defined");
        return false;
    }
    
    // Check if PHPMailer is available
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        error_log("PHPMailer not installed. Please run: composer install");
        return false;
    }
    
    try {
        $mail = new PHPMailer(true);
        // Prepare debug capture
        if (!isset($GLOBALS['LAST_PHPMAILER_DEBUG'])) { $GLOBALS['LAST_PHPMAILER_DEBUG'] = ''; }
        if (!isset($GLOBALS['LAST_PHPMAILER_ERROR'])) { $GLOBALS['LAST_PHPMAILER_ERROR'] = ''; }
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? 'tls' : 'ssl';
        $mail->Port = SMTP_PORT;
        
        // Disable verbose SMTP debug output for production
        $mail->SMTPDebug = 0;
        // $mail->Debugoutput can remain set for future troubleshooting if needed
        
        // Recipients
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->addReplyTo(EMAIL_REPLY_TO, EMAIL_FROM_NAME);
        
        // Content
        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        // Send the email
        $result = $mail->send();
        
        if ($result) {
            error_log("PHPMailer email sent successfully to: $to");
            // Persist debug
            @file_put_contents(__DIR__ . '/../emails/email_log.txt', "PHPMailer SUCCESS to $to\n" . $GLOBALS['LAST_PHPMAILER_DEBUG'] . "\n", FILE_APPEND);
            return true;
        } else {
            error_log("PHPMailer email sending failed to: $to");
            $GLOBALS['LAST_PHPMAILER_ERROR'] = $mail->ErrorInfo;
            @file_put_contents(__DIR__ . '/../emails/email_log.txt', "PHPMailer FAIL to $to\nError: " . $mail->ErrorInfo . "\n" . $GLOBALS['LAST_PHPMAILER_DEBUG'] . "\n", FILE_APPEND);
            return false;
        }
        
    } catch (Exception $e) {
        $GLOBALS['LAST_PHPMAILER_ERROR'] = $e->getMessage();
        @file_put_contents(__DIR__ . '/../emails/email_log.txt', "PHPMailer EXCEPTION\n" . $e->getMessage() . "\n" . ($GLOBALS['LAST_PHPMAILER_DEBUG'] ?? '') . "\n", FILE_APPEND);
        error_log("PHPMailer Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Enhanced SMTP Email Function with PHPMailer fallback
 */
function sendEnhancedSMTPEmail($to, $subject, $message, $isHtml = true) {
    // Try PHPMailer first
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        return sendPHPMailerEmail($to, $subject, $message, $isHtml);
    }
    
    // Fallback to custom SMTP
    return sendSMTPEmail($to, $subject, $message, $isHtml);
}
?>
