(function () {
    const logoutBtn = document.getElementById("confirmLogout");

    if (!logoutBtn) return;

    logoutBtn.addEventListener("click", function () {
        localStorage.removeItem("token");
        localStorage.removeItem("user");

        window.location.href = "/frontend/views/login.html";
    });
})();
