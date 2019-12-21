<table id="table1" class="table table-bordered table-striped nowrap" style="width:100%">
  <thead>
    <tr id="thead-group1">
      <th rowspan="2"><input type="checkbox" class="selectAll"></th>
      <th rowspan="2">Name</th>
      <th colspan="2">Unpaid Invoice</th>
      <th colspan="2">Paid Invoice</th>
      <th colspan="2">Total</th>
      <th rowspan="2">Wallet</th>
      <th rowspan="2">Group</th>
    </tr>
    <tr id="thead-group2">
      <th>Amount</th>
      <th></th>
      <th>Amount</th>
      <th></th>
      <th>Amount</th>
      <th></th>
    </tr>
  </thead>
</table>

<script type="text/javascript">
Table1Gen("billing/sql-proc.php?qs=member-report",
function ( row, data, index ) {
  var trans = parseInt(data[3])/5;
  $('td', row).eq(1).css( 'background-image','linear-gradient(-90deg,  rgba(255,0,0,'+trans+'), rgba(255,0,0,0) 90%)' );
  $('td', row).eq(2).css( 'background-image','linear-gradient(90deg,  rgba(255,0,0,'+trans+'), rgba(255,0,0,0) 90%)' );
  if (data[3] <= 0) {
    $('td', row).eq(1).css( 'background-image','linear-gradient(-90deg,  rgba(85,230,85,0.5), rgba(255,0,0,0) 90%)' );
    $('td', row).eq(2).css( 'background-image','linear-gradient(90deg,  rgba(85,230,85,0.5), rgba(255,0,0,0) 90%)' );
  }
},
function(oSettings, json) {});

if ( table.responsive.hasHidden() ) {
  $('#thead-group1').hide();
}
$('#table1').DataTable().order([3, 'desc']).draw();
table.column(9).visible( false );
</script>
