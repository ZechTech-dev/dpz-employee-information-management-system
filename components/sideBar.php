<?php

require_once __DIR__ . '/../config/session.php';

sesh();

$role = $_SESSION['role'] ?? '';
$name = $_SESSION['employee_name'] ?? '';

$current = basename($_SERVER['PHP_SELF']);

function active($page, $current)
{
    return $page === $current ? "active" : "";
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/dpz-eims/assets/css/global.css">
<link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

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

                <!-- Dashboard - everyone -->
                <a href="/dpz-eims/pages/dashboard.php"
                    class="<?= active('dashboard.php', $current); ?>">

                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>

                </a>

                <div class="nav-section-title">
                    MANAGEMENT
                </div>

                <!-- MANAGER -->
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

                    <a href="/dpz-eims/pages/manager/docUploadM.php"
                        class="<?= active('docUploadM.php', $current); ?>">

                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>Document Management</span>

                    </a>

                    <a href="/dpz-eims/pages/manager/govInformationM.php"
                        class="<?= active('govInformationM.php', $current); ?>">

                        <i class="bi bi-buildings-fill"></i>
                        <span>Time in & Out</span>

                    </a>

                    <a href="/dpz-eims/pages/manager/reports.php"
                        class="<?= active('reports.php', $current); ?>">

                        <i class="bi bi-flag-fill"></i>
                        <span>Reports</span>

                    </a>


                    <!-- TECH -->
                <?php elseif ($role === 'Tech'): ?>

                    <a href="/dpz-eims/pages/tech/leaveManagementT.php"
                        class="<?= active('leaveManagementT.php', $current); ?>">

                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Leave Records</span>

                    </a>

                    <a href="/dpz-eims/pages/tech/docUploadT.php"
                        class="<?= active('docUploadT.php', $current); ?>">

                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>Document Records</span>

                    </a>

                    <a href="/dpz-eims/pages/tech/govInformationT.php"
                        class="<?= active('govInformationT.php', $current); ?>">

                        <i class="bi bi-buildings-fill"></i>
                        <span>Gov. Information</span>

                    </a>

                    <a href="/dpz-eims/pages/tech/changePass.php"
                        class="<?= active('changePass.php', $current); ?>">

                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Change Password</span>

                    </a>


                    <!-- EMPLOYEE -->
                <?php elseif ($role === 'Employee'): ?>

                    <a href="/dpz-eims/pages/emp/attendance.php"
                        class="<?= active('attendance.php', $current); ?>">

                        <i class="bi bi-calendar2-check-fill"></i>
                        <span>Time in & Out</span>

                    </a>

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


                    <!-- INVALID ROLE -->
                <?php else: ?>

                    <?php
                    session_destroy();
                    header("Location: /dpz-eims/auth/login.php");
                    exit;
                    ?>

                <?php endif; ?>

            </div>

        </nav>

        <div class="sidebar-bottom">

            <div class="user-info">

                <div class="profile">
                    <img src="/dpz-eims/assets/src/prof.jpg" alt="Profile">
                </div>

                <div class="user-details">
                    <h6><?php echo $name ?></h6>
                    <small><?php
                            echo $role; ?></small>
                </div>

            </div>


            <a href="/dpz-eims/process/logoutProcess.php" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>

        </div>

    </div>

</aside>