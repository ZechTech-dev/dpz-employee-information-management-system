<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $action = $_POST['action'] ?? '';

    if ($action === 'time_in') {

        $stmt = $connected->prepare("
            SELECT *
            FROM attendance
            WHERE employee_id = ?
            AND date = CURDATE()
            LIMIT 1
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            echo json_encode([
                "success" => true,
                "already" => true,
                "time_in" => date("h:i A", strtotime($row['time_in']))
            ]);

            exit;
        }

        $stmt = $connected->prepare("
            INSERT INTO attendance
            (
                employee_id,
                date,
                time_in,
                late_minutes,
                overtime_hours,
                status,
                total_hours
            )
            VALUES
            (
                ?,
                CURDATE(),
                CURTIME(),
                0,
                0,
                'Present',
                0
            )
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        echo json_encode([
            "success" => true,
            "already" => false,
            "time_in" => date("h:i A")
        ]);

        exit;
    }


    if ($action === 'break_in') {

        $stmt = $connected->prepare("
            UPDATE attendance
            SET break_in = CURTIME()
            WHERE employee_id = ?
            AND date = CURDATE()
            AND time_in IS NOT NULL
            AND time_out IS NULL
            AND break_in IS NULL
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {

            echo json_encode([
                "success" => false,
                "message" => "Unable to start break."
            ]);

            exit;
        }

        echo json_encode([
            "success" => true,
            "break_in" => date("h:i A")
        ]);

        exit;
    }


    if ($action === 'break_out') {

        $stmt = $connected->prepare("
            UPDATE attendance
            SET break_out = CURTIME()
            WHERE employee_id = ?
            AND date = CURDATE()
            AND time_in IS NOT NULL
            AND time_out IS NULL
            AND break_in IS NOT NULL
            AND break_out IS NULL
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {

            echo json_encode([
                "success" => false,
                "message" => "Unable to end break."
            ]);

            exit;
        }

        $stmt = $connected->prepare("
            SELECT break_out, break_hours
            FROM attendance
            WHERE employee_id = ?
            AND date = CURDATE()
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        echo json_encode([
            "success" => true,
            "break_out" => date("h:i A", strtotime($row['break_out'])),
            "break_hours" => $row['break_hours']
        ]);

        exit;
    }


    if ($action === 'time_out') {

        $stmt = $connected->prepare("
            UPDATE attendance
            SET time_out = CURTIME()
            WHERE employee_id = ?
            AND date = CURDATE()
            AND time_in IS NOT NULL
            AND time_out IS NULL
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {

            echo json_encode([
                "success" => false,
                "message" => "Unable to time out."
            ]);

            exit;
        }

        $stmt = $connected->prepare("
            SELECT
                date,
                time_in,
                time_out,
                break_hours,
                total_hours,
                status
            FROM attendance
            WHERE employee_id = ?
            AND date = CURDATE()
        ");

        $stmt->bind_param("s", $employee_id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        echo json_encode([
            "success" => true,
            "date" => $row['date'],
            "time_in" => date("h:i A", strtotime($row['time_in'])),
            "time_out" => date("h:i A", strtotime($row['time_out'])),
            "break_hours" => $row['break_hours'],
            "total_hours" => $row['total_hours'],
            "status" => $row['status']
        ]);

        exit;
    }


    echo json_encode([
        "success" => false,
        "message" => "Invalid action."
    ]);

    exit;
}


$limit = 8;

$page = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$stmt = $connected->prepare("
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE employee_id = ?
");

$stmt->bind_param("s", $employee_id);
$stmt->execute();

$totalRows = $stmt->get_result()->fetch_assoc()['total'];

$totalPages = max(1, ceil($totalRows / $limit));

if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;


$stmt = $connected->prepare("
    SELECT
        attendance_id,
        date,
        time_in,
        time_out,
        break_in,
        break_out,
        status,
        break_hours,
        total_hours,
        overtime_hours
    FROM attendance
    WHERE employee_id = ?
    ORDER BY date DESC
    LIMIT ? OFFSET ?
");

$stmt->bind_param(
    "sii",
    $employee_id,
    $limit,
    $offset
);

$stmt->execute();

$result = $stmt->get_result();
