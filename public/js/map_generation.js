function initialize(lat, lng, map_name, title) {
    var myOptions = {
        center: new google.maps.LatLng(lat+0.002, lng),
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById(map_name),
        myOptions);
    createMarkerInfoWindow(new google.maps.LatLng(lat, lng), map, "Association Impulsion<br />"+title);
}

function createMarker(latLng, map) {
    var options = {
        map:map, 
        position:latLng
    };
    return new google.maps.Marker(options);
}

function createMarkerInfoWindow(latLng, map, text)
{
    var marker = createMarker(latLng, map);
    
    var options = {
        content:text
    };
    var infowindow = new google.maps.InfoWindow(options);
    infowindow.open(map, marker);
}

$(document).ready(function() {
    initialize(45.153552,5.748485, "map1", "Salle Odyssée");
    initialize(45.154867,5.748854, "map2", "La maison des associations");    
});
