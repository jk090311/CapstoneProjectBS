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
    // Handle Edit button click for Advisers
    const editButtons = document.querySelectorAll('.edit_data[data-bs-target="#addTeacher"]');
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

            // Add a hidden input to store the adviser's original name for identification in PHP
            let originalNameInput = document.getElementById('originalAdviserName');
            if (!originalNameInput) {
                originalNameInput = document.createElement('input');
                originalNameInput.type = 'hidden';
                originalNameInput.name = 'originalAdviserName';
                originalNameInput.id = 'originalAdviserName';
                document.querySelector('#addTeacher form').appendChild(originalNameInput);
            }
            originalNameInput.value = name;

            // Set the form action to adviserUpdate.php
            document.querySelector('#addTeacher form').action = '../PHP/adviserUpdate.php';
        });
    });

    // Handle Adviser modal close/hidden to reset form
    const modal = document.getElementById('addTeacher');
    modal.addEventListener('hidden.bs.modal', function () {
        // Reset form
        document.getElementById('action_type').value = 'add';
        document.getElementById('adviserFullName').value = '';
        document.getElementById('adviserContactNumber').value = '';
        document.getElementById('adviserGrLvl').value = '7';
        document.getElementById('adviserEmailAddress').value = '';
        document.getElementById('adviserPassword').value = '';
        document.getElementById('adviserSection').value = 'Aqua';

        // Remove hidden input field if exists
        const nameInput = document.getElementById('originalAdviserName');
        if (nameInput) {
            nameInput.remove();
        }

        // Reset button text
        document.getElementById('submitBtn').innerText = 'Register';
        document.getElementById('addTeacherLabel').innerText = 'Create New Teacher Account';
        console.log("Submitting function called. Proceeding to PHP");
        // Reset form action to adviserRegister.php
        document.querySelector('#addTeacher form').action = '../PHP/adviserRegister.php';
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

    // Handle Subject Teacher Edit button click - FIXED VERSION
    const editSubjectButtons = document.querySelectorAll('.edit_data[data-bs-target="#addSubjectTeacher"]');
    editSubjectButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log('Edit subject teacher button clicked'); // Debug log
            
            // Get data from data attributes
            const name = this.getAttribute('data-name');
            const contact = this.getAttribute('data-contact');
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');
            const subject1 = this.getAttribute('data-subject1');
            const subject2 = this.getAttribute('data-subject2');

            console.log('Data retrieved:', {name, contact, email, password, subject}); // Debug log

            // Fill form with existing data - use correct field names that match the form
            document.getElementById('subjectTeacherFullName').value = name || '';
            document.getElementById('subjectTeacherContactNumber').value = contact || '';
            document.getElementById('subjectTeacherEmailAddress').value = email || '';
            document.getElementById('subjectTeacherPassword').value = password || '';
            // If the modal uses select elements for subject1/subject2, pre-select them
            const sel1 = document.getElementById('subjectTeacherSubject1');
            const sel2 = document.getElementById('subjectTeacherSubject2');
            if (sel1) sel1.value = subject1 || '';
            if (sel2) sel2.value = subject2 || '';

            // Set action type to edit - find the hidden input in the subject teacher form
            const actionTypeInput = document.querySelector('#addSubjectTeacher input[name="action_type"]');
            if (actionTypeInput) {
                actionTypeInput.value = 'edit';
            }

            // Add hidden input for original name
            let originalNameInput = document.getElementById('originalstName');
            if (!originalNameInput) {
                originalNameInput = document.createElement('input');
                originalNameInput.type = 'hidden';
                originalNameInput.name = 'originalstName';
                originalNameInput.id = 'originalstName';
                document.querySelector('#addSubjectTeacher form').appendChild(originalNameInput);
            }
            originalNameInput.value = name;

            // Update modal UI for edit mode
            document.getElementById('addSubjectTeacherLabel').innerText = 'Edit Subject Teacher Account';
            const submitButton = document.querySelector('#addSubjectTeacher button[type="submit"]');
            if (submitButton) {
                submitButton.innerText = 'Update';
            }

            // Change form action
            const subjectForm = document.querySelector('#addSubjectTeacher form');
            if (subjectForm) {
                subjectForm.action = '../PHP/subjectTeacherUpdate.php';
            }
        });
    });

    // Prevent selecting the same subject in both selects (client-side guard)
    const s1 = document.getElementById('subjectTeacherSubject1');
    const s2 = document.getElementById('subjectTeacherSubject2');
    if (s1 && s2) {
        function preventDuplicate() {
            if (s1.value && s1.value === s2.value) {
                // clear the second select if it matches the first
                s2.value = '';
                alert('Please select a different subject for Subject 2.');
            }
        }
        s1.addEventListener('change', preventDuplicate);
        s2.addEventListener('change', preventDuplicate);
    }

    // Handle Subject Teacher modal close/hidden to reset form
    const subjectModal = document.getElementById('addSubjectTeacher');
    if (subjectModal) {
        subjectModal.addEventListener('hidden.bs.modal', function () {
            // Reset form fields
            document.getElementById('subjectTeacherFullName').value = '';
            document.getElementById('subjectTeacherContactNumber').value = '';
            document.getElementById('subjectTeacherEmailAddress').value = '';
            document.getElementById('subjectTeacherPassword').value = '';
            document.getElementById('subjectTeacherSubject').value = '';

            // Reset action type
            const actionTypeInput = document.querySelector('#addSubjectTeacher input[name="action_type"]');
            if (actionTypeInput) {
                actionTypeInput.value = 'add';
            }

            // Remove hidden input if exists
            const nameInput = document.getElementById('originalstName');
            if (nameInput) {
                nameInput.remove();
            }

            // Reset modal title and button text
            document.getElementById('addSubjectTeacherLabel').innerText = 'Create New Subject Teacher Account';
            const submitButton = document.querySelector('#addSubjectTeacher button[type="submit"]');
            if (submitButton) {
                submitButton.innerText = 'Register';
            }

            // Reset form action
            const subjectForm = document.querySelector('#addSubjectTeacher form');
            if (subjectForm) {
                subjectForm.action = '../PHP/subjectTeacherRegister.php';
            }
        });
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

// Add function to remove subject teacher
function removeSubjectTeacher(stFullName) {
    if (confirm("Are you sure you want to remove this subject teacher?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../PHP/subjectTeacherRemove.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log("Server response:", xhr.responseText); // Debug line
                alert(xhr.responseText);
                if (xhr.responseText.includes("successfully")) {
                    location.reload();
                }
            } else {
                alert("An error occurred while removing the subject teacher.");
            }
        };

        // Debug line
        console.log("Sending request with stFullName:", stFullName);
        xhr.send("stFullName=" + encodeURIComponent(stFullName));
    }
}