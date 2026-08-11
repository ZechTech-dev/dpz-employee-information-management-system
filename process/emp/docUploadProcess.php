<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();

$employee_id = $_SESSION['employee_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['document_type_id'];
    $file = $_FILES['document'];

    // Check file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Upload failed.";
        header("Location: /dpz-eims/pages/emp/docUpload.php");
        exit;
    }

    // Check size
    if ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['error'] = "File is too large. Maximum is 5MB.";
        header("Location: /dpz-eims/pages/emp/docUpload.php");
        exit;
    }

    // Check file type
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

    $extension = strtolower(
        pathinfo($file['name'], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowed)) {
        $_SESSION['error'] = "Only JPG, PNG and PDF files are allowed.";
        header("Location: /dpz-eims/pages/emp/docUpload.php");
        exit;
    }

    // Upload folder
    $folder = __DIR__ . '/../../uploads/documents/';

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    // New filename
    $filename = uniqid('doc_') . '.' . $extension;

    // Move file
    if (!move_uploaded_file(
        $file['tmp_name'],
        $folder . $filename
    )) {
        $_SESSION['error'] = "Could not upload file.";
        header("Location: /dpz-eims/pages/emp/docUpload.php");
        exit;
    }

    // Save to database
    $path = 'uploads/documents/' . $filename;
    $name = $file['name'];

    $stmt = $connected->prepare("
        INSERT INTO documents
        (employee_id, document_type_id, document_name, file_path)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "siss",
        $employee_id,
        $type,
        $name,
        $path
    );

    if ($stmt->execute()) {

        $_SESSION['success'] = "Document uploaded successfully.";
    } else {

        // Remove file if database insert failed
        unlink($folder . $filename);

        $_SESSION['error'] = "Could not save document.";
    }

    header("Location: /dpz-eims/pages/emp/docUpload.php");
    exit;
}

$stmt = $connected->prepare("
    SELECT
        d.document_id,
        d.document_name,
        d.file_path,
        d.uploaded_at,
        dt.document_type_name

    FROM documents d

    JOIN document_types dt
        ON d.document_type_id = dt.document_type_id

    WHERE d.employee_id = ?

    ORDER BY d.uploaded_at DESC
");

$stmt->bind_param("s", $employee_id);
$stmt->execute();

$result = $stmt->get_result();

$documentTotal = $result->num_rows;

$countStmt = $connected->prepare("
    SELECT
        document_type_id,
        COUNT(*) AS total

    FROM documents

    WHERE employee_id = ?

    GROUP BY document_type_id
");

$countStmt->bind_param("s", $employee_id);
$countStmt->execute();

$countResult = $countStmt->get_result();

$categoryCounts = [];

while ($row = $countResult->fetch_assoc()) {
    $categoryCounts[$row['document_type_id']] = $row['total'];
}

$categoryCounts['all'] = $documentTotal;
$typeStmt = $connected->query("
    SELECT document_type_id, document_type_name
    FROM document_types
    ORDER BY document_type_id
");

$documentTypes = $typeStmt;
