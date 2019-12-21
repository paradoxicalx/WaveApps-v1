<?php require "../../assets/func/sesscek.php"; ?>
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
          <ul class="dropdown-menu role="menu"">
            <li><a class="selected-edit pointer">
              <i class="fas fa-user-times text-yellow icoinput"></i> Disconnect
            </a></li>
            <li><a class="selected-remove pointer">
              <i class="fas fa-trash-alt text-red icoinput"></i> Remove
            </a></li>
          </ul>
        </li>
      </ul>
      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="radius/sql-proc.php?qs=active" class="menu-nav info active"><a href="#">Active</a></li>
          <li url="radius/sql-proc.php?qs=today"class="menu-nav warning" ><a href="#">Today</a></li>
          <li url="radius/sql-proc.php?qs=all" class="menu-nav danger"><a href="#">All Session</a></li>
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
          <div class="box-tools pull-right btn-table">
          </div>
        </div>
        <div class="box-body">
          <table id="table1" class="table table-bordered table-striped" style="width:100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="selectAll"></th>
                <th>Login ID</th>
                <th>Router</th>
                <th>Start Time</th>
                <th>Session Time</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Station</th>
                <th>IP Address</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
Table1Gen("radius/sql-proc.php?qs=active",
function ( row, data, index ) {});
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();
</script>
