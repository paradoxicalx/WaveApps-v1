<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<script src="../assets/js/mytable/table1.js"></script>
<div id="alert"></div>
<div class="box box-info">
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
        <tr>
          <th><input type="checkbox" class="selectAll"></th>
          <th>Title</th>
          <th>Last Reply</th>
          <th>Time Create</th>
          <th>Ticket Number</th>
          <th>Topic</th>
          <th>assign</th>
          <th>Member</th>
          <th>Device</th>
          <th>Invoice</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script type="text/javascript">
  Table1Gen("ticket/sql-proc.php?q=<?= $_GET['s'] ?>",
  function ( row, data, index ) {
    var att = "";
    var proc = "";
    if ( data[10] == "critical") {$('td', row).eq(0).html("<i class='far fa-circle text-red'></i>");}
    if ( data[10] == "major") {$('td', row).eq(0).html("<i class='far fa-circle text-yellow'></i>");}
    if ( data[10] == "minor") {$('td', row).eq(0).html("<i class='far fa-circle text-blue'></i>");}
    if ( data[12] == "yes") {var att = "<i class='fas fa-paperclip pull-right'></i>";}
    if ( data[11] == "new") {var proc = "<span class='label label-info'>New</span> ";}
    $('td', row).eq(1).html(proc+"<a class='pointer' data-ticket='"+data[0]+"'>"+data[1]+"</a>"+att);
    $('td', row).eq(1).find('a').on( 'click', function () {
      $.get('ticket/view-ticket.php?id='+data[0], function(data) {
        $('#ticlist').html(data);
      });
    });
  });

  for ( i=5 ; i<10 ; i++ ) {
    table.column( i ).visible( false );
  }

  $('#table1 tbody').on('dblclick','tr',function(e){
    var ticketid = $(this).find('a').data('ticket');
    $.get('ticket/view-ticket.php?id='+ticketid, function(data) {
      $('#ticlist').html(data);
    });
  });

  $('.clearinput').on('click', function() {
    $('#tableSearch').val('');
    $('#tableSearch').keyup();
  });

</script>
