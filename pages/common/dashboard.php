<?php

require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';
require_once __DIR__ . '/../../process/dashboardProcess.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/dashboard.css">

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
                <div class="icon"><i class="bi bi-alarm-fill"></i></div>
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
                <a href="/dpz-eims/pages/emp/leaveRequest.php" class="details">View details</a>
            </div>

        </div>

        <div class="mid-row">

            <div class="card">
                <div class="card-header">
                    <h3>My Recent Documents</h3>
                    <a href="/dpz-eims/pages/emp/docUpload.php/">View All</a>
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
                <a href="/dpz-eims/pages/emp/attendance.php" style="font-size:12px; color:#2563EB; text-decoration:none; display:inline-block; margin-top:10px;">View Full Attendance History</a>
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

</body>

</html>