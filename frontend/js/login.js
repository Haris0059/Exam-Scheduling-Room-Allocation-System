$(function () {

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
            
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();

        if (!email) {
            toastr.error("Email is required");
            return;
        }
            
        $.ajax({
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/auth/login",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ email, password }),
            success: function (response) {
                if (response.status === "ok" && response.data.token) {
                    localStorage.setItem("token", response.data.token);
                    
                    if (response.data.user) {
                        localStorage.setItem("user", JSON.stringify(response.data.user));
                    }
                    
                    toastr.success("Login successful!");
                    
                    setTimeout(() => {
                        window.location.href = "/Exam-Scheduling-Room-Allocation-System/frontend/index.html";
                    }, 500);
                } else {
                    toastr.error("Unexpected response from server");
                }
            },
            error: function (xhr) {
                let errorMsg = "Login failed";
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.status === 400) {
                    errorMsg = "Invalid email or password";
                } else if (xhr.status === 401) {
                    errorMsg = "Invalid credentials";
                } else if (xhr.status === 0) {
                    errorMsg = "Cannot connect to server";
                }
                
                toastr.error(errorMsg);
            }
        });
    });

});