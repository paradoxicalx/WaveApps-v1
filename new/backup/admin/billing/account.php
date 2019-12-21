<?php require "../../assets/func/sesscek.php"; ?>
<script src="../assets/js/mytable/table1.js"></script>
<script src="../assets/js/mytable/table2.js"></script>
<script src="../assets/js/menu-content.js"></script>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= $weburl ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
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
              <i class="fas fa-edit text-green icoinput"></i> Edit
            </a></li>
            <li><a class="selected-remove pointer">
              <i class="fas fa-trash-alt text-red icoinput"></i> Remove
            </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <button class="btn btn-warning navbar-btn btn-block btn-transfer">
            <i class="fas fa-random icoinput"></i> Transfer
          </button>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new">New Account</button>
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
                <th>Name</th>
                <th>Balance</th>
                <th>Bank Account</th>
                <th>Phone</th>
                <th>Bank URL</th>
                <th>Description</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div class="row text-center">
      <h4><span class="label label-warning">Transfer History</span></h4>
    </div>
    <div class="col-md-12">
      <div id="alert"></div>
      <div class="box box-success">
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
          <div class="box-tools pull-right btn-table2">
          </div>
        </div>
        <div class="box-body">
          <table id="table2" class="table table-bordered table-striped" style="width:100%">
            <thead>
              <tr>
                <th></th>
                <th>From</th>
                <th>To</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
Table1Gen("billing/sql-proc.php?q=account",
function ( row, data, index ) {
  // $('td', row).eq(0).html(index+1);
});
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();

Table2Gen("billing/sql-proc.php?q=transfer",
function ( row, data, index ) {});
table2.on( 'order.dt search.dt', function () {
    table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();

$('.btn-new').on('click', function() {
  $('#modal-default').modal('show');
  $('#modal-title-default').text("Add New Account");
  $('#modal-body-default').load("billing/new-account.php");
});
$('.btn-transfer').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Transfer Balance");
  $('#modal-body-default').load("billing/transfer.php");
});
$('.selected-edit').on('click', function() {
  $('#modal-default').modal('show');
  $('#modal-title-default').text("Edit Selected Account");
  $('#modal-body-default').load("billing/edit-account.php");
});
$('.selected-remove').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Remove Account");
  $('#modal-body-default').load("billing/remove-account.php");
});
</script>
