document.getElementById("uploadPic").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("profileImg").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    let rfidInput = document.getElementById("rfidNo");
    let studentForm = document.getElementById("studentForm");
    let submitButton = document.getElementById("submit");

    // Prevent Enter key from submitting the form when scanning RFID
    rfidInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            this.value = event.target.value; // Copy RFID value to input
            event.preventDefault(); // Stops RFID from triggering form submission
            console.log("RFID Scanned, but form will NOT submit.");
        }
    });

    // Allow form submission ONLY when clicking the Submit button
    submitButton.addEventListener("click", function () {
        console.log("Submit button clicked, form will be submitted.");
        studentForm.submit(); // Manually submit the form
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

