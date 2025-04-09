function fetchSections() {
    const gradeLevel = document.getElementById("adviserGrLvl").value;
    const sectionDropdown = document.getElementById("adviserSection");

    // Clear the current options
    sectionDropdown.innerHTML = '<option value="">Select Section</option>';

    if (gradeLevel) {
        // Fetch sections via AJAX
        fetch("../PHP/getSections.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "grade_level=" + encodeURIComponent(gradeLevel)
        })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                data.forEach(section => {
                    const option = document.createElement("option");
                    option.value = section;
                    option.textContent = section;
                    sectionDropdown.appendChild(option);
                });
            } else {
                console.error("Invalid response:", data);
            }
        })
        .catch(error => console.error("Error fetching sections:", error));
    }
}
