<table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
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
$('#table1').DataTable().order([6, 'desc']).draw();
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();

for ( i=8 ; i<12 ; i++ ) {
  table.column( i ).visible( false );
}

$('#table1 tbody').on('dblclick','tr',function(e){
  table.row(this).select();
  var invid = $(this).find('a, .inv-detail').text();
  $('#modal-invoice').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
  $('#modal-title-invoice').text("Invoice Detail");
  $('#modal-body-invoice').load("billing/invoice-detail.php?id="+invid);
});
</script>
