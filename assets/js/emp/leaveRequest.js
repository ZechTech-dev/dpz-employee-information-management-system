console.log("Leave Request JS Loaded");

let currentStep = 1;

function openRequestModal() {
    const modal = document.getElementById("requestModal");

    if (modal) {
        modal.style.display = "flex";
        currentStep = 1;
        showStep(currentStep);
    }
}

function closeRequestModal() {
    document.getElementById("requestModal").style.display = "none";
}

function closeLeaveModal() {
    document.getElementById("leaveModal").style.display = "none";
}

function showStep(step) {

    document.querySelectorAll(".step").forEach(list => {
        list.classList.remove("active");
    });

    document.getElementById("step" + step)?.classList.add("active");

    document.querySelectorAll(".circle").forEach(el => {
        el.classList.remove("active");
    });

    for (let i = 1; i <= step; i++) {
        document.getElementById("circle" + i)?.classList.add("active");
    }
}

function nextStep() {

    if (currentStep === 1) {
        const type = document.getElementById("leaveType");

        if (!type.value) {
            alert("Please select leave type.");
            return;
        }
    }

    if (currentStep === 2) {

        const start = document.getElementById("startDate");
        const end = document.getElementById("endDate");
        const reason = document.getElementById("reason");

        if (!start.value || !end.value || !reason.value.trim()) {
            alert("Please complete all leave details.");
            return;
        }

        if (end.value < start.value) {
            alert("End date cannot be before start date.");
            return;
        }

        updateReview();
    }

    if (currentStep < 4) {
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep() {

    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

const startDate = document.getElementById("startDate");
const endDate = document.getElementById("endDate");
const duration = document.getElementById("duration");

startDate?.addEventListener("change", calculateDuration);
endDate?.addEventListener("change", calculateDuration);

function calculateDuration() {

    if (!startDate.value || !endDate.value) {
        duration.value = "";
        return;
    }

    const MS_IN_A_DAY = 1000 * 60 * 60 * 24;

    const start = new Date(startDate.value);
    const end = new Date(endDate.value);

    const daysBetween = Math.round((end - start) / MS_IN_A_DAY);

    if (daysBetween >= 0) {
        duration.value = (daysBetween + 1) + " day(s)";
    } 
    else {
        duration.value = "Invalid date";
    }
}

function updateReview() {

    document.getElementById("reviewType").textContent =
        document.getElementById("leaveType").value;

    document.getElementById("reviewFrom").textContent =
        document.getElementById("startDate").value;

    document.getElementById("reviewTo").textContent =
        document.getElementById("endDate").value;

    document.getElementById("reviewDuration").textContent =
        document.getElementById("duration").value;

    document.getElementById("reviewReason").textContent =
        document.getElementById("reason").value;
}
function openLeaveModal(id) {

    fetch("/dpz-eims/process/emp/viewLeaveProcess.php?id=" + id)
        .then(response => response.json())
        .then(data => {

            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById("modalType").textContent = data.leave_type || "";
            document.getElementById("modalStatus").textContent = data.status || "";
            document.getElementById("modalFiled").textContent = data.applied_at || "";
            document.getElementById("modalFrom").textContent = data.start_date || "";
            document.getElementById("modalTo").textContent = data.end_date || "";
            document.getElementById("modalDuration").textContent = (data.duration || 0) + " day(s)";
            document.getElementById("modalReason").textContent = data.reason || "";

            document.getElementById("leaveModal").style.display = "flex";
        })
        .catch(error => {
            console.error(error);
            alert("Unable to load leave request details.");
        });
}


const statusFilter = document.getElementById("statusFilter");
const yearFilter = document.getElementById("yearFilter");

function applyFilters() {

    const status = statusFilter?.value || "allRequest";
    const year = yearFilter?.value || new Date().getFullYear();

    window.location.href =
        "leaveRequest.php?status=" + status + "&year=" + year;
}

statusFilter?.addEventListener("change", applyFilters);
yearFilter?.addEventListener("change", applyFilters);

function goToPage(page) {

    const status = statusFilter?.value || "allRequest";
    const year = yearFilter?.value || new Date().getFullYear();

    window.location.href =
        "leaveRequest.php?page=" + page +
        "&status=" + status +
        "&year=" + year;
}


const leaveForm = document.getElementById("leaveForm");

leaveForm?.addEventListener("submit", function (event) {

    event.preventDefault();

    const button = leaveForm.querySelector('button[type="submit"]');

    button.disabled = true;
    button.textContent = "Submitting...";

    fetch("/dpz-eims/process/emp/leaveRequestProcess.php", {
        method: "POST",
        body: new FormData(leaveForm)
    })
    .then(response => response.json())
    .then(data => {

        alert(data.message);

        if (data.success) {
            closeRequestModal();
            leaveForm.reset();
            currentStep = 1;
            showStep(currentStep);
            location.reload();
        } else {
            button.disabled = false;
            button.textContent = "Submit Request";
        }
    })
    .catch(error => {

        console.error(error);
        alert("Something went wrong while submitting your leave request.");

        button.disabled = false;
        button.textContent = "Submit Request";
    });
});


// =========================
// CLOSE MODAL WHEN CLICKING OUTSIDE
// =========================

window.addEventListener("click", event => {

    if (event.target === document.getElementById("leaveModal")) {
        closeLeaveModal();
    }

    if (event.target === document.getElementById("requestModal")) {
        closeRequestModal();
    }
});


// Start
showStep(currentStep);