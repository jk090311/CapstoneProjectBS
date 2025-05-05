document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('addStudentModal'));

    // Function to show the selected part of the modal
    function showPart(partNumber) { 
        const parts = document.querySelectorAll('.part');
        parts.forEach(part => part.style.display = 'none');
        document.getElementById('part' + partNumber).style.display = 'block';
    }

    // Show the first part by default when modal opens
    document.getElementById('addStudentModal').addEventListener('show.bs.modal', function () {
        showPart(1);
    });

    // Make showPart globally accessible
    window.showPart = showPart;
});




document.addEventListener('DOMContentLoaded', function () {
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const firstName = this.getAttribute('data-id');

            if (confirm(`Are you sure you want to remove the student: ${firstName}?`)) {
                fetch('../PHP/studentRemove.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `first_name=${encodeURIComponent(firstName)}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Refreshes the page to show the updated list
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

document.querySelectorAll('.edit_data').forEach(button => {
    button.addEventListener('click', function() {
        const firstName = this.getAttribute('data-firstname');
        const middleName = this.getAttribute('data-middlename');
        const lastName = this.getAttribute('data-lastname');
        const contact = this.getAttribute('data-contact');
        const grade = this.getAttribute('data-grade');
        const section = this.getAttribute('data-section');

        document.getElementById('originalFirstName').value = firstName;
        document.getElementById('originalMiddleName').value = middleName;
        document.getElementById('originalLastName').value = lastName;

        document.getElementById('edit_studentFirstName').value = firstName;
        document.getElementById('edit_studentMiddleName').value = middleName;
        document.getElementById('edit_studentLastName').value = lastName;
        document.getElementById('edit_studentContactNumber').value = contact;
        document.getElementById('edit_studentGrLvl').value = grade;
        document.getElementById('edit_studentSection').value = section;
    });
});

function fetchSectionDetails(section) {
    if (section === "") {
        document.getElementById("section_grade_level").value = "";
        document.getElementById("section_year_start_level").value = "";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetchSectionDetails.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            document.getElementById("section_grade_level").value = response.section_grade_level || "";
            document.getElementById("section_year_start_level").value = response.section_year_start_level || "";
        }
    };
    xhr.send("section=" + encodeURIComponent(section));
}

    function filterByGender(sex) {
        const urlParams = new URLSearchParams(window.location.search);
        if (sex) {
            urlParams.set('sex', sex); // Set the 'sex' parameter if a value is selected
        } else {
            urlParams.delete('sex'); // Remove the 'sex' parameter if "All" is selected
        }
        const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.location.href = newUrl; // Update the URL and reload the page
    }

    // Pre-select the current filter value in the dropdown
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const currentSex = urlParams.get('sex') || '';
        document.getElementById('sexFilter').value = currentSex;
    });
