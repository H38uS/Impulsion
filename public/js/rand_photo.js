$(document).ready(function() {
    var intervalId = setInterval(load_photo, 8000);
});

function load_photo() {
    var lien = $("#photo_aleatoire").children(":first");
    var img = lien.children(":first");
    var last_photo = img.attr("src");
    img.fadeOut("slow");
    $.post('/public/index.php/galerie/getnextimage', {last:last_photo, folder:$("#selected").text()},	
        function(data) {
            setTimeout(function() {
                var paths = data.split("PATTERNACAUSEJSONDESARACE");
                img.attr("src", paths[0]);  
                lien.attr("href", paths[1]);
                img.load(function() {
                    img.fadeIn("slow");                    
                });    
            }, 350);       
        });
}