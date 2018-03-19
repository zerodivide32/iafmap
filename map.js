/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * 
 * IntFormatter formater for toggle 0/1 values only
 */
var IntFormatter = {'to': function (value) {
        return value !== undefined && value.toFixed(0);
    }, 'from': Number};
/*
 * Init noUiSlider
 */
var yearRS = document.getElementById('yearRange');

noUiSlider.create(yearRS, {
    start: [1950, 2020],
    step: 10,
    behaviour: 'drag',
    connect: true,
    range: {
        'min': 1900,
        'max': 2099
    }
});
/* Update display values with a slide. */
yearRS.noUiSlider.on('slide', function (values, handle, decoded) {
    yearRangeValue.innerHTML = `${decoded[0]} ~ ${decoded[1]}`;
    toggleMarkerVisibilityByYear();
});

/*var togglePest = document.getElementById('swPest');
noUiSlider.create(togglePest, {
    orientation: "vertical",
    start: 0,
    format: IntFormatter,
    range: {
        'min': [0, 1],
        'max': 1
    }
});
*/

var customLabel = {
    restaurant: {
        label: 'R'
    },
    bar: {
        label: 'B'
    }
};
var markers = []; /* Array of marker objects. We keep this reference so we can manually remove them without refreshing the page */
var map; /* The GMap object */
/* Function to update the toggle button's background */
function toggleButton(cb) {
    cb.checked = !cb.checked; /* I'm not using the hack correctly it seems, so to get that square peg in all the way, I'm manually togggling the checkbox */
    if (cb.checked) {
        cb.parentElement.style.background = "DodgerBlue";
        toggleMarkerVisibility(cb.value,true);
    } else {
        cb.parentElement.style.background = "LightGray";
        toggleMarkerVisibility(cb.value,false);
    }
}
/*
 * Update marker visibility on filter toggle
 * @param {type} marker type name
 * @param {type} boolean
 * @returns aether
 */
function toggleMarkerVisibility(type, visible){
    Array.prototype.forEach.call(markers, function (m){
        if(m.typename==type) m.setVisible(visible);
    });
}
/*
 * Update marker visibility on slider update
 * @returns 
 */
function toggleMarkerVisibilityByYear(){
    var yearStart = parseInt(this.yearRS.noUiSlider.get()[0]);
    var yearEnd = parseInt(this.yearRS.noUiSlider.get()[1]);
    Array.prototype.forEach.call(markers, function (m){
        if(parseInt(m.year) < yearStart || parseInt(m.year) > yearEnd) {
            m.setVisible(false) 
        } else{
            m.setVisible(true);
            
        }
    });
}
/*
 * initMap() - Initialize gmap object & start async download of data
 */
function initMap() {
    this.map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(-33.863276, 151.207977),
        zoom: 12
    });
    var infoWindow = new google.maps.InfoWindow;
    performDownload();
    
    
}
/*
 * performDownload() - this method will prepare URL parameters and handle the callback.
 * URL parameters: yearStart(int), yearEnd(int), excludeTypes(list of string types)
 */
function performDownload() {
    var yearStart = parseInt(this.yearRS.noUiSlider.get()[0]);
    var yearEnd = parseInt(this.yearRS.noUiSlider.get()[1]);
    var excludeTypes = "";
    var url = 'mapdata.php';

/*
 * Server based filtering.. abandoning for client filtering until marker count/avg desktop memory ratio becomes problematic
 */
//    /* Prepare base URL */
//    url = `${url}?yearStart=${yearStart}&yearEnd=${yearEnd}`;
//    /* Checked means we are INCLUDING, this is EXCLUSION list. */
//    if (!ckPest.checked)
//        excludeTypes += "Pest,";
//    if (!ckDrought.checked)
//        excludeTypes += "Drought,";
//    if (!ckFlood.checked)
//        excludeTypes += "Flood,";
//    if (!ckHail.checked)
//        excludeTypes += "Hail,";
//    if (!ckCold.checked)
//        excludeTypes += "Cold,";
//    if (!ckHot.checked)
//        excludeTypes += "Hot,";
//    if (!ckStorm.checked)
//        excludeTypes += "Storm,";
//    if (!ckWind.checked)
//        excludeTypes += "Wind";
//
//    if (excludeTypes.length > 0) {
//        if (excludeTypes.endsWith(',')) {
//            excludeTypes = excludeTypes.substring(0, excludeTypes.length - 1);
//        }
//        excludeTypes = "&excludeTypes=" + excludeTypes;
//        url += excludeTypes;
//    }
    // Change this depending on the name of your PHP or XML file
    downloadUrl(url, function (data, ) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function (markerElem) {
            var id = markerElem.getAttribute('id');
            var name = markerElem.getAttribute('name');
            var address = markerElem.getAttribute('address');
            var type = markerElem.getAttribute('type');
            var point = new google.maps.LatLng(
                    parseFloat(markerElem.getAttribute('lat')),
                    parseFloat(markerElem.getAttribute('lng')));

            /*
             * InfoWindow's content
             */
            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = address
            infowincontent.appendChild(text);

            /*
             * Marker object
             */
            var marker = new google.maps.Marker({
                map: this.map,
                position: point,
                icon: markerElem.getAttribute('icon')
            });
            marker.typename=type;
            marker.year = markerElem.getAttribute('year');
            /* Create a new InfoWindow object & stuff it in the marker instance */
            marker.infoWindow = new google.maps.InfoWindow;
            marker.infoWindow.setContent(infowincontent);
            /* Ensure the click event has access to the Marker object and its instance of InfoWindow for display. */
            google.maps.event.addListener(marker, 'click', function () {
                marker.infoWindow.open(this.map, marker);
            });

            /* Array of the map markers for filtering/removal */
            this.markers.push(marker);
        });
    });
}

/*
 * Clear markers and refresh. To destroy the markers, set their map object to null
 */
function reloadMarkers() {
    console.log('test');
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    performDownload();


}
/*
 * Async download method
 */
function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}

function doNothing() {}