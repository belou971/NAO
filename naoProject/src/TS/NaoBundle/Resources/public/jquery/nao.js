/**********************************************************************************************************************/
/*                                                 Specimen auto-completion manager                                   */
/**********************************************************************************************************************/
var xhr = new XMLHttpRequest();
xhr.open('GET', Routing.generate('ts_nao_specimens_names'), true);
xhr.onload = function() {
   var list = JSON.parse(xhr.responseText);
   var specimenInput = document.querySelector("input#input-specimen");
   var awesomplete = new Awesomplete(specimenInput,{ list: list });
    $('#specimen .dropdown-btn').on("click", function() {
        if (awesomplete.ul.childNodes.length === 0) {
            awesomplete.minChars = 1;
            awesomplete.evaluate();
        }
        else if (awesomplete.ul.hasAttribute('hidden')) {
            awesomplete.open();
        }
        else {
            awesomplete.close();
        }
    });

};
xhr.send();

/* ------------------------------------------------------------------------------------------------------------------ */
/*                       Event on search button of the research by the name of a specimen                             */
/* ------------------------------------------------------------------------------------------------------------------ */
document.getElementById("input-specimen").addEventListener("awesomplete-selectcomplete", function (event) {
    $('#input-specimen').val(event.text.value);
    $('#specimen button[name=submit_btn]').prop('disabled', false);
});

$specimen_search_btn = $('#specimen button[name=submit_btn]');
$specimen_search_btn.on('click', function () {
    removeLayers();

    var $name = $('#input-specimen').val();
    var $data = {specimen_name: $name};
    $.ajax({
        type: 'post',
        url: Routing.generate('ts_nao_search_specimen_by_name'),
        data: JSON.stringify($data),
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        success: function (response) {
            var outputData = response.data;
            var outputMessages = response.messages;
            var errors = response.errors;
            var hasFound = outputData.length > 0;

            if(errors.length === 0) {
                setResearchMessage(outputMessages,hasFound);
                if(hasFound) {
                    AddMarkersToClusterGroup(outputData);
                }
            }
            else {
                setResearchMessage(errors, hasFound);
            }

            displayResearchMeassage();

            $('#input-specimen').val("");
        }
    });
});

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
/*                                                 Cities auto-completion manager                                     */
/**********************************************************************************************************************/
var citiesInput = document.querySelector("input#input-cities");
var awesomplete2 = new Awesomplete( citiesInput, {
                                    minChars:1,
                                    autoFirst:true,
                                    maxItems:5,
                                    replace: function(text){
                                        this.input.value = text;
                                    } });

/* ------------------------------------------------------------------------------------------------------------------ */
/*                       Event on search button of the research by the city                                           */
/* ------------------------------------------------------------------------------------------------------------------ */
$("input#input-cities").on("keyup", function() {
    var url = "https://geo.api.gouv.fr/communes?nom="+this.value+"&format=json&geometry=centre";
    $.get(url)
        .done(function(data) {
            var list = data.map(function(item){
                return {label: item.nom+" - "+item.codeDepartement, value:item.nom+" "+item.codeDepartement}
            });
            awesomplete2.list = list;
    })
});

document.getElementById("input-cities").addEventListener("awesomplete-selectcomplete", function (event) {
    $('#cities button[name=submit_btn]').prop('disabled', false);
});

$('#cities button[name=submit_btn]').on('click', function() {
    removeLayers();

    var selected_city = extract_selected_city($('#input-cities').val());
    if(2 === Object.keys(selected_city).length) {
        var url = "https://geo.api.gouv.fr/communes?nom=" + selected_city["nom"] + "&codeDepartement=" + selected_city["departement"] + "&fields=nom&format=geojson&geometry=contour";
        $.get(url)
            .done(function (cityInfo) {
                if (cityInfo["features"].length > 1) {
                    var firstFeature = cityInfo["features"][0];
                    cityInfo["features"] = [firstFeature];
                }

                var data = {city: selected_city["nom"], geo_properties: cityInfo};
                $.ajax({
                    type: 'post',
                    url: Routing.generate('ts_nao_search_specimen_by_city'),
                    data: JSON.stringify(data),
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    success: function (response) {
                        var outputData = response.data;
                        var outputMessages = response.messages;
                        var errors = response.errors;
                        var hasFound = outputData.length > 0;

                        if (errors.length === 0) {
                            setResearchMessage(outputMessages, hasFound);
                            AddMarkersToClusterGroup(outputData);
                        }
                        else {
                            setResearchMessage(errors, hasFound);
                        }

                        var poly = addPolygonOfCity(cityInfo);
                        contourOnMap.push(poly);

                        var group = poly.getBounds();
                        mymap.fitBounds(group);

                        displayResearchMeassage();

                        $('#input-cities').val("");
                    }
                })
            });
    }
});

