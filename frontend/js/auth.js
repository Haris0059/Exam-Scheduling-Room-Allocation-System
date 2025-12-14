(function () {
    const token = localStorage.getItem("token");
    
    if (!token) {
        window.location.href = "/Exam-Scheduling-Room-Allocation-System/frontend/views/login.html";
        return;
    }

    // validate token expiration
    try {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const now = Date.now() / 1000;

        if (payload.exp && payload.exp < now) {
            localStorage.removeItem("token");
            window.location.href = "/Exam-Scheduling-Room-Allocation-System/frontend/index.html";
        }
    } catch (e) {
        localStorage.removeItem("token");
        window.location.href = "/Exam-Scheduling-Room-Allocation-System/frontend/views/login.html";
    }
})();
