$(function () {

    $('#createExamModal form').on('submit', function (e) {
        e.preventDefault();

        const payload = {
            course_id: $('#courseId').val(),
            date: $('#date').val(),
            start: $('#startTime').val(),
            end: $('#endTime').val(),
            type: $('#typeOfExam').val(),
            room_type: $('#typeOfRoom').val()
        };

        $.ajax({
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/exams",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(payload),
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            success: function (res) {

                const examId = res.data.id;

                $.ajax({
                    url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/allocate-exam",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        exam_id: examId,
                        room_type: payload.room_type
                    }),
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("token")
                    },
                    success: function () {
                    
                        $('#createExamModal').modal('hide');
                        $('#createExamModal form')[0].reset();
                    
                        const calendarEl = document.getElementById('calendar');
                        if (calendarEl && calendarEl._fcInstance) {
                            calendarEl._fcInstance.refetchEvents();
                        }
                    
                        toastr.success("Exam created and room allocated");
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseText || "Room allocation failed");
                    }
                });
            }
        });
    });

    function loadCourses() {
        $.ajax({
            url: "http://localhost/Exam-Scheduling-Room-Allocation-System/backend/courses",
            method: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
            },
            success: function (res) {
                const select = $('#courseId');
                select.empty().append('<option value="">Select course</option>');

                (res.data || []).forEach(course => {
                    select.append(
                        `<option value="${course.id}">
                            ${course.name} (${course.code})
                         </option>`
                    );
                });
            }
        });
    }

    $('#createExamModal').on('show.bs.modal', loadCourses);

});
