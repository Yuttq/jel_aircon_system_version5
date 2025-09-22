<?php
/**
 * Enhanced Configuration with Security and Error Handling
 */

// Error reporting configuration
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', 'logs/php_errors.log');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'jel_aircon');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL
define('BASE_URL', 'http://localhost/jel_aircon_system/');

// Security configuration
define('SESSION_TIMEOUT', 28800); // 8 hours
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// Include security manager first
require_once 'security.php';

// Initialize security manager
$security = SecurityManager::getInstance();

// Create database connection with enhanced error handling
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
} catch(PDOException $e) {
    // Log the error securely
    $security->logSecurityEvent('database_connection_failed', [
        'error' => $e->getMessage()
    ]);
    
    // Show user-friendly error
    die("Database connection failed. Please try again later.");
}

// Include authentication functions
require_once 'auth.php';

// Initialize authentication manager
$authManager = new AuthManager($pdo);

// Include notification configuration
require_once 'notification_config.php';

/**
 * Enhanced error handler
 */
function handleError($errno, $errstr, $errfile, $errline) {
    global $security;
    
    $errorTypes = [
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated'
    ];
    
    $errorType = $errorTypes[$errno] ?? 'Unknown Error';
    
    // Log the error
    $security->logSecurityEvent('php_error', [
        'type' => $errorType,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]);
    
    // Don't execute PHP internal error handler
    return true;
}

set_error_handler('handleError');

/**
 * Exception handler
 */
function handleException($exception) {
    global $security;
    
    $security->logSecurityEvent('uncaught_exception', [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    // Show user-friendly error page
    http_response_code(500);
    include 'error_pages/500.php';
    exit();
}

set_exception_handler('handleException');

/**
 * Utility function for redirects with messages
 */
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit();
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
?>