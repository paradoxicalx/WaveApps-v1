<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-user">
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
$(".box-user-edit").removeClass('hide')

Table1DynGen ( [
    { "title": ""},
    { "title": "Disabled"},
    { "title": "Username"},
    { "title": "Password"},
    { "title": "Server"},
    { "title": "Profile"},
    { "title": "Download"},
    { "title": "Upload"},
    { "title": "Uptime"},
    { "title": "Comment"},
    { "title": "Limit Time"},
    { "title": "Limit Download"},
    { "title": "Limit Upload"},
    { "title": "Limit Total"},
    { "title": "Email"},
    { "title": "Packet"},
])

for (var i = 0; i < hsdata.user.length; i++) {
  if (hsdata.user[i]['disabled'] == "true") {
    var disabled = "<span class='label label-danger'>Yes</span>";
  } else {
    var disabled = "<span class='label label-default'>No</span>";
  }
  var id = "<i class='fas fa-edit pointer text-yellow' data-id='"+hsdata.user[i]['.id']+"'></i>"
  table.row.add([
    id,
    disabled,
    hsdata.user[i]['name'],
    hsdata.user[i]['password'],
    hsdata.user[i]['server'],
    hsdata.user[i]['profile'],
    bytesToSize(hsdata.user[i]['bytes-out']),
    bytesToSize(hsdata.user[i]['bytes-in']),
    hsdata.user[i]['uptime'],
    hsdata.user[i]['comment'],
    hsdata.user[i]['limit-uptime'],
    hsdata.user[i]['limit-bytes-out'],
    hsdata.user[i]['limit-bytes-in'],
    hsdata.user[i]['limit-bytes-total'],
    hsdata.user[i]['email'],
    "Out: "+hsdata.user[i]['packets-out']+", In: "+hsdata.user[i]['packets-in'],
    hsdata.user[i]['.id'],
  ]).draw( true );
}
table.column( 3 ).visible( false );
for ( i=10 ; i<16 ; i++ ) {
  table.column( i ).visible( false );
}

</script>
