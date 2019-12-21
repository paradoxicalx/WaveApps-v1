<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<link rel="stylesheet" href="../assets/css/openlayers/ol.css" type="text/css">
<script src="../assets/js/openlayers/ol.js"></script>
<script src="../assets/js/openlayers/turf.js"></script>
<script src="../assets/js/openlayers/arc.js"></script>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= $weburl ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
  </ol>
</section>

<section class="content">
  <div class="row container-data">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-body">
              <div class="select-map">
                <select id="layer-select">
                  <option value="Road" selected>Road (static)</option>
                  <option value="RoadOnDemand">Road (dynamic)</option>
                  <option value="Aerial">Aerial</option>
                  <option value="AerialWithLabels">Aerial with labels</option>
                </select>
              </div>
              <div id="map" class="map"></div>
              <div class="select-mark">
                <div class="pretty p-default p-round p-thick" style="margin-left: 10px">
                  <input type="checkbox" id="show-mark">
                  <div class="state p-primary-o">
                    <label>Show location mark</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Point 1</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="point1">Coordinate</label>
                <select id="point1" class="form-control select2 coorsel" style="width: 100%;">
                  <option value=""></option>
                  <?php
                  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_maps WHERE `deleted` = '0' ");
                  foreach ($query as $key) :
                    $name = $key['name'];
                    $long = $key['long'];
                    $lat = $key['lat'];
                    ?>
                    <option value="<?php echo $long.", ".$lat ?>"> <?= $name ?> </option>
                  <?php endforeach ?>
                  <?php
                  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `deleted` = '0' ");
                  foreach ($query as $key) :
                    $name = $key['name'];
                    $long = $key['long'];
                    $lat = $key['lat'];
                    ?>
                    <option value="<?php echo $long.", ".$lat ?>"> <?= $name ?> </option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="form-group">
                <label for="height1">Device Height</label>
                <div class="input-group">
                  <input id="height1" type="number" class="form-control" value="0">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-flat" style="width:100px;">Meters</button>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Point 2</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="point2">Coordinate</label>
                <select id="point2" class="form-control select2 coorsel" style="width: 100%;">
                  <option value=""></option>
                  <?php
                  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_maps WHERE `deleted` = '0' ");
                  foreach ($query as $key) :
                    $name = $key['name'];
                    $long = $key['long'];
                    $lat = $key['lat'];
                    ?>
                    <option value="<?php echo $long.", ".$lat ?>"> <?= $name ?> </option>
                  <?php endforeach ?>
                  <?php
                  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `deleted` = '0' ");
                  foreach ($query as $key) :
                    $name = $key['name'];
                    $long = $key['long'];
                    $lat = $key['lat'];
                    ?>
                    <option value="<?php echo $long.", ".$lat ?>"> <?= $name ?> </option>
                  <?php endforeach ?>
                </select>
              </div>
              <div class="form-group">
                <label for="height2">Device Height</label>
                <div class="input-group">
                  <input id="height2" type="number" class="form-control" value="0">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-flat" style="width:100px;">Meters</button>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Optional</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="simulate">Simulate Tree/Building</label>
                <div class="input-group">
                  <input id="simulate" type="number" class="form-control" value="0">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-flat" style="width:100px;">Meters</button>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="angle">Antenna Angle</label>
                <div class="input-group">
                  <input id="angle" type="number" class="form-control coorsel" value="10">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-flat" style="width:100px;">Degree</button>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Elevation</h3>
              <div class="box-tools pull-right">
                <span id="info-distance">Distance: 0 Meter</span>
              </div>
            </div>
            <div class="box-body">
              <button type="button" name="button" id="generate" class="btn clear btn-block btn-success">Generate Elevation</button>
              <div class="chart chart-container" style="position: relative; height:40vh; display: none; margin-top: 10px;">
                <canvas id="elevationChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
