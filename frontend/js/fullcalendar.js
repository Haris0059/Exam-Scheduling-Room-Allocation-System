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
        events: [{
            title: 'Web Programming Final Exam',
            start: '2025-10-17T12:00',
            end: '2025-10-17T14:00'
        }],
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