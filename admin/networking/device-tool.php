<div class="box-body traffic-body">
  <div class="row">
    <div class="col-md-8">
      <div class="alert alert-warning alert-dismissible text-center">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fas fa-times-circle"></i></button>
        Note: all actions use API service, this will cover routeros log
      </div>
    </div>
    <div class="col-md-4">
      <div class="box box-solid">
        <div class="input-group ">
          <select id="interface-list" class="form-control select2" style="width:100%">
            <option value=""></option>
          </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-success" id="start-monitor"><i class="fas fa-play"></i></button>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div class="chart-container" style="height:250px">
        <canvas id="TrafficChart"></canvas>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box box-solid bg-aqua-gradient" id="bar-monitor">
        <div class="box-body">
          <div class="row" id="bar-cpu">
            <div class="col-md-12">
              <div class="progress-group" id="cpu-usage">
                <span class="progress-text">CPU 0</span>
                <span class="progress-number" id="cpu0">0%</span>
                <div class="progress sm">
                  <div id="progress-cpu0" class="progress-bar progress-bar-green" style="width: 0%"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="progress-group" id="memory-usage">
            <span class="progress-text">Memory Usage</span>
            <span class="progress-number" id=mem-usage><b>0</b>/0</span>
            <div class="progress sm">
              <div id="progress-mem" class="progress-bar progress-bar-red" style="width: 0%"></div>
            </div>
          </div>
          <div class="progress-group">
            <span class="progress-text">Disk Usage</span>
            <span class="progress-number" id="disk-usage"><b>0</b>/0</span>
            <div class="progress sm">
              <div id="progress-disk" class="progress-bar progress-bar-green" style="width: 0%"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="progress-group">
                <span class="progress-text">Ping</span>
                <span class="progress-number" id="ping-now">0ms</span>
                <input type="text" id="ping-host" value="8.8.8.8" style="width: 100%; color:black; text-align: center;">
              </div>
            </div>
          </div>
          <p>
            <div class="col-md-3 text-center">
              Min : <span class="label label-success"><b id="ping-min"></b>ms</span>
            </div>
            <div class="col-md-3 text-center">
              Max : <span class="label label-danger"><b id="ping-max"></b>ms</span>
            </div>
            <div class="col-md-6 text-center">
              Timeout : <span class="label label-info"><b id="ping-timeout">0</b>/<b id="ping-total">0</b></span>
            </div>
          </p>
        </div>
      </div>
    </div>
  </div>
  <hr>
</div>

<script type="text/javascript">
var ctx = document.getElementById('TrafficChart');
var DChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: 'Rx',
      data: [],
      backgroundColor: ['rgba(255, 99, 132, 0.2)'],
      borderColor: ['rgba(255, 99, 132, 1)'],
      pointBackgroundColor: [],
      pointRadius: 2,
      pointHoverRadius: 6,
      borderWidth: 1
    },{
      label: 'Tx',
      data: [],
      backgroundColor: ['rgba(0, 64, 255, 0.2)'],
      borderColor: ['rgba(0, 64, 255, 1)'],
      pointBackgroundColor: [],
      pointRadius: 2,
      pointHoverRadius: 6,
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    animation: false,
    scales: {
      yAxes: [{
        gridLines: {display: true},
        ticks: {
          beginAtZero: true,
          callback: function(label, index, labels) {
            return ConvertBytes(label);
          }
        }
      }],
      xAxes: [{
        gridLines: {
          display: false,
          drawOnChartArea: true
        }
      }]
    }
  }
});

