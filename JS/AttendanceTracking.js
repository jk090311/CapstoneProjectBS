function updateClock() {
    let now = new Date();

    let hours = now.getHours().toString().padStart(2, '0');
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    let minutes = now.getMinutes().toString().padStart(2, '0');
    let seconds = now.getSeconds().toString().padStart(2, '0');

    let options = { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' };
    let formattedDate = now.toLocaleDateString(undefined, options);

    document.getElementById("clock").innerText = `${hours}:${minutes}:${seconds}:${ampm}`;
    document.getElementById("date").innerText = formattedDate;
}

setInterval(updateClock, 1000);
updateClock();


function fetchAttendanceData() {
    console.log("Fetching updated attendance data...");

    fetch("/FinalCapstoneWebsite/PHP/fetchAttendance.php")
        .then(response => response.json())
        .then(data => {
            console.log("Updated Attendance Data:", data);

            let tableBody = document.getElementById("attendanceTable");

            tableBody.innerHTML = `
                <tr>
                    <th>RFID Number</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date Logged</th>
                </tr>
            `;

            data.forEach(row => {
                let newRow = document.createElement("tr");
                newRow.innerHTML = `
                    <td>${row.rfid_number}</td>
                    <td>${row.name}</td>
                    <td>${row.time_in || 'N/A'}</td>
                    <td>${row.time_out || 'N/A'}</td>
                    <td>${row.date_logged}</td>
                `;
                tableBody.appendChild(newRow);
            });
        })
        .catch(error => console.error("Error fetching attendance:", error));
}

// Auto-refresh every 5 seconds
setInterval(fetchAttendanceData, 2000);

// Load attendance when the page loads
document.addEventListener("DOMContentLoaded", fetchAttendanceData);
