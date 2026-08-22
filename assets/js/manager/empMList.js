const employeeSearch = document.getElementById("employeeSearch");
const employeeRows = document.querySelectorAll(".emp-list tbody tr");

employeeSearch.addEventListener("input", function () {

    const search = this.value.toLowerCase();

    employeeRows.forEach(row => {

        const employee = row.textContent.toLowerCase();

        if (employee.includes(search)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});

const addModal = document.getElementById("addEmployeeModal");
const openAddEmployee = document.getElementById("openAddEmployee");
const closeAddEmployee = document.getElementById("closeAddEmployee");
const cancelAddEmployee = document.getElementById("cancelAddEmployee");
const addModalOverlay = document.querySelector(".add-modal-overlay");

const addProfile = document.getElementById("addProfile");
const addProfilePreview = document.getElementById("addProfilePreview");
const addEmployeeForm = document.getElementById("addEmployeeForm");

openAddEmployee.addEventListener("click", function () {
    addModal.classList.add("show");
});

function closeAddModal() {
    addModal.classList.remove("show");
}

closeAddEmployee.addEventListener("click", closeAddModal);
cancelAddEmployee.addEventListener("click", closeAddModal);

addModalOverlay.addEventListener("click", function (event) {
    if (event.target === addModalOverlay) {
        closeAddModal();
    }
});

addProfile.addEventListener("change", function () {

    const file = this.files[0];

    if (file) {
        addProfilePreview.src = URL.createObjectURL(file);
    }

});

addEmployeeForm.addEventListener("submit", function (event) {

    event.preventDefault();

    const formData = new FormData(addEmployeeForm);

    fetch("/dpz-eims/process/manager/addEmployeeProcess.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                alert("Employee added successfully.");

                closeAddModal();

                addEmployeeForm.reset();

                addProfilePreview.src =
                    "/dpz-eims/assets/src/prof.jpg";

                location.reload();

            } else {

                alert(data.message || "Failed to add employee.");

            }

        })
        .catch(error => {

            console.error(error);
            alert("Something went wrong while adding the employee.");

        });

});

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {
        closeAddModal();
    }

});

const viewModal = document.getElementById("viewModal");
const closeViewModal = document.getElementById("closeViewModal");
const modalOverlay = document.querySelector(".view-modal-overlay");

const viewButtons = document.querySelectorAll(".action-view");

viewButtons.forEach(button => {

    button.addEventListener("click", function () {

        const employeeId = this.dataset.employeeId;

        fetch(
            "/dpz-eims/process/manager/empMlistProcess.php?employee_id=" +
            encodeURIComponent(employeeId)
        )
            .then(response => {

                if (!response.ok) {
                    throw new Error("Failed to load employee information.");
                }

                return response.json();

            })
            .then(employee => {

                if (!employee) {
                    throw new Error("Employee information not found.");
                }

                document.getElementById("modalProfile").src =
                    "/dpz-eims/uploads/profile/" +
                    employee.profile_picture;

                document.getElementById("modalName").textContent =
                    employee.employee_name;

                document.getElementById("modalRole").textContent =
                    employee.role;

                document.getElementById("modalEmployeeId").textContent =
                    employee.employee_id;

                document.getElementById("detailEmployeeId").textContent =
                    employee.employee_id;

                document.getElementById("detailName").textContent =
                    employee.employee_name;

                document.getElementById("detailRole").textContent =
                    employee.role;

                document.getElementById("detailDepartment").textContent =
                    employee.department;

                document.getElementById("detailEmail").textContent =
                    employee.email;

                document.getElementById("detailStatus").textContent =
                    employee.status;

                document.getElementById("detailHireDate").textContent =
                    employee.hire_date;

                viewModal.classList.add("show");

            })
            .catch(error => {

                console.error(error);
                alert("Failed to load employee information.");

            });

    });

});


function closeModal() {
    viewModal.classList.remove("show");
}


closeViewModal.addEventListener("click", closeModal);

modalOverlay.addEventListener("click", function (event) {

    if (event.target === modalOverlay) {
        closeModal();
    }

});

