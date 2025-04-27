<?php
session_start();
require_once 'admin/include/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit();
}

// Get form data
$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Validate input
if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
    header("Location: register.php?error=invalid_input");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=invalid_input");
    exit();
}

// Check if passwords match
if ($password !== $confirmPassword) {
    header("Location: register.php?error=password_mismatch");
    exit();
}

try {
    // Connect to database
    $db = new Database();
    $conn = $db->connect();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("
        INSERT INTO users (first_name, last_name, email, phone, password, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword]);

    // Get the new user's ID
    $userId = $conn->lastInsertId();

    // Set session variables
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'user';

    // Redirect to success page
    header("Location: register_success.php");
    exit();

} catch (PDOException $e) {
    // Log error
    error_log("Registration error: " . $e->getMessage());
    header("Location: register.php?error=system_error");
    exit();
} 