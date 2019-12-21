<?php require "../../assets/func/sesscek.php"; ?>
<script src="../assets/js/mytable/table1.js"></script>
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
            <li><a class="selected-makepay pointer">
              <i class="fas fa-money-bill-wave text-green icoinput"></i> Make Payment
            </a></li>
            <li><a class="selected-print pointer">
              <i class="fas fa-print text-blue icoinput"></i> Print
            </a></li>
            <li><a class="selected-refund pointer">
              <i class="fas fa-funnel-dollar text-yellow icoinput"></i> Refund
            </a></li>
            <li><a class="selected-remove pointer">
              <i class="fas fa-trash-alt text-red icoinput"></i> Remove
            </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="billing/sql-proc.php?qs=invoice" class="menu-nav info active"><a href="#">Invoice</a></li>
          <li url="billing/sql-proc.php?qs=member-report"class="menu-nav danger" ><a href="#">Member Report</a></li>
          <li url="billing/sql-proc.php?qs=payment-report" class="menu-nav warning"><a href="#">Payment Report</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new">New Invoice</button>
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
                <th>Status</th>
                <th>Invoice ID</th>
                <th>Member</th>
                <th>Total Bill</th>
                <th>Identity</th>
                <th>Inv Date</th>
                <th>Due Date</th>
                <th>Paid Date</th>
                <th>Pay To</th>
                <th>Payment</th>
                <th>Notes</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div id="modal-invoice" class="modal modal-wide fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-invoice"></h4>
      </div>
      <div class="modal-body" id="modal-body-invoice">
      </div>
      <div class="modal-footer" id="modal-footer-invoice">
      </div>
    </div>
  </div>
</div>
<div id="modal-payment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-payment"></h4>
      </div>
      <div class="modal-body" id="modal-body-payment">
      </div>
      <div class="modal-footer" id="modal-footer-payment">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../assets/js/currency/currency.js"></script>
<script type="text/javascript">
Table1Gen("billing/sql-proc.php?qs=invoice",
function ( row, data, index ) {
  if ( data[1] == "paid") {
    $('td', row).eq(1).html("<span class='label label-success'>PAID OFF</span>");
  }
  if (data[1] == "unpaid") {
    $('td', row).eq(1).html("<span class='label label-danger'>UNPAID</span>");
  };
  if (data[1] == "refund") {
    $('td', row).eq(1).html("<span class='label label-warning'>REFUND</span>");
  };
  $('td', row).eq(2).html("<a class='pointer inv-detail'>"+data[2]+"</a>");
  $('td', row).eq(2).find('a').on( 'click', function () {
    var invid = $(this).find('a, .inv-detail').text();
    $('#modal-invoice').modal('show');
    $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
    $('#modal-title-invoice').text("Invoice Detail");
    $('#modal-body-invoice').load("billing/invoice-detail.php?id="+data[2]);
  });
});
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();

for ( i=8 ; i<12 ; i++ ) {
  table.column( i ).visible( false );
}

$(".modal-wide").on("show.bs.modal", function() {
  var height = $(window).height() - 150;
  $('#modal-body-invoice').css("height", height);
});

$('.btn-new').on('click', function() {
  $('#modal-invoice').modal('show');
  $('#modal-title-invoice').text("Create New Invoice");
  $('#modal-body-invoice').load("billing/new-invoice.php");
});

$('#modal-invoice').on('hide.bs.modal', function (e) {
  $(".modal-header,.modal-footer").removeClass("error warning success");
  $(".modal-title,.modal-footer").html("");
  $('.modal-content').removeAttr('style');
})

$('#table1 tbody').on('dblclick','tr',function(e){
  table.row(this).select();
  var invid = $(this).find('a, .inv-detail').text();
  $('#modal-invoice').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
  $('#modal-title-invoice').text("Invoice Detail");
  $('#modal-body-invoice').load("billing/invoice-detail.php?id="+invid);
});

$('.selected-makepay').on('click', function() {
  $('#modal-payment').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-payment').text("Make Payment");
  $('#modal-body-payment').load("billing/make-payment.php");
});

$('.selected-remove').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Remove Invoice");
  $('#modal-body-default').load("billing/remove-invoice.php");
});

$('.selected-refund').on('click', function() {
  $('#modal-default').modal('show');
  $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-title-default').text("Refund Invoice");
  $('#modal-body-default').load("billing/refund-invoice.php");
});

$('.selected-print').on('click', function() {
  $('#modal-invoice').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
  $('#modal-title-invoice').text("Print Invoice");
  $('#modal-body-invoice').load("billing/invoice-detail.php?");
});

</script>
