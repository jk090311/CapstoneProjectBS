document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit_data');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const sectionId = this.getAttribute('data-id');
            const sectionName = this.getAttribute('data-SectionName');
            const gradeLevel = this.getAttribute('data-GradeLevel');

            // Populate the modal fields
            document.getElementById('edit_section_id').value = sectionId;
            document.getElementById('edit_section_name').value = sectionName;
            document.getElementById('edit_section_year_level').value = gradeLevel;


            // Change action type to edit
            document.getElementById('action_type').value = 'edit';

            // Change button text and modal title
            document.getElementById('submitBtn').innerText = 'Update Section';
            document.getElementById('addSectionLabel').innerText = 'Edit Section';

            // Add a hidden input to store the section's original ID for identification in PHP
            let sectionIdInput = document.getElementById('section_id ');
            if (!sectionIdInput) {
                sectionIdInput = document.createElement('input');
                sectionIdInput.type = 'hidden';
                sectionIdInput.name = 'sectionId';
                sectionIdInput.id = 'sectionId';
                document.querySelector('form').appendChild(sectionIdInput);
            }
            sectionIdInput.value = this.getAttribute('data-id'); // Store the section's ID

            // Set the form action to adminSectionUpdate.php
            document.querySelector('form').action = '../PHP/adminSectionUpdate.php';
        });
    });

    // Handle modal close/hidden to reset form
    const modal = document.getElementById('addSection');
    modal.addEventListener('hidden.bs.modal', function () {
        // Reset form
        document.getElementById('action_type').value = 'add';
        document.getElementById('section_name').value = '';
        document.getElementById('section_year_level').value = '7'; // Default grade level

        // Remove hidden input field if it exists
        const sectionIdInput = document.getElementById('sectionId');
        if (sectionIdInput) {
            sectionIdInput.remove();
        }

        // Reset button text and modal title
        document.getElementById('submitBtn').innerText = 'Save Section';
        document.getElementById('addSectionLabel').innerText = 'Create New Section';

        // Reset form action to adminSectionAdd.php
        document.querySelector('form').action = '../PHP/adminSectionAdd.php';
    });
});