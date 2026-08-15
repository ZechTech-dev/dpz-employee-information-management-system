<?php

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$today = date("Y-m-d");


$stmt = $connected->prepare("
    SELECT time_in, time_out, status
    FROM attendance
    WHERE employee_id = ?
    AND date = ?
");
$stmt->bind_param("ss", $employee_id, $today);
$stmt->execute();
$todayRow = $stmt->get_result()->fetch_assoc();

$time_in_display = ($todayRow && $todayRow['time_in']) ? date("h:i A", strtotime($todayRow['time_in'])) : "--:-- --";
$time_out_display = ($todayRow && $todayRow['time_out']) ? date("h:i A", strtotime($todayRow['time_out'])) : "--:-- --";
$today_status = $todayRow ? $todayRow['status'] : "Not yet timed in";
$timed_out_yet = ($todayRow && $todayRow['time_out']) ? true : false;


$stmt = $connected->prepare("
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE employee_id = ?
    AND status = 'Late'
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$lateCount = $stmt->get_result()->fetch_assoc()['total'];


$stmt = $connected->prepare("
    SELECT COUNT(*) AS total
    FROM leave_requests
    WHERE employee_id = ?
    AND status = 'Pending'
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$pendingLeaves = $stmt->get_result()->fetch_assoc()['total'];


$stmt = $connected->prepare("
    SELECT
        d.document_name,
        d.uploaded_at,
        t.document_type_name
    FROM documents d
    LEFT JOIN document_types t ON d.document_type_id = t.document_type_id
    WHERE d.employee_id = ?
    ORDER BY d.uploaded_at DESC
    LIMIT 3
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$documents = $stmt->get_result();


$stmt = $connected->prepare("
    SELECT
        SUM(status = 'Present') AS present_count,
        SUM(status = 'Late') AS late_count,
        SUM(status = 'Day Off') AS dayoff_count,
        COUNT(*) AS total_count
    FROM attendance
    WHERE employee_id = ?
    AND MONTH(date) = MONTH(CURDATE())
    AND YEAR(date) = YEAR(CURDATE())
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();

$presentCount = $summary['present_count'] ?? 0;
$lateCountTotal = $summary['late_count'] ?? 0;
$dayOffCount = $summary['dayoff_count'] ?? 0;
$totalCount = $summary['total_count'] ?? 0;

$presentPercent = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;


$stmt = $connected->prepare("
    SELECT date, time_in, time_out, status
    FROM attendance
    WHERE employee_id = ?
    ORDER BY date DESC
    LIMIT 3
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$recentAttendance = $stmt->get_result();


$stmt = $connected->prepare("
    SELECT employee_name, role, department, hire_date
    FROM users
    WHERE employee_id = ?
");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$personalInfo = $stmt->get_result()->fetch_assoc();
