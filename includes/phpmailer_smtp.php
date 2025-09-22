<?php
/**
 * PHPMailer SMTP Email Function
 * A more reliable alternative to the custom SMTP implementation
 */

// Load PHPMailer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

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
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION === 'tls' ? 'tls' : 'ssl';
        $mail->Port = SMTP_PORT;
        
        // Disable debug output for production
        $mail->SMTPDebug = 0;
        
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
            return true;
        } else {
            error_log("PHPMailer email sending failed to: $to");
            return false;
        }
        
    } catch (Exception $e) {
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
