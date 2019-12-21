<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="row">
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fas fa-user-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Active User</span>
        <span class="info-box-number" id="count-active">0</span>
        <button type="button" class="btn btn-info btn-xs pull-right" id="view-active" style="width: 50%">View</button>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-orange"><i class="fas fa-user-clock"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Host</span>
        <span class="info-box-number" id="count-host">0</span>
        <button type="button" class="btn btn-info btn-xs pull-right" id="view-host" style="width: 50%">View</button>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fas fa-user-times"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Expired User</span>
        <span class="info-box-number" id="count-expired">0</span>
        <button type="button" class="btn btn-info btn-xs pull-right" id="view-expired" style="width: 50%">View</button>
      </div>
    </div>
  </div>
</div>

<div class="box box-solid hide" id="box-home">
  <div class="box-header" data-widget="collapse">
    <div class="input-group" style="width:50%;">
      <input type="text" class="form-control" placeholder="Search Table" id="tableSearch">
      <div class="input-group-btn">
        <button class="btn btn-default clearinput" type="button">
          <i class="fas fa-eraser"></i>
        </button>
        <div class="btn-group">
          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button">
            <i class="fas fa-sort-numeric-down"></i>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li class="table-length" value="10"><a href="#">10 Entries</a></li>
            <li class="table-length" value="20"><a href="#">20 Entries</a></li>
            <li class="table-length" value="50"><a href="#">50 Entries</a></li>
            <li class="table-length" value="100"><a href="#">100 Entries</a></li>
            <li class="divider"></li>
            <li class="table-length" value="-1"><a href="#">Show All</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="box-tools pull-right btn-table" style="top:10px">
    </div>
  </div>
  <div class="box-body">
    <table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
      <thead>

      </thead>
    </table>
  </div>
</div>

<script src="../assets/js/mytable/table-dynamic-col.js"></script>
<script type="text/javascript">
  $('.select2').select2({
    placeholder: 'Choose device',
    allowClear: true
  });
  $('.clearinput').on('click', function() {
    $('#tableSearch').val('');
    $('#tableSearch').keyup();
  });
  $("#view-active").click();

  function tblDestroy() {
    $("#box-home").removeClass('hide')
    $("#count-active").text(hsdata.active.length)
    $("#count-host").text(hsdata.host.length)
    if (typeof table !== 'undefined') {
      table.clear();
      table.destroy();
    }
  }

  $("#view-active").on('click', function() {
    tblDestroy();
    Table1DynGen( [
        { "title": "MK ID"},
        { "title": "Username"},
        { "title": "Server"},
        { "title": "Address"},
        { "title": "Mac Address"},
        { "title": "Uptime"},
        { "title": "Download"},
        { "title": "Upload"},
        { "title": "Comment"},
        { "title": "Limit Download"},
        { "title": "Limit Upload"},
        { "title": "Limit Total"},
        { "title": "Time Left"},
        { "title": "Packet"},
    ])
    for (var i = 0; i < hsdata.active.length; i++) {
      table.row.add([
        hsdata.active[i]['.id'],
        hsdata.active[i]['user'],
        hsdata.active[i]['server'],
        hsdata.active[i]['address'],
        hsdata.active[i]['mac-address'],
        hsdata.active[i]['uptime'],
        bytesToSize(hsdata.active[i]['bytes-out']),
        bytesToSize(hsdata.active[i]['bytes-in']),
        hsdata.active[i]['comment'],
        hsdata.active[i]['limit-bytes-out'],
        hsdata.active[i]['limit-bytes-in'],
        hsdata.active[i]['limit-bytes-total'],
        hsdata.active[i]['session-time-left'],
        "Out: "+hsdata.active[i]['packets-out']+", In: "+hsdata.active[i]['packets-in'],
      ]).draw( false );
    }
    table.column( 0 ).visible( false );
    for ( i=9 ; i<14 ; i++ ) {
      table.column( i ).visible( false );
    }
  });
  $("#view-host").on('click', function() {
    tblDestroy();
    Table1DynGen( [
        { "title": "ID"},
        { "title": "Authorized"},
        { "title": "Comment"},
        { "title": "Address"},
        { "title": "To Address"},
        { "title": "Mac Address"},
        { "title": "Server"},
        { "title": "Bypassed"},
        { "title": "Uptime"},
        { "title": "Download"},
        { "title": "Upload"},
        { "title": "DHCP"},
        { "title": "Idle Time"},
        { "title": "Packet"},
    ])
    for (var i = 0; i < hsdata.active.length; i++) {
      if (hsdata.host[i]['authorized'] == "true") {
        var authorized = "<span class='label label-success'>Authorized</span>";
      } else {
        var authorized = "";
      }
      if (hsdata.host[i]['bypassed'] == "true") {
        var bypassed = "<span class='label label-success'>Yes</span>";
      } else {
        var bypassed = "<span class='label label-warning'>No</span>";
      }
      table.row.add([
        hsdata.host[i]['.id'],
        authorized,
        hsdata.host[i]['comment'],
        hsdata.host[i]['address'],
        hsdata.host[i]['to-address'],
        hsdata.host[i]['mac-address'],
        hsdata.host[i]['server'],
        bypassed,
        hsdata.host[i]['uptime'],
        bytesToSize(hsdata.active[i]['bytes-out']),
        bytesToSize(hsdata.active[i]['bytes-in']),
        hsdata.host[i]['DHCP'],
        hsdata.host[i]['idle-time'],
        "Out: "+hsdata.active[i]['packets-out']+", In: "+hsdata.active[i]['packets-in'],
      ]).draw( false );
    }
    table.column( 0 ).visible( false );
    for ( i=9 ; i<15 ; i++ ) {
      table.column( i ).visible( false );
    }
  });
  $("#view-expired").on('click', function() {
    tblDestroy();
    Table1DynGen( [
        { "title": "ID"},
        { "title": "Server"},
        { "title": "Username"},
        { "title": "Password"},
        { "title": "Profile"},
        { "title": "Time Left"},
        { "title": "Limit Download"},
        { "title": "Limit Upload"},
        { "title": "Limit Total"},
        { "title": "Uptime"},
        { "title": "Download"},
        { "title": "Upload"},
        { "title": "Disabled"},
        { "title": "Comment"},
    ])
  });

  if (typeof hsdata !== 'undefined') {
    $("#view-active").click();
  }
</script>
