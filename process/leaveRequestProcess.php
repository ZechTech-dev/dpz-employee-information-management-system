<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];

$stmt = $connected->prepare("SELECT * FROM leave_requests WHERE employee_id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();

$result = $stmt->get_result();
$numberOfRequests = $result->num_rows;
