var app = $.spapp({
    defaultView: "dashboard",
    templateDir: "/frontend/views/"
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
                }
            });
        })(id);
    });

    // start the SPA router after routes are registered
    app.run();
});