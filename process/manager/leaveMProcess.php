<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $query = "
        SELECT
            lr.request_id,
            lr.employee_id,
            u.employee_name,
            u.profile_picture,
            lr.leave_type,
            lr.start_date,
            lr.end_date,
            lr.reason,
            lr.status,
            lr.decision_reason,
            lr.applied_at
        FROM leave_requests lr
        INNER JOIN users u
            ON lr.employee_id = u.employee_id
        ORDER BY lr.applied_at DESC
    ";

    $result = $connected->query($query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request_id = $_POST['request_id'] ?? '';
    $decision = $_POST['decision'] ?? '';
    $reason = trim($_POST['decision_reason'] ?? '');
    $manager_id = $_SESSION['employee_id'] ?? '';

    header('Content-Type: application/json');

    if (!$request_id || !$decision || !$manager_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required information.'
        ]);
        exit;
    }

    if ($decision === 'Rejected' && $reason === '') {
        echo json_encode([
            'success' => false,
            'message' => 'A reason is required when rejecting a leave request.'
        ]);
        exit;
    }

    if (!in_array($decision, ['Approved', 'Rejected'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid decision.'
        ]);
        exit;
    }

    $check = $connected->prepare("
        SELECT status
        FROM leave_requests
        WHERE request_id = ?
    ");

    $check->bind_param("i", $request_id);
    $check->execute();

    $request = $check->get_result()->fetch_assoc();

    $check->close();

    if (!$request) {
        echo json_encode([
            'success' => false,
            'message' => 'Leave request not found.'
        ]);
        exit;
    }

    if ($request['status'] !== 'Pending') {
        echo json_encode([
            'success' => false,
            'message' => 'This request has already been processed.'
        ]);
        exit;
    }

    if ($decision === 'Approved' && $reason === '') {
        $reason = null;
    }

    $stmt = $connected->prepare("
        UPDATE leave_requests
        SET
            status = ?,
            decision_reason = ?,
            approved_by = ?
        WHERE request_id = ?
        AND status = 'Pending'
    ");

    $stmt->bind_param(
        "sssi",
        $decision,
        $reason,
        $manager_id,
        $request_id
    );

    if ($stmt->execute()) {

        echo json_encode([
            'success' => true,
            'message' => 'Leave request ' . strtolower($decision) . ' successfully.'
        ]);
    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Failed to update leave request.'
        ]);
    }

    $stmt->close();
    exit;
}
