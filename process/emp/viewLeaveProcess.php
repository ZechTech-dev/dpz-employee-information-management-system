<?php

require_once __DIR__ . '/../../config/db.php';


$id = $_GET['id'];

$stmt = $connected->prepare(
    "SELECT * FROM leave_requests WHERE request_id = ?"
);


$stmt->bind_param("i", $id);

$stmt->execute();


$result = $stmt->get_result();


$data = $result->fetch_assoc();


$start = new DateTime($data['start_date']);
$end = new DateTime($data['end_date']);


$data['duration'] = $start->diff($end)->days + 1;


echo json_encode($data);
