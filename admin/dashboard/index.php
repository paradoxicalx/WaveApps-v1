<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<script src="../assets/js/mytable/table-log.js"></script>
<section class="content-header">
  <h1></h1>
  <ol class="breadcrumb"></ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3 id="c-cust">0</h3>
          <p>Active Customer</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        <a id="more-customer" class="small-box-footer pointer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-green">
        <div class="inner">
          <h3 id="c-ticket">0</h3>
          <p>Open Ticket</p>
        </div>
        <div class="icon">
          <i class="fas fa-ticket-alt"></i>
        </div>
        <a id="more-ticket" class="small-box-footer pointer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3 id="c-invoice">0</h3>
          <p>Unpaid Invoice</p>
        </div>
        <div class="icon">
          <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <a id="more-invoice" class="small-box-footer pointer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="small-box bg-red">
        <div class="inner">
          <h3 id="c-devices">0</h3>
          <p>Device Down</p>
        </div>
        <div class="icon">
          <i class="fas fa-network-wired"></i>
        </div>
        <a id="more-device" class="small-box-footer pointer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header">
          <i class="fas fa-chart-area"></i>
          <h3 class="box-title">Traffic Monitor</h3>
          <div class="box-tools pull-right">
            <div class="btn-group" id="realtime" data-toggle="btn-toggle">
              <button type="button" class="btn btn-success btn-xs active" id="traffic-on">On</button>
              <button type="button" class="btn btn-default btn-xs" id="traffic-off">Off</button>
            </div>
          </div>
        </div>
        <?php include 'traffic-monitor.php' ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="box box-solid bg-teal-gradient">
        <div class="box-header">
          <i class="fas fa-user-friends"></i>
          <h3 class="box-title">Online Users</h3>
          <span class="label label-success pull-right" id='count-online-user'></span>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="chart-responsive">
              <canvas id="radius-chart" height="155" width="213" style="width: 213px; height: 155px;"></canvas>
            </div>
          </div>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-pills nav-stacked" id='count-radius'>
        </div>
      </div>
      <!-- <div class="box box-solid bg-green-gradient">
        <div class="box-header">
          <i class="fas fa-sticky-note"></i>
      	  <h3 class="box-title">Notes</h3>
      	</div>
        <div class="box-body">
          <ul class="todo-list ui-sortable">
            <li>
              <span class="text">Ing ngarso sung tulodo</span>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash"></i>
              </div>
            </li>
          </ul>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> New Notes</button>
        </div>
      </div> -->
    </div>
    <div class="col-md-9">
      <div class="box box-warning">
        <div class="box-header">
          <i class="fas fa-history"></i>
      	  <h3 class="box-title">Logs</h3>
          <div class="box-tools pull-right">
            <div class="pretty p-default p-round p-thick">
              <input type="checkbox" id="getalllogs">
              <div class="state p-primary-o">
                <label>Show login logs</label>
              </div>
            </div>
            <button type="button" class="btn btn-box-tool" id="reload-log"><i class="fas fa-redo-alt"></i></button>
          </div>
      	</div>
        <div class="box-body">
          <table id="table-log" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Messages</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="../assets/js/color-hash/color-hash.js"></script>
<script src="../assets/js/dashboard.js"></script>
<script type="text/javascript">
  $('.select2').select2({
    placeholder: 'Select Routerboard'
  });

  $('#btn-new-device').on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Add New Devices");
    $('#modal-body-default').load("networking/new-devices.php");
  });

  getCount();
  getLogs();
  $('#reload-log,#getalllogs').on('click', function() {
    tablelog.destroy();
    tablelog.clear();
    getLogs();
  });

  $('#more-customer').on('click', function() {
    $('.menu.member').click();
  });
  $('#more-ticket').on('click', function() {
    $('.menu.ticket').click();
  });
  $('#more-invoice').on('click', function() {
    $('.menu.bill-sales').click();
  });
  $('#more-device').on('click', function() {
    $('.menu.net-devices').click();
  });
</script>
