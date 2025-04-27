<?php
session_start();
require_once 'admin/include/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Get form data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validate input
if (empty($email) || empty($password)) {
    header("Location: login.php?error=empty_fields");
    exit();
}

try {
    // Connect to database
    $database = new Database();
    $db = $database->connect();
    
    // Prepare statement to fetch user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    
    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Check if account is active
        if (isset($user['is_active']) && $user['is_active'] == 0) {
            header("Location: login.php?error=account_inactive");
            exit();
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        
        // Update last login timestamp
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);
        
        // Handle "Remember Me" functionality
        if ($remember) {
            // Generate a secure token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Store token in database
            $stmt = $db->prepare("UPDATE users SET remember_token = :token, token_expiry = :expiry WHERE id = :id");
            $stmt->execute([
                'token' => $token,
                'expiry' => $expiry,
                'id' => $user['id']
            ]);
            
            // Set cookies with 30-day expiry
            setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true);
            setcookie('user_email', $email, time() + (86400 * 30), '/', '', true, true);
        }
        
        // Redirect to home page
        header("Location: index.php");
        exit();
    } else {
        // Invalid credentials
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
} catch (PDOException $e) {
    // Log error and redirect with generic error message
    error_log("Login error: " . $e->getMessage());
    header("Location: login.php?error=system_error");
    exit();
}
?> 