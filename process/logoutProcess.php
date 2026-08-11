<?php

require_once __DIR__ . '/../config/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];

session_destroy();

// Go back to login
header("Location: /dpz-eims/auth/login.php");
exit;
