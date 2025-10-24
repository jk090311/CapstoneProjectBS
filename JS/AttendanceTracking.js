function updateClock() {
    let now = new Date();

    let hours = now.getHours().toString().padStart(2, '0');
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12;
    let minutes = now.getMinutes().toString().padStart(2, '0');
    let seconds = now.getSeconds().toString().padStart(2, '0');

    let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    let formattedDate = now.toLocaleDateString(undefined, options);

    document.getElementById("clock").innerText = `${hours}:${minutes}:${seconds}:${ampm}`;
    document.getElementById("date").innerText = formattedDate;
}

setInterval(updateClock, 1000);
updateClock();


function fetchAttendanceData(dateFilter = null) {
    // Get date from the filter input or use provided parameter, default to today
    const filterDate = dateFilter || document.getElementById("filterDate")?.value || new Date().toISOString().split('T')[0];
    
    console.log("Fetching attendance data for date:", filterDate); // Debug log
    
    fetch(`../PHP/fetchAttendance.php?date=${filterDate}`)
        .then(response => response.json())
        .then(data => {
            console.log("Received data:", data); // Debug log
            let tableBody = document.getElementById("attendanceTable");
            tableBody.innerHTML = `
                <tr>
                    <th>RFID Number</th>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date Logged</th>
                </tr>
            `;
            
            if (data.length === 0) {
                // Show "No records found" message when no data
                let noDataRow = document.createElement("tr");
                noDataRow.innerHTML = `
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666; font-style: italic;">
                        No attendance records found for ${filterDate}
                    </td>
                `;
                tableBody.appendChild(noDataRow);
            } else {
                // Show actual data
                data.forEach(row => {
                    let newRow = document.createElement("tr");
                    newRow.innerHTML = `
                        <td>${row.rfid_number}</td>
                        <td>${row.name}</td>
                        <td>N/A</td>
                        <td>${row.time_in || 'N/A'}</td>
                        <td>${row.time_out || 'N/A'}</td>
                        <td>${row.date_logged}</td>
                    `;
                    tableBody.appendChild(newRow);
                });
            }
        })
        .catch(error => {
            console.error("Error fetching attendance:", error);
            let tableBody = document.getElementById("attendanceTable");
            tableBody.innerHTML = `
                <tr>
                    <th>RFID Number</th>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Date Logged</th>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #d32f2f;">
                        Error loading attendance data. Please try again.
                    </td>
                </tr>
            `;
        });
}

// Function to load attendance data when date filter changes
function loadAttendanceData() {
    const filterDate = document.getElementById("filterDate").value;
    console.log("Date filter changed to:", filterDate); // Debug log
    if (filterDate) {
        fetchAttendanceData(filterDate);
    } else {
        fetchAttendanceData(); // Load today's data if no date selected
    }
}

// Auto-refresh every 5 seconds, but only if user isn't actively using the date filter
setInterval(() => {
    // Only refresh if the date filter doesn't have focus and we're showing today's data
    const dateFilter = document.getElementById("filterDate");
    const today = new Date().toISOString().split('T')[0];
    
    if (dateFilter && !dateFilter.matches(':focus') && dateFilter.value === today) {
        fetchAttendanceData();
    }
}, 5000);

// Load attendance when the page loads and set up date filter
document.addEventListener("DOMContentLoaded", function() {
    // Set today's date as default in the date filter
    const today = new Date().toISOString().split('T')[0];
    const dateFilter = document.getElementById("filterDate");
    if (dateFilter) {
        dateFilter.value = today;
    }
    
    // Load today's attendance data
    fetchAttendanceData();
});
