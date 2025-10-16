document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal with proper options
    const editModalElement = document.getElementById('editSubjectTeacher');
    if (!editModalElement) return;

    const editModal = new bootstrap.Modal(editModalElement, {
        backdrop: 'static',
        keyboard: true
    });

    // Handle modal cleanup when hidden
    editModalElement.addEventListener('hidden.bs.modal', function() {
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    });

    // Add click event listeners to all edit buttons
    const editButtons = document.querySelectorAll('.edit_subject_teacher');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get data from button attributes
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const contact = this.getAttribute('data-contact');
            const email = this.getAttribute('data-email');
            const gradelevel = this.getAttribute('data-gradelevel');
            const section = this.getAttribute('data-section');
            const subject = this.getAttribute('data-subject');
            
            // Populate the edit modal fields
            document.getElementById('edit_stID').value = id || '';
            document.getElementById('edit_stFullName').value = name || '';
            document.getElementById('edit_stContactNumber').value = contact || '';
            document.getElementById('edit_stEmail').value = email || '';
            document.getElementById('edit_stPassword').value = ''; // Clear password field
            
            // Set the select fields
            const gradeLvlSelect = document.getElementById('edit_stGradelvl');
            if (gradeLvlSelect && gradelevel) {
                gradeLvlSelect.value = gradelevel;
            }
            
            const sectionSelect = document.getElementById('edit_stSection');
            if (sectionSelect && section) {
                sectionSelect.value = section;
            }
            
            const subjectSelect = document.getElementById('edit_stSubject');
            if (subjectSelect && subject) {
                subjectSelect.value = subject;
            }
            
            // Show the modal
            editModal.show();
        });
    });
});