session_start();

const form = document.querySelector(".typing-area"),
    inputField = form.querySelector(".input-field"),
    sendBtn = form.querySelector("button"),
    chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
    e.preventDefault(); // Prevent form submission
};

sendBtn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../PHP/insert-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                inputField.value = ""; // Clear the input field
                scrollToBottom(); // Scroll to the bottom after sending a message
            }
        }
    };
    let formData = new FormData(form);
    formData.append("outgoing_id", document.querySelector("input[name='outgoing_id']").value);
    formData.append("incoming_id", document.querySelector("input[name='incoming_id']").value);
    xhr.send(formData); // Send the form data
};

chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
};

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../PHP/get-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                chatBox.innerHTML = data; // Update the chat box with new messages
                if (!chatBox.classList.contains("active")) {
                    scrollToBottom(); // Scroll to the bottom if the user is not actively scrolling
                }
            }
        }
    };
    let formData = new FormData(form);
    xhr.send(formData); // Send the form data
}, 500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight; // Correctly scroll to the bottom of the chat box
}