<?php

require_once __DIR__ . '/../config/session.php';

sesh();

require_once __DIR__ . '/../components/sideBar.php';

$showPopup = false;

if (isset($_SESSION['login_success'])) {
    $showPopup = true;
    unset($_SESSION['login_success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>


    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #F5F7FB;
        }

        .content {
            margin-left: 95px;
            padding: 40px;
            transition: .3s;
        }

        h1 {
            color: #0B2E6D;
            margin-bottom: 10px;
        }

        .popup {
            position: fixed;
            inset: 0;

            display: none;
            justify-content: center;
            align-items: center;

            background: rgba(0, 0, 0, .35);

            z-index: 9999;
        }

        .popup-content {
            width: 360px;

            background: #fff;

            border-radius: 20px;

            padding: 35px;

            text-align: center;

            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);

            animation: popup .35s ease;
        }

        .popup-content i {
            font-size: 60px;
            color: #22C55E;
        }

        .popup-content h2 {
            margin: 15px 0 5px;
            color: #0B2E6D;
        }

        .popup-content p {
            color: #6B7280;
            margin-bottom: 0;
        }

        @keyframes popup {

            from {
                opacity: 0;
                transform: scale(.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }

        }
    </style>

</head>

<body>



    <div id="popup" class="popup">

        <div class="popup-content">

            <div style="font-size:60px;">🎉</div>

            <h2>Welcome Back!</h2>

            <p><?= $_SESSION['employee_name']; ?></p>

        </div>

    </div>

    <div class="">
        <span>Year:
            <select>
                <!--
                <option value='2026'>2026</option>
                <option value='2025'>2025</option>
                <option value='2024'>2024</option>
                <option value='2023'>2023</option>
                <option value='2022'>2022</option>
                <option value='2021'>2021</option>
                <option value='2020'>2020</option>
                <option value='2019'>2019</option>
                <option value='2018'>2018</option>
                <option value='2017'>2017</option>
                <option value='2016'>2016</option>
                <option value='2015'>2015</option>
                <option value='2014'>2014</option>
                <option value='2013'>2013</option>
                <option value='2012'>2012</option>
                <option value='2011'>2011</option>
                <option value='2010'>2010</option>
                <option value='2009'>2009</option>
                <option value='2008'>2008</option>
                <option value='2007'>2007</option>
                <option value='2006'>2006</option>
                <option value='2005'>2005</option>
                <option value='2004'>2004</option>
                <option value='2003'>2003</option>
                <option value='2002'>2002</option>
                <option value='2001'>2001</option>
                <option value='2000'>2000</option>

            </select>
        </span>
-->
    </div>

    <!-- Dashboard -->

    <main class="content">

        <h1>Dashboard</h1>

        <p>Welcome back, <strong><?= $_SESSION['employee_name']; ?></strong>.</p>

    </main>

    <?php if ($showPopup): ?>

        <script>
            const popup = document.getElementById("popup");

            popup.style.display = "flex";

            setTimeout(() => {

                popup.style.display = "none";

            }, 2500);
        </script>

    <?php endif; ?>

</body>

</html>