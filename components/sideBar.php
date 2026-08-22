<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';

sesh();

$role = $_SESSION['role'] ?? '';
$name = $_SESSION['employee_name'] ?? '';
$employee_id = $_SESSION['employee_id'] ?? '';


// Default picture
$profileImage = 'default-picture.jpg';


// Get logged-in user's profile picture (no process file for sidebar)
$stmt = $connected->prepare("
    SELECT profile_picture
    FROM users
    WHERE employee_id = ?
");

$stmt->bind_param("s", $employee_id);
$stmt->execute();

$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {

    if (!empty($user['profile_picture'])) {
        $profileImage = $user['profile_picture'];
    }
}

$stmt->close();


$current = basename($_SERVER['PHP_SELF']);

function active($page, $current)
{
    return $page === $current ? "active" : "";
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/dpz-eims/assets/css/global.css">
<link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">

<aside class="sidebar">

    <div class="side-top">

        <div class="profile">
            <img src="/dpz-eims/assets/src/logo.png" alt="Servisis Logo">
        </div>

        <div class="brand-info">
            <span class="logo-text">ServiSIS</span>
            <span class="logo-subtitle">Staff Information System</span>
        </div>

    </div>

    <div class="wrapper">

        <nav class="side-nav">

            <div class="navigation">

                <div class="nav-section-title">
                    MAIN
                </div>

                <!-- Dashboard/visible to ol -->
                <a href="/dpz-eims/pages/common/dashboard.php"
                    class="<?= active('dashboard.php', $current); ?>">

                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>

                </a>

                <a href="/dpz-eims/pages/common/attendance.php"
                    class="<?= active('attendance.php', $current); ?>">

                    <i class="bi bi-calendar2-check-fill"></i>
                    <span>Attendance</span>

                </a>

                <div class="nav-section-title">
                    MANAGEMENT
                </div>

                <?php if ($role === 'Manager'): ?>

                    <a href="/dpz-eims/pages/manager/empMlist.php"
                        class="<?= active('empMlist.php', $current); ?>">

                        <i class="bi bi-card-checklist"></i>
                        <span>Employee List</span>

                    </a>

                    <a href="/dpz-eims/pages/manager/leaveManagement.php"
                        class="<?= active('leaveManagement.php', $current); ?>">

                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Leave Management</span>

                    </a>

                    <a href="/dpz-eims/pages/manager/documentsM.php"
                        class="<?= active('documentsM.php', $current); ?>">

                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>Document Management</span>

                    </a>



                    <!-- RBAC -->
                <?php elseif ($role === 'Employee'): ?>

                    <a href="/dpz-eims/pages/emp/leaveRequest.php"
                        class="<?= active('leaveRequest.php', $current); ?>">

                        <i class="bi bi-file-earmark-text"></i>
                        <span>My Leave Request</span>

                    </a>

                    <a href="/dpz-eims/pages/emp/docUpload.php"
                        class="<?= active('docUpload.php', $current); ?>">

                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>My Documents</span>

                    </a>


                    <!-- role not in the access control -->
                <?php else: ?>

                    <?php
                    session_destroy();
                    header("Location: /dpz-eims/auth/login.php");
                    exit;
                    ?>

                <?php endif; ?>

            </div>

        </nav>

        <div class="sidebar-city">
            <img src="/dpz-eims/assets/src/bg.png" alt="">
        </div>

        <div class="sidebar-bottom">

            <div class="user-info">

                <div class="profile">
                    <img
                        src="/dpz-eims/uploads/profile/<?= htmlspecialchars($profileImage) ?>"
                        alt="Profile">
                </div>

                <div class="user-details">
                    <h6><?php echo $name ?></h6>
                    <small><?php echo $role; ?></small>
                </div>

            </div>

            <a href="/dpz-eims/process/logoutProcess.php" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>

        </div>

    </div>

</aside>