deviceMobTraffic = function() {
  var datalabel = DChart.data.labels;
  var rxdata = DChart.data.datasets[0].data;
  var txdata = DChart.data.datasets[1].data;
  var rxpoin = DChart.data.datasets[0].pointBackgroundColor;
  var txpoin = DChart.data.datasets[1].pointBackgroundColor;
  var lastrx = rxdata[rxdata.length-1];
  var lasttx = txdata[txdata.length-1];
  $.post("networking/sql-proc.php?i=device-traffic", {
    id: devicesid,
    interface: $('#interface-list').val(),
    host: $('#ping-host').val(),
  }, function(data){
    var midata = JSON.parse(data);
    var TX=parseInt(midata['rx']);
    var RX=parseInt(midata['tx']);
    var arnumb = rxdata.length;
    var dt = new Date();
    var time = dt.getSeconds();
    // Graph Traffic
    if (arnumb >= 12) {
      datalabel.shift();
      rxdata.shift();
      txdata.shift();
    }
    DChart.data.labels.push(time);
    rxdata.push(RX);
    rxpoin.push("red");
    DChart.data.datasets[0].label = "RX : "+ConvertBytes(RX);
    txdata.push(TX);
    txpoin.push("blue");
    DChart.data.datasets[1].label = "TX : "+ConvertBytes(TX);
    $('#cpu0').text(midata['cpu-load']+"%");
    $('#progress-cpu0').css("width", midata['cpu-load']+"%");
    if (midata['cpu-load'] < 50) {
      $('#progress-cpu').removeClass().addClass("progress-bar progress-bar-success");
    } else if (midata['cpu-load'] < 80) {
      $('#progress-cpu').removeClass().addClass("progress-bar progress-bar-warning");
    } else {
      $('#progress-cpu').removeClass().addClass("progress-bar progress-bar-danger");
    }
    var memoryused = midata['total-memory']-midata['free-memory'];
    $('#mem-usage').html("<b>"+bytesToSize(memoryused)+"</b>/"+bytesToSize(midata['total-memory']));
    var mempercent = Math.ceil((parseInt(memoryused)/parseInt(midata['total-memory']))*100);
    $('#progress-mem').css("width", mempercent+"%");
    if (mempercent < 50) {
      $('#progress-mem').removeClass().addClass("progress-bar progress-bar-success");
    } else if (mempercent < 80) {
      $('#progress-mem').removeClass().addClass("progress-bar progress-bar-warning");
    } else {
      $('#progress-mem').removeClass().addClass("progress-bar progress-bar-danger");
    }
    var diskused = midata['total-hdd-space']-midata['free-hdd-space'];
    $('#disk-usage').html("<b>"+bytesToSize(diskused)+"</b>/"+bytesToSize(midata['total-hdd-space']));
    var diskpercent = Math.ceil((parseInt(diskused)/parseInt(midata['total-hdd-space']))*100);
    $('#progress-disk').css("width", diskpercent+"%");
    if (diskpercent < 50) {
      $('#progress-disk').removeClass().addClass("progress-bar progress-bar-success");
    } else if (diskpercent < 80) {
      $('#progress-disk').removeClass().addClass("progress-bar progress-bar-warning");
    } else {
      $('#progress-disk').removeClass().addClass("progress-bar progress-bar-danger");
    }
    if (!$('#ping-max').text()) { var pingmax = 0; } else { var pingmax = parseInt($('#ping-max').text()); }
    if (!$('#ping-min').text()) { var pingmin = 99999; } else { var pingmin = parseInt($('#ping-min').text()); }
    var pingtotal = parseInt($('#ping-total').text());
    var pingtimeout = parseInt($('#ping-timeout').text());
    if (midata['ping-stat']) {
      $('#ping-now').text(midata['ping-stat']);
      $('#ping-timeout').text(pingtimeout+1);
    } else {
      $('#ping-now').text(midata['ping-time']+"ms");
      if (parseInt(midata['ping-time']) > pingmax) { $('#ping-max').text(midata['ping-time']); }
      if (parseInt(midata['ping-time']) < pingmin) { $('#ping-min').text(midata['ping-time']); }
      $('#ping-total').text(pingtotal+1);
    }

    DChart.update();
  });
}

trafficDeviceData = setInterval(deviceMobTraffic, 5000);
clearInterval(trafficDeviceData);

$('#start-monitor').on('click', function() {
  if (!$('#start-monitor').hasClass('onplay') && $('#interface-list').val()) {
    $('#start-monitor').removeClass('btn-success');
    $('#start-monitor').addClass('onplay btn-danger');
    $('#start-monitor').html("<i class='fas fa-pause'></i>");
    clearInterval(trafficDeviceData);
    trafficDeviceData = setInterval(deviceMobTraffic, 5000);
  } else {
    $('#start-monitor').removeClass('onplay btn-danger');
    $('#start-monitor').addClass('btn-success');
    $('#start-monitor').html("<i class='fas fa-play'></i>");
    clearInterval(trafficDeviceData);
    $('#ping-max,#ping-min,#ping-total,#ping-timeout,#ping-now').text("0");
  }
});

</script>
