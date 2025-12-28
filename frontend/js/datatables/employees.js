$(function () {
    // FETCH DEPARTMENTS FROM FACULTY
    $(document).on('change', '#faculty', function () {
        loadDepartmentsByFaculty($(this).val());
    });

    // FETCH ALL FACULTIES TO facultyMap AND USE THEM FOR DATATABLE INSTEAD OF ID's
    let facultyMap = {};

    fetch("http://esras-app-5ejka.ondigitalocean.app/backend/faculty", {
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
        }
    })
    .then(res => res.json())
    .then(res => {
        if (res.data && Array.isArray(res.data)) {
            res.data.forEach(faculty => {
                facultyMap[faculty.id] = faculty.name;
            });
        }

        initEmployeesTable();
    });

    // DATATABLE
    function initEmployeesTable() {
        $('#dataTableEmployees').DataTable({
            ajax: {
                url: "http://esras-app-5ejka.ondigitalocean.app/backend/employees",
                type: "GET",
                dataSrc: "data",
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.first_name + " " + row.last_name;
                    }
                },
                { data: "email" },
                { 
                    data: "role",
                    render: function (role) {
                        return role ? role.toUpperCase() : "";
                    }
                },
                {
                    data: "faculty_id",
                    render: function (faculty_id) {
                        return facultyMap[faculty_id] || "Unknown";
                    }
                },
                {
                    data: "id",
                    className: "actions-column",
                    orderable: false,
                    searchable: false,
                    render: function (id) {
                        return `
                            <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm remove-employee"
                               data-id="${id}"
                               data-toggle="modal"
                               data-target="#removeEmployeeModal">
                                <i class="fas fa-times fa-sm text-white-50"></i> Remove
                            </a>
                        `;
                    }
                }
            ]
        });
    }

    function loadDepartmentsByFaculty(facultyId, selectedDepartmentId = null) {
        const departmentSelect = $('#department');

        departmentSelect.html('<option value="">Loading...</option>');

        if (!facultyId) {
            departmentSelect.html('<option value="">Select department</option>');
            return;
        }

        $.ajax({
            url: `http://esras-app-5ejka.ondigitalocean.app/backend/departments/byFaculty/${facultyId}`,
            method: 'GET',
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            success: function (res) {
                departmentSelect.html('<option value="">Select department</option>');

                res.data.forEach(d => {
                    const selected =
                        selectedDepartmentId && d.id == selectedDepartmentId
                            ? 'selected'
                            : '';

                    departmentSelect.append(
                        `<option value="${d.id}" ${selected}>${d.name}</option>`
                    );
                });
            },
            error: function (xhr) {
                departmentSelect.html('<option value="">Failed to load</option>');
                console.log(xhr.responseText);
            }
        });
    }

    // ADD EMPLOYEE WITH PASSWORD VALIDATION
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();
    
        const password = $('#password').val().trim();
        const repeatPassword = $('#repeatPassword').val().trim();
        
        // Validate all fields are filled
        const firstName = $('#firstName').val().trim();
        const lastName = $('#lastName').val().trim();
        const email = $('#email').val().trim();
        const role = $('#role').val();
        const facultyId = $('#faculty').val();
        const departmentId = $('#department').val();

        if (!firstName || !lastName || !email || !role || !facultyId || !departmentId) {
            toastr.error("All fields are required");
            return;
        }

        // Validate password fields
        if (!password || !repeatPassword) {
            toastr.error("Password fields cannot be empty");
            return;
        }

        // Check if passwords match
        if (password !== repeatPassword) {
            toastr.error("Passwords do not match");
            return;
        }

        // Optional: Add password strength validation
        if (password.length < 6) {
            toastr.error("Password must be at least 6 characters long");
            return;
        }

        const payload = {
            first_name: firstName,
            last_name: lastName,
            email: email,
            password: password,
            role: role,
            faculty_id: facultyId,
            department_id: departmentId
        };
    
        $.ajax({
            url: "https://esras-app-5ejka.ondigitalocean.app/backend/auth/register",
            method: "POST",
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("token"),
                "Content-Type": "application/json"
            },
            data: JSON.stringify(payload),
            success: function () {
                toastr.success("Employee registered successfully");
            
                $('#addEmployeeModal').modal('hide');
                $('#addEmployeeForm')[0].reset();
                $('#department').html('<option value="">Select department</option>');
            
                // reload datatable
                $('#dataTableEmployees').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                
                // Try to parse error message
                let errorMsg = "Failed to register employee";
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg = response.message || response.error || errorMsg;
                } catch (e) {
                    errorMsg = xhr.responseText || errorMsg;
                }
                
                toastr.error(errorMsg);
            }
        });
    });

    // EMPLOYEE DELETE
    let employeeIdToDelete = null;

    $(document).on("click", ".remove-employee", function () {
        employeeIdToDelete = $(this).data("id");
        $("#removeEmployeeModal").data("id", employeeIdToDelete);
    });

    $('#confirmRemoveEmployeeBtn').on('click', function () {
        const id = $("#removeEmployeeModal").data("id");
        
        if (!id) {
            toastr.error("Employee ID missing");
            return;
        }
    
        $.ajax({
            url: `https://esras-app-5ejka.ondigitalocean.app/backend/employees/${id}`,
            method: "DELETE",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            success: function () {
                toastr.success("Employee removed");
            
                $('#removeEmployeeModal').modal('hide');
                $('#dataTableEmployees').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                let errorMsg = "Failed to remove employee";
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg = response.message || response.error || errorMsg;
                } catch (e) {
                    errorMsg = xhr.responseText || errorMsg;
                }
                toastr.error(errorMsg);
            }
        });
    });
});