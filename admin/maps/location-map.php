<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="col-md-12">
  <div id="alert"></div>
  <div class="box box-info">
    <div class="box-body">
      <div class="select-map">
        <select id="layer-select" class="select2">
          <option value="Road" selected>Road (static)</option>
          <option value="RoadOnDemand">Road (dynamic)</option>
          <option value="Aerial">Aerial</option>
          <option value="AerialWithLabels">Aerial with labels</option>
        </select>
      </div>
      <div id="map" class="map"></div>
      <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
      </div>
      <div class="select-mark">
        <select id="mark-select" class="select2">
          <option value="all" selected>All</option>
          <option value="ap">Access Point</option>
          <option value="transmitter">Transmitter</option>
          <option value="customer">Customer</option>
          <option value="partner">Partner</option>
          <option value="admin">Admin</option>
        </select>
        <div class="pretty p-default p-round p-thick" style="margin-left: 10px">
          <input type="checkbox" id="show-label">
          <div class="state p-primary-o">
            <label>Show label</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
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
      visible: true,
      preload: Infinity,
      source: new ol.source.BingMaps({
        key: 'AsFhFog0dylN0aPD-0dHsunhHEs8dVE_LAMNdYiP7OWlJDRcsw0OgMjAcPp6Y3n8',
        imagerySet: styles[i],
      })
    })
    )
  }

  var container = document.getElementById('popup');
  var content = document.getElementById('popup-content');
  var closer = document.getElementById('popup-closer');

  var overlay = new ol.Overlay({
    element: container,
    autoPan: true,
    autoPanAnimation: {
      duration: 250
    }
  });

  closer.onclick = function() {
    overlay.setPosition(undefined);
    closer.blur();
    return false;
  };

  var map = new ol.Map({
    layers: layers,
    target: 'map',
    overlays: [overlay],
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

  function getMark(places) {
    aptransmark = [];
    var features = [];
    for (var i = 0; i < places.length; i++) {
      if (places[i][2] == "ap") {
        var src = "../image/map/pin-wifi-red.png";
        aptransmark.push([places[i][0], places[i][1], places[i][2]]);
      } else if (places[i][2] == "transmitter") {
        var src = "../image/map/pin-train-orange.png";
        aptransmark.push([places[i][0], places[i][1], places[i][2]]);
      } else if (places[i][2] == "customer") {
        var src = "../image/map/pin-user-green.png";
      } else if (places[i][2] == "admin") {
        var src = "../image/map/pin-user-purple.png";
      } else if (places[i][2] == "partner") {
        var src = "../image/map/pin-user-blue.png";
      }

      if ($("#show-label").is(':checked')) {
        var iconStyle = new ol.style.Style({
          image: new ol.style.Icon({
            anchor: [0.5, 1],
            src: src,
          }),
          text: new ol.style.Text({
            text: places[i][3],
            scale: 1.3,
            offsetY: 8,
            fill: new ol.style.Fill({
              color: 'rgba(0, 0, 0, 1)'
            }),
            stroke: new ol.style.Stroke({
              color: 'rgba(255, 255, 255, 1)',
              width: 3
            })
          })
        });
      } else {
        var iconStyle = new ol.style.Style({
          image: new ol.style.Icon({
            anchor: [0.5, 1],
            src: src,
          }),
        });
      }

      if (places[i][0] && places[i][1]) {
        var iconFeature = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.transform([places[i][0], places[i][1]], 'EPSG:4326', 'EPSG:3857')),
          name: places[i][3],
        });
        iconFeature.setStyle(iconStyle);
        features.push(iconFeature);
      }
    }

    var vectorSource = new ol.source.Vector({
      features: features
    });
    var vectorLayer = new ol.layer.Vector({
      source: vectorSource
    });
    map.addLayer(vectorLayer);
    vectorLayer.set('name', 'vectorLayer');
  }

  function markChange(name) {
    $.get("maps/sql-proc.php?location="+name, function(data){
      var json = JSON.parse(data);
      getMark(json);
    });
  }
  markChange('all');

  var select_mark = document.getElementById('mark-select');
  $('#mark-select').on('change', function() {
    var mark = select_mark.value;
    removeMark('vectorLayer');
    removeMark('lineLayer');
    markChange(mark);
  });

  $('#show-label').on('click', function() {
    var mark = select_mark.value;
    removeMark('vectorLayer');
    removeMark('lineLayer');
    markChange(mark);
  });

  function removeMark(name) {
    var layersToRemove = [];
    map.getLayers().forEach(function (layer) {
      if (layer.get('name') != undefined && layer.get('name') === name) {
        layersToRemove.push(layer);
      }
    });
    var len = layersToRemove.length;
    for(var i = 0; i < len; i++) {
      map.removeLayer(layersToRemove[i]);
    }
  }

  map.on("click", function(e) {
    $('#popup-closer').click();
    map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
      var loca = feature.getGeometry().getCoordinates();
      var pixel_coordinate = ol.proj.transform(loca, 'EPSG:3857', 'EPSG:4326');
      var pixel_name = feature.get('name');
      var coordinate = e.coordinate;
      if (pixel_name == 'Line') {
        var distance = getDistance(
          ol.proj.transform(loca[0], 'EPSG:3857', 'EPSG:4326'),
          ol.proj.transform(loca[1], 'EPSG:3857', 'EPSG:4326')
        )
        var bearing = getBearing(
          ol.proj.transform(loca[0], 'EPSG:3857', 'EPSG:4326'),
          ol.proj.transform(loca[1], 'EPSG:3857', 'EPSG:4326')
        )
        content.innerHTML = 'Distance : '+distance+
        '<br>Bearing : '+bearing;
        overlay.setPosition(coordinate);
      } else {
        content.innerHTML = '<p>'+pixel_name+
        '</p>Lon : <code>'+pixel_coordinate[0]+
        '</code><br>Lat : <code>'+pixel_coordinate[1]+
        '</code><br><a href="https://www.google.com/maps/place/'+
        pixel_coordinate[1]+
        ','+pixel_coordinate[0]+
        '" target="_blank">Open Google Maps</a>';
        overlay.setPosition(coordinate);
        generateLine(pixel_coordinate)
      }
    })
  });

  var target = map.getTarget();
  var jTarget = typeof target === "string" ? $("#"+target) : $(target);
  map.on("pointermove", function (event) {
    var mouseCoordInMapPixels = [event.originalEvent.offsetX, event.originalEvent.offsetY];
    var hit = map.forEachFeatureAtPixel(mouseCoordInMapPixels, function (feature, layer) {
      return true;
    });
    if (hit) {
      jTarget.css("cursor", 'pointer');
    } else {
      jTarget.css("cursor", "");
    }
    // map.forEachFeatureAtPixel(event.pixel, function (feature, layer) {
    //   var loca = feature.getGeometry().getCoordinates();
    //   var pixel_coordinate = ol.proj.transform(loca, 'EPSG:3857', 'EPSG:4326');
    //   var pixel_name = feature.get('name');
    //   var coordinate = event.coordinate;
    //   if (pixel_name == 'Line') {
    //     var distance = getDistance(
    //       ol.proj.transform(loca[0], 'EPSG:3857', 'EPSG:4326'),
    //       ol.proj.transform(loca[1], 'EPSG:3857', 'EPSG:4326')
    //     )
    //     var bearing = getBearing(
    //       ol.proj.transform(loca[0], 'EPSG:3857', 'EPSG:4326'),
    //       ol.proj.transform(loca[1], 'EPSG:3857', 'EPSG:4326')
    //     )
    //     content.innerHTML = 'Distance : '+distance+
    //     '<br>Bearing : '+bearing;
    //     overlay.setPosition(coordinate);
    //   } else {
    //     content.innerHTML = '<p>'+pixel_name+
    //     '</p>Lon : <code>'+pixel_coordinate[0]+
    //     '</code><br>Lat : <code>'+pixel_coordinate[1]+
    //     '</code><br><a href="https://www.google.com/maps/place/'+
    //     pixel_coordinate[1]+
    //     ','+pixel_coordinate[0]+
    //     '" target="_blank">Open Google Maps</a>';
    //     overlay.setPosition(coordinate);
    //   }
    // })
  });

  function getDistance(loc1, loc2) {
    var lonlat1 = ol.proj.fromLonLat(loc1);
    var lonlat2 = ol.proj.fromLonLat(loc2);
    var line = new ol.geom.LineString([lonlat1, lonlat2]);
    var jarak = formatDistance(line.getLength())
    return jarak;
  }

  var old_loc1 = [];
  function generateLine(loc1) {
    var line_features = [];
    for (var i = 0; i < aptransmark.length; i++) {
      var lonlat1 = ol.proj.fromLonLat(loc1);
      var lonlat2 = ol.proj.fromLonLat(aptransmark[i]);

      if (aptransmark[i][2] == 'ap') {
        var clr = 'red'
      } else if (aptransmark[i][2] == 'transmitter') {
        var clr = 'yellow'
      } else {
        var clr = '#fffff'
      }

      var linestyle = [
        new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: clr,
            width: 2
          })
        })
      ];

      var line = new ol.layer.Vector({
        source: new ol.source.Vector({
          features: [new ol.Feature({
            geometry: new ol.geom.LineString([lonlat1, lonlat2]),
            name: 'Line',
          })]
        })
      });

      var lineFeature = new ol.Feature({
        geometry: new ol.geom.LineString([lonlat1, lonlat2]),
        name: 'Line',
      });
      lineFeature.setStyle(linestyle);
      line_features.push(lineFeature);
    }
    var l_vectorSource = new ol.source.Vector({
      features: line_features
    });
    var l_vectorLayer = new ol.layer.Vector({
      source: l_vectorSource
    });
    if (loc1[0] == old_loc1[0] && loc1[1] == old_loc1[1]) {
      removeMark('lineLayer');
      old_loc1 = [];
    } else {
      removeMark('lineLayer');
      map.addLayer(l_vectorLayer);
      l_vectorLayer.set('name', 'lineLayer');
      old_loc1 = loc1;
    }
  }

  function formatDistance(length) {
    if (length >= 1000) {
      length = (Math.round(length / 1000 * 100) / 100) +
      ' ' + 'Km';
    } else {
      length = Math.round(length) +
      ' ' + 'M';
    }
    return length;
  }

  function getBearing(loc1, loc2) {
    var point1 = turf.point(loc1);
    var point2 = turf.point(loc2);
    var bearing = turf.bearing(point1, point2);
    return Math.round(bearing * 1000) / 1000;
  }

  $('#mapsearch').donetyping(function(callback){
    removeMark('vectorLayer');
    removeMark('lineLayer');
    var name = $('#mapsearch').val();
    if (name == "") {
      markChange('all');
    } else {
      $.get("maps/sql-proc.php?search="+name, function(data){
        var json = JSON.parse(data);
        getMark(json);
        CenterMap(json[0][0], json[0][1]);
      });
    }
  });

  function CenterMap(long, lat) {
    map.getView().setCenter(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'));
    map.getView().setZoom(13);
  }

  $('.clr-mapsearch').on('click', function() {
    $('#mapsearch').val("");
    markChange('all');
  })

})
</script>
