<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-server-profile">
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
    { "title": "Address"},
    { "title": "DNS Name"},
    { "title": "HTML Directory"},
    { "title": "Radius"},
    { "title": "Login By"},
    { "title": "Comment"},
    { "title": "Directory Override"},
    { "title": "Rate Limit"},
    { "title": "Proxy"},
    { "title": "SMTP"},
    { "title": "HTTP Cookie Lifetime"},
    { "title": "Split User Domain"},
])

for (var i = 0; i < hsdata.server_profile.length; i++) {
  if (hsdata.server_profile[i]['use-radius'] == "true") {
    var radius = "<span class='label label-success'>Yes</span>";
  } else {
    var radius = "<span class='label label-default'>No</span>";
  }
  var id = "<i class='fas fa-edit pointer text-yellow' data-id='"+hsdata.server_profile[i]['.id']+"'></i>"
  table.row.add([
    id,
    hsdata.server_profile[i]['name'],
    hsdata.server_profile[i]['hotspot-address'],
    hsdata.server_profile[i]['dns-name'],
    hsdata.server_profile[i]['html-directory'],
    radius,
    hsdata.server_profile[i]['login-by'],
    hsdata.server_profile[i]['comment'],
    hsdata.server_profile[i]['html-directory-override'],
    hsdata.server_profile[i]['rate-limit'],
    hsdata.server_profile[i]['http-proxy'],
    hsdata.server_profile[i]['smtp-server'],
    hsdata.server_profile[i]['http-cookie-lifetime'],
    hsdata.server_profile[i]['split-user-domain'],
  ]).draw( false );
}
for ( i=8 ; i<14 ; i++ ) {
  table.column( i ).visible( false );
}
</script>
