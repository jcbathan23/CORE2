<?php
session_start();
require_once 'db.php';
require_once 'security.php';

// Authentication functions
function isLoggedIn() {
    // Check if session exists and has required data
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
        return false;
    }
    
    // Check session timeout (8 hours)
    $timeout = 8 * 60 * 60; // 8 hours in seconds
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
        // Session expired
        session_unset();
        session_destroy();
        return false;
    }
    
    // Update login time on each check
    $_SESSION['login_time'] = time();
    
    return true;
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php?error=access_denied');
        exit();
    }
}

function login($username, $password) {
    global $mysqli;
    
    // Check rate limiting
    if (!checkLoginRateLimit($username)) {
        return ['success' => false, 'message' => 'Too many login attempts. Please try again in 15 minutes.'];
    }
    
    // Sanitize input
    $username = sanitizeInput($username);
    $username = $mysqli->real_escape_string($username);
    
    // Get user from database
    $stmt = $mysqli->prepare("SELECT id, username, password_hash, role, is_active FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if user is active
        if (!$user['is_active']) {
            recordLoginAttempt($username, false);
            return ['success' => false, 'message' => 'Account is deactivated'];
        }
        
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['csrf_token'] = generateCSRFToken();
            
            // Update last login
            $updateStmt = $mysqli->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();
            
            // Record successful login
            recordLoginAttempt($username, true);
            
            return ['success' => true, 'role' => $user['role']];
        }
    }
    
    // Record failed login attempt
    recordLoginAttempt($username, false);
    
    return ['success' => false, 'message' => 'Invalid username or password'];
}

function logout() {
    // Destroy session
    session_unset();
    session_destroy();
    
    // Redirect to login
    header('Location: login.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $response = ['success' => false, 'message' => 'Please enter both username and password'];
    } else {
        $response = login($username, $password);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    logout();
}
?>
