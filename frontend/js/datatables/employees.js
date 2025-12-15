$(function () {
    // FETCH DEPARTMENTS FROM FACULTY
    $(document).on('change', '#faculty', function () {
        loadDepartmentsByFaculty($(this).val());
    });


    // FETCH ALL FACULTIES TO facultyMap AND USE THEM FOR DATATABLE INSTEAD OF ID's
    let facultyMap = {};

    fetch("http://localhost/Exam-Scheduling-Room-Allocation-System/backend/faculty", {
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
                url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/employees",
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
            url: `http://localhost/Exam-Scheduling-Room-Allocation-System/backend/departments/byFaculty/${facultyId}`,
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

    // ADD EMPLOYEE
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();
    
        const payload = {
            first_name: $('#firstName').val().trim(),
            last_name: $('#lastName').val().trim(),
            email: $('#email').val().trim(),
            role: $('#role').val(),
            faculty_id: $('#faculty').val(),
            department_id: $('#department').val()
        };
    
        // basic frontend validation
        for (let key in payload) {
            if (!payload[key]) {
                toastr.error("All fields are required");
                return;
            }
        }
    
        $.ajax({
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/employees",
            method: "POST",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            data: payload,
            success: function () {
                toastr.success("Employee added successfully");
            
                $('#addEmployeeModal').modal('hide');
                $('#addEmployeeForm')[0].reset();
                $('#department').html('<option value="">Select department</option>');
            
                // reload datatable
                $('#dataTableEmployees').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                toastr.error(xhr.responseText || "Failed to add employee");
            }
        });
    });

    // ROOM DELETE
    let employeeIdToDelete = null;

    $(document).on("click", ".remove-employee", function () {
        employeeIdToDelete = $(this).data("id");

        // store it on modal as well (optional, but clean)
        $("#removeEmployeeModal").data("id", employeeIdToDelete);
    });

    $('#confirmRemoveEmployeeBtn').on('click', function () {
        const id = $("#removeEmployeeModal").data("id");
        
        if (!id) {
            toastr.error("Employee ID missing");
            return;
        }
    
        $.ajax({
            url: `http://localhost/Exam-Scheduling-Room-Allocation-System/backend/employees/${id}`,
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
                toastr.error(xhr.responseText || "Failed to remove employee");
            }
        });
    });

    
});
