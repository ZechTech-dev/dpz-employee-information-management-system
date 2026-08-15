<?php

require_once __DIR__ . '/../config/session.php';

sesh();

require_once __DIR__ . '/../components/sideBar.php';
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../process/dashboardProcess.php';

$showPopup = false;

if (isset($_SESSION['login_success'])) {
    $showPopup = true;
    unset($_SESSION['login_success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #F5F7FB;
        }

        .space {
            margin-bottom: 32px;
        }

        h1 {
            color: #0B2E6D;
            margin-bottom: 10px;
        }

        .popup {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, .35);
            z-index: 9999;
        }

        .popup-content {
            width: 360px;
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
            animation: popup .35s ease;
        }

        .popup-content h2 {
            margin: 15px 0 5px;
            color: #0B2E6D;
        }

        .popup-content p {
            color: #6B7280;
            margin-bottom: 0;
        }

        @keyframes popup {
            from {
                opacity: 0;
                transform: scale(.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .top-row {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 25px;
        }

        .welcome-card {
            background: #0B2E6D;
            color: #fff;
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-card .icon {
            font-size: 32px;
            background: rgba(255, 255, 255, .15);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-card h2 {
            margin: 0;
            font-size: 20px;
        }

        .welcome-card p {
            margin: 5px 0 0;
            opacity: .85;
            font-size: 13px;
        }

        .stat-card span.label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            font-weight: bold;
            font-size: 13px;
        }

        .stat-card .icon-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .stat-card .icon-circle.blue {
            background: #DBEAFE;
            color: #2563EB;
        }

        .stat-card .icon-circle.red {
            background: #FEE2E2;
            color: #DC2626;
        }

        .stat-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #0B2E6D;
            margin: 12px 0 5px;
        }

        .stat-card .sub {
            font-size: 12px;
            color: #9CA3AF;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 8px;
        }

        .badge.green {
            background: #DCFCE7;
            color: #16A34A;
        }

        .badge.gray {
            background: #F3F4F6;
            color: #6B7280;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summary-row .big-number {
            font-size: 28px;
            font-weight: bold;
            color: #0B2E6D;
        }

        .summary-row .icon-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #FEE2E2;
            color: #DC2626;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pending-leaves .value {
            font-size: 20px;
            font-weight: bold;
            color: #0B2E6D;
        }

        .pending-leaves a {
            font-size: 12px;
            color: #2563EB;
            text-decoration: none;
        }

        .pending-leaves a:hover {
            text-decoration: underline;
        }

        .mid-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-header h3 {
            margin: 0;
            color: #0B2E6D;
            font-size: 16px;
        }

        .card-header a {
            font-size: 12px;
            color: #2563EB;
            text-decoration: none;
        }

        .card-header a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        thead th {
            text-align: left;
            font-size: 12px;
            color: #fff;
            padding: 12px 14px;
            background: #0B2E6D;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        thead th:first-child {
            border-top-left-radius: 10px;
        }

        thead th:last-child {
            border-top-right-radius: 10px;
        }

        tbody tr {
            background: #EAF0FB;
            transition: background .15s;
        }

        tbody tr:nth-child(even) {
            background: #F5F8FD;
        }

        tbody tr:hover {
            background: #DCE7F8;
        }

        tbody td {
            padding: 14px;
            font-size: 13px;
            color: #374151;
        }

        tbody td i {
            color: #6B7280;
        }

        tbody td i.bi-eye,
        tbody td i.bi-download {
            color: #2563EB;
            cursor: pointer;
            margin: 0 6px;
            font-size: 14px;
            transition: color .15s;
        }

        tbody td i.bi-eye:hover,
        tbody td i.bi-download:hover {
            color: #0B2E6D;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pill.present {
            background: #DCFCE7;
            color: #16A34A;
        }

        .status-pill.late {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-pill.dayoff {
            background: #F3F4F6;
            color: #6B7280;
        }

        .donut-wrap {
            display: flex;
            align-items: center;
            gap: 25px;
            justify-content: center;
            margin-top: 20px;
        }

        .donut {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .donut span {
            position: relative;
            z-index: 2;
            font-weight: bold;
            color: #0B2E6D;
            font-size: 20px;
        }

        .donut::before {
            content: "";
            position: absolute;
            width: 78px;
            height: 78px;
            background: #fff;
            border-radius: 50%;
            z-index: 1;
        }

        .legend div {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #374151;
            margin-bottom: 12px;
        }

        .legend div:last-child {
            margin-bottom: 0;
        }

        .legend .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .bottom-row {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .quick-links {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        .quick-link {
            background: #EAF0FB;
            border: 1px solid #DCE7F8;
            border-radius: 14px;
            padding: 20px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #0B2E6D;
            cursor: pointer;
            transition: .2s;
            display: block;
        }

        .quick-link:hover {
            background: #DCE7F8;
            border-color: #B9CDF0;
            transform: translateY(-2px);
        }

        .quick-link .icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            color: #0B2E6D;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 18px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .06);
        }

        .quick-link span.desc {
            display: block;
            color: #6B7280;
            font-size: 10px;
            font-weight: normal;
            margin-top: 4px;
            line-height: 1.3;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 10px 0;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row span:first-child {
            color: #6B7280;
        }

        .info-row span:last-child {
            color: #0B2E6D;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <main class="content">

        <div class="space"></div>

        <div id="popup" class="popup">
            <div class="popup-content">
                <div style="font-size:60px;">🎉</div>
                <h2>Welcome Back!</h2>
                <p><?= $employee_name ?></p>
            </div>
        </div>

        <div class="top-row">

            <div class="welcome-card">
                <div class="icon">⏰</div>
                <div>
                    <p style="margin:0;">Welcome back,</p>
                    <h2><?= $employee_name ?>!</h2>
                    <p>Let's keep up the good work.</p>
                </div>
            </div>

            <div class="card stat-card">
                <span class="label">
                    <span class="icon-circle blue"><i class="bi bi-clock"></i></span>
                    TIME IN
                </span>
                <div class="value"><?= $time_in_display ?></div>
                <div class="sub"><?= date("F d, Y") ?></div>
                <span class="badge <?= $today_status === 'Late' ? 'gray' : 'green' ?>">
                    <?= $today_status ?>
                </span>
            </div>

            <div class="card stat-card">
                <span class="label">
                    <span class="icon-circle red"><i class="bi bi-clock"></i></span>
                    TIME OUT
                </span>
                <div class="value"><?= $time_out_display ?></div>
                <span class="badge gray">
                    <?= $timed_out_yet ? 'Timed out' : 'Not yet timed out' ?>
                </span>
            </div>

            <div class="card">
                <span class="label" style="color:#6B7280; font-weight: normal;">My Summary</span>
                <p style="font-size:12px; color:#9CA3AF; margin:5px 0;">Number of Late</p>
                <div class="summary-row">
                    <span class="big-number"><?= $lateCount ?></span>
                    <span class="icon-badge"><i class="bi bi-person-fill"></i></span>
                </div>
            </div>

            <div class="card pending-leaves">
                <span class="label" style="color:#6B7280; font-weight: normal;">Pending Leaves</span>
                <div class="summary-row" style="margin-top:10px;">
                    <span class="icon-badge" style="background:#DBEAFE; color:#2563EB;">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                    <div>
                        <div class="value"><?= $pendingLeaves ?></div>
                        <div class="sub">Request</div>
                    </div>
                </div>
                <a href="#">View details</a>
            </div>

        </div>

        <div class="mid-row">

            <div class="card">
                <div class="card-header">
                    <h3>My Recent Documents</h3>
                    <a href="#">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Document Name</th>
                            <th>Category</th>
                            <th>Date Uploaded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($documents->num_rows === 0): ?>
                            <tr>
                                <td colspan="4" style="text-align:center; color:#9CA3AF;">No documents uploaded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($doc = $documents->fetch_assoc()): ?>
                                <tr>
                                    <td><i class="bi bi-file-earmark-text"></i> <?= $doc['document_name'] ?></td>
                                    <td><?= $doc['document_type_name'] ?? '---' ?></td>
                                    <td><?= date("F j, Y", strtotime($doc['uploaded_at'])) ?></td>
                                    <td>
                                        <i class="bi bi-eye" title="View"></i>
                                        <i class="bi bi-download" title="Download"></i>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>My Attendance Summary</h3>
                </div>
                <div class="donut-wrap">
                    <div class="donut" style="background: conic-gradient(#0B2E6D <?= $presentPercent ?>%, #E5E7EB 0);">
                        <span><?= $presentPercent ?>%</span>
                    </div>
                    <div class="legend">
                        <div><span class="dot" style="background:#0B2E6D;"></span> Present <?= $presentCount ?></div>
                        <div><span class="dot" style="background:#F59E0B;"></span> Late <?= $lateCountTotal ?></div>
                        <div><span class="dot" style="background:#6B7280;"></span> Day Off <?= $dayOffCount ?></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="bottom-row">

            <div class="card">
                <div class="card-header">
                    <h3>My Recent Attendance Records</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentAttendance->num_rows === 0): ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#9CA3AF;">No attendance records yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($rec = $recentAttendance->fetch_assoc()): ?>
                                <tr>
                                    <td><?= date("F j, Y", strtotime($rec['date'])) ?></td>
                                    <td><?= date("D", strtotime($rec['date'])) ?></td>
                                    <td><?= $rec['time_in'] ? date("h:i A", strtotime($rec['time_in'])) : '---' ?></td>
                                    <td><?= $rec['time_out'] ? date("h:i A", strtotime($rec['time_out'])) : '---' ?></td>
                                    <td>
                                        <?php
                                        $statusClass = "present";
                                        if ($rec['status'] === 'Late') $statusClass = "late";
                                        if ($rec['status'] === 'Day Off') $statusClass = "dayoff";
                                        ?>
                                        <span class="status-pill <?= $statusClass ?>"><?= $rec['status'] ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="#" style="font-size:12px; color:#2563EB; text-decoration:none; display:inline-block; margin-top:10px;">View Full Attendance History</a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Quick Links</h3>
                </div>
                <div class="quick-links">
                    <a href="/dpz-eims/pages/emp/attendance.php" class="quick-link">
                        <div class="icon-wrap"><i class="bi bi-clock"></i></div>
                        Time In & Out
                        <span class="desc">Record your time</span>
                    </a>
                    <a href="/dpz-eims/pages/emp/documents.php" class="quick-link">
                        <div class="icon-wrap"><i class="bi bi-folder"></i></div>
                        My Documents
                        <span class="desc">View your documents</span>
                    </a>
                    <a href="/dpz-eims/pages/emp/leaveRequest.php" class="quick-link">
                        <div class="icon-wrap"><i class="bi bi-calendar-plus"></i></div>
                        Leave Request
                        <span class="desc">Create and track leave request</span>
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Personal Information</h3>
                </div>
                <div class="info-row">
                    <span>Position</span>
                    <span><?= $personalInfo['role'] ?? '---' ?></span>
                </div>
                <div class="info-row">
                    <span>Department</span>
                    <span><?= $personalInfo['department'] ?? '---' ?></span>
                </div>
                <div class="info-row">
                    <span>Hire Date</span>
                    <span><?= $personalInfo['hire_date'] ? date("F j, Y", strtotime($personalInfo['hire_date'])) : '---' ?></span>
                </div>
            </div>

        </div>

    </main>

    <?php if ($showPopup): ?>
        <script>
            const popup = document.getElementById("popup");
            popup.style.display = "flex";
            setTimeout(() => {
                popup.style.display = "none";
            }, 2500);
        </script>
    <?php endif; ?>

</body>

</html>