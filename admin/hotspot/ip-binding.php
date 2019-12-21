<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="box box-solid" id="box-ip-binding">
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
  { "title": "Mac Address"},
  { "title": "Type"},
  { "title": "Server"},
  { "title": "Address"},
  { "title": "To Address"},
  { "title": "Disabled"},
  { "title": "Comment"},
  { "title": "Bypassed"},
])

for (var i = 0; i < hsdata.ip_binding.length; i++) {
  if (hsdata.ip_binding[i]['disabled'] == "true") {
    var disabled = "<span class='label label-danger'>Yes</span>";
  } else {
    var disabled = "<span class='label label-default'>No</span>";
  }
  var id = "<i class='fas fa-edit pointer text-yellow' data-id='"+hsdata.ip_binding[i]['.id']+"'></i>"
  table.row.add([
    id,
    hsdata.ip_binding[i]['mac-address'],
    hsdata.ip_binding[i]['type'],
    hsdata.ip_binding[i]['server'],
    hsdata.ip_binding[i]['address'],
    hsdata.ip_binding[i]['to-address'],
    disabled,
    hsdata.ip_binding[i]['comment'],
    hsdata.ip_binding[i]['bypassed'],
  ]).draw( false );
}
table.column( 8 ).visible( false );
</script>
