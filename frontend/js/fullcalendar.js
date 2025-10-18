document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            right: 'prev,today,next',
            center: 'title',
            left: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            month: 'Month',
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
            daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
            startTime: '09:00',
            endTime: '18:00',
        },
        hiddenDays: [0, 6] // hide Sunday (0), Friday (5), Saturday (6)
    });

    calendar.render();
});
