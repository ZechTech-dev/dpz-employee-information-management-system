<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];
$limit = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json; charset=utf-8');

    $leave_type = trim($_POST['leave_type'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    if (
        empty($leave_type) ||
        empty($start_date) ||
        empty($end_date) ||
        empty($reason)
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Please complete all fields.'
        ]);
        exit;
    }

    if ($end_date < $start_date) {
        echo json_encode([
            'success' => false,
            'message' => 'End date cannot be before start date.'
        ]);
        exit;
    }

    $stmt = $connected->prepare("
        INSERT INTO leave_requests
        (
            employee_id,
            leave_type,
            start_date,
            end_date,
            reason,
            status
        )
        VALUES (?, ?, ?, ?, ?, 'Pending')
    ");

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare the request.'
        ]);
        exit;
    }

    $stmt->bind_param(
        "sssss",
        $employee_id,
        $leave_type,
        $start_date,
        $end_date,
        $reason
    );

    if (!$stmt->execute()) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save leave request.'
        ]);

        $stmt->close();
        exit;
    }

    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Leave request submitted successfully.'
    ]);

    exit;
}

$status = $_GET['status'] ?? 'allRequest';

$year = isset($_GET['year'])
    ? (int) $_GET['year']
    : (int) date('Y');

$page = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$where = "WHERE employee_id = ?";
$params = [$employee_id];
$types = "s";

if ($status !== 'allRequest') {

    $statusValue = str_replace('Request', '', $status);

    if ($statusValue === 'approved') {
        $statusValue = 'Approved';
    } elseif ($statusValue === 'pending') {
        $statusValue = 'Pending';
    } elseif ($statusValue === 'rejected') {
        $statusValue = 'Rejected';
    }

    $where .= " AND status = ?";
    $params[] = $statusValue;
    $types .= "s";
}

$where .= " AND YEAR(applied_at) = ?";
$params[] = $year;
$types .= "i";

$countStmt = $connected->prepare("
    SELECT COUNT(*) AS total
    FROM leave_requests
    $where
");

$countStmt->bind_param(
    $types,
    ...$params
);

$countStmt->execute();

$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();

$totalRows = (int) $countRow['total'];

$countStmt->close();

$totalPages = max(
    1,
    (int) ceil($totalRows / $limit)
);

if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;

$queryParams = $params;
$queryTypes = $types;

$queryParams[] = $limit;
$queryParams[] = $offset;

$queryTypes .= "ii";

$leaveStmt = $connected->prepare("
    SELECT
        request_id,
        employee_id,
        leave_type,
        start_date,
        end_date,
        reason,
        status,
        approved_by,
        applied_at
    FROM leave_requests
    $where
    ORDER BY applied_at DESC
    LIMIT ? OFFSET ?
");

$leaveStmt->bind_param(
    $queryTypes,
    ...$queryParams
);

$leaveStmt->execute();

$leaveResult = $leaveStmt->get_result();

$numberOfRequests = $totalRows;
