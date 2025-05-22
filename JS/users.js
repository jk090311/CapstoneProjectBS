const searchBar = document.querySelector(".users .search input"),
    searchBtn = document.querySelector(".users .search button"),
    usersList = document.querySelector(".users .users-list"),
    searchText = document.querySelector(".users .search .text"); // Select the text element

searchBtn.onclick = () => {
    // Toggle the visibility of the search bar
    searchBar.classList.toggle("active");

    if (searchBar.classList.contains("active")) {
        searchBar.focus(); // Focus on the search bar when visible
        searchText.style.display = "none"; // Hide the "Select a user to chat" text
    } else {
        searchBar.value = ""; // Clear the search bar when hidden
        searchText.style.display = "block"; // Show the text again
    }
};

searchBar.onkeyup = () => {
    let searchTerm = searchBar.value;
    if (searchTerm != "") {
        searchBar.classList.add("active");
    } else {
        searchBar.classList.remove("active");
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../PHP/search.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                usersList.innerHTML = data;
            }
        }
    };
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + searchTerm);
};

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "../PHP/users.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (!searchBar.classList.contains("active")) {
                    usersList.innerHTML = data;
                }
            }
        }
    };
    xhr.send();
}, 500);