$( document ).ready(function() {
  var iconStyle = new ol.style.Style({
    image: new ol.style.Icon(({
      anchor: [0.5, 1],
      src: "../image/map/pin-wifi-cyan.png"
    }))
  });
  function addMark(longitude, latitude) {
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

  function getBearing(loc1, loc2) {
    var point1 = turf.point(loc1);
    var point2 = turf.point(loc2);
    var bearing = turf.bearing(point1, point2);
    return Math.round(bearing * 1000) / 1000;
  }

  $('.coorsel').on('change', function() {
    removeMark('vectorLayer');
    removeMark('lineLayer');
    removeMark('sectorLayer');

    var point1 = $('#point1').val().split(',');
    var point2 = $('#point2').val().split(',');
    var angle = $('#angle').val();

    addMark(parseFloat(point1[0]), parseFloat(point1[1]))
    addMark(parseFloat(point2[0]), parseFloat(point2[1]))

    //generate line
    var line_features = [];
    var lonlat1 = ol.proj.fromLonLat([parseFloat(point1[0]), parseFloat(point1[1])]);
    var lonlat2 = ol.proj.fromLonLat([parseFloat(point2[0]), parseFloat(point2[1])]);
    var linestyle = [
      new ol.style.Style({
        stroke: new ol.style.Stroke({
          color: "red",
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
    var l_vectorSource = new ol.source.Vector({
      features: line_features
    });
    var l_vectorLayer = new ol.layer.Vector({
      source: l_vectorSource
    });
    map.addLayer(l_vectorLayer);
    l_vectorLayer.set('name', 'lineLayer');

    // Sector
    if (point1[0] && point2[0]) {
      var from = turf.point([parseFloat(point1[0]), parseFloat(point1[1])]);
      var to = turf.point([parseFloat(point2[0]), parseFloat(point2[1])]);
      var distance = turf.distance(from, to);

      var center = turf.point([parseFloat(point1[0]), parseFloat(point1[1])]);
      var radius = distance;
      var bearing = getBearing([parseFloat(point1[0]), parseFloat(point1[1])], [parseFloat(point2[0]), parseFloat(point2[1])])
      var bearing1 = bearing - (angle/2);
      var bearing2 = bearing + (angle/2);

      var sector = turf.sector(center, radius, bearing1, bearing2);

      var polygon = new ol.geom.Polygon([sector.geometry.coordinates[0]]);
      polygon.transform('EPSG:4326', 'EPSG:3857');
      var feature = new ol.Feature(polygon);
      var vectorSource = new ol.source.Vector();
      vectorSource.addFeature(feature);
      var sectorLayer = new ol.layer.Vector({
        source: vectorSource
      });
      map.addLayer(sectorLayer);
      sectorLayer.set('name', 'lineLayer');
    }

  })

  $('.select2').select2({
    placeholder: 'Lon, Lat',
    allowClear: true,
    tags: true
  });

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

  var map = new ol.Map({
    layers: layers,
    target: 'map',
    view: new ol.View({
      center: ol.proj.fromLonLat([110.366935, -7.782953]),
      zoom: 13
    })
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
    var marklocLayer = new ol.layer.Vector({
      source: vectorSource
    });
    map.addLayer(marklocLayer);
    marklocLayer.set('name', 'marklocLayer');

    marklocLayer.setVisible(false)
  }

  $.get("maps/sql-proc.php?location=all", function(data){
    var json = JSON.parse(data);
    getMark(json);
  });

  $('#show-mark').on('click', function() {
    var layersToHide = [];
    map.getLayers().forEach(function (layer) {
      if (layer.get('name') === "marklocLayer") {
        layersToHide.push(layer);
      }
    });
    var len = layersToHide.length;
    for(var i = 0; i < len; i++) {
      if ($("#show-mark").is(':checked')) {
        layersToHide[i].setVisible(true)
      } else {
        layersToHide[i].setVisible(false)
      }
    }
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

  function getDistance(loc1, loc2) {
    var lonlat1 = ol.proj.fromLonLat(loc1);
    var lonlat2 = ol.proj.fromLonLat(loc2);
    var line = new ol.geom.LineString([lonlat1, lonlat2]);
    return Math.round(line.getLength());
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

  $('#generate').on('click', function() {
    var ctx = document.getElementById('elevationChart');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Ground',
          data: [],
          backgroundColor: ['rgba(102, 51, 0, 1)'],
          borderColor: ['rgba(102, 51, 0, 0)'],
          pointRadius: 0,
          borderWidth: 1,
          spanGaps: false,
        },{
          label: 'Building',
          data: [],
          backgroundColor: ['rgba(89, 179, 0, 0.7)'],
          borderColor: ['rgba(89, 179, 0, 0)'],
          pointRadius: 0,
          borderWidth: 1,
          spanGaps: false,
        },{
          label: 'Line View',
          data: [],
          backgroundColor: ['rgba(51, 102, 204, 0)'],
          borderColor: ['rgba(51, 102, 204, 1)'],
          pointRadius: 0,
          borderWidth: 2,
          spanGaps: true,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        scales: {
          yAxes: [{
            gridLines: {
              display: false
            },
            ticks: {
              beginAtZero: true,
            }
          }],
          xAxes: [{
            gridLines: {
              display: false,
              drawOnChartArea: true
            },
            ticks: {
              display: false,
            }
          }]
        }
      }
    });

    var point1 = $('#point1').val().split(',');
    var point2 = $('#point2').val().split(',');


    var height1 = $('#height1').val();
    var height2 = $('#height2').val();
    var simulate = $('#simulate').val();

    var distance = getDistance([point1[1], point1[0]], [point2[1], point2[0]]);
    var pj = Math.round(distance/10);
    if (pj >= 1000) {
      var pj = 1000;
    }
    var points = point1[1]+","+point1[0]+","+point2[1]+","+point2[0];
    var key = "AsFhFog0dylN0aPD-0dHsunhHEs8dVE_LAMNdYiP7OWlJDRcsw0OgMjAcPp6Y3n8";

    if (point1[1] && point2[0] && $('#point1').val() != $('#point2').val()) {
      $('#generate').html("<i class='fas fa-spinner fa-spin'></i> Generating")
      $.ajax({
        url: "http://dev.virtualearth.net/REST/v1/Elevation/Polyline?points="+points+"&samples="+pj+"&key="+key,
        dataType: "jsonp",
        jsonp: "jsonp",
        success: function (data) {
          if (data) {
            var arr = data.resourceSets[0].resources[0].elevations;
            $.each( arr, function( i, val ) {
              var ground = myChart.data.datasets[0].data;
              var building = myChart.data.datasets[1].data;
              var lineview = myChart.data.datasets[2].data;

              if (i == 0) {
                lineview.push(parseInt(val) + parseInt(height1));
              } else if (i == pj-1) {
                lineview.push(parseInt(val) + parseInt(height2));
              } else {
                lineview.push(null);
              }

              myChart.data.labels.push(val);
              ground.push(val);
              building.push(parseInt(val) + parseInt(simulate));

              myChart.update();
              myChart.canvas.parentNode.style.height = '40vh';
            });
            $('#generate').html("Generate Elevation")
            $('#info-distance').text("Distance : "+formatDistance(distance))
            $('.chart-container').show()
          } else {
            $('#generate').html("Generate Elevation")
          }
        }
      });
    }
  })
})


</script>