const editModal = document.getElementById("editModal");
const closeEditModal = document.getElementById("closeEditModal");
const cancelEdit = document.getElementById("cancelEdit");
const editModalOverlay = document.querySelector(".edit-modal-overlay");
const editProfilePreview = document.getElementById("editProfilePreview");
const editProfile = document.getElementById("editProfile");
const editButtons = document.querySelectorAll(".action-edit");

editButtons.forEach(button => {

    button.addEventListener("click", function () {

        const employeeId = this.dataset.employeeId;

        fetch(
            "/dpz-eims/process/manager/empMlistProcess.php?employee_id=" +
            encodeURIComponent(employeeId)
        )
            .then(response => {

                if (!response.ok) {
                    throw new Error("Failed to load employee information.");
                }

                return response.json();

            })
            .then(employee => {

                if (!employee) {
                    throw new Error("Employee information not found.");
                }

                document.getElementById("editEmployeeId").value =
                    employee.employee_id;

                document.getElementById("editName").value =
                    employee.employee_name;

                document.getElementById("editRole").value =
                    employee.role;

                document.getElementById("editDepartment").value =
                    employee.department;

                document.getElementById("editEmail").value =
                    employee.email;

                document.getElementById("editStatus").value =
                    employee.status;

                document.getElementById("editHireDate").value =
                    employee.hire_date;

                editProfilePreview.src =
                    employee.profile_picture
                        ? "/dpz-eims/uploads/profile/" + employee.profile_picture
                        : "/dpz-eims/uploads/profile/default-picture.jpg";

                editModal.classList.add("show");

            })
            .catch(error => {

                console.error(error);
                alert("Failed to load employee information.");

            });

    });

});

editProfile.addEventListener("change", function () {

    const file = this.files[0];

    if (file) {
        editProfilePreview.src = URL.createObjectURL(file);
    }

});

function closeEditEmployeeModal() {
    editModal.classList.remove("show");
}

closeEditModal.addEventListener(
    "click",
    closeEditEmployeeModal
);

cancelEdit.addEventListener(
    "click",
    closeEditEmployeeModal
);

editModalOverlay.addEventListener(
    "click",
    function (event) {

        if (event.target === editModalOverlay) {
            closeEditEmployeeModal();
        }

    }
);

const editEmployeeForm =
    document.getElementById("editEmployeeForm");

editEmployeeForm.addEventListener("submit", function (event) {

    event.preventDefault();

    const formData = new FormData(editEmployeeForm);

    fetch(
        "/dpz-eims/process/manager/empMlistProcess.php",
        {
            method: "POST",
            body: formData
        }
    )
        .then(response => {

            if (!response.ok) {
                throw new Error("Failed to update employee.");
            }

            return response.json();

        })
        .then(data => {

            if (data.success) {

                alert("Employee information updated successfully.");

                closeEditEmployeeModal();

                location.reload();

            } else {

                alert(
                    data.message ||
                    "Failed to update employee information."
                );

            }

        })
        .catch(error => {

            console.error(error);
            alert("Something went wrong while updating the employee.");

        });

});

const deleteModal = document.getElementById("deleteModal");
const closeDeleteModal = document.getElementById("closeDeleteModal");
const cancelDelete = document.getElementById("cancelDelete");
const confirmDelete = document.getElementById("confirmDelete");
const deleteModalOverlay = document.querySelector(".delete-modal-overlay");
const deleteEmployeeName = document.getElementById("deleteEmployeeName");
const deleteEmployeeId = document.getElementById("deleteEmployeeId");

const deleteButtons = document.querySelectorAll(".action-delete");

deleteButtons.forEach(button => {

    button.addEventListener("click", function () {

        const employeeId = this.dataset.employeeId;
        const employeeName = this.dataset.name;

        deleteEmployeeId.value = employeeId;
        deleteEmployeeName.textContent = employeeName;

        deleteModal.classList.add("show");

    });

});


function closeDeleteEmployeeModal() {

    deleteModal.classList.remove("show");

}


closeDeleteModal.addEventListener(
    "click",
    closeDeleteEmployeeModal
);


cancelDelete.addEventListener(
    "click",
    closeDeleteEmployeeModal
);


deleteModalOverlay.addEventListener(
    "click",
    function (event) {

        if (event.target === deleteModalOverlay) {
            closeDeleteEmployeeModal();
        }

    }
);