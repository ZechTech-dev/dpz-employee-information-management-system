<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

$action = $_GET['action'] ?? '';

if ($action === 'view' || $action === 'download') {

    $document_id = $_GET['id'] ?? '';

    if (!$document_id || !is_numeric($document_id)) {
        http_response_code(400);
        exit('Invalid document.');
    }

    $stmt = $connected->prepare("
        SELECT
            document_name,
            file_path
        FROM documents
        WHERE document_id = ?
    ");

    $stmt->bind_param("i", $document_id);
    $stmt->execute();

    $document = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    if (!$document) {
        http_response_code(404);
        exit('Document not found.');
    }

    $filePath = dirname(__DIR__, 2) . '/' . $document['file_path'];

    if (!file_exists($filePath)) {
        http_response_code(404);
        exit('Document file not found.');
    }

    $fileName = basename($document['document_name']);

    $extension = strtolower(
        pathinfo($fileName, PATHINFO_EXTENSION)
    );

    $mimeTypes = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png'
    ];

    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    if ($action === 'view') {

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

    if ($action === 'download') {

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }
}


$query = "
    SELECT
        d.document_id,
        d.employee_id,
        d.document_type_id,
        d.document_name,
        d.file_path,
        d.uploaded_at,
        u.employee_name,
        u.profile_picture,
        dt.document_type_name

    FROM documents d

    INNER JOIN users u
        ON d.employee_id = u.employee_id

    INNER JOIN document_types dt
        ON d.document_type_id = dt.document_type_id

    ORDER BY d.uploaded_at DESC
";

$result = $connected->query($query);

$documents = [];

if ($result) {

    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
}


$categoryCounts = [
    'all' => count($documents),
    1 => 0,
    2 => 0,
    3 => 0
];

foreach ($documents as $document) {

    $type = (int) $document['document_type_id'];

    if (isset($categoryCounts[$type])) {
        $categoryCounts[$type]++;
    }
}
