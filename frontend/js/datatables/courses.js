$(function () {

    // initialize DataTable for courses
    let table = $('#dataTableCourses').DataTable({
        ajax: {
            url: "http://localhost/backend/courses",
            type: "GET",
            dataSrc: "data",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            }
        },
        columns: [
            { data: "code" },
            { data: "name" },
            { data: "ects" },

            // students modal button
            {
                data: "id",
                render: function (id) {
                    return `
                        <a 
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm view-students"
                            data-id="${id}"
                            data-toggle="modal"
                            data-target="#viewStudentsModal"
                        >
                            <i class="fas fa-user-graduate fa-sm text-white-50"></i> Students
                        </a>
                    `;
                }
            },

            // status column (active is set always due to it not implemented in db will be change later myb)
            {
                data: null,
                render: function () {
                    return `<p">Active</p>`;
                }
            }
        ]
    });

    // on click student button open modal
    let studentsTable = null;

    $(document).on('click', '.view-students', function () {

        let courseId = $(this).data('id');
        $('#viewStudentsModal').modal('show');

        // destroy previous dataTable instance if exists
        if ($.fn.DataTable.isDataTable('#dataTableStudents')) {
            $('#dataTableStudents').DataTable().clear().destroy();
        }

        // modal dataTable for students
        studentsTable = $('#dataTableStudents').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: `http://localhost/backend/enrollments/course/${courseId}`,
                type: "GET",
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            },
            columns: [
                { data: "student_id" },
                { data: "full_name" },
                {
                    data: "academic_level",
                    render: function (level) {
                    
                        // in case backend returns null
                        if (!level) return "";
                    
                        // first letter capitalized
                        return level.charAt(0).toUpperCase() + level.slice(1);
                    }
                },
                { data: "department" }
            ]
        });

    });
});
