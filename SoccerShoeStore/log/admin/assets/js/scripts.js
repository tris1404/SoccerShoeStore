document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".toggle-btn");
    const sidebar = document.querySelector(".sidebar");
    const mainContent = document.querySelector(".main-content");
    const navbar = document.querySelector(".navbar");

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("closed");
        mainContent.classList.toggle("expanded");
        navbar.classList.toggle("expanded");
    });
});
