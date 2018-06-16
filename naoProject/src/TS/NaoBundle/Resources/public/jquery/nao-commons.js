/**********************************************************************************************************************/
/*                                                 Specimen auto-completion manager                                   */
/**********************************************************************************************************************/
var xhr = new XMLHttpRequest();
xhr.open('GET', Routing.generate('ts_nao_specimens_names'), true);
xhr.onload = function() {
   var list = JSON.parse(xhr.responseText);
   var specimenInput = document.querySelector("input.complete-specimen");
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

/**********************************************************************************************************************/
/*                                                 Cities auto-completion manager                                     */
/**********************************************************************************************************************/
var citiesInput = document.querySelector("input.complete-cities");
var awesomplete2;
if (citiesInput) {
    awesomplete2 = new Awesomplete(citiesInput, {
        minChars: 1,
        autoFirst: true,
        maxItems: 5,
        replace: function (text) {
            this.input.value = text;
        }
    });
}

/* ------------------------------------------------------------------------------------------------------------------ */
/*                       Event on search button of the research by the city                                           */
/* ------------------------------------------------------------------------------------------------------------------ */
$("input.complete-cities").on("keyup", function() {
    var url = "https://geo.api.gouv.fr/communes?nom="+this.value+"&format=json&geometry=centre";
    $.get(url)
        .done(function(data) {
            var list = data.map(function(item){
                return {label: item.nom+" - "+item.codeDepartement, value:item.nom+" "+item.codeDepartement}
            });
            awesomplete2.list = list;
    })
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

/**********************************************************************************************************************/
/*                                                 Cities auto-completion manager                                     */
/**********************************************************************************************************************/

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

