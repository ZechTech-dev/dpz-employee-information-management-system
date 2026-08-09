<?php

$role = "Employee";

include_once __DIR__ . '/../process/loginProcess.php';

$current = basename($_SERVER['PHP_SELF']);

function active($page, $current)
{
    return $page == $current ? "active" : "";
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/dpz-eims/assets/css/global.css">
<link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">

<aside class="sidebar">

    <div class="side-top">

        <div class="profile">
            <img src="/dpz-eims/assets/src/logo.png" alt="Servisis Logo">
        </div>

        <span class="logo-text">SERVISIS</span>

    </div>

    <div class="wrapper">

        <nav class="side-nav">

            <div class="navigation">

                <a href="/dpz-eims/pages/dashboard.php" class="<?= active('dashboard.php', $current); ?>">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>
                </a>
                <!--RBAC IN DASHBOARD YAH-->

                <!--all navi under manager are the masterlist (reason for the capital M in naming)-->
                <?php if ($role === "Manager"): ?>
                    <a href="/dpz-eims/pages/manager/empMlist.php" class="<?= active('empMlist.php', $current); ?>">
                        <i class="bi bi-card-checklist"></i>
                        <span>Employee List</span>
                    </a>

                    <a href="/dpz-eims/pages/manager/leaveManagement.php" class="<?= active('leaveManagement.php', $current); ?>">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Leave Management</span>
                    </a>

                    <a href="/dpz-eims/pages/manager/docUploadM.php" class="<?= active('docUploadM.php', $current); ?>">
                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>Document Management</span>
                    </a>

                    <a href="/dpz-eims/pages/manager/govInformationM.php" class="<?= active('govInformationM.php', $current); ?>">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Gov. Information</span>
                    </a>

                    <a href="/dpz-eims/pages/manager/reports.php" class="<?= active('reports.php', $current); ?>">
                        <i class="bi bi-flag-fill"></i>
                        <span>Reports</span>
                    </a>

                <?php elseif ($role === "Tech"): ?>
                    <a href="/dpz-eims/pages/tech/leaveManagementT.php" class="<?= active('leaveManagementT.php', $current); ?>">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Leave Records</span>
                    </a>

                    <a href="/dpz-eims/pages/tech/docUploadT.php" class="<?= active('docUploadT.php', $current); ?>">
                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>Document Records</span>
                    </a>

                    <a href="/dpz-eims/pages/tech/govInformationT.php" class="<?= active('govInformationT.php', $current); ?>">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Gov. Information</span>
                    </a>

                    <a href="/dpz-eims/pages/tech/changePass.php" class="<?= active('changePass.php', $current); ?>">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Change Password</span>
                    </a>

                <?php elseif ($role === "Employee") : ?>
                    <a href="/dpz-eims/pages/emp/leaveRequest.php" class="<?= active('leaveRequest.php', $current); ?>">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>My Leave Request</span>
                    </a>

                    <a href="/dpz-eims/pages/emp/docUpload.php" class="<?= active('docUpload.php', $current); ?>">
                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                        <span>My Documents</span>
                    </a>

                    <a href="/dpz-eims/pages/emp/govInformation.php" class="<?= active('govInformation.php', $current); ?>">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Gov. Information</span>
                    </a>

                    <!--will not execute, since RBAC is implemented in the login (Only 3 position is allowed)-->
                <?php else:
                    //will only execute if role is not allowed
                    header("Location: /dpz-eims/login.php");
                    exit;
                ?>

                <?php endif; ?>

            </div>

        </nav>

        <div class="sidebar-bottom">

            <div class="user-info">

                <div class="profile">
                    <img src="/dpz-eims/assets/src/prof.png" alt="Profile">
                </div>

                <div class="user-details">
                    <h6><?php ?></h6>
                    <small><?= ucfirst($role); ?></small>
                </div>

            </div>


            <a href="/dpz-eims/process/logoutProcess.php" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>

        </div>

    </div>

</aside>