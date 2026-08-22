<?php

require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../process/emp/leaveRequestProcess.php';
require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leave Request</title>


    <link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/leaveRequest.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/viewLeave.css">

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">



</head>

<body>

    <main class="content">

        <div class="space"></div>
        <section class=" yearFilter">

            <div class="space"></div>
            <div class="leave">
                <div class="calc">
                    <h1>Total Number of Request: <?php echo $numberOfRequests; ?></h1>
                </div>

                <span>Filter:
                    <select id="statusFilter">
                        <option value="allRequest" <?= $status === 'allRequest' ? 'selected' : '' ?>>
                            All Status
                        </option>

                        <option value="approvedRequest" <?= $status === 'approvedRequest' ? 'selected' : '' ?>>
                            Approved
                        </option>

                        <option value="pendingRequest" <?= $status === 'pendingRequest' ? 'selected' : '' ?>>
                            Pending
                        </option>

                        <option value="rejectedRequest" <?= $status === 'rejectedRequest' ? 'selected' : '' ?>>
                            Rejected
                        </option>
                    </select>
                </span>

                <span>
                    Year:
                    <select id="yearFilter">
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

                <button
                    type="button"
                    id="newRequestBtn"
                    class="new-request-btn"
                    onclick="openRequestModal()">

                    <i class="bi bi-plus-circle-fill"></i>
                    Add New Request
                </button>
            </div>
        </section>

        <section class="tableLeave">
            <table>

                <!--eto ba yung namatay jahahahahwhwahwa-->
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Date Filed</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while ($row = $leaveResult->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($row['leave_type']) ?>
                            </td>

                            <td>
                                <?= date("F j, Y", strtotime($row['applied_at'])) ?>
                            </td>

                            <td>
                                <?= date("F j, Y", strtotime($row['start_date'])) ?>
                            </td>

                            <td>
                                <?= date("F j, Y", strtotime($row['end_date'])) ?>
                            </td>

                            <td>
                                <?php
                                $start = new DateTime($row['start_date']);
                                $end = new DateTime($row['end_date']);

                                echo $start->diff($end)->days + 1;
                                ?>
                                day(s)
                            </td>

                            <td>
                                <span class="status <?= strtolower($row['status']) ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>

                            <td>
                                <button
                                    class="btn-view"
                                    onclick="openLeaveModal(<?= (int) $row['request_id'] ?>)">
                                    View
                                </button>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>




            </table>
        </section>

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

    <section class="viewLeave">
        <div class="modal-overlay" id="leaveModal">

            <div class="leave-modal">

                <h2>Leave Request Details</h2>

                <button class="close-modal" onclick="closeLeaveModal()">
                    &times;
                </button>



                <div class="modal-details">

                    <div>
                        <label>Leave Type</label>
                        <p id="modalType"></p>
                    </div>


                    <div>
                        <label>Status</label>
                        <p id="modalStatus"></p>
                    </div>


                    <div>
                        <label>Date Filed</label>
                        <p id="modalFiled"></p>
                    </div>


                    <div>
                        <label>From</label>
                        <p id="modalFrom"></p>
                    </div>


                    <div>
                        <label>To</label>
                        <p id="modalTo"></p>
                    </div>


                    <div>
                        <label>Duration</label>
                        <p id="modalDuration"></p>
                    </div>


                    <div class="reason-box">

                        <label>Reason</label>

                        <p id="modalReason"></p>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="newLeaveRequest">

        <div class="request-overlay" id="requestModal">

            <div class="request-modal">

                <div class="modal-header">
                    <h2>New Leave Request</h2>

                    <button class="close-btn" onclick="closeRequestModal()">
                        &times;
                    </button>

                </div>

                <!-- Progress -->
                <div class="progress">

                    <div class="circle active" id="circle1">1</div>
                    <div class="line"></div>

                    <div class="circle" id="circle2">2</div>
                    <div class="line"></div>

                    <div class="circle" id="circle3">3</div>
                    <div class="line"></div>

                    <div class="circle" id="circle4">4</div>

                </div>

                <form
                    id="leaveForm"
                    action="/dpz-eims/process/emp/leaveRequestProcess.php"
                    method="POST">

                    <!-- STEP 1 -->
                    <div class="step active" id="step1">

                        <h3>Select Leave Type</h3>

                        <p class="instruction">

                            Select the leave category you are requesting.

                        </p>

                        <select id="leaveType" name="leave_type" required>
                            <option value="">Choose Leave Type</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Vacation Leave">Vacation Leave</option>
                            <option value="Emergency Leave">Emergency Leave</option>
                        </select>

                        <div class="buttons">

                            <button type="button" onclick="nextStep()">

                                Next

                            </button>

                        </div>

                    </div>

                    <!-- STEP 2 -->
                    <div class="step" id="step2">

                        <h3>Leave Details</h3>

                        <p class="instruction">

                            Select your leave dates and provide a reason.

                        </p>

                        <label>From</label>

                        <input type="date" id="startDate" name="start_date" required>

                        <label>To</label>

                        <input type="date" id="endDate" name="end_date" required>

                        <label>Total Duration</label>

                        <input
                            type="text"
                            id="duration"
                            readonly>

                        <label>Reason</label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="5"
                            placeholder="Explain why you're requesting leave"
                            required></textarea>

                        <div class="buttons">

                            <button type="button"
                                onclick="prevStep()">

                                Back

                            </button>

                            <button type="button"
                                onclick="nextStep()">

                                Next

                            </button>

                        </div>

                    </div>

                    <!-- STEP 3 -->
                    <div class="step" id="step3">

                        <h3>Review Request</h3>

                        <p class="instruction">

                            Verify all information before submitting.

                        </p>

                        <div class="review">

                            <p><strong>Leave Type:</strong>
                                <span id="reviewType"></span>
                            </p>

                            <p><strong>From:</strong>
                                <span id="reviewFrom"></span>
                            </p>

                            <p><strong>To:</strong>
                                <span id="reviewTo"></span>
                            </p>

                            <p><strong>Duration:</strong>
                                <span id="reviewDuration"></span>
                            </p>

                            <p><strong>Reason:</strong></p>

                            <div class="reason-box">

                                <span id="reviewReason"></span>

                            </div>

                        </div>

                        <div class="buttons">

                            <button type="button"
                                onclick="prevStep()">

                                Back

                            </button>

                            <button type="button"
                                onclick="nextStep()">

                                Continue

                            </button>

                        </div>

                    </div>

                    <!-- STEP 4 -->
                    <div class="step" id="step4">

                        <i class="bi bi-send-check-fill success-icon"></i>

                        <h3>Confirm Submission</h3>

                        <p class="instruction">

                            Your leave request will be sent to your manager for approval.

                        </p>

                        <div class="buttons">

                            <button type="button"
                                onclick="prevStep()">

                                Back

                            </button>

                            <button type="submit">

                                Submit Request

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </section>
    <script src="/dpz-eims/assets/js/emp/leaveRequest.js"></script>
</body>

</html>