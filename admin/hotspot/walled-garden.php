<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-walled-garden">
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
  { "title": "Server"},
  { "title": "Src Address"},
  { "title": "Action"},
  { "title": "Dst Host"},
  { "title": "Dst Port"},
  { "title": "Disabled"},
  { "title": "Comment"},
  { "title": "Method"},
  { "title": "Path"},
  { "title": "Hits"},
  { "title": "Dynamic"},
])

for (var i = 0; i < hsdata.walled_garden.length; i++) {
  if (hsdata.walled_garden[i]['disabled'] == "true") {
    var disabled = "<span class='label label-danger'>Yes</span>";
  } else {
    var disabled = "<span class='label label-default'>No</span>";
  }
  if (hsdata.walled_garden[i]['action'] == "allow") {
    var action = "<span class='label label-success'>"+hsdata.walled_garden[i]['action']+"</span>";
  } else {
    var action = "<span class='label label-danger'>"+hsdata.walled_garden[i]['action']+"</span>";
  }
  var id = "<i class='fas fa-edit pointer text-yellow' data-id='"+hsdata.walled_garden[i]['.id']+"'></i>"
  table.row.add([
    id,
    hsdata.walled_garden[i]['server'],
    hsdata.walled_garden[i]['src-address'],
    action,
    hsdata.walled_garden[i]['dst-host'],
    hsdata.walled_garden[i]['dst-port'],
    disabled,
    hsdata.walled_garden[i]['comment'],
    hsdata.walled_garden[i]['method'],
    hsdata.walled_garden[i]['path'],
    hsdata.walled_garden[i]['hits'],
    hsdata.walled_garden[i]['dynamic'],
  ]).draw( false );
}
for ( i=8 ; i<12 ; i++ ) {
  table.column( i ).visible( false );
}
</script>
