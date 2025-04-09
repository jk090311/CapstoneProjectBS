document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('addTeacher'));

    // Function to show the selected part of the modal
    function showPart(partNumber) { 
        const parts = document.querySelectorAll('.part');
        parts.forEach(part => part.style.display = 'none');
        document.getElementById('part' + partNumber).style.display = 'block';
    }

    // Show the first part by default when modal opens
    document.getElementById('addTeacher').addEventListener('show.bs.modal', function () {
        showPart(1);
    });

    // Make showPart globally accessible
    window.showPart = showPart;
});



document.addEventListener('DOMContentLoaded', function () {
    // Handle Edit button click
    const editButtons = document.querySelectorAll('.edit_data');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Get data from data attributes
            const name = this.getAttribute('data-name');
            const contact = this.getAttribute('data-contact');
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');
            const grade = this.getAttribute('data-grade');
            const section = this.getAttribute('data-section');

            // Populate the form
            document.getElementById('adviserFullName').value = name;
            document.getElementById('adviserContactNumber').value = contact;
            document.getElementById('adviserEmailAddress').value = email;
            document.getElementById('adviserPassword').value = password;
            document.getElementById('adviserGrLvl').value = grade;
            document.getElementById('adviserSection').value = section;

            // Change action type to edit
            document.getElementById('action_type').value = 'edit';

            // Change button text
            document.getElementById('submitBtn').innerText = 'Update';
            document.getElementById('addTeacherLabel').innerText = 'Edit Teacher Account';

            /// Add a hidden input to store the adviser's original name for identification in PHP
            let originalNameInput = document.getElementById('originalAdviserName');
            if (!originalNameInput) {
                originalNameInput = document.createElement('input');
                originalNameInput.type = 'hidden';
                originalNameInput.name = 'originalAdviserName';
                originalNameInput.id = 'originalAdviserName';
                document.querySelector('form').appendChild(originalNameInput);
            }
            originalNameInput.value = name;  // Store the original adviser's name
            

            // Set the form action to adviserUpdate.php
            document.querySelector('form').action = '../PHP/adviserUpdate.php';
        });
    });

    // Handle modal close/hidden to reset form
    const modal = document.getElementById('addTeacher');
    modal.addEventListener('hidden.bs.modal', function () {
        // Reset form
        document.getElementById('action_type').value = 'add';
        document.getElementById('adviserFullName').value = '';
        document.getElementById('adviserContactNumber').value = '';
        document.getElementById('adviserGrLvl').value = '7';
        document.getElementById('adviserEmailAddress').value = '';
        document.getElementById('adviserPassword').value ='';
        document.getElementById('adviserSection').value = 'Aqua';

        // Remove hidden input field if exists
        const nameInput = document.getElementById('adviser_name_input');
        if (nameInput) {
            nameInput.remove();
        }

        // Reset button text
        document.getElementById('submitBtn').innerText = 'Register';
        document.getElementById('addTeacherLabel').innerText = 'Create New Teacher Account';

        // Reset form action to adviserRegister.php
        document.querySelector('form').action = '../PHP/adviserRegister.php';
    });
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("adviserGrLvl").addEventListener("change", filterSections);
    });
    
    function filterSections() {
        const gradeLevel = document.getElementById("adviserGrLvl").value;
        const sectionDropdown = document.getElementById("adviserSection");
    
        // Clear current options
        sectionDropdown.innerHTML = '<option value="">Select Section</option>';
    
        if (gradeLevel) {
            // Fetch sections using AJAX
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
            .catch(error => console.error("AJAX error:", error));
        }
    }
});

function removeAdviser(adviserFullName) {
    if (confirm("Are you sure you want to remove this adviser?")) {
        // Send an AJAX request to remove the adviser
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../PHP/adviserRemove.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText); // Show success or error message
                location.reload(); // Reload the page to update the table
            } else {
                alert("An error occurred while removing the adviser.");
            }
        };

        xhr.send("adviserFullName=" + encodeURIComponent(adviserFullName));
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const nextBtn = document.getElementById("nextBtn");
    const part1 = document.getElementById("part1");
    const part2 = document.getElementById("part2");

    nextBtn.addEventListener("click", function () {
        // Hide part 1 and show part 2
        part1.style.display = "none";
        part2.style.display = "block";
    });
});
