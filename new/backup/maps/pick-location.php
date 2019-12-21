<style>
  #map { position:relative; top:0; bottom:0; width:100%; height: 100%}
  #menu {
        position: relative;
        background: #fff;
        font-family: 'Open Sans', sans-serif;
    }
</style>

<div id='map' style="width:0px; height: 0px"></div>

<script>
  mapboxgl.accessToken = 'pk.eyJ1IjoicGFyYWRveGljYWxzIiwiYSI6ImNqb2oyejk0ajAwd2Izd3A0OGx3cWNkcTEifQ.acXV2amsiLRmv54yzRk63w';
  var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v9',
      center: [110.368224, -7.791671],
      zoom: 12
  });

  map.addControl(new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true
    },
    trackUserLocation: true
  }));

  map.on("load", function(){
    document.getElementById("map").style.width = "100%";
    document.getElementById("map").style.height = "100%";
    map.resize();
  })

  map.on('click', function (e) {

    map.resize();

    var wrapped = e.lngLat.wrap();
    var ln = wrapped.lng;
    var la = wrapped.lat;
    var cc = la+','+ln;

    function geocode(query){
        $.ajax({
          url: 'https://api.opencagedata.com/geocode/v1/json',
          method: 'GET',
          data: {
            'key': '615afefa10444bd3a403332c76f4053e',
            'q': query,
            'language': 'id',
            'abbrv': 1
          },
          dataType: 'json',
          statusCode: {
            200: function(response){
              var ff = response.results[0].formatted;
              var descript = "<center>"+ff+"<br> Long : "+la+"<br> Lat : "+ln+"</center>";
              new mapboxgl.Popup().setLngLat([ln, la]).setHTML(descript).addTo(map);
              document.getElementById("long").value = ln;
              document.getElementById("lat").value = la;
              document.getElementById("address").value = ff;
              //console.log(ff);
            },
            402: function(){
              console.log('hit free-trial daily limit');
              console.log('become a customer: https://opencagedata.com/pricing');
            }
          }
        });
      }

    geocode(cc);
  });

var layerList = document.getElementById('menu');
var inputs = layerList.getElementsByTagName('input');

function switchLayer(layer) {
    var layerId = layer.target.id;
    map.setStyle('mapbox://styles/mapbox/' + layerId + '-v9');
}

for (var i = 0; i < inputs.length; i++) {
    inputs[i].onclick = switchLayer;
}

</script>
