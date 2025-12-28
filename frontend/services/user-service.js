var UserService = {

    init: function () {
        const token = localStorage.getItem("user_token");

        // If no token → redirect to login
        if (!token) {
            window.location.href = "/frontend/views/login.html";
            return;
        }

        let user = null;

        try {
            user = Utils.parseJwt(token).user;
        } catch (e) {
            localStorage.removeItem("user_token");
            window.location.href = "/frontend/views/login.html";

            return;
        }

        // Set username in top bar if available
        if (user.first_name && user.last_name) {
            $("#userName").text(user.first_name + " " + user.last_name);
        }

        // Apply role logic
        UserService.applyRoleRules(user.role);

        // Protect unauthorized pages
        UserService.routeProtection(user.role);
    },



    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                localStorage.setItem("user_token", result.data.token);
                window.location.replace("../index.html");
            },
            error: function (XMLHttpRequest) {
                toastr.error(XMLHttpRequest?.responseText || "Login failed");
            }
        });
    },



    logout: function () {
        localStorage.removeItem("user_token");
        window.location.href = "views/login.html";
    },



    applyRoleRules: function (role) {

        // -----------------------------
        // ASSISTANT RESTRICTIONS
        // -----------------------------
        if (role === Constants.ASSISTANT_ROLE) {

            // Hide employees from sidebar
            $('a[href="#employees"]').parent().hide();

            // Remove employees page
            $('#employees').remove();
        }

        // -----------------------------
        // PROFESSOR RESTRICTIONS
        // -----------------------------
        if (role === Constants.PROFESSOR_ROLE) {

            // Hide Add Employee button inside employees.html
            $(document).on("spapp:loaded", function () {
                $("#addEmployeeBtn").hide();
            });
        }

        // -----------------------------
        // ADMIN → full access, nothing to hide
        // -----------------------------
    },



    routeProtection: function (role) {

        // Block assistant from #employees
        $(window).on("hashchange", function () {
            const page = window.location.hash;

            if (role === Constants.ASSISTANT_ROLE && page === "#employees") {
                alert("You are not allowed to view employees.");
                window.location.hash = "#dashboard";
            }
        });

        // Prevent professors from entering #addEmployee
        $(window).on("hashchange", function () {
            const page = window.location.hash;

            if (role !== Constants.ADMIN_ROLE && page === "#addEmployee") {
                alert("Only admin can add employees.");
                window.location.hash = "#employees";
            }
        });
    }
};