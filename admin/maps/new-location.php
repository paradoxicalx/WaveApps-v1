<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
if (isset($_GET['edit'])) {
  $location = sqlQuAssoc("SELECT * FROM wavenet.tb_maps WHERE `id` = ".$_GET[edit]);
}
?>
<link rel="stylesheet" href="../assets/css/openlayers/ol.css" type="text/css">
<script src="../assets/js/openlayers/ol.js"></script>
<div class="row">
  <div class="col-md-9">
    <div id="map" class="map" style="margin-bottom: 10px"></div>
  </div>
  <div class="col-md-3">
    <div id="info"></div>
    <div class="box box-info">
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
          <label for="sample-name">Name</label>
          <input class="form-control" type="text" id="sample-name" value="<?= $location[0]['name'] ?>">
        </div>
        <div class="form-group">
          <label for="sample-long">Longitude</label>
          <input class="form-control" type="text" id="sample-long" value="<?= $location[0]['long'] ?>">
        </div>
        <div class="form-group">
          <label for="sample-lat">Latitude </label>
          <input class="form-control" type="text" id="sample-lat" value="<?= $location[0]['lat'] ?>">
        </div>
        <div class="form-group">
          <label for="sample-type">Type</label>
          <select id="sample-type" class="select2" style="width: 100%;">
            <option value="ap" <?php if ($location[0]['type'] == 'ap') { echo "selected"; } ?> >Access Point</option>
            <option value="transmitter" <?php if ($location[0]['type'] == 'transmitter') { echo "selected"; } ?>>Transmitter</option>
          </select>
        </div>
        <div class="form-group">
          <?php if (isset($_GET['edit'])) : ?>
            <input type="button" class="btn btn-success btn-block" id="edit-map" value="Save" />
          <?php else : ?>
            <input type="button" class="btn btn-success btn-block" id="save-map" value="Save" />
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
  $('.select2').select2();

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
      src: "../image/map/pin-wifi-red.png"
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
    addMark(coordinate[0], coordinate[1]);
  });

  $('#save-map').on('click', function () {
    $.post("maps/sql-proc.php?n=new", {
      name: $("#sample-name").val(),
      long: $("#sample-long").val(),
      lat: $("#sample-lat").val(),
      type: $("#sample-type").val()
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status != "success") {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
        $(".has-error").removeClass("has-error");
        $.each( json, function( key, value ) {
          $("#sample-"+json[key]['col']).closest(".form-group").addClass("has-error");
          key++
        });
        $("#info").load( "../include/alert.php #callout-warning", function() {
          $('#callout-title-warning').html(json[0]['error']);
        });
      } else {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
        $("#info").load( "../include/alert.php #callout-success", function() {
          $('#callout-title-success').html("Success Add New Location!");
        });
        $(".has-error").removeClass("has-error");
      }
    });
  })

  $('#edit-map').on('click', function () {
    $.post("maps/sql-proc.php?edit", {
      name: $("#sample-name").val(),
      long: $("#sample-long").val(),
      lat: $("#sample-lat").val(),
      type: $("#sample-type").val(),
      <?php if (isset($_GET['edit'])) : ?>
      id: <?= $_GET['edit'] ?>
      <?php endif ?>
    },
    function(data) {
      console.log(data);
      var json = JSON.parse(data);
      var status = json['status'];
      if (status != "success") {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
        $(".has-error").removeClass("has-error");
        $.each( json, function( key, value ) {
          $("#sample-"+json[key]['col']).closest(".form-group").addClass("has-error");
          key++
        });
        $("#info").load( "../include/alert.php #callout-warning", function() {
          $('#callout-title-warning').html(json[0]['error']);
        });
      } else {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
        $("#info").load( "../include/alert.php #callout-success", function() {
          $('#callout-title-success').html("Success Add New Location!");
        });
        $(".has-error").removeClass("has-error");
      }
    });
  })

});
</script>
