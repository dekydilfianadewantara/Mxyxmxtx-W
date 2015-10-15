<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Circles</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px;
        width: 80%;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>
// This example creates circles on the map, representing
// populations in North America.

// First, create an object containing LatLng and population for each city.
var tempat = new google.maps.LatLng(41.878113, -87.629798);
var tempat2 = new google.maps.LatLng(41.890905, -87.747073);
var citymap = {};
citymap['chicago'] = {
  center: tempat,
  population: 2714856
};

var cityCircle;

function initialize() {
  // Create the map.
  var mapOptions = {
    zoom: 11,
    center: tempat,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
  
  var marker = new google.maps.Marker({
    position: tempat,
    center:tempat,
    map: map,
    title: 'Hello World!'
  });

  var marker2 = new google.maps.Marker({
    position: tempat2,
    center:tempat,
    map: map,
    title: 'Hello World!'
  });

  // Construct the circle for each value in citymap.
  // Note: We scale the area of the circle based on the population.
  for (var city in citymap) {
    var populationOptions = {
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: citymap[city].center,
      radius: 10000
    };
    // Add the circle for this city to the map.
    cityCircle = new google.maps.Circle(populationOptions);
  }
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>