function removeLayers() {
    $.each(markersOnMap, function (idx, marker) {
        clusterGroup.removeLayer(marker);
    });

    $.each(contourOnMap, function (idx, contour) {
        mymap.removeLayer(contour);
    });

    resetMap();
    updateZoomMax();
}

/**********************************************************************************************************************/
/*                                                 OpenStreetMap manager                                              */
/**********************************************************************************************************************/
var mymap = L.map('mapid').setView([46.90296, 1.90925], 6);

var mapLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    /*maxZoom: 14,*/
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiYmVsb3U5NzEiLCJhIjoiY2pnbXBrMW52MnhzNDJxbnpnb3p5YjU0MCJ9.yh0DNDJQUqv1IHeb9OEbtA'
});

mymap.removeControl(mymap.zoomControl);
mymap.addLayer(mapLayer);


mapheight = $('#mapid').width();
$('#mapid').height(mapheight);
mymap.invalidateSize();

updateView();

function updateView() {
    var url = Routing.generate('ts_nao_zoom_setting');
    $.get(url)
        .done(function (data) {
            var response = JSON.parse(data);
            var outputData = response.data;
            var outputMessages = response.messages;
            var errors = response.errors;

            if(outputData.zoomMax > 0) {
                var lat = $('#lat').text();
                var lgn = $('#lgn').text();
                var coord = new L.LatLng(lat, lgn);
                L.marker(coord).addTo(mymap);
                mymap.setView(coord, outputData.zoomMax);
            }
        });
}



