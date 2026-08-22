<?php

require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';
require_once __DIR__ . '/../../process/manager/leaveMProcess.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leave Management</title>

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/manager/leaveManagement.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <main class="content">

        <div class="space"></div>

        <div class="list-header">

            <div class="left">

                <div class="cover">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>

                <div class="cover-info">
                    <h3>Leave Management</h3>
                    <span>Review and manage employee leave requests</span>
                </div>

            </div>

            <div class="employee-search">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="leaveSearch"
                    placeholder="Search employee or leave type">

            </div>

        </div>

        <div class="space"></div>

        <table class="emp-list leave-list">

            <thead>

                <tr>

                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>Leave Period</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody id="leaveTableBody">

                <?php if ($result && $result->num_rows > 0): ?>

                    <?php while ($leave = $result->fetch_assoc()): ?>

                        <tr>

                            <td>

                                <div class="employee-info">

                                    <img
                                        src="/dpz-eims/uploads/profile/<?= htmlspecialchars($leave['profile_picture'] ?: 'default-picture.jpg') ?>"
                                        alt="Profile"
                                        onerror="this.src='/dpz-eims/uploads/profile/default-picture.jpg'">

                                    <span>
                                        <?= htmlspecialchars($leave['employee_name']) ?>
                                    </span>

                                </div>

                            </td>

                            <td>

                                <span class="leave-type">
                                    <?= htmlspecialchars($leave['leave_type']) ?>
                                </span>

                            </td>

                            <td>

                                <div class="leave-date">

                                    <strong>
                                        <?= date('M d, Y', strtotime($leave['start_date'])) ?>
                                    </strong>

                                    <span>
                                        to
                                        <?= date('M d, Y', strtotime($leave['end_date'])) ?>
                                    </span>

                                </div>

                            </td>

                            <td>

                                <span class="leave-reason">
                                    <?= htmlspecialchars($leave['reason'] ?: 'No reason provided') ?>
                                </span>

                            </td>

                            <td>

                                <?php if ($leave['status'] === 'Approved'): ?>

                                    <span class="employee-status active">
                                        Approved
                                    </span>

                                <?php elseif ($leave['status'] === 'Rejected'): ?>

                                    <span class="employee-status inactive">
                                        Rejected
                                    </span>

                                <?php else: ?>

                                    <span class="employee-status on-leave">
                                        Pending
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= date('M d, Y', strtotime($leave['applied_at'])) ?>

                            </td>

                            <td>

                                <div class="action-buttons">

                                    <?php if ($leave['status'] === 'Pending'): ?>

                                        <button
                                            type="button"
                                            class="action-view review-button"
                                            data-id="<?= htmlspecialchars($leave['request_id']) ?>"
                                            data-name="<?= htmlspecialchars($leave['employee_name']) ?>"
                                            data-leave-type="<?= htmlspecialchars($leave['leave_type']) ?>"
                                            data-start="<?= htmlspecialchars($leave['start_date']) ?>"
                                            data-end="<?= htmlspecialchars($leave['end_date']) ?>"
                                            data-reason="<?= htmlspecialchars($leave['reason'] ?? '') ?>">

                                            <i class="bi bi-eye"></i>

                                        </button>

                                    <?php elseif ($leave['status'] === 'Approved'): ?>

                                        <button
                                            type="button"
                                            class="action-view view-button"
                                            data-status="Approved"
                                            data-decision-reason="<?= htmlspecialchars($leave['decision_reason'] ?? '') ?>">

                                            <i class="bi bi-info-circle"></i>

                                        </button>

                                    <?php elseif ($leave['status'] === 'Rejected'): ?>

                                        <button
                                            type="button"
                                            class="action-view view-button"
                                            data-status="Rejected"
                                            data-decision-reason="<?= htmlspecialchars($leave['decision_reason'] ?? '') ?>">

                                            <i class="bi bi-info-circle"></i>

                                        </button>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="7"
                            class="empty-state">

                            No leave requests found.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </main>

    <div class="leave-modal" id="decisionModal">

        <div
            class="leave-modal-overlay"
            id="decisionOverlay">
        </div>

        <div class="leave-modal-box">

            <button
                type="button"
                class="leave-modal-close"
                id="closeDecisionModal">

                <i class="bi bi-x-lg"></i>

            </button>

            <div class="leave-modal-header">

                <div class="leave-modal-icon">

                    <i class="bi bi-calendar-check"></i>

                </div>

                <div>

                    <h2>Review Leave Request</h2>

                    <p>
                        Review the request before making a decision.
                    </p>

                </div>

            </div>

            <div class="leave-details">

                <div>

                    <span>Employee</span>

                    <strong id="modalEmployeeName"></strong>

                </div>

                <div>

                    <span>Leave Type</span>

                    <strong id="modalLeaveType"></strong>

                </div>

                <div>

                    <span>Leave Period</span>

                    <strong id="modalLeaveDates"></strong>

                </div>

                <div class="leave-detail-reason">

                    <span>Employee's Reason</span>

                    <p id="modalEmployeeReason"></p>

                </div>

            </div>

            <form id="decisionForm">

                <input
                    type="hidden"
                    name="action"
                    value="decide">

                <input
                    type="hidden"
                    name="request_id"
                    id="requestId">

                <input
                    type="hidden"
                    name="decision"
                    id="decision">

                <div class="decision-field">

                    <label for="decisionReason">

                        Decision Reason

                        <small>
                            (required when rejecting)
                        </small>

                    </label>

                    <textarea
                        id="decisionReason"
                        name="decision_reason"
                        placeholder="Enter a reason for your decision..."></textarea>

                </div>

                <div class="decision-actions">

                    <button
                        type="button"
                        class="decision-cancel"
                        id="cancelDecision">

                        Cancel

                    </button>

                    <button
                        type="button"
                        class="decision-reject"
                        id="rejectLeave">

                        <i class="bi bi-x-circle"></i>

                        Reject

                    </button>

                    <button
                        type="button"
                        class="decision-approve"
                        id="approveLeave">

                        <i class="bi bi-check-circle"></i>

                        Approve

                    </button>

                </div>

            </form>

        </div>

    </div>

    <div class="view-modal" id="viewDecisionModal">

        <div
            class="view-modal-overlay"
            id="viewDecisionOverlay">
        </div>

        <div class="view-decision-box">

            <button
                type="button"
                class="modal-close"
                id="closeViewDecision">

                <i class="bi bi-x-lg"></i>

            </button>

            <div
                class="view-decision-icon"
                id="viewDecisionIcon">

                <i class="bi bi-info-circle"></i>

            </div>

            <h2 id="viewDecisionStatus"></h2>

            <span>
                Manager's Decision
            </span>

            <p id="viewDecisionReason"></p>

            <button
                type="button"
                class="view-done"
                id="doneViewDecision">

                Done

            </button>

        </div>

    </div>

    <script src="/dpz-eims/assets/js/manager/LeaveM.js"></script>

</body>

</html>