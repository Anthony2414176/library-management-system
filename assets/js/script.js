document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector(".menu-toggle");
    const navlinks = document.querySelectorAll(".nav-link");

    menuToggle.addEventListener("click", function () {
        navlinks.forEach(link => {
            link.classList.toggle("active"); // Loop through each nav link and toggle the class
        });
    });
});

// Automatically hide the message box after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
    const messageBox = document.getElementById("message-box");
    if (messageBox) {
        setTimeout(() => {
            messageBox.style.display = "none";
        }, 5000); // 5 seconds
    }
});