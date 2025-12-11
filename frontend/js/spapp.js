var app = $.spapp({
    defaultView: "dashboard",
    templateDir: "views/"
})

// register onReady handlers for each section so only the active view is shown
// and the corresponding sidebar item gets the "active" class
$(function(){
    // hide all sections initially (spapp will load the active one)
    $("main#spapp > section").hide();

    $("main#spapp > section").each(function(){
        var id = $(this).attr('id');
        (function(viewId){
            app.route({
                view: viewId,
                onReady: function(){
                    // hide other sections and show this one
                    $("main#spapp > section").hide();
                    $("#" + viewId).show();

                    // toggle active class on sidebar nav items
                    $(".navbar-nav .nav-item").removeClass('active');
                    $(".navbar-nav .nav-link[href='#" + viewId + "']").closest('.nav-item').addClass('active');

                    // initialize view-specific widgets
                    if (viewId === 'exams' && typeof window.initFullCalendar === 'function') {
                        // allow a tiny delay to ensure the view DOM is attached
                        setTimeout(function(){ window.initFullCalendar('calendar'); }, 0);
                    }

                    if (viewId === 'courses') {

                        // dataTable already exists, just reload it
                        if ($.fn.DataTable.isDataTable('#dataTableCourses')) {
                            $('#dataTableCourses').DataTable().ajax.reload(null, false);
                        } 
                        else { // load courses.js to initialize everything 
                            $.getScript("js/datatables/courses.js");
                        }
                    }

                    if (viewId === 'rooms') {
                        // dataTable already exists, just reload it
                        if ($.fn.DataTable.isDataTable('#dataTableRooms')) {
                            $('#dataTableRooms').DataTable().ajax.reload(null, false);
                        } 
                        else { // load courses.js to initialize everything 
                            $.getScript("js/datatables/rooms.js");
                        }
                    }
                }
            });
        })(id);
    });

    app.run();
});