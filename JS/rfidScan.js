document.addEventListener("DOMContentLoaded", function () {
    const rfidInput = document.getElementById("rfidInput");

    if (rfidInput) {
        rfidInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                const rfidNumber = rfidInput.value.trim();

                if (rfidNumber !== "") {
                    sendRFIDToServer(rfidNumber);
                    rfidInput.value = "";
                }
            }
        });
    }
});

function sendRFIDToServer(rfidNumber) {
    fetch("/FinalCapstoneWebsite/PHP/insertAttendance.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `rfid_number=${rfidNumber}`
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
        
        if (data.status === "success") {
            alert(data.message + ` (${data.name})`);
            updateAttendanceTable();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Fetch Error:", error));
}

function updateAttendanceTable() {
    fetch("/FinalCapstoneWebsite/PHP/fetchAttendance.php")
        .then(response => response.json())
        .then(data => {
            const table = document.getElementById("attendanceTable");

            table.innerHTML = `
                <tr>
                    <th>RFID Number</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date Logged</th>
                </tr>`;

            data.forEach(entry => {
                const row = table.insertRow();
                row.innerHTML = `
                    <td>${entry.rfid_number}</td>
                    <td>${entry.student_name || "Unknown"}</td>
                    <td>${entry.time_in || "N/A"}</td>
                    <td>${entry.time_out || "N/A"}</td>
                    <td>${entry.date_logged || "N/A"}</td>`;
            });
        })
        .catch(error => console.error("Error fetching attendance:", error));
}

// Refresh on page load
updateAttendanceTable();
