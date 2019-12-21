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
            <li><a class="selected-status pointer">
              <i class="fas fa-ban text-yellow icoinput"></i> Change Status
            </a></li>
            <li><a class="selected-remove pointer">
              <i class="fas fa-trash-alt text-red icoinput"></i> Remove
            </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="radius/sql-proc.php?qu=all" class="menu-nav info active"><a href="#">All</a></li>
          <li url="radius/sql-proc.php?qu=admin"class="menu-nav danger" ><a href="#">Admin</a></li>
          <li url="radius/sql-proc.php?qu=customer" class="menu-nav warning"><a href="#">Customer</a></li>
          <li url="radius/sql-proc.php?qu=partner" class="menu-nav success"><a href="#">Partner</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new">New User</button>
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
          <table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="selectAll"></th>
                <th>Status</th>
                <th>Login ID</th>
                <th>Member Name</th>
                <th>Member ID</th>
                <th>Group</th>
                <th>Static IPv4</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Date Add</th>
                <th>Download Limit</th>
                <th>Upload Limit</th>
                <th>Rate Limit</th>
                <th>Password</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  Table1Gen("radius/sql-proc.php?qu=all",
  function ( row, data, index ) {
    // $('td', row).eq(2).html("<a class='pointer'>"+data[2]+"<i class='text-green pull-right fas fa-external-link-alt'></i></a>");
    // $('td', row).eq(2).find('a').on( 'click', function () {
    //   $.get('member?p=profile&id='+data[2], function(link) {
    //     $('.content-container').html(link);
    //   });
    // });
    $('td', row).eq(2).html("<a class='pointer'>"+data[2]+"</a>");
    $('td', row).eq(2).find('a').on( 'click', function () {
      table.row(this).select();
      $('#modal-default').modal('show');
      $('#modal-title-default').text("User Detail");
      $('#modal-body-default').load("radius/user-detail.php");
    });
    if ( data[14] == "Enable") {
      var span_enable = "<span class='label label-success mr10'>Enable</span>";
    } else {
      var span_enable = "<span class='label label-danger mr10'>Disable</span>";
    };
    if ( data[1] == true) {
      var span_status = "<span class='label label-info'>Online</span>";
    } else {
      var span_status = "<span class='label label-warning'>Offline</span>";
    };
    $('td', row).eq(1).html(span_enable + span_status);
    $('td', row).eq(7).html(data[7]+"<i class='text-blue pull-right fas fa-arrow-down'></i>");
    $('td', row).eq(8).html(data[8]+"<i class='text-red pull-right fas fa-arrow-up'></i>");
  });
  table.on( 'order.dt search.dt', function () {
      table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  for ( i=9 ; i<14 ; i++ ) {
    table.column( i ).visible( false );
  }

  $('.selected-edit').on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Edit Selected User");
    $('#modal-body-default').load("radius/edit-user.php");
  });
  $('.selected-status').on('click', function() {
    $('#modal-default').modal('show');
    $(".modal-dialog").removeClass("modal-lg");
    $('#modal-title-default').text("Change User Status");
    $('#modal-body-default').load("radius/change-status.php");
  });
  $('.selected-remove').on('click', function() {
    $('#modal-default').modal('show');
    $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
    $(".modal-dialog").removeClass("modal-lg");
    $('#modal-title-default').text("Remove User Data");
    $('#modal-body-default').load("radius/remove-user.php");
  });

  $('.btn-new').on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Add Radius User");
    $('#modal-body-default').load("radius/new-user.php");
  });

  $('#table1 tbody').on('dblclick','tr',function(e){
    table.row(this).select();
    rowData = table.rows({selected:  true}).data().toArray();
    $('#modal-default').modal('show');
    $('#modal-title-default').text("User Detail");
    $('#modal-body-default').load("radius/user-detail.php");
  });

</script>
