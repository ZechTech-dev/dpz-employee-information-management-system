<?php

require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../process/emp/attendanceProcess.php';
require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/emp/attendance.css">
</head>

<body>

    <main class="content">
        <div class="space"></div>

        <div class="attendance-wrapper">

            <div class="attendance-top">

                <div class="today-info">
                    <span>TODAY</span>
                    <strong id="currentDate">Sunday, August 16, 2026</strong>
                </div>

                <div class="current-time">
                    <span>CURRENT TIME</span>
                    <strong id="currentTime">12:12 AM</strong>
                </div>

            </div>


            <div class="attendance-actions">

                <div class="attendance-box">
                    <div class="attendance-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>

                    <span>TIME IN</span>

                    <strong id="timeInDisplay">Not timed in</strong>

                    <button id="timeInBtn">
                        TIME IN
                    </button>
                </div>


                <div class="attendance-box">
                    <div class="attendance-icon">
                        <i class="bi bi-pause-circle"></i>
                    </div>

                    <span>BREAK IN</span>

                    <strong id="breakInDisplay">No break</strong>

                    <button id="breakInBtn" disabled>
                        BREAK IN
                    </button>
                </div>


                <div class="attendance-box">
                    <div class="attendance-icon">
                        <i class="bi bi-play-circle"></i>
                    </div>

                    <span>BREAK OUT</span>

                    <strong id="breakOutDisplay">No break</strong>

                    <button id="breakOutBtn" disabled>
                        BREAK OUT
                    </button>
                </div>


                <div class="attendance-box">
                    <div class="attendance-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>

                    <span>TIME OUT</span>

                    <strong id="timeOutDisplay">Not timed out</strong>

                    <button id="timeOutBtn" disabled>
                        TIME OUT
                    </button>
                </div>

            </div>

        </div>


        <table class="attendance-records">
            <thead>

                <tr>
                    <th colspan="7" class="table-title">My Attendance Record</th>
                </tr>

                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Break</th>
                    <th>Total Hours</th>
                    <th>Status</th>

                    <?php ?>
                </tr>
            </thead>


            <tbody id="attendanceTableBody">

                <?php foreach ($attendanceRecords as $row): ?>

                    <tr>

                        <td>
                            <?= date("F j, Y", strtotime($row['date'])) ?>
                        </td>

                        <td>
                            <?= date("l", strtotime($row['date'])) ?>
                        </td>

                        <td>
                            <?= !empty($row['time_in'])
                                ? date("h:i A", strtotime($row['time_in']))
                                : '---'
                            ?>
                        </td>

                        <td>
                            <?= !empty($row['time_out'])
                                ? date("h:i A", strtotime($row['time_out']))
                                : '---'
                            ?>
                        </td>

                        <td>
                            <?= $row['break_hours'] !== null
                                ? $row['break_hours']
                                : '---'
                            ?>
                        </td>

                        <td>
                            <?= $row['total_hours'] !== null
                                ? $row['total_hours']
                                : '---'
                            ?>
                        </td>

                        <td>

                            <?php if ($row['status'] === 'Present'): ?>

                                <span class="status present">
                                    Present
                                </span>

                            <?php elseif ($row['status'] === 'Late'): ?>

                                <span class="status late">
                                    Late
                                </span>

                            <?php elseif ($row['status'] === 'Absent'): ?>

                                <span class="status absent">
                                    Absent
                                </span>

                            <?php elseif ($row['status'] === 'Day Off'): ?>

                                <span class="status day-off">
                                    Day Off
                                </span>

                            <?php else: ?>

                                <?= htmlspecialchars($row['status']) ?>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>


        </table>

        <section class="pageActive">

            <div class="pagination">

                <span>
                    <?php if ($totalRows > 0): ?>

                        <?= (($page - 1) * $limit) + 1 ?>
                        -
                        <?= min($page * $limit, $totalRows) ?>

                    <?php else: ?>

                        0

                    <?php endif; ?>

                    of <?= $totalRows ?>
                </span>


                <div>

                    <?php if ($page > 1): ?>

                        <button onclick="goToPage(<?= $page - 1 ?>)">
                            &lt;
                        </button>

                    <?php endif; ?>


                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                        <button
                            class="<?= $i == $page ? 'active' : '' ?>"
                            onclick="goToPage(<?= $i ?>)">

                            <?= $i ?>

                        </button>

                    <?php endfor; ?>


                    <?php if ($page < $totalPages): ?>

                        <button onclick="goToPage(<?= $page + 1 ?>)">
                            &gt;
                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </section>
    </main>
    <script src="/dpz-eims/assets/js/emp/attendance.js"></script>
</body>

</html>