<?php

require_once __DIR__ . '/../config/db.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ======================================================
// 1. ONLY ALLOW POST REQUESTS
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /dpz-eims/auth/login.php");
    exit;
}


// ======================================================
// 2. GET LOGIN INPUT
// ======================================================

$employee_id = trim($_POST['id'] ?? '');
$password    = $_POST['password'] ?? '';


// ======================================================
// 3. VALIDATE EMPTY INPUT
// ======================================================

if ($employee_id === '' || $password === '') {

    $_SESSION['error'] = "Please enter your Employee ID and password.";

    header("Location: /dpz-eims/auth/login.php");
    exit;
}


// ======================================================
// 4. FIND USER
// ======================================================

$stmt = $connected->prepare("
    SELECT
        employee_id,
        employee_name,
        password,
        role,
        department
    FROM users
    WHERE employee_id = ?
    LIMIT 1
");

if (!$stmt) {

    $_SESSION['error'] = "Database error.";

    header("Location: /dpz-eims/auth/login.php");
    exit;
}


$stmt->bind_param("s", $employee_id);

$stmt->execute();

$result = $stmt->get_result();


// ======================================================
// 5. CHECK IF USER EXISTS
// ======================================================

if ($result->num_rows !== 1) {

    $stmt->close();

    $_SESSION['error'] = "Invalid Employee ID or password.";

    header("Location: /dpz-eims/auth/login.php");
    exit;
}


$user = $result->fetch_assoc();

$stmt->close();


// ======================================================
// 6. VERIFY PASSWORD
// ======================================================

if (!password_verify($password, $user['password'])) {

    $_SESSION['error'] = "Invalid Employee ID or password.";

    header("Location: /dpz-eims/auth/login.php");
    exit;
}


// ======================================================
// 7. LOGIN SUCCESS
// ======================================================

// Regenerate session ID for security
session_regenerate_id(true);


// Store logged-in user's information
$_SESSION['employee_id']   = $user['employee_id'];
$_SESSION['employee_name'] = $user['employee_name'];
$_SESSION['role']          = $user['role'];
$_SESSION['department']    = $user['department'];


// This tells dashboard to show the welcome popup
$_SESSION['login_success'] = true;


// ======================================================
// 8. REDIRECT TO DASHBOARD
// ======================================================

header("Location: /dpz-eims/pages/common/dashboard.php");
exit;
