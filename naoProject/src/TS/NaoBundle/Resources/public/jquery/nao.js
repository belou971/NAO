/**********************************************************************************************************************/
/*                                                   specimen autocompletion manager                                           */
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

/**********************************************************************************************************************/
/*                                                   cities autocompletion manager                                           */
/**********************************************************************************************************************/
var citiesInput = document.querySelector("input#input-cities");
var awesomplete2 = new Awesomplete( citiesInput, {
                                    minChars:1,
                                    autoFirst:true,
                                    maxItems:5,
                                    replace: function(text){
                                        this.input.value = text;
                                    } });
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

document.getElementById("input-cities").addEventListener("awesomplete-select", function (event) {
    console.log(event.text.value);
});


var mymap = L.map('mapid').setView([47.102732, 2.443096], 11);

var street = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiYmVsb3U5NzEiLCJhIjoiY2pnbXBrMW52MnhzNDJxbnpnb3p5YjU0MCJ9.yh0DNDJQUqv1IHeb9OEbtA'
}).addTo(mymap);

//var marker = L.marker([48.757766, 2.3231051]).addTo(mymap);

/*var circle = L.circle([48.757766, 2.3231051], {
    color: 'brown',
    fillColor: '#b73e0b',
    fillOpacity: 0.8,
    radius: 90
}).addTo(mymap);*/

var fresnes = L.circle([48.757766, 2.3231051], {
    color: 'brown',
    fillColor: '#b73e0b',
    fillOpacity: 0.8,
    radius: 190
}).bindPopup("<b><u>Fresnes</u></b><br>I am here.");

var paris = L.circle([48.864716, 2.349014], {
    color: 'brown',
    fillColor: '#b73e0b',
    fillOpacity: 0.8,
    radius: 190
}).bindPopup("<b><u>Paris</u></b><br>Paris est magique!");

var massy = L.circle([48.730755, 2.271370], {
    color: 'brown',
    fillColor: '#b73e0b',
    fillOpacity: 0.8,
    radius: 190
}).bindPopup("<b><u>Massy</u></b><br>Paris - sud");

var lyon = L.circle([45.763584, 4.841425], {
    color: 'blue',
    fillColor: '#453e94',
    fillOpacity: 0.8,
    radius: 190
}).bindPopup("<b><u>Lyon</u></b><br>Province");

var ileFrCities = L.layerGroup([fresnes, paris, massy]).addTo(mymap);
var provCities = L.layerGroup([lyon]).addTo(mymap);


var overlayMaps = {
    "Villes d'ile-de-france": ileFrCities,
    "Province": provCities
};

L.control.layers(null, overlayMaps).addTo(mymap);

var group = L.featureGroup([fresnes, paris, massy, lyon]);

mymap.fitBounds(group.getBounds());
