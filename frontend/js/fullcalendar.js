window.initFullCalendar = function(selector) {
    var id = selector || 'calendar';
    var calendarEl = document.getElementById(id);
    if (!calendarEl) return;

    // destroy existing instance if present
    if (calendarEl._fcInstance) {
        try { calendarEl._fcInstance.destroy(); } catch(e) { /* ignore */ }
        calendarEl._fcInstance = null;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        allDaySlot: false,
        headerToolbar: {
            right: 'prev,today,next',
            center: 'title',
            left: 'timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            week: 'Week',
            day: 'Day',
            list: 'List',
            today: 'Today'
        },
        events: function (info, successCallback, failureCallback) {
            fetch("http://localhost/backend/exams", {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                }
            })
            .then(res => res.json())
            .then(res => {
                const exams = Array.isArray(res)
                    ? res
                    : Array.isArray(res.data)
                        ? res.data
                        : [];
            
                const events = exams.map(exam => ({
                    title: `Course #${exam.course_id} (${exam.type})`,
                    start: `${exam.date}T${exam.start}`,
                    end: `${exam.date}T${exam.end}`,
                    extendedProps: {
                        course_id: exam.course_id,
                        type: exam.type
                    }
                }));
            
                successCallback(events);
            })
            .catch(err => {
                console.error(err);
                failureCallback(err);
            });
        },
        businessHours: {
            daysOfWeek: [1,2,3,4,5],
            startTime: '09:00',
            endTime: '18:00'
        },
        hiddenDays: [0,6],
        slotMinTime: '09:00:00',
        slotMaxTime: '18:00:00'
    });

    calendar.render();
    calendarEl._fcInstance = calendar; 
};