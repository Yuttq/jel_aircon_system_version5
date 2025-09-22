<?php
/**
 * PHPMailer Autoloader
 * Simple autoloader for PHPMailer classes
 */

spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $class = str_replace('PHPMailer\\PHPMailer\\', '', $class);
    $file = __DIR__ . '/phpmailer/phpmailer/src/' . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});
