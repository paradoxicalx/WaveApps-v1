var ctx2 = document.getElementById('radius-chart');
var myChart2 = new Chart(ctx2, {
  type: 'doughnut',
  data: {
    datasets: [{
      data: [],
      backgroundColor: [],
    }],
    labels: []
  },
  options: {
    responsive: true,
    legend: {
      display: false,
    },
    title: {
      display: false,
    },
    animation: {
      animateScale: true,
      animateRotate: true
    }
  }
})

function radCount() {
  $.get("dashboard/sql-proc.php?radiuscount", function(data) {
    var colorHash = new ColorHash();
    var json = JSON.parse(data);
    var totalonline = 0;
    var totaloffline = 0;
    for (var i = 0; i < json.length; i++) {
      $('#count-radius').append('<li><a>' + json[i]['group'] + '<span class="pull-right text-red"><b class="text-green">' + json[i]['online'] + '</b>/' + json[i]['user'] + '</span></a></li>')
      totalonline = totalonline + json[i]['online'];
      totaloffline = totaloffline + (json[i]['user'] - json[i]['online']);
      myChart2.data.datasets[0].data.push(json[i]['online'])
      myChart2.data.labels.push(json[i]['group'])
      myChart2.data.datasets[0].backgroundColor.push(colorHash.hex(json[i]['group']))
    }
    myChart2.data.datasets[0].data.push(totaloffline)
    myChart2.data.labels.push('Offline')
    myChart2.data.datasets[0].backgroundColor.push('#fff')
    $('#count-online-user').text("Online : " + totalonline)
    myChart2.update();
  });
}
radCount();

function getCount() {
  $.post("dashboard/sql-proc.php?getcount", {},
    function(data) {
      var dat = JSON.parse(data);
      $('#c-cust').text(dat['countcustomer']);
      $('#c-ticket').text(dat['countticket']);
      $('#c-invoice').text(dat['countinvoice']);
      $('#c-devices').text(dat['countdevice']);
    });
}

function getLogs() {
  var colorHash = new ColorHash();
  var getall = document.getElementById("getalllogs").checked;
  if (getall == true) {
    var loglink = "dashboard/sql-proc.php?getlog=all";
  } else {
    var loglink = "dashboard/sql-proc.php?getlog";
  }
  TableLogGen(loglink,
    function(row, data, index) {
      $('td', row).eq(1).html("<span class='label' style='background-color: " + colorHash.hex(data[1]) + " ;'>" + data[1] + "</span>");
    });
  tablelog.draw();
}

