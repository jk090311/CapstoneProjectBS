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
});
