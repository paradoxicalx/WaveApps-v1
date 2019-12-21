<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-user-profile">
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
    { "title": ""},
    { "title": "Name"},
    { "title": "Shared"},
    { "title": "Proxy"},
    { "title": "Rate Limit"},
    { "title": "Session Timeout"},
    { "title": "Parent Queue"},
    { "title": "Mac Cookie"},
    { "title": "Insert Queue Before"},
    { "title": "Queue Type"},
    { "title": "Mac Cookie Timeout"},
    { "title": "Address List"},
    { "title": "Idle Timeout"},
    { "title": "Keepalive Timeout"},
    { "title": "Status Autorefresh"},
    { "title": "Incoming Filter"},
    { "title": "Outgoing Filter"},
    { "title": "Incoming Packet Mark"},
    { "title": "Outgoing Packet Mark"},
    { "title": "Open Status Page"},
    { "title": "Advertise"},
    { "title": "Advertise URL"},
    { "title": "Advertise Interval"},
    { "title": "Advertise Timeout"},
])

for (var i = 0; i < hsdata.user_profile.length; i++) {
  if (hsdata.user_profile[i]['transparent-proxy'] == "true") {
    var proxy = "<span class='label label-danger'>Yes</span>";
  } else {
    var proxy = "<span class='label label-default'>No</span>";
  }
  if (hsdata.user_profile[i]['shared-users'] == "unlimited") {
    var maxlogin = "<span class='label label-success'>Max</span>";
  } else {
    var maxlogin = "<span class='label label-info'>"+hsdata.user_profile[i]['shared-users']+"</span>";
  }
  if (hsdata.user_profile[i]['add-mac-cookie'] == "true") {
    var cookie = "<span class='label label-success'>Yes</span>";
  } else {
    var cookie = "<span class='label label-default'>No</span>";
  }
  var id = "<i class='fas fa-edit pointer text-yellow' data-id='"+hsdata.user_profile[i]['.id']+"'></i>"
  table.row.add([
    id,
    hsdata.user_profile[i]['name'],
    maxlogin,
    proxy,
    hsdata.user_profile[i]['rate-limit'],
    hsdata.user_profile[i]['session-timeout'],
    hsdata.user_profile[i]['parent-queue'],
    cookie,
    hsdata.user_profile[i]['insert-queue-before'],
    hsdata.user_profile[i]['queue-type'],
    hsdata.user_profile[i]['mac-cookie-timeout'],
    hsdata.user_profile[i]['address-list'],
    hsdata.user_profile[i]['idle-timeout'],
    hsdata.user_profile[i]['keepalive-timeout'],
    hsdata.user_profile[i]['status-autorefresh'],
    hsdata.user_profile[i]['incoming-filter'],
    hsdata.user_profile[i]['outgoing-filter'],
    hsdata.user_profile[i]['incoming-packet-mark'],
    hsdata.user_profile[i]['outgoing-packet-mark'],
    hsdata.user_profile[i]['open-status-page'],
    hsdata.user_profile[i]['advertise'],
    hsdata.user_profile[i]['advertise-url'],
    hsdata.user_profile[i]['advertise-interval'],
    hsdata.user_profile[i]['advertise-timeout'],
    hsdata.user_profile[i]['on-login'],
    hsdata.user_profile[i]['on-logout'],
  ]).draw( false );
}
for ( i=8 ; i<24 ; i++ ) {
  table.column( i ).visible( false );
}
</script>
