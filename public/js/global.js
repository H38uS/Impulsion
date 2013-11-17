$(document).ready(function() {
    updateSize();
    $(window).resize(updateSize);
    $(".submenucontroller").click(displayhide);
});

function updateSize() {
    if ($(window).width() > 1280) {
        $("#container").css("left", "50%");
    } else {
        $("#container").css("left", "640px");
    }
    if ($(window).height() > 800) {
        $("#container").css("top", "50%");
    } else {
        $("#container").css("top", "400px");
    }
}

function displayhide()
{
    // init
    var menucontroller = $(this);
    if (menucontroller.hasClass("actif")) {
        // can't hide/display
        // just return
        return;	
    }
	
    if (!menucontroller.hasClass("smhided") && !menucontroller.hasClass("smdisplayed")) {
        // hided at first... we show them
        menucontroller.addClass("smhided");
        display(menucontroller);	
    } else {
        if (menucontroller.hasClass("smhided")) {
            // show them !				
            display(menucontroller);	
        } else {
            if (menucontroller.hasClass("smdisplayed")) {
                hide(menucontroller);
            }
        }
    }
}

function display(menucontroller)
{
    var submenu = menucontroller.next();
    submenu.show('slow');
    menucontroller.removeClass("smhided").addClass("smdisplayed");	
}



function hide(menucontroller)
{
    var submenu = menucontroller.next();
    submenu.hide('slow');
    menucontroller.removeClass("smdisplayed").addClass("smhided");
}