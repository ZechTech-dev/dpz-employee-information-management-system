const processUrl = "/dpz-eims/process/manager/leaveMProcess.php";

const search = document.getElementById("leaveSearch");

const modal = document.getElementById("decisionModal");
const requestId = document.getElementById("requestId");
const decision = document.getElementById("decision");
const decisionReason = document.getElementById("decisionReason");

const employeeName = document.getElementById("modalEmployeeName");
const leaveType = document.getElementById("modalLeaveType");
const leaveDates = document.getElementById("modalLeaveDates");
const employeeReason = document.getElementById("modalEmployeeReason");

document.querySelectorAll(".review-button").forEach(button => {

    button.addEventListener("click", function () {

        requestId.value = this.dataset.id;

        employeeName.textContent = this.dataset.name;

        leaveType.textContent = this.dataset.leaveType;

        leaveDates.textContent =
            formatDate(this.dataset.start) +
            " to " +
            formatDate(this.dataset.end);

        employeeReason.textContent =
            this.dataset.reason || "No reason provided.";

        decisionReason.value = "";

        modal.classList.add("show");

    });

});

document.querySelectorAll(".view-button").forEach(button => {

    button.addEventListener("click", function () {

        const status = this.dataset.status;
        const reason = this.dataset.decisionReason;

        document.getElementById("viewDecisionStatus").textContent = status;

        document.getElementById("viewDecisionReason").textContent =
            reason || "No decision reason was provided.";

        document.getElementById("viewDecisionModal").classList.add("show");

    });

});

function formatDate(date) {

    return new Date(
        date.replace(" ", "T")
    ).toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric"
    });

}

function submitDecision(value) {

    if (
        value === "Rejected" &&
        decisionReason.value.trim() === ""
    ) {

        alert("Please provide a reason when rejecting.");

        return;

    }

    decision.value = value;

    const formData = new FormData(
        document.getElementById("decisionForm")
    );

    fetch(processUrl, {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            if (!data.success) {

                alert(data.message);

                return;

            }

            closeDecision();

            alert(data.message);

            location.reload();

        })
        .catch(() => {

            alert("Something went wrong.");

        });

}

function closeDecision() {

    modal.classList.remove("show");

}

function closeView() {

    document
        .getElementById("viewDecisionModal")
        .classList.remove("show");

}

search.addEventListener("input", function () {

    const value = this.value.toLowerCase().trim();

    document
        .querySelectorAll("#leaveTableBody tr")
        .forEach(row => {

            const text = row.textContent.toLowerCase();

            row.style.display =
                text.includes(value) ? "" : "none";

        });

});

document
    .getElementById("approveLeave")
    .addEventListener("click", function () {

        submitDecision("Approved");

    });

document
    .getElementById("rejectLeave")
    .addEventListener("click", function () {

        submitDecision("Rejected");

    });

document
    .getElementById("closeDecisionModal")
    .addEventListener("click", closeDecision);

document
    .getElementById("cancelDecision")
    .addEventListener("click", closeDecision);

document
    .getElementById("decisionOverlay")
    .addEventListener("click", closeDecision);

document
    .getElementById("closeViewDecision")
    .addEventListener("click", closeView);

document
    .getElementById("doneViewDecision")
    .addEventListener("click", closeView);

document
    .getElementById("viewDecisionOverlay")
    .addEventListener("click", closeView);