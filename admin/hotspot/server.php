<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-server">
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

<script type="text/javascript">
$('.clearinput').on('click', function() {
  $('#tableSearch').val('');
  $('#tableSearch').keyup();
});

Table1DynGen ( [
    { "title": "Proxy"},
    { "title": "Disabled"},
    { "title": "Name"},
    { "title": "Interface"},
    { "title": "Profile"},
    { "title": "Address Pool"},
    { "title": "Host Address"},
    { "title": "Max Login"},
    { "title": "HTTPS"},
    { "title": "Idle Timeout"},
    { "title": "Keepalive Timeout"},
    { "title": "Login Timeout"},
])

for (var i = 0; i < hsdata.server.length; i++) {
  if (hsdata.server[i]['proxy-status'] == "running") {
    var proxy = "<span class='label label-success'>Running</span>";
  } else {
    var proxy = "<span class='label label-warning'>Stoped</span>";
  }
  if (hsdata.server[i]['disabled'] == "true") {
    var disabled = "<span class='label label-danger'>Yes</span>";
  } else {
    var disabled = "<span class='label label-default'>No</span>";
  }
  if (hsdata.server[i]['HTTPS'] == "true") {
    var https = "<span class='label label-success'>Yes</span>";
  } else {
    var https = "<span class='label label-default'>No</span>";
  }
  table.row.add([
    proxy,
    disabled,
    hsdata.server[i]['name'],
    hsdata.server[i]['interface'],
    hsdata.server[i]['profile'],
    hsdata.server[i]['address-pool'],
    hsdata.server[i]['ip-of-dns-name'],
    hsdata.server[i]['addresses-per-mac'],
    https,
    hsdata.server[i]['idle-timeout'],
    hsdata.server[i]['keepalive-timeout'],
    hsdata.server[i]['login-timeout'],

  ]).draw( false );
}
// table.column( 2 ).visible( false );
for ( i=8 ; i<12 ; i++ ) {
  table.column( i ).visible( false );
}
</script>
