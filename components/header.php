<?php
$name = $_SESSION['name'] ?? '';
$role = $_SESSION['role'] ?? '';

$location = $_SERVER['REQUEST_URI'];

if (str_contains($location, 'docUpload')) {
    $headerpage = 'Document Upload';
} elseif (str_contains($location, 'attendance')) {
    $headerpage = 'Attendance';
} elseif (str_contains($location, 'leave')) {
    $headerpage = 'Leave Request';
} else {
    $headerpage = 'Dashboard';
}
?>
<header class="main-header">

    <div class="servisis-page-heading">
        <h1><?= $headerpage ?></h1>
        <span>Staff Information System</span>
    </div>

    <div class="servisis-header-right">

        <button type="button" class="servisis-notification-btn" aria-label="Notifications">
            <i class="bi bi-bell-fill"></i>
            <span class="servisis-notification-badge">3</span>
        </button>

        <div class="servisis-header-divider"></div>

        <div class="servisis-header-user">

            <div class="servisis-user-avatar">
                <img src="/dpz-eims/assets/src/prof.jpg" alt="Profile">
            </div>

            <div class="servisis-user-info">
                <strong><?= htmlspecialchars($name) ?></strong>
                <small><?= htmlspecialchars($role) ?></small>
            </div>

            <i class="bi bi-chevron-down servisis-user-arrow"></i>

        </div>

    </div>



</header>