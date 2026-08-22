<?php

require_once __DIR__ . '/../../config/session.php';

sesh();

require_once __DIR__ . '/../../components/sideBar.php';
require_once __DIR__ . '/../../components/header.php';
require_once __DIR__ . '/../../process/manager/empMlistProcess.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Management</title>

    <link rel="stylesheet" href="/dpz-eims/assets/css/components/sideBar.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/header.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/components/global.css">
    <link rel="stylesheet" href="/dpz-eims/assets/css/manager/empMlist.css">
</head>

<body>

    <main class="content">

        <div class="space"></div>

        <div class="list-header">

            <div class="left">
                <div class="cover">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div class="cover-info">
                    <h3>Employee List</h3>
                    <span>Manage and view all employees in the organization</span>
                </div>
            </div>

            <div class="employee-search">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    id="employeeSearch"
                    placeholder="Search employee...">
            </div>

            <button class="add" id="openAddEmployee">
                <i class="bi bi-plus"></i>
                Add Employee
            </button>
        </div>

        <div class="space"></div>

        <table class="emp-list">

            <thead>
                <tr class="table-head">
                    <th>Employee ID</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date Hired</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($employee = $result->fetch_assoc()): ?>

                    <tr>

                        <td><?= htmlspecialchars($employee['employee_id']) ?></td>

                        <td>
                            <div class="employee-info">

                                <img
                                    src="/dpz-eims/uploads/profile/<?= htmlspecialchars($employee['profile_picture']) ?>"
                                    alt="Profile"> </img>

                                <span>
                                    <?= htmlspecialchars($employee['employee_name']) ?>
                                </span>

                            </div>
                        </td>

                        <td><?= htmlspecialchars($employee['role']) ?></td>

                        <td><?= htmlspecialchars($employee['department']) ?></td>

                        <td><?= htmlspecialchars($employee['email']) ?></td>

                        <td>
                            <span class="employee-status <?= strtolower(str_replace(' ', '-', $employee['status'])) ?>">
                                <?= htmlspecialchars($employee['status']) ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($employee['hire_date']) ?></td>

                        <td>
                            <div class="action-buttons">

                                <button
                                    type="button"
                                    class="action-view"
                                    data-employee-id="<?= htmlspecialchars($employee['employee_id']) ?>"
                                    data-name="<?= htmlspecialchars($employee['employee_name']) ?>"
                                    data-role="<?= htmlspecialchars($employee['role']) ?>"
                                    data-department="<?= htmlspecialchars($employee['department']) ?>"
                                    data-email="<?= htmlspecialchars($employee['email']) ?>"
                                    data-status="<?= htmlspecialchars($employee['status']) ?>"
                                    data-hire-date="<?= htmlspecialchars($employee['hire_date']) ?>"
                                    data-profile="<?= htmlspecialchars($employee['profile_picture']) ?>"
                                    data-profile="<?= htmlspecialchars($employee['profile_picture']) ?>">

                                    <i class="bi bi-eye"></i>

                                </button>

                                <button
                                    type="button"
                                    class="action-edit"
                                    data-employee-id="<?= htmlspecialchars($employee['employee_id']) ?>"
                                    data-name="<?= htmlspecialchars($employee['employee_name']) ?>"
                                    data-role="<?= htmlspecialchars($employee['role']) ?>"
                                    data-department="<?= htmlspecialchars($employee['department']) ?>"
                                    data-email="<?= htmlspecialchars($employee['email']) ?>"
                                    data-status="<?= htmlspecialchars($employee['status']) ?>"
                                    data-hire-date="<?= htmlspecialchars($employee['hire_date']) ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </button>

                                <button
                                    type="button"
                                    class="action-delete"
                                    data-employee-id="<?= htmlspecialchars($employee['employee_id']) ?>"
                                    data-name="<?= htmlspecialchars($employee['employee_name']) ?>">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </div>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </main>

    <section class="add-modal" id="addEmployeeModal">

        <div class="add-modal-overlay"></div>

        <div class="addEmp-modal">

            <button type="button" class="add-modal-close" id="closeAddEmployee">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="add-modal-header">

                <div class="add-icon">
                    <i class="bi bi-person-plus"></i>
                </div>

                <div>
                    <h2>Add Employee</h2>
                    <p>Enter the employee's information below.</p>
                </div>

            </div>

            <form id="addEmployeeForm">

                <div class="add-profile">

                    <div class="add-profile-preview">
                        <img
                            id="addProfilePreview"
                            src="/dpz-eims/assets/src/prof.jpg"
                            alt="Profile">
                    </div>

                    <div class="add-profile-upload">

                        <label for="addProfile">
                            <i class="bi bi-camera"></i>
                            Choose Profile
                        </label>

                        <input
                            type="file"
                            id="addProfile"
                            name="profile_picture"
                            accept=".jpg,.jpeg,.png">

                        <span>JPG, JPEG or PNG</span>

                    </div>

                </div>

                <div class="add-form-grid">

                    <div class="add-form-group">

                        <label for="addEmployeeId">
                            Employee ID
                        </label>

                        <input
                            type="text"
                            id="addEmployeeId"
                            name="employee_id"
                            required>

                    </div>

                    <div class="add-form-group">

                        <label for="addName">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="addName"
                            name="employee_name"
                            required>

                    </div>

                    <div class="add-form-group">

                        <label for="addRole">
                            Position
                        </label>

                        <select
                            id="addRole"
                            name="role"
                            required>

                            <option value="">Select Position</option>
                            <option value="Manager">Manager</option>
                            <option value="Employee">Employee</option>

                        </select>

                    </div>

                    <div class="add-form-group">

                        <label for="addDepartment">
                            Department
                        </label>

                        <input
                            type="text"
                            id="addDepartment"
                            name="department"
                            required>

                    </div>

                    <div class="add-form-group">

                        <label for="addEmail">
                            Email
                        </label>

                        <input
                            type="email"
                            id="addEmail"
                            name="email"
                            required>

                    </div>

                    <div class="add-form-group">

                        <label for="addStatus">
                            Status
                        </label>

                        <select
                            id="addStatus"
                            name="status"
                            required>

                            <option value="Active">Active</option>
                            <option value="On Leave">On Leave</option>
                            <option value="Inactive">Inactive</option>

                        </select>

                    </div>

                    <div class="add-form-group">

                        <label for="addHireDate">
                            Date Hired
                        </label>

                        <input
                            type="date"
                            id="addHireDate"
                            name="hire_date"
                            required>

                    </div>

                </div>

                <div class="add-modal-actions">

                    <button
                        type="button"
                        class="add-cancel"
                        id="cancelAddEmployee">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="add-save">
                        <i class="bi bi-person-plus"></i>
                        Add Employee
                    </button>

                </div>

            </form>

        </div>

    </section>


    <section class="view-modal" id="viewModal">

        <div class="view-modal-overlay"></div>

        <div class="viewEmp-modal">

            <button type="button" class="modal-close" id="closeViewModal">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="modal-profile">

                <img
                    id="modalProfile"
                    src=""
                    alt="Employee Profile">

                <div class="modal-profile-info">

                    <h2 id="modalName"></h2>

                    <span id="modalRole"></span>

                    <span id="modalEmployeeId"></span>

                </div>

            </div>

            <div class="modal-divider"></div>

            <div class="employee-details">

                <div class="detail-item">

                    <span class="detail-label">
                        Employee ID
                    </span>

                    <span
                        class="detail-value"
                        id="detailEmployeeId">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Full Name
                    </span>

                    <span
                        class="detail-value"
                        id="detailName">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Position
                    </span>

                    <span
                        class="detail-value"
                        id="detailRole">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Department
                    </span>

                    <span
                        class="detail-value"
                        id="detailDepartment">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Email
                    </span>

                    <span
                        class="detail-value"
                        id="detailEmail">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Status
                    </span>

                    <span
                        class="detail-value"
                        id="detailStatus">
                    </span>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Date Hired
                    </span>

                    <span
                        class="detail-value"
                        id="detailHireDate">
                    </span>

                </div>

            </div>

        </div>

    </section>

    <section class="edit-modal" id="editModal">

        <div class="edit-modal-overlay"></div>

        <div class="editEmp-modal">

            <button type="button" class="edit-modal-close" id="closeEditModal">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="edit-modal-header">

                <div class="edit-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2>Edit Employee Information</h2>
                    <p>Update the employee's information below.</p>
                </div>

            </div>

            <form id="editEmployeeForm" enctype="multipart/form-data">

                <input
                    type="hidden"
                    id="editEmployeeId"
                    name="employee_id">

                <div class="profile-edit">

                    <div class="profile-preview">

                        <img
                            id="editProfilePreview"
                            src="/dpz-eims/assets/src/prof.jpg"
                            alt="Profile">

                    </div>

                    <div class="profile-upload">

                        <label for="editProfile">
                            <i class="bi bi-camera"></i>
                            Change Profile
                        </label>

                        <input
                            type="file"
                            id="editProfile"
                            name="profile_picture"
                            accept=".jpg,.jpeg,.png">

                        <span>JPG, JPEG or PNG</span>

                    </div>

                </div>

                <div class="edit-form-grid">

                    <div class="edit-form-group">

                        <label for="editName">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="editName"
                            name="employee_name"
                            required>

                    </div>

                    <div class="edit-form-group">

                        <label for="editRole">
                            Position
                        </label>

                        <select
                            id="editRole"
                            name="role"
                            required>

                            <option value="Manager">Manager</option>
                            <option value="Employee">Employee</option>

                        </select>

                    </div>

                    <div class="edit-form-group">

                        <label for="editDepartment">
                            Department
                        </label>

                        <select
                            id="editDepartment"
                            name="department"
                            required>

                            <option value="Human Resources">Human Resources</option>
                            <option value="Finance">Finance</option>
                            <option value="IT">IT</option>
                            <option value="Administration">Administration</option>

                        </select>

                    </div>

                    <div class="edit-form-group">

                        <label for="editEmail">
                            Email
                        </label>

                        <input
                            type="email"
                            id="editEmail"
                            name="email"
                            required>

                    </div>

                    <div class="edit-form-group">

                        <label for="editStatus">
                            Status
                        </label>

                        <select
                            id="editStatus"
                            name="status"
                            required>

                            <option value="Active">Active</option>
                            <option value="On Leave">On Leave</option>
                            <option value="Inactive">Inactive</option>

                        </select>

                    </div>

                    <div class="edit-form-group">

                        <label for="editHireDate">
                            Date Hired
                        </label>

                        <input
                            type="date"
                            id="editHireDate"
                            name="hire_date"
                            required>

                    </div>

                </div>

                <div class="edit-modal-actions">

                    <button
                        type="button"
                        class="edit-cancel"
                        id="cancelEdit">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="edit-save">
                        <i class="bi bi-check2"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </section>

    <section class="delete-modal" id="deleteModal">

        <div class="delete-modal-overlay"></div>

        <div class="deleteEmp-modal">

            <button
                type="button"
                class="delete-modal-close"
                id="closeDeleteModal">

                <i class="bi bi-x-lg"></i>

            </button>

            <div class="delete-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="delete-content">

                <h2>Delete Employee?</h2>

                <p>
                    You are about to permanently delete
                    <strong id="deleteEmployeeName"></strong>.
                </p>

                <p class="delete-warning">
                    This action will permanently remove the employee's
                    information from the system and cannot be undone.
                </p>

            </div>

            <input
                type="hidden"
                id="deleteEmployeeId">

            <div class="delete-actions">

                <button
                    type="button"
                    class="delete-cancel"
                    id="cancelDelete">

                    Cancel

                </button>

                <button
                    type="button"
                    class="delete-confirm"
                    id="confirmDelete">

                    <i class="bi bi-trash"></i>
                    Delete Employee

                </button>

            </div>

        </div>

    </section>

    <script src="/dpz-eims/assets/js/manager/empMList.js"></script>

</body>

</html>