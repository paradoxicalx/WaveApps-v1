<link rel="stylesheet" href="../assets/css/openlayers/ol.css" type="text/css">
<script src="../assets/js/openlayers/ol.js"></script>
<div class="row">
  <div class="col-md-9">
    <div id="map" class="map" style="margin-bottom: 10px"></div>
  </div>
  <div class="col-md-3">
    <div class="box box-solid bg-light-blue-gradient">
      <div class="box-body">
        <div class="form-group">
          <label for="layer-select">Map style</label>
          <select id="layer-select" class="select2" style="width: 100%;">
            <option value="Road" selected>Road (static)</option>
            <option value="RoadOnDemand">Road (dynamic)</option>
            <option value="Aerial">Aerial</option>
            <option value="AerialWithLabels">Aerial with labels</option>
          </select>
        </div>
        <hr>
        <div class="form-group">
          <label for="sample-long">Longitude</label>
          <input class="form-control" type="text" id="sample-long" value="">
        </div>
        <div class="form-group">
          <label for="sample-lat">Latitude </label>
          <input class="form-control" type="text" id="sample-lat" value="">
        </div>
        <div class="form-group">
          <label for="sample-address">Address </label>
          <textarea class="form-control" id="sample-address" rows="5"></textarea>
        </div>
        <div class="form-group">
          <input type="button" class="btn btn-success btn-block" id="save-map" value="Save" />
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
  $('.select2').select2();

  function simpleReverseGeocoding(lon, lat) {
    $.get("https://nominatim.openstreetmap.org/reverse?format=json&lon="+lon+"&lat="+lat+"&accept-language=id", function(data){
      $('#sample-address').val(data.display_name);
    });
  }

  var styles = [
    'Road',
    'RoadOnDemand',
    'Aerial',
    'AerialWithLabels',
  ];

  var layers = [];
  var i, ii;
  for (i = 0, ii = styles.length; i < ii; ++i) {
    layers.push(
      new ol.layer.Tile({
        visible: false,
        preload: Infinity,
        source: new ol.source.BingMaps({
          key: 'AsFhFog0dylN0aPD-0dHsunhHEs8dVE_LAMNdYiP7OWlJDRcsw0OgMjAcPp6Y3n8',
          imagerySet: styles[i],
        })
      })
    )
  }

  var map = new ol.Map({
    layers: layers,
    target: 'map',
    view: new ol.View({
      center: ol.proj.fromLonLat([110.366935, -7.782953]),
      zoom: 13
    })
  });

  var select = document.getElementById('layer-select');
  function onChange() {
    var style = select.value;
    for (var i = 0, ii = layers.length; i < ii; ++i) {
      layers[i].setVisible(styles[i] === style);
    }
    map.updateSize();
  }
  onChange();
  $('#layer-select').on('change', function() {
    onChange();
  });

  ////////
  var iconStyle = new ol.style.Style({
    image: new ol.style.Icon(({
      anchor: [0.5, 1],
      src: "../image/map/pin-home.png"
    }))
  });
  function addMark(longitude, latitude) {
    // remove old layer
    var layersToRemove = [];
    map.getLayers().forEach(function (layer) {
      if (layer.get('name') != undefined && layer.get('name') === 'vectorLayer') {
        layersToRemove.push(layer);
      }
    });
    var len = layersToRemove.length;
    for(var i = 0; i < len; i++) {
      map.removeLayer(layersToRemove[i]);
    }
    // add new layer
    var features = [];
    var iconFeature = new ol.Feature({
      geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'))
    });
    iconFeature.setStyle(iconStyle);
    features.push(iconFeature);

    var vectorSource = new ol.source.Vector({
      features: features
    });
    var vectorLayer = new ol.layer.Vector({
      source: vectorSource
    });
    map.addLayer(vectorLayer);
    vectorLayer.set('name', 'vectorLayer');
  }

  map.on('click', function (evt) {
    var coordinate = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
    $('#sample-long').val(coordinate[0]);
    $('#sample-lat').val(coordinate[1]);
    simpleReverseGeocoding(coordinate[0], coordinate[1]);
    addMark(coordinate[0], coordinate[1]);
  });

  $('#save-map').on('click', function () {
    $('#address').val( $('#sample-address').val() );
    $('#lat').val( $('#sample-lat').val() );
    $('#long').val( $('#sample-long').val() );
    $('#modal-map').modal('hide');
  })

  $('#sample-long, #sample-lat').on('keyup', function () {
    var long = parseFloat($('#sample-long').val());
    var lat = parseFloat($('#sample-lat').val());
    addMark(long, lat);
    map.getView().setCenter(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'));
  })

});
</script>
