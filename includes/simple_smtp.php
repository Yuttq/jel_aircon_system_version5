<?php
/**
 * Simple SMTP Email Function
 * A lightweight alternative to PHPMailer for basic SMTP functionality
 */

// Stores the last SMTP error for diagnostics
if (!isset($GLOBALS['LAST_SMTP_ERROR'])) {
    $GLOBALS['LAST_SMTP_ERROR'] = '';
}

function sendSMTPEmail($to, $subject, $message, $isHtml = true) {
    if (!defined('SMTP_HOST') || !defined('SMTP_USERNAME') || !defined('SMTP_PASSWORD')) {
        return false;
    }
    
    try {
        $logSteps = [];
        $writeLog = function($line) use (&$logSteps) {
            $timestamp = date('Y-m-d H:i:s');
            $logSteps[] = "[$timestamp] $line";
        };

        // Create socket connection (use ssl:// for SMTPS on port 465)
        $connectHost = SMTP_HOST;
        if (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'ssl') {
            $connectHost = 'ssl://' . SMTP_HOST;
        }
        $socket = fsockopen($connectHost, SMTP_PORT, $errno, $errstr, 15);
        if (!$socket) {
            throw new Exception("Could not connect to SMTP server: $errstr ($errno)");
        }
        stream_set_timeout($socket, 15);
        
        // Read initial response
        $response = fgets($socket, 512);
        $writeLog("CONNECT: $connectHost:" . SMTP_PORT . " -> $response");
        if (substr($response, 0, 3) != '220') {
            throw new Exception("SMTP server error: $response");
        }
        
        // Send EHLO command
        $ehloHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        fputs($socket, "EHLO " . $ehloHost . "\r\n");
        $response = fgets($socket, 512);
        $writeLog("EHLO: $response");
        
        // Start TLS if required
        if (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'tls') {
            fputs($socket, "STARTTLS\r\n");
            $response = fgets($socket, 512);
            $writeLog("STARTTLS: $response");
            if (substr($response, 0, 3) != '220') {
                throw new Exception("STARTTLS failed: $response");
            }
            
            // Enable crypto
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new Exception("TLS encryption failed");
            }
            
            // Send EHLO again after TLS
            fputs($socket, "EHLO " . $ehloHost . "\r\n");
            $response = fgets($socket, 512);
            $writeLog("EHLO (after TLS): $response");
        }
        
        // Authenticate
        fputs($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket, 512);
        $writeLog("AUTH LOGIN -> $response");
        if (substr($response, 0, 3) != '334') {
            throw new Exception("AUTH LOGIN failed: $response");
        }
        
        fputs($socket, base64_encode(SMTP_USERNAME) . "\r\n");
        $response = fgets($socket, 512);
        $writeLog("USERNAME -> $response");
        if (substr($response, 0, 3) != '334') {
            throw new Exception("Username authentication failed: $response");
        }
        
        fputs($socket, base64_encode(SMTP_PASSWORD) . "\r\n");
        $response = fgets($socket, 512);
        $writeLog("PASSWORD -> $response");
        if (substr($response, 0, 3) != '235') {
            throw new Exception("Password authentication failed: $response");
        }
        
        // Send MAIL FROM
        fputs($socket, "MAIL FROM: <" . EMAIL_FROM . ">\r\n");
        $response = fgets($socket, 512);
        $writeLog("MAIL FROM -> $response");
        if (substr($response, 0, 3) != '250') {
            throw new Exception("MAIL FROM failed: $response");
        }
        
        // Send RCPT TO
        fputs($socket, "RCPT TO: <$to>\r\n");
        $response = fgets($socket, 512);
        $writeLog("RCPT TO -> $response");
        if (substr($response, 0, 3) != '250') {
            throw new Exception("RCPT TO failed: $response");
        }
        
        // Send DATA
        fputs($socket, "DATA\r\n");
        $response = fgets($socket, 512);
        $writeLog("DATA -> $response");
        if (substr($response, 0, 3) != '354') {
            throw new Exception("DATA command failed: $response");
        }
        
        // Send email headers and body
        $headers = "From: " . EMAIL_FROM_NAME . " <" . EMAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . EMAIL_REPLY_TO . "\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Content-Type: " . ($isHtml ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "\r\n";
        
        fputs($socket, $headers . $message . "\r\n.\r\n");
        $response = fgets($socket, 512);
        $writeLog("END DATA -> $response");
        if (substr($response, 0, 3) != '250') {
            throw new Exception("Email sending failed: $response");
        }
        
        // Send QUIT
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        
        // Persist log for diagnostics
        $logPath = __DIR__ . '/../emails/email_log.txt';
        @file_put_contents($logPath, implode("\n", $logSteps) . "\n\n", FILE_APPEND);
        $GLOBALS['LAST_SMTP_ERROR'] = '';
        return true;
        
    } catch (Exception $e) {
        if (isset($socket)) {
            fclose($socket);
        }
        $GLOBALS['LAST_SMTP_ERROR'] = $e->getMessage();
        // Persist log for diagnostics
        if (!empty($logSteps)) {
            $logSteps[] = '[ERROR] ' . $e->getMessage();
            $logPath = __DIR__ . '/../emails/email_log.txt';
            @file_put_contents($logPath, implode("\n", $logSteps) . "\n\n", FILE_APPEND);
        }
        error_log("SMTP Error: " . $e->getMessage());
        return false;
    }
}
?>
