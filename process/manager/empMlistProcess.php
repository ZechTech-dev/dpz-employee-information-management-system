<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';

sesh();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $employee_id = $_POST['employee_id'] ?? '';
    $employee_name = $_POST['employee_name'] ?? '';
    $role = $_POST['role'] ?? '';
    $department = $_POST['department'] ?? '';
    $email = $_POST['email'] ?? '';
    $status = $_POST['status'] ?? '';
    $hire_date = $_POST['hire_date'] ?? '';

    $profile_picture = null;

    if (
        isset($_FILES['profile_picture']) &&
        $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to upload profile picture.'
            ]);
            exit;
        }

        $allowedTypes = ['jpg', 'jpeg', 'png'];

        $fileName = $_FILES['profile_picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid profile picture format.'
            ]);
            exit;
        }

        if ($_FILES['profile_picture']['size'] > 5 * 1024 * 1024) {
            echo json_encode([
                'success' => false,
                'message' => 'Profile picture must be 5MB or smaller.'
            ]);
            exit;
        }

        $uploadDirectory = __DIR__ . '/../../uploads/profile/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $newFileName = $employee_id . '_' . uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDirectory . $newFileName;

        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to save profile picture.'
            ]);
            exit;
        }

        $profile_picture = $newFileName;
    }

    if ($profile_picture !== null) {

        $stmt = $connected->prepare("
            UPDATE users
            SET
                employee_name = ?,
                role = ?,
                department = ?,
                email = ?,
                status = ?,
                hire_date = ?,
                profile_picture = ?
            WHERE employee_id = ?
        ");

        $stmt->bind_param(
            "ssssssss",
            $employee_name,
            $role,
            $department,
            $email,
            $status,
            $hire_date,
            $profile_picture,
            $employee_id
        );
    } else {

        $stmt = $connected->prepare("
            UPDATE users
            SET
                employee_name = ?,
                role = ?,
                department = ?,
                email = ?,
                status = ?,
                hire_date = ?
            WHERE employee_id = ?
        ");

        $stmt->bind_param(
            "sssssss",
            $employee_name,
            $role,
            $department,
            $email,
            $status,
            $hire_date,
            $employee_id
        );
    }

    if ($stmt->execute()) {

        echo json_encode([
            'success' => true,
            'message' => 'Employee information updated successfully.'
        ]);
    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Failed to update employee information.'
        ]);
    }

    $stmt->close();
    exit;
}


if (isset($_GET['employee_id'])) {

    header('Content-Type: application/json');

    $employee_id = $_GET['employee_id'];

    $stmt = $connected->prepare("
        SELECT
            employee_id,
            employee_name,
            role,
            department,
            email,
            status,
            hire_date,
            profile_picture
        FROM users
        WHERE employee_id = ?
    ");

    $stmt->bind_param("s", $employee_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $employee = $result->fetch_assoc();

    echo json_encode($employee);

    $stmt->close();
    exit;
}


$employee_id = $_SESSION['employee_id'];

$query = "
    SELECT
        employee_id,
        employee_name,
        role,
        department,
        email,
        status,
        hire_date,
        profile_picture
    FROM users
    ORDER BY employee_id ASC
";

$result = $connected->query($query);
