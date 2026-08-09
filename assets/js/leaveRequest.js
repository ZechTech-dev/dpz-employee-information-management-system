console.log("Leave Request JS Loaded"); //pang check lang hawhwahwahwah

function closeLeaveModal(){
    document.getElementById("leaveModal").style.display="none";
}

function openRequestModal() {
    const modal = document.getElementById("requestModal");

    if (modal) {
        modal.style.display = "flex";
    }
}

function closeRequestModal() {
    const modal = document.getElementById("requestModal");

    if (modal) {
        modal.style.display = "none";
    }
}

let currentStep = 1;

function showStep(step) {

    document.querySelectorAll(".step").forEach(function (el) {
        el.classList.remove("active");
    });

    const current = document.getElementById("step" + step);

    if (current) {
        current.classList.add("active");
    }

    document.querySelectorAll(".circle").forEach(function (el) {
        el.classList.remove("active");
    });

    for (let i = 1; i <= step; i++) {

        const circle = document.getElementById("circle" + i);

        if (circle) {
            circle.classList.add("active");
        }
    }
}

function nextStep() {

    if (currentStep === 1) {

        const type = document.getElementById("leaveType").value;

        if (type === "") {
            alert("Please select leave type");
            return;
        }
    }

    if (currentStep === 2) {
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

const start = document.getElementById("startDate");
const end = document.getElementById("endDate");
const duration = document.getElementById("duration");

if (start && end) {

    start.addEventListener("change", calculateDuration);
    end.addEventListener("change", calculateDuration);
}


function calculateDuration() {

    if (!start.value || !end.value) {
        return;
    }

    const startDate = new Date(start.value);
    const endDate = new Date(end.value);

    const difference =
        (endDate - startDate) / (1000 * 60 * 60 * 24);

    if (difference >= 0) {

        duration.value = (difference + 1) + " day(s)";

    } else {

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

    fetch("/dpz-eims/process/viewLeaveProcess.php?id=" + id)

    .then(response => response.json())
    .then(data => {
        document.getElementById("modalType").innerHTML = data.leave_type;
        document.getElementById("modalStatus").innerHTML = data.status;
        document.getElementById("modalFiled").innerHTML = data.applied_at;
        document.getElementById("modalFrom").innerHTML = data.start_date;
        document.getElementById("modalTo").innerHTML = data.end_date;
        document.getElementById("modalDuration").innerHTML = data.duration + " day(s)";
        document.getElementById("modalReason").innerHTML = data.reason;
        document.getElementById("leaveModal").style.display = "flex";
    })

    .catch(error => {
        console.log(error);
    });

}