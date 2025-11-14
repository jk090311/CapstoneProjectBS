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
            
            console.log('Edit data:', { id, name, contact, email });
            
            // Populate the basic fields
            document.getElementById('edit_stID').value = id || '';
            document.getElementById('edit_stFullName').value = name || '';
            document.getElementById('edit_stContactNumber').value = contact || '';
            document.getElementById('edit_stEmail').value = email || '';
            document.getElementById('edit_stPassword').value = ''; // Clear password field
            
            // Clear existing subject assignments
            if (typeof clearSubjectAssignments === 'function') {
                clearSubjectAssignments();
            }
            
            // Fetch subject assignments from server
            if (id) {
                fetch(`../PHP/getSubjectTeacherAssignments.php?stID=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched assignments:', data);
                        
                        if (data.success && data.assignments && data.assignments.length > 0) {
                            data.assignments.forEach(assignment => {
                                if (typeof addSubjectAssignmentRow === 'function') {
                                    addSubjectAssignmentRow(
                                        assignment.grade_level || '',
                                        assignment.section_id || '',
                                        assignment.subject_id || ''
                                    );
                                }
                            });
                        } else {
                            // If no assignments, add one empty row
                            if (typeof addSubjectAssignmentRow === 'function') {
                                addSubjectAssignmentRow('', '', '');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching assignments:', error);
                        // Add one empty row on error
                        if (typeof addSubjectAssignmentRow === 'function') {
                            addSubjectAssignmentRow('', '', '');
                        }
                    });
            } else {
                // No ID, add one empty row
                if (typeof addSubjectAssignmentRow === 'function') {
                    addSubjectAssignmentRow('', '', '');
                }
            }
            
            // Show the modal
            editModal.show();
        });
    });
});