<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<script src="../assets/js/mytable/table1.js"></script>
<script src="../assets/js/menu-content.js"></script>
<script src="../assets/js/datepicker/daterange.js"></script>
<link rel="stylesheet" href="../assets/css/datepicker/daterange.css">

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
  <nav class="navbar navbar-inverse nav-fixed" style="display:none"></nav>
  <div class="row container-data">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header" data-widget="collapse">

        </div>
        <div class="box-body" style='display: none'>
          <div class="chart-container">
            <canvas id="areaChart" style="height:20vh; width:80vw"></canvas>
          </div>
        </div>

        <div class="box-footer">
          <div class="row">
            <!-- <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                <span class="description-percentage text-green">
                  <i class="fa fa-caret-up"></i> <b>Rp. 2.530.000</b>
                </span>
                <p class="description-text">TOTAL REVENUE</p>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                <span class="description-percentage text-yellow">
                  <i class="fa fa-caret-left"></i> Rp. 1.000.000
                </span>
                <p class="description-text">TOTAL COST</p>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                <span class="description-percentage text-green">
                  <i class="fa fa-caret-up"></i> Rp. 5.500.000
                </span>
                <p class="description-text">TOTAL PROFIT</p>
              </div>
            </div>
            <div class="col-sm-3 col-xs-6">
              <div class="description-block">
                <span class="description-percentage text-red">
                  <i class="fa fa-caret-down"></i> 117 / 999
                </span>
                <p class="description-text">COMPLETED PAYMENT</p>
              </div>
            </div> -->
          </div>
          <div class="col-sm-3">
            <form class="form-horizontal margin">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search Table" id="tableSearch">
                  <div class="input-group-btn">
                    <button class="btn btn-default clearinput" type="button">
                      <i class="fas fa-eraser"></i>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-3">
            <form class="form-horizontal margin">
              <div class="form-group">
                <select id="account" class="form-control select2-account" style="width: 100%;">
                  <option value=""></option>
                  <?php
                  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `deleted` = '0'");
                  foreach ($query as $key) :
                    $name = $key['name'];
                    $id = $key['id'];
                    ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </form>
          </div>
          <div class="col-sm-3">
            <form class="form-horizontal margin">
              <div class="form-group">
                <select id="type" class="form-control select2-type" style="width: 100%;">
                  <option value=""></option>
                  <?php
                  $query = sqlQuAssoc("SELECT DISTINCT type FROM wavenet.tb_translog");
                  foreach ($query as $key) :
                    $type = $key['type'];
                    ?>
                    <option value="<?= $type ?>"><?= ucfirst($type) ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </form>
          </div>
          <div class="col-sm-3">
            <form class="form-horizontal margin">
              <div class="form-group">
                <input type="button" class="form-control" placeholder="Date" id="daterange">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div id="alert"></div>
      <div class="box box-info">
        <div class="box-header" data-widget="collapse">
          <div class="btn-group">
            <a class="btn btn-default dropdown-toggle fas fa-sort-numeric-down" data-toggle="dropdown"></a>
            <ul class="dropdown-menu" role="menu">
              <li class="table-length" value="10"><a href="#">10 Entries</a></li>
              <li class="table-length" value="20"><a href="#">20 Entries</a></li>
              <li class="table-length" value="50"><a href="#">50 Entries</a></li>
              <li class="table-length" value="100"><a href="#">100 Entries</a></li>
              <li class="divider"></li>
              <li class="table-length" value="-1"><a href="#">Show All</a></li>
            </ul>
          </div>
          <div class="box-tools pull-right btn-table">
          </div>
        </div>
        <div class="box-body">
          <table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="selectAll"></th>
                <th>Account</th>
                <th>Type</th>
                <th>Number/Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Account Balance</th>
                <th>Total Balance</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="../assets/js/color-hash/color-hash.js"></script>
<script type="text/javascript">
dateone = 0;
datetwo = 0;
$(function() {
  var start = moment().startOf('month');
  var end = moment();

  function cb(start, end) {
      dateone = start.format('YYYY-MM-DD') + ' 00:00:00';
      datetwo = end.format('YYYY-MM-DD') + ' 23:59:00';
  }
  $('#daterange').daterangepicker({
      opens: "left",
      // parentEl: ".content",
      startDate: start,
      endDate: end,
      ranges: {
         'Today': [moment(), moment()],
         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
         'This Month': [moment().startOf('month'), moment().endOf('month')],
         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
         'This Year': [moment().startOf('year'), moment().endOf('year')],
      }
  }, cb);
  cb(start, end);
});


$('.select2-account').select2({
  placeholder: 'Account',
  dropdownAutoWidth : true,
  allowClear: true
});
$('.select2-type').select2({
  placeholder: 'Type',
  dropdownAutoWidth : true,
  allowClear: true
});
var ctx = document.getElementById('areaChart');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
          label: 'Balance',
          data: [12, 19, 3, 5, 2, 3],
          backgroundColor: ['rgba(255, 99, 132, 0.2)'],
          borderColor: ['rgba(255, 99, 132, 1)'],
          borderWidth: 1
      }]
    },
});

function GenerateData() {
  table.destroy();
  table.clear();
  var colorHash = new ColorHash();
  var account = $('#account').val();
  var type = $('#type').val();
  Table1Gen("billing/sql-proc.php?qr=report&account="+account+"&type="+type+"&startdate="+dateone+"&enddate="+datetwo,
  function ( row, data, index ) {
    $('td', row).eq(2).html("<span class='label' style='background-color: "+colorHash.hex(data[2])+" ;'>"+data[2]+"</span>");
    if (data[8] == "equals") {
      $('td', row).eq(0).html("<i class='fas fa-"+data[8]+" text-blue'></i>");
    } else if (data[8] == "minus") {
      $('td', row).eq(0).html("<i class='fas fa-"+data[8]+" text-red'></i>");
    } else {
      $('td', row).eq(0).html("<i class='fas fa-"+data[8]+" text-green'></i>");
    }
  });
  $('#table1').DataTable().order([0, 'desc']).draw();
}

$( document ).ready(function() {
  GenerateData();
  $('#daterange,#account,#type').on('change', function() {
    GenerateData();
  });
});

</script>
