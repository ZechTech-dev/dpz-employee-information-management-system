<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sesh()
{
    if (!isset($_SESSION['employee_id'])) {
        header("Location: /dpz-eims/auth/login.php");
        exit;
    }
}
