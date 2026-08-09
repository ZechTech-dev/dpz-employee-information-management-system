<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();


// ========================================
// GET LOGGED-IN EMPLOYEE
// ========================================

$employee_id = $_SESSION['employee_id'];


// ========================================
// GET EMPLOYEE DOCUMENTS
// ========================================

$stmt = $connected->prepare("
    SELECT
        d.document_id,
        d.employee_id,
        d.document_type_id,
        d.document_name,
        d.file_path,
        d.uploaded_at,
        dt.document_type_name

    FROM documents d

    INNER JOIN document_types dt
        ON d.document_type_id = dt.document_type_id

    WHERE d.employee_id = ?

    ORDER BY d.uploaded_at DESC
");

$stmt->bind_param("s", $employee_id);

$stmt->execute();

$result = $stmt->get_result();


// Total number of documents
$documentTotal = $result->num_rows;


// ========================================
// GET DOCUMENT COUNTS BY CATEGORY
// ========================================

$countStmt = $connected->prepare("
    SELECT
        dt.document_type_id,
        dt.document_type_name,
        COUNT(d.document_id) AS total

    FROM document_types dt

    LEFT JOIN documents d
        ON d.document_type_id = dt.document_type_id
        AND d.employee_id = ?

    GROUP BY
        dt.document_type_id,
        dt.document_type_name

    ORDER BY
        dt.document_type_id
");

$countStmt->bind_param("s", $employee_id);

$countStmt->execute();

$countResult = $countStmt->get_result();


// Store counts using the TYPE ID
$categoryCounts = [];

while ($row = $countResult->fetch_assoc()) {

    $categoryCounts[$row['document_type_id']] = $row['total'];
}


// Total documents
$categoryCounts['all'] = $documentTotal;


// ========================================
// GET DOCUMENT TYPES
// ========================================

$typeStmt = $connected->prepare("
    SELECT
        document_type_id,
        document_type_name

    FROM document_types

    ORDER BY document_type_id
");

$typeStmt->execute();

$documentTypes = $typeStmt->get_result();