var ctx = document.getElementById('TrafficChart');
var myChart = new Chart(ctx, {
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
    }, {
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
        gridLines: {
          display: true
        },
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

clearInterval(TrafficInterval);
trafficChartData = function() {
  getCount();
  var datalabel = myChart.data.labels;
  var rxdata = myChart.data.datasets[0].data;
  var txdata = myChart.data.datasets[1].data;
  var rxpoin = myChart.data.datasets[0].pointBackgroundColor;
  var txpoin = myChart.data.datasets[1].pointBackgroundColor;
  var lastrx = rxdata[rxdata.length - 1];
  var lasttx = txdata[txdata.length - 1];
  $.post("dashboard/getSNMPdata.php", {
      id: $('#ro-list').val(),
      lastrx: lastrx,
      lasttx: lasttx,
    },
    function(data) {
      try {
        var midata = JSON.parse(data);
      } catch (e) {
        clearInterval(TrafficInterval);
      };
      if (midata['status'] === true) {
        var TX = parseInt(midata['rx']);
        var RX = parseInt(midata['tx']);
        var arnumb = rxdata.length;
        var dt = new Date();
        var time = dt.getSeconds();
        // Graph Traffic
        if (arnumb >= 12) {
          datalabel.shift();
          rxdata.shift();
          txdata.shift();
        }
        myChart.data.labels.push(time);
        rxdata.push(RX);
        rxpoin.push("red");
        myChart.data.datasets[0].label = "RX : " + ConvertBytes(RX);
        txdata.push(TX);
        txpoin.push("blue");
        myChart.data.datasets[1].label = "TX : " + ConvertBytes(TX);
        // CPU Usage
        for (var i = 0; i < midata['cpu'].length; i++) {
          $('#cpu' + i).text(midata['cpu'][i] + "%");
          $('#progress-cpu' + i).css("width", midata['cpu'][i] + "%");
          if (midata['cpu'][i] < 50) {
            $('#progress-cpu' + i).removeClass().addClass("progress-bar progress-bar-success");
          } else if (midata['cpu'][i] < 80) {
            $('#progress-cpu' + i).removeClass().addClass("progress-bar progress-bar-warning");
          } else {
            $('#progress-cpu' + i).removeClass().addClass("progress-bar progress-bar-danger");
          }
        }
        // Memory Usage
        $('#mem-usage').html("<b>" + bytesToSize(midata['memory_used']) + "</b>/" + bytesToSize(midata['memory_total']));
        var mempercent = Math.ceil((parseInt(midata['memory_used']) / parseInt(midata['memory_total'])) * 100);
        $('#progress-mem').css("width", mempercent + "%");
        if (mempercent < 50) {
          $('#progress-mem').removeClass().addClass("progress-bar progress-bar-success");
        } else if (mempercent < 80) {
          $('#progress-mem').removeClass().addClass("progress-bar progress-bar-warning");
        } else {
          $('#progress-mem').removeClass().addClass("progress-bar progress-bar-danger");
        }
        // Disk Usage
        var usedhdd = parseInt(midata['total_hdd']) - parseInt(midata['free_hdd']);
        var hddpercent = Math.ceil((usedhdd / parseInt(midata['total_hdd'])) * 100);
        $('#disk-usage').html("<b>" + bytesToSize(usedhdd) + "</b>/" + bytesToSize(midata['total_hdd']));
        $('#progress-disk').css("width", hddpercent + "%");
        if (hddpercent < 50) {
          $('#progress-disk').removeClass().addClass("progress-bar progress-bar-success");
        } else if (hddpercent < 80) {
          $('#progress-disk').removeClass().addClass("progress-bar progress-bar-warning");
        } else {
          $('#progress-disk').removeClass().addClass("progress-bar progress-bar-danger");
        }
        // Uptime
        $('#uptime-router').html(midata['uptime']);
        $('#timezone-router').text(midata['timezone']);
        // Download Total
        $('#dl-total').html(bytesToSize(midata['dl']));
        $('#dl-average').html(bytesToSize(midata['dl_average']) + "/day");
        // Upload Total
        $('#ul-total').html(bytesToSize(midata['ul']));
        $('#ul-average').html(bytesToSize(midata['ul_average']) + "/day");
        // Temperature
        if (midata['temperature'] > 0) {
          $('#temperature').html((midata['temperature'] / 10) + " &#8451");
        }
        if (midata['voltage'] > 0) {
          $('#voltage').html("Voltage : " + (midata['voltage'] / 10) + " V");
        }
        // Cek update API
        if (midata['update'] == "true") {
          updateAPIdata();
        }
        myChart.update();
        myChart.canvas.parentNode.style.height = '250px';
        $("#info-traffic").html("");
      } else {
        $("#info-traffic").html("<div class='alert alert-warning alert-dismissible'>" +
          "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>" +
          "Failed to get SNMP data. Please check device <b class='pointer' onclick='openSetting()'>settings</b>" +
          "</div>");
        $("#traffic-off").click();
      }
    });
};
$(trafficChartData);
var TrafficInterval = setInterval(trafficChartData, 5000);

function updateAPIdata() {
  clearInterval(TrafficInterval);
  $.post("dashboard/sql-proc.php?updateapidata", {
      oid_id: $('#ro-list').val()
    },
    function(data) {
      TrafficInterval = setInterval(trafficChartData, 5000);
    });
}

$('#traffic-on').on('click', function() {
  clearInterval(TrafficInterval);
  TrafficInterval = setInterval(trafficChartData, 5000);
  if ($('#traffic-off').hasClass("active")) {
    $(trafficChartData);
  }
  $('#traffic-on').addClass("active btn-success");
  $('#traffic-off').removeClass("active btn-danger");
});

$('#traffic-off').on('click', function() {
  clearInterval(TrafficInterval);
  $('#traffic-on').removeClass("active btn-success");
  $('#traffic-off').addClass("active btn-danger");
});

$('#device-setting').on('click', function() {
  var id = $('#ro-list').val();
  $('#modal-default').modal('show');
  $('#modal-title-default').text("Devices Setting");
  $('#modal-body-default').load("dashboard/device-setting.php?id=" + id + "");
});

function openSetting() {
  $('#device-setting').click();
}

$('#ro-list').on('change', function() {
  $.post("dashboard/sql-proc.php?changeDevice", {
      id: $('#ro-list').val()
    },
    function(data) {
      var mdata = JSON.parse(data);
      myChart.data.labels = [];
      myChart.data.datasets[0].data = [];
      myChart.data.datasets[1].data = [];
      myChart.update();
      $('#temperature').html("Unknown");
      $('#voltage').html("Unknown");
      $('#dl-total,#dl-average,#ul-total,#ul-average').html("");
      $(trafficChartData);
      if (mdata[0]['data'][0]['cpu-count'] > 1) {
        $('#bar-cpu').html("");
        for (var i = 0; i < mdata[0]['data'][0]['cpu-count']; i++) {
          $('#bar-cpu').append(
            "<div class='col-md-6'>" +
            "<div class='progress-group' id='cpu-usage'>" +
            "<span class='progress-text'>CPU " + i + "</span>" +
            "<span class='progress-number' id='cpu" + i + "'>0%</span>" +
            "<div class='progress sm'>" +
            "<div id='progress-cpu" + i + "' class='progress-bar progress-bar-green' style='width: 0%'>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>");
        }
      } else {
        $('#bar-cpu').html("");
        $('#bar-cpu').append(
          "<div class='col-md-12'>" +
          "<div class='progress-group' id='cpu-usage'>" +
          "<span class='progress-text'>CPU 0</span>" +
          "<span class='progress-number' id='cpu0'>0%</span>" +
          "<div class='progress sm'>" +
          "<div id='progress-cpu0' class='progress-bar progress-bar-green' style='width: 0%'>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>");
      }
    });
});