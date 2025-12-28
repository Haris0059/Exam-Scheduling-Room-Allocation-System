$(function () {

    // ✅ ALWAYS unbind before binding
    $('#createExamModal form')
        .off('submit')
        .on('submit', function (e) {
            e.preventDefault();

            const payload = {
                course_id: $('#courseId').val(),
                date: $('#date').val(),
                start: $('#startTime').val(),
                end: $('#endTime').val(),
                type: $('#typeOfExam').val(),
                room_type: $('#typeOfRoom').val()
            };

            // CREATE EXAM
            $.ajax({
                url: "http://localhost/backend/exams",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify(payload),
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                },
                success: function (res) {

                    const examId = res.data.id;

                    // ✅ close modal immediately (UI should not depend on allocation)
                    $('#createExamModal').modal('hide');
                    $('#createExamModal form')[0].reset();

                    // ALLOCATE ROOM
                    $.ajax({
                        url: "http://localhost/backend/allocate-exam",
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
                            toastr.success("Exam created and room allocated");
                        },
                        error: function () {
                            toastr.warning("Exam created but room allocation failed");
                        }
                    });
                }
            });
        });

    // ✅ KEEP THIS — REQUIRED
    function loadCourses() {
        $.ajax({
            url: "http://localhost/backend/courses",
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

    // load courses every time modal opens
    $('#createExamModal').on('show.bs.modal', loadCourses);

});
