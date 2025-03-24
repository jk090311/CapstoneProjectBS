function loadAttendance() {
    const selectedDate = document.getElementById('filterDate').value;
    window.location.href = "attendanceTracking.php?date=" + selectedDate;
}