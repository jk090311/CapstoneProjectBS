document.addEventListener('DOMContentLoaded', function() {
    // Handle Edit button click
    const editButtons = document.querySelectorAll('.edit_data');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get data from data attributes
            const name = this.getAttribute('data-name');
            const contact = this.getAttribute('data-contact');
            const grade = this.getAttribute('data-grade');
            const section = this.getAttribute('data-section');
            
            // Populate the form
            document.getElementById('adviserFullName').value = name;
            document.getElementById('adviserContactNumber').value = contact;
            document.getElementById('adviserGrLvl').value = grade;
            document.getElementById('adviserSection').value = section;
            
            // Change action type to edit
            document.getElementById('action_type').value = 'edit';
            
            // Change button text
            document.getElementById('submitBtn').innerText = 'Update';
            document.getElementById('addTeacherLabel').innerText = 'Edit Teacher Account';
        });
    });
    
    // Handle modal close/hidden to reset form
    const modal = document.getElementById('addTeacher');
    modal.addEventListener('hidden.bs.modal', function() {
        // Reset form
        document.getElementById('action_type').value = 'add';
        document.getElementById('adviserFullName').value = '';
        document.getElementById('adviserContactNumber').value = '';
        document.getElementById('adviserGrLvl').value = '7';
        document.getElementById('adviserSection').value = 'Aqua';
        
        // Reset button text
        document.getElementById('submitBtn').innerText = 'Register';
        document.getElementById('addTeacherLabel').innerText = 'Create New Teacher Account';
    });
    
    // Handle Remove button click
    // Handle Remove button click
const removeButtons = document.querySelectorAll('.btn-remove');
removeButtons.forEach(button => {
    button.addEventListener('click', function() {
        if(confirm('Are you sure you want to remove this adviser?')) {
            const adviserName = this.getAttribute('data-id'); // Get the name from the button
            console.log("Removing adviser with Name:", adviserName); // Debug log

            // Create and submit a form programmatically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../PHP/adviserRemove.php'; // Ensure this path is correct

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'adviserFullName';
            nameInput.value = adviserName;

            form.appendChild(nameInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});

});