function extract_selected_city(value){
    var city = [];
    var idx_separator = value.indexOf('-');
    if(idx_separator > 0) {
        var length_value = value.length;
        city["nom"] = value.substr(0, idx_separator-1);
        city["departement"]=value.substr(idx_separator+2, length_value-1);
    }
    return city;
}

function displayResearchMeassage()
{
    $('.search-message').css('display', 'block');
}

function hideResearchMeassage()
{
    $('.date-message').css('display', 'none');
}

function setResearchMessage(messages, hasFound)
{
    var htmlMessage = "";
    $.each(messages, function(idx, message) {
        htmlMessage += "<p>"+message+"</p>";
    });

    var search_mesg_container = $('div.search-message');
    search_mesg_container.html(htmlMessage);

    if(hasFound && search_mesg_container.hasClass('text-danger')){
        search_mesg_container.removeClass('text-danger');
        search_mesg_container.addClass('text-success');
    }

    if(!hasFound && search_mesg_container.hasClass('text-success')){
        search_mesg_container.removeClass('text-success');
        search_mesg_container.addClass('text-danger');
    }
}

/**********************************************************************************************************************/
/*                                                 Cities auto-completion manager                                     */
/**********************************************************************************************************************/

function enablesearchBtn() {
    var is_lat_set = $('#latitude').val().length > 0;
    var is_lgn_set = $('#longitude').val().length > 0;
    if(is_lat_set && is_lgn_set) {
        $('#coord button[name=submit_btn]').prop('disabled', false);
    }
    else {
        $('#coord button[name=submit_btn]').prop('disabled', true);
    }
}

$('#latitude').on("input", enablesearchBtn);
$('#longitude').on("input", enablesearchBtn);

$('#coord button[name=submit_btn]').on('click', function(event) {
    var coordLat = $('#latitude').val();
    var coordLgn = $('#longitude').val();
    removeLayers();

    var url = "https://geo.api.gouv.fr/communes?lat="+coordLat+"&lon="+coordLgn+"&fields=,nom,code&format=geojson&geometry=contour";
    $.get(url)
        .done (function(coordInfo){

            var data = {coord_properties: coordInfo};
            $.ajax({
                type: 'post',
                url: Routing.generate('ts_nao_search_specimen_by_coord'),
                data: JSON.stringify(data),
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    var outputData = response.data;
                    var outputMessages = response.messages;
                    var errors = response.errors;
                    var hasFound = outputData.length > 0;

                    if (errors.length === 0) {
                        setResearchMessage(outputMessages, hasFound);
                        AddMarkersToClusterGroup(outputData);
                    }
                    else {
                        setResearchMessage(errors, hasFound);
                    }

                    var poly = addPolygonOfCity(coordInfo);
                    contourOnMap.push(poly);

                    var group = poly.getBounds();
                    mymap.fitBounds(group);

                    displayResearchMeassage();

                    $('#latitude').val("");
                    $('#longitude').val("");
                }
            })


        });
});

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

mymap.addLayer(mapLayer);

var markersOnMap = [];
var contourOnMap = [];
var clusterGroup = new L.MarkerClusterGroup();
mymap.addLayer(clusterGroup);

function resetMap() {
    mymap.setView([46.90296, 1.90925], 6);
}


function AddMarkersToClusterGroup(obsList) {
    markersOnMap = obsList.map(addObservationOnMap);
    for (var i = 0; i < markersOnMap.length; i++) {
        clusterGroup.addLayer(markersOnMap[i]);
    }
}

function addObservationOnMap(observation) {
    var lat = observation[0].latitude;
    var lgn = observation[0].longitude;
    var marker = L.marker([lat, lgn], {title: observation[0].specimen});

    marker.on('click', getObservationContent(1));
    return marker;
}

function getObservationContent(observationId) {
    return function(event) {

        var $data = {id: observationId};
        $.ajax({
            type: 'post',
            url: Routing.generate('ts_nao_read_observation'),
            data: JSON.stringify($data),
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            success: function (content) {
                var outputData = content.html;

                $('.modal-body').html(outputData);
                $('#observationModal').modal('show');
            }
        });


    };
}

function addPolygonOfCity(contour) {
    return L.geoJSON(contour).addTo(mymap);
}

function updateZoomMax() {
    var url = Routing.generate('ts_nao_zoom_setting');
    $.get(url)
        .done(function (data) {
            var response = JSON.parse(data);
            var outputData = response.data;
            var outputMessages = response.messages;
            var errors = response.errors;

            if(outputData.zoomMax > 0) {
                mymap.options.maxZoom = outputData.zoomMax;
                mymap.fire('zoomend');
            }
        });

}