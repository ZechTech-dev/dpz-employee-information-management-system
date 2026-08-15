const timeInBtn = document.getElementById("timeInBtn");
const breakInBtn = document.getElementById("breakInBtn");
const breakOutBtn = document.getElementById("breakOutBtn");
const timeOutBtn = document.getElementById("timeOutBtn");

const timeInDisplay = document.getElementById("timeInDisplay");
const breakInDisplay = document.getElementById("breakInDisplay");
const breakOutDisplay = document.getElementById("breakOutDisplay");
const timeOutDisplay = document.getElementById("timeOutDisplay");

const currentDate = document.getElementById("currentDate");
const currentTime = document.getElementById("currentTime");

const table = document.getElementById("attendanceTableBody");


function sendAttendance(action) {

    return fetch("/dpz-eims/process/emp/attendanceProcess.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: "action=" + action

    }).then(response => response.json());
}


function addAttendanceRow(data) {

    const row = document.createElement("tr");

    row.innerHTML = `
        <td>${data.date}</td>
        <td>${new Date(data.date).toLocaleDateString("en-US", {
            weekday: "long"
        })}</td>
        <td>${data.time_in}</td>
        <td>${data.time_out}</td>
        <td>${data.break_hours}</td>
        <td>${data.total_hours}</td>
        <td>
            <span class="status present">
                ${data.status}
            </span>
        </td>
    `;

    table.prepend(row);
}


timeInBtn.addEventListener("click", function () {

    sendAttendance("time_in")

        .then(data => {

            if (!data.success) {
                alert(data.message);
                return;
            }

            timeInDisplay.textContent = data.time_in;

            timeInBtn.textContent = "TIMED IN";
            timeInBtn.disabled = true;

            breakInBtn.disabled = false;

        })

        .catch(error => {

            console.error(error);

            alert("Something went wrong while timing in.");

        });

});


breakInBtn.addEventListener("click", function () {

    sendAttendance("break_in")

        .then(data => {

            if (!data.success) {
                alert(data.message);
                return;
            }

            breakInDisplay.textContent = data.break_in;

            breakInBtn.textContent = "ON BREAK";

            breakInBtn.disabled = true;

            breakOutBtn.disabled = false;

        })

        .catch(error => {

            console.error(error);

            alert("Something went wrong while starting the break.");

        });

});


breakOutBtn.addEventListener("click", function () {

    sendAttendance("break_out")

        .then(data => {

            if (!data.success) {
                alert(data.message);
                return;
            }

            breakOutDisplay.textContent = data.break_out;

            breakOutBtn.textContent = "BREAK ENDED";

            breakOutBtn.disabled = true;

            timeOutBtn.disabled = false;

        })

        .catch(error => {

            console.error(error);

            alert("Something went wrong while ending the break.");

        });

});


timeOutBtn.addEventListener("click", function () {

    sendAttendance("time_out")

        .then(data => {

            if (!data.success) {
                alert(data.message);
                return;
            }

            timeOutDisplay.textContent = data.time_out;

            timeOutBtn.textContent = "TIMED OUT";
            timeOutBtn.disabled = true;

            breakInBtn.disabled = true;
            breakOutBtn.disabled = true;

            addAttendanceRow(data);

        })

        .catch(error => {

            console.error(error);

            alert("Something went wrong while timing out.");

        });

});


function updateDateTime() {

    const now = new Date();

    currentDate.textContent = now.toLocaleDateString("en-PH", {
        weekday: "long",
        month: "long",
        day: "numeric",
        year: "numeric"
    });

    currentTime.textContent = now.toLocaleTimeString("en-PH", {
        hour: "numeric",
        minute: "2-digit",
        second: "2-digit"
    });
}


updateDateTime();

setInterval(updateDateTime, 1000);