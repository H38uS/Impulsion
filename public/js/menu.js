$(document).ready(function () {
    $("#sousMenuCours").hide();    
    $("#menuCours").hover(hoverMenuIn, hoverMenuOut);
    $("#sousMenuCours").hover(hoverSousMenuIn, hoverSousMenuOut);
});

function hoverSousMenuIn() {
    $("#sousMenuCours").show();
}

function hoverMenuIn() {
    $("#sousMenuCours").show();
}

function hoverMenuOut() {
    $("#sousMenuCours").hide(); 
}

function hoverSousMenuOut() {
    $("#sousMenuCours").hide(); 
}


