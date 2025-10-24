document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal with proper options
    const editModal = new bootstrap.Modal(document.getElementById('editSubjectTeacher'), {
        backdrop: true,
        keyboard: true,
        focus: true
    });

    // Add click event listeners to all edit buttons
    const editButtons = document.querySelectorAll('.edit_data');
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
            document.getElementById('edit_stID').value = id;
            document.getElementById('edit_stFullName').value = name;
            document.getElementById('edit_stContactNumber').value = contact;
            document.getElementById('edit_stEmail').value = email;
            document.getElementById('edit_stPassword').value = ''; // Clear password field
            
            // Set the select fields
            const gradeLvlSelect = document.getElementById('edit_stGradelvl');
            if (gradeLvlSelect) {
                gradeLvlSelect.value = gradelevel;
            }
            
            const sectionSelect = document.getElementById('edit_stSection');
            if (sectionSelect) {
                sectionSelect.value = section;
            }
            
            const subjectSelect = document.getElementById('edit_stSubject');
            if (subjectSelect) {
                subjectSelect.value = subject;
            }
            
            // Show the modal
            editModal.show();
        });
    });

    // Add event listener for modal hidden event
    const editModalElement = document.getElementById('editSubjectTeacher');
    editModalElement.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        const modalBackdrops = document.getElementsByClassName('modal-backdrop');
        while(modalBackdrops.length > 0) {
            modalBackdrops[0].parentNode.removeChild(modalBackdrops[0]);
        }
    });
});