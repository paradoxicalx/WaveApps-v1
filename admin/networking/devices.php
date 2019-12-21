<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<script src="../assets/js/mytable/table1.js"></script>
<script src="../assets/js/menu-content.js"></script>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
  </ol>
</section>

<section class="content">
  <nav class="navbar navbar-inverse nav-fixed">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="#" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" role="button">
          <i class="fas fa-bars"></i>
        </a>
        <a class="navbar-brand text-blue" href="<?= $weburl ?>"><span class="fas fa-home"></span></a>
      </div>
      <form class="navbar-form navbar-left">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search Table" id="tableSearch">
          <div class="input-group-btn">
            <button class="btn btn-default clearinput" type="button">
              <i class="fas fa-eraser"></i>
            </button>
          </div>
        </div>
      </form>
      <ul class="nav navbar-nav nav-menu">
        <li class="dropdown">
          <button type="button" class="navbar-btn btn btn-default btn-block disabled" data-toggle="dropdown">
            <span>Action</span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a class="selected-edit pointer">
              <i class="fas fa-edit text-green icoinput"></i> Edit
            </a></li>
            <li><a class="selected-service pointer">
              <i class="fas fa-bell text-yellow icoinput"></i> Change Service
            </a></li>
            <li><a class="selected-remove pointer">
              <i class="fas fa-trash-alt text-red icoinput"></i> Remove
            </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="networking/sql-proc.php?qd=all" class="menu-nav info active"><a href="#">All</a></li>
          <li url="networking/sql-proc.php?qd=cpe"class="menu-nav danger" ><a href="#">CPE</a></li>
          <li url="networking/sql-proc.php?qd=server" class="menu-nav warning"><a href="#">Server</a></li>
          <li url="networking/sql-proc.php?qd=distribution" class="menu-nav success"><a href="#">Distribution</a></li>
          <li url="networking/sql-proc.php?qd=vm" class="menu-nav default"><a href="#">Virtual Server</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new">New Devices</button>
        </ul>
      </div>
    </div>
  </nav>
  <div class="row container-data">
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
          <button class="btn btn-sm btn-success" id="check-devices"><i class="fas fa-sync-alt"></i></button>
          <div class="box-tools pull-right btn-table">
          </div>
        </div>
        <div class="box-body">
          <table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="selectAll"></th>
                <th>Name</th>
                <th>Area</th>
                <th>Type</th>
                <th>Model</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Uptime</th>
                <th>Mikrotik API</th>
                <th>API Username</th>
                <th>API Password</th>
                <th>Last Up</th>
                <th>Last Down</th>
                <th>Date Add</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="modal-device" class="modal modal-wide fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-device"></h4>
      </div>
      <div class="modal-body" id="modal-body-device">
      </div>
      <div class="modal-footer" id="modal-footer-device">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
Table1Gen("networking/sql-proc.php?qd=all",
function ( row, data, index ) {
  $('td', row).eq(1).html("<a class='pointer openinfo'>"+data[1]+"</a>");
  $('td', row).eq(1).find('a').on( 'click', function () {
    devicesid = data[0];
    $('#modal-device').modal('show');
    $('#modal-title-device').text("Devices Info");
    $('#modal-body-device').load("networking/info-devices.php");
  });
  if (data[6] == "Down") {
    $('td', row).eq(6).html("<span class='label label-danger'>Down</span>");
  }
  if (data[6] == "Unknown") {
    $('td', row).eq(6).html("<span class='label label-warning'>Unknown</span>");
  }
  if (data[6] == "Up") {
    $('td', row).eq(6).html("<span class='label label-success'>Up</span>");
  }

});
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();
for ( i=8 ; i<14 ; i++ ) {
  table.column( i ).visible( false );
}

$("#modal-device").on("hide.bs.modal", function() {
  $('#apidata').hide();
  if (trafficDeviceData) {
    clearInterval(trafficDeviceData);
  }
});

$('.btn-new').on('click', function() {
  $('#modal-default').modal('show');
  $('#modal-title-default').text("Add New Devices");
  $('#modal-body-default').load("networking/new-devices.php");
});
$('.selected-edit').on('click', function() {
  $('#modal-default').modal('show');
  $('#modal-title-default').text("Edit Selected Devices");
  $('#modal-body-default').load("networking/edit-devices.php");
});
$('.selected-remove').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Remove Devices");
  $('#modal-body-default').load("networking/remove-devices.php");
});
$('.selected-service').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Change Device Service");
  $('#modal-body-default').load("networking/change-service.php");
});

$('#table1 tbody').on('dblclick','tr',function(e){
  table.row(this).select();
  rowData = table.rows({selected:  true}).data().toArray();
  devicesid = rowData[0][0];
  $('#modal-device').modal('show');
  $('#modal-title-device').text("Devices Info - "+rowData[0][1]);
  $('#modal-body-device').load("networking/info-devices.php");
});

$('#check-devices').on('click', function() {
  $.post("networking/sql-proc.php?check-device", {},
  function(data) {
    var json = JSON.parse(data);
    var status = json['status'];
    if (status == "success") {
      $("#alert").load( "../include/alert.php #callout-info", function() {
        $("#callout-title-info").html("Checking complete. Up : "+json[0]['up']+", Down : "+json[0]['down']);
      });
    } else {
      $("#alert").load( "../include/alert.php #callout-warning", function() {
        $('#callout-title-warning').html("Checking devices status failed");
      });
    }
  });
});
</script>
