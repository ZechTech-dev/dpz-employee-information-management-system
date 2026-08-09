<?php

include __DIR__ . '/../config/db.php';
include __DIR__ . '/../config/session.php';

sesh();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST['id'] ?? "";
    $pass = $_POST['password'] ?? "";
    $table = 'users';

    if (empty($id) || empty($pass)) {
        $_SESSION["error"] = "Please enter your username and password.";
        header('Location: ../auth/login.php');
        exit();
    }

    if (empty($id) || empty($pass)) {
        $_SESSION["error"] = "Please enter your username and password.";
        header('Location: ../auth/login.php');
        exit();
    }

    $stmt = $connected->prepare("SELECT * FROM $table WHERE employee_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($pass, $user['password'])) {

            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['employee_name'] = $user['employee_name'];
            $_SESSION['role'] = $user['role'];

            header("Location: /dpz-eims/pages/dashboard.php");
            exit;
        } else {

            $_SESSION['error'] = "Incorrect password.";
            header("Location: ../auth/login.php");
            exit;
        }
    } else {

        $_SESSION['error'] = "Employee ID does not exist.";
        header("Location: ../auth/login.php");
        exit;
    }
}

/* list lang ng
$until = 2000;

for ($i = 2026; $i >= $until; $i--) {
    echo "<option value = '$i'>$i</option>\n";
}*/
