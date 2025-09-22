<?php
require_once 'security.php';

class AuthManager {
    private $pdo;
    private $security;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->security = SecurityManager::getInstance();
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['login_time']);
    }
    
    /**
     * Redirect if not logged in
     */
    public function checkAuth() {
        if (!$this->isLoggedIn()) {
            $this->security->logSecurityEvent('unauthorized_access', [
                'page' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ]);
            header('Location: ../login.php');
            exit();
        }
        
        // Check session timeout (8 hours)
        if (time() - $_SESSION['login_time'] > 28800) {
            $this->logout();
        }
    }
    
    /**
     * Check user role
     */
    public function hasRole($role) {
        if (!$this->isLoggedIn()) return false;
        return $_SESSION['user_role'] === $role;
    }
    
    /**
     * Enhanced login function with rate limiting
     */
    public function login($username, $password) {
        $username = $this->security->sanitizeInput($username);
        
        // Check rate limiting
        if (!$this->security->checkRateLimit($username)) {
            $this->security->logSecurityEvent('rate_limit_exceeded', ['username' => $username]);
            return ['success' => false, 'message' => 'Too many login attempts. Please try again later.'];
        }
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && $this->security->verifyPassword($password, $user['password'])) {
                // Clear rate limit on successful login
                $this->security->clearRateLimit($username);
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
                $_SESSION['last_activity'] = time();
                
                // Log successful login
                $this->security->logSecurityEvent('successful_login', [
                    'user_id' => $user['id'],
                    'username' => $username
                ]);
                
                return ['success' => true, 'message' => 'Login successful'];
            } else {
                // Record failed attempt
                $this->security->recordFailedAttempt($username);
                
                // Log failed login
                $this->security->logSecurityEvent('failed_login', [
                    'username' => $username,
                    'reason' => 'invalid_credentials'
                ]);
                
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
        } catch (PDOException $e) {
            $this->security->logSecurityEvent('login_error', [
                'username' => $username,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Login system temporarily unavailable'];
        }
    }
    
    /**
     * Enhanced logout function
     */
    public function logout() {
        if ($this->isLoggedIn()) {
            $this->security->logSecurityEvent('logout', [
                'user_id' => $_SESSION['user_id'] ?? 'unknown'
            ]);
        }
        
        // Clear all session data
        $_SESSION = [];
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        header('Location: login.php');
        exit();
    }
    
    /**
     * Update last activity
     */
    public function updateActivity() {
        if ($this->isLoggedIn()) {
            $_SESSION['last_activity'] = time();
        }
    }
}

// Legacy functions for backward compatibility
function isLoggedIn() {
    global $authManager;
    return $authManager->isLoggedIn();
}

function checkAuth() {
    global $authManager;
    $authManager->checkAuth();
}

function hasRole($role) {
    global $authManager;
    return $authManager->hasRole($role);
}

function login($username, $password) {
    global $authManager;
    $result = $authManager->login($username, $password);
    return $result['success'];
}

function logout() {
    global $authManager;
    $authManager->logout();
}
?>