<?php include_once '../config/session.php';
sesh();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <div class="wrapper">

        <div class="LPanel">

            <h1 class="brand-name">ServiSIS</h1>
            <img src="../assets/src/logo.png" alt="ServiSIS Logo" class="panel-logo">

            <h2 class="welcome">
                Welcome Back!
            </h2>

            <div class="divider"></div>
            <h1 class="system-title">
                Employee Information<br>
                Management System
            </h1>

            <p>Sign in to access the system</p>



        </div>

        <div class="RPanel">
            <!--hawhwahwh extra lang pandesign-->
            <div class="extra-design">
                <span href="employees.php" class="yes-i">
                    <i class="bi bi-person-fill"></i>
                    <span>Employee Portal</span>
                </span>
                <h1>Login</h1>
                <p>Enter your correct credentials to continue</p>
            </div>

            <form class="login-page" action="../pages/dashboard.php" method="POST">

                <div class="input-id">

                    <label for="id">Employee ID</label>
                    <div class="inputs">
                        <input
                            type="text"
                            id="id"
                            name="id"
                            placeholder="Ex. 24-0787"
                            autocomplete="off"
                            required>
                    </div>

                    <label for="password">Password</label>
                    <div class="inputs">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="new-password"
                            required>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>

                        <div class="session-error">

                            <i class="bi bi-x-circle-fill"></i>

                            <span>
                                <?= $_SESSION['error'];
                                 ?>
                            </span>

                        </div>

                    <?php
                        unset($_SESSION['error']);
                    endif;
                    ?>

                    <button type="submit" class="login-btn">
                        Login
                    </button>

                </div>

            </form>

            <div class="security-box">

                <div class="security-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div class="security-content">
                    <h4>Secure Login</h4>

                    <p>
                        This portal is intended only for authorized employees.
                        Unauthorized access is strictly prohibited.
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>