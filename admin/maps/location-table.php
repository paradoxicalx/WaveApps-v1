<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<script src="../assets/js/mytable/table1.js"></script>

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
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Longitude</th>
            <th>Latitude</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
Table1Gen("maps/sql-proc.php?list",
function ( row, data, index ) {
  $('td', row).eq(0).html("<a class='pointer' onclick='editLocation("+data[0]+")''><i class='fas fa-edit'></i></a>");
  if ( data[2] == "ap") {
    $('td', row).eq(2).html("Access Point");
    data[2] = "Access Point";
  } else {
    $('td', row).eq(2).html("Transmitter");
    data[2] = "Transmitter";
  }
});

function editLocation(id) {
  $('#modal-map').modal('show');
  $('#modal-title-map').text("Edit New Location");
  $('#modal-body-map').load("maps/new-location.php?edit="+id);
}

$('.btn-new').on('click', function() {
  $('#modal-map').modal('show');
  $('#modal-title-map').text("Add New Location");
  $('#modal-body-map').load("maps/new-location.php");
});
</script>
