<table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
  <thead>
    <tr>
      <th><input type="checkbox" class="selectAll"></th>
      <th>Type</th>
      <th>Transaction ID</th>
      <th>Member Name</th>
      <th>Member ID</th>
      <th>Pay To</th>
      <th>Refund From</th>
      <th>Method</th>
      <th>Total Amount</th>
      <th>Pay Date</th>
    </tr>
  </thead>
</table>

<script type="text/javascript">
Table1Gen("billing/sql-proc.php?qs=payment-report",
function ( row, data, index ) {
  if ( data[1] == "Payment") {
    $('td', row).eq(1).html("<span class='label label-success'>Payment</span>");
  }
  if (data[1] == "Refund") {
    $('td', row).eq(1).html("<span class='label label-warning'>Refund</span>");
  };
  if (data[1] == "Wallet") {
    $('td', row).eq(1).html("<span class='label label-info'>Wallet</span>");
  };
});
$('#table1').DataTable().order([9, 'desc']).draw();
table.column(6).visible( false );
table.on( 'order.dt search.dt', function () {
    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
}).draw();

</script>
