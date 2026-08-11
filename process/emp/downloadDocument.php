<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];
$document_id = $_GET['id'] ?? null;

if (!$document_id) {
    die("Document not found.");
}


// Get document
$stmt = $connected->prepare("
    SELECT document_name, file_path
    FROM documents
    WHERE document_id = ?
    AND employee_id = ?
");

$stmt->bind_param(
    "is",
    $document_id,
    $employee_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Document not found.");
}

$document = $result->fetch_assoc();


// Physical file
$file = __DIR__ . '/../../' . $document['file_path'];


// Check file
if (!file_exists($file)) {
    die("File does not exist.");
}


// Force download
header('Content-Type: application/octet-stream');

header(
    'Content-Disposition: attachment; filename="' .
        basename($document['document_name']) .
        '"'
);

header('Content-Length: ' . filesize($file));

readfile($file);

exit;
