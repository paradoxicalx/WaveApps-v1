<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>

<div class="nav-tabs-custom" id="datamember">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_i1" data-toggle="tab">Information</a></li>
    <li><a href="#tab_i2" data-toggle="tab">Devices</a></li>
    <li><a href="#tab_i3" data-toggle="tab">Services</a></li>
    <li><a href="#tab_i4" data-toggle="tab">Invoice</a></li>
    <li><a href="#tab_i5" data-toggle="tab">Ticket</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab_i1">
      <div class="row">
        <div class="col-sm-3">
          <img id="i-usrimg" style="width:100%; margin-bottom:10px" id="image" src="...">
        </div>
        <div class="col-sm-9">
          <table id="table-information" class="table table-bordered table-striped" style="width:100%">
            <tbody class="memberinfo">
              <tr>
                <td>User ID</td>
                <td id="i-userid"></td>
              </tr>
              <tr>
                <td>Role</td>
                <td id="i-role"></td>
              </tr>
              <tr>
                <td>Name</td>
                <td id="i-name"></td>
              </tr>
              <tr>
                <td>Phone</td>
                <td id="i-phone"></td>
              </tr>
              <tr>
                <td>Date Added</td>
                <td id="i-date"></td>
              </tr>
              <tr>
                <td>Status</td>
                <td id="i-status"></td>
              </tr>
              <tr>
                <td>Username</td>
                <td id="i-username"></td>
              </tr>
              <tr>
                <td>Email</td>
                <td id="i-email"></td>
              </tr>
              <tr>
                <td>Notes</td>
                <td id="i-notes"></td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-sm-12" id="i-maps"></div>
          </div>
          <hr>
          <table id="table-location" class="table table-bordered table-striped" style="width:100%">
            <tr>
              <td>Coordinates</td>
              <td id="i-coordinate"></td>
            </tr>
            <tr>
              <td>Address</td>
              <td id="i-address"></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab_i2"> <!-- Devices -->
      <table id="table-device" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Status</th>
            <th>Name</th>
            <th>Area</th>
            <th>Type</th>
            <th>Model</th>
            <th>IP Address</th>
            <th>Uptime</th>
          </tr>
        </thead>
      </table>
      <hr>
      <h4>Logs</h4>
      <table id="table-log-device-i" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Messages</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="tab-pane" id="tab_i3"> <!-- Service -->
      <table id="table-service" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" class="selectAll"></th>
            <th>Login ID</th>
            <th>Password</th>
            <th>Group</th>
            <th>Static IPv4</th>
            <th>Status</th>
            <th>Download</th>
            <th>Upload</th>
          </tr>
        </thead>
      </table>
      <hr>
      <h4>Session</h4>
      <table id="table-log-session" class="table table-bordered table-striped nowrap" style="width:100%">
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
    <div class="tab-pane" id="tab_i4"> <!-- Invoice -->
      <table id="table-invoice" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Status</th>
            <th>Invoice ID</th>
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
    <div class="tab-pane" id="tab_i5"> <!-- Ticket -->
      <table id="table-ticket" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" class="selectAll"></th>
            <th>Title</th>
            <th>Last Reply</th>
            <th>Time Create</th>
            <th>Ticket Number</th>
            <th>Topic</th>
            <th>assign</th>
          </tr>
        </thead>
      </table>
      <hr>
      <div id="ticket-data"></div>
    </div>
  </div>
</div>

<script src="../assets/js/mytable/table-log.js"></script>
<script type="text/javascript">
  rowData = table.rows({selected:  true}).data().toArray();
  if (rowData[0][0] == "" || rowData[0][0] == undefined || rowData[0][0] == null) {
    img = "https://api.adorable.io/avatars/"+rowData[0][1];
  } else {
    img = "../image/userimg/"+rowData[0][0];
  }
  $('#i-usrimg').attr('src', img);
  $('#i-userid').text(rowData[0][1])
  $('#i-name').text(rowData[0][2])
  if ( rowData[0][3] == "active") {
    $('#i-status').html("<span class='label label-success'>Active</span>")
  } else {
    $('#i-status').html("<span class='label label-danger'>Inactive</span>")
  };
  if ( rowData[0][4] == "admin") {
    $('#i-role').html("<span class='label label-danger'>Admin</span>")
  } else if ( rowData[0][4] == "partner") {
    $('#i-role').html("<span class='label label-warning'>Partner</span>")
  } else {
    $('#i-role').html("<span class='label label-info'>Customer</span>")
  };
  $('#i-phone').text(rowData[0][5])
  $('#i-date').text(rowData[0][6])
  $('#i-username').text(rowData[0][7])
  $('#i-email').text(rowData[0][8])
  $('#i-notes').text(rowData[0][12])
  $('#i-coordinate').text(rowData[0][10]+", "+rowData[0][9])
  $('#i-address').text(rowData[0][11])
  var srcmap = "https://maps.google.com/maps?q="+rowData[0][10]+", "+rowData[0][9]+"&t=&z=13&ie=UTF8&iwloc=&output=embed";
  $('#i-maps').append('<iframe width="100%" height="500" id="gmap_canvas" src="'+srcmap+'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>')

  // devices
  tabledevice = {
    responsive: true,
    select: 'single',
    paging: true,
    ordering: true,
    dom:  "<'row'<'col-sm-12'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-6'i> <'col-sm-6'p>>",
  };
  tabledev = $('#table-device').DataTable(tabledevice);
  tablelogdevice = $('#table-log-device-i').DataTable(tabledevice);
  tableservice = $('#table-service').DataTable(tabledevice);
  tablelogsession = $('#table-log-session').DataTable(tabledevice);
  tableinvoice = $('#table-invoice').DataTable(tabledevice);
  tableticket = $('#table-ticket').DataTable(tabledevice);

  $.post("networking/sql-proc.php?qd=member", {
    member: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      var name = "<a class='pointer openinfo-device' data-id='"+json.data[i][0]+"'>"+json.data[i][1]+"</a>"
      if (json.data[i][6] == "Down") {
        var status = "<span class='label label-danger'>Down</span>";
      }
      if (json.data[i][6] == "Unknown") {
        var status = "<span class='label label-warning'>Unknown</span>";
      }
      if (json.data[i][6] == "Up") {
        var status = "<span class='label label-success'>Up</span>";
      }
      tabledev.row.add([
        status,
        name,
        json.data[i][2],
        json.data[i][3],
        json.data[i][4],
        json.data[i][5],
        json.data[i][7],
      ]).draw( false );
    }
    $('.openinfo-device').on('click', function() {
      devicesid = $(this).data('id');
      $('#modal-device').modal('show');
      $('#modal-title-device').text("Devices Info");
      $('#modal-body-device').load("networking/info-devices.php");
    });
  });

  $.post("member/sql-proc.php?log-device", {
    id: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      tablelogdevice.row.add([
        json.data[i][0],
        json.data[i][1],
        json.data[i][2]
      ]).draw( false );
    }
  });

  // Service.
  $.post("radius/sql-proc.php?qu=member", {
    member: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      if ( json.data[i][14] == true) {
        var isonline = "<span class='label label-info'>Online</span>";
      } else {
        var isonline = "<span class='label label-warning'>Offline</span>";
      };
      if ( json.data[i][7] == "Enable") {
        var status = "<span class='label label-success'>Enable</span>";
      } else {
        var status = "<span class='label label-danger'>Disable</span>";
      };
      tableservice.row.add([
        isonline,
        json.data[i][3],
        json.data[i][4],
        json.data[i][5],
        json.data[i][6],
        status,
        json.data[i][12],
        json.data[i][13],
      ]).draw( false );
    }
  });

  $.post("radius/sql-proc.php?qs=member", {
    id: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      if (json.data[i][9]) {
        var status = "<span class='label label-warning'>Expired</span>";
      } else {
        var status = "<span class='label label-info'>Active</span>";
      }
      tablelogsession.row.add([
        status,
        json.data[i][1],
        json.data[i][2],
        json.data[i][3],
        json.data[i][4],
        json.data[i][5],
        json.data[i][6],
        json.data[i][7],
        json.data[i][8],
      ]).draw( false );
    }
  });

  // Invoice
  $.post("billing/sql-proc.php?qs=invoice", {
    member: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      if (json.data[i][1] == "paid") {
        var status = "<span class='label label-success'>PAID OFF</span>";
      }
      if (json.data[i][1] == "unpaid") {
        var status = "<span class='label label-danger'>UNPAID</span>";
      };
      if (json.data[i][1] == "refund") {
        var status = "<span class='label label-warning'>REFUND</span>";
      };
      var invid = "<a class='pointer inv-detail' data-id='"+json.data[i][2]+"'>"+json.data[i][2]+"</a>";
      tableinvoice.row.add([
        status,
        invid,
        json.data[i][4],
        json.data[i][5],
        json.data[i][6],
        json.data[i][7],
        json.data[i][8],
        json.data[i][9],
        json.data[i][10],
        json.data[i][11],
      ]).draw( false );
      $('.inv-detail').on('click', function() {
        var invid = $(this).data('id');
        $('#modal-invoice').modal('show');
        $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
        $('#modal-title-invoice').text("Invoice Detail");
        $('#modal-body-invoice').load("billing/invoice-detail.php?id="+invid);
      });
    }
  });

  // Ticket
  $.post("ticket/sql-proc.php?q=member-detail", {
    member: rowData[0][1]
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.data.length; i++) {
      if ( json.data[i][11] == "new") {var status = "<span class='label label-info'>New</span> ";}
      if ( json.data[i][11] == "open") {var status = "<span class='label label-danger'>Open</span> ";}
      if ( json.data[i][11] == "closed") {var status = "<span class='label label-success'>Closed</span> ";}
      if ( json.data[i][11] == "pending") {var status = "<span class='label label-warning'>Pending</span> ";}
      var idtiket = "<a class='pointer' data-ticket='"+json.data[i][0]+"'>"+json.data[i][4]+"</a>";
      tableticket.row.add([
        status,
        json.data[i][1],
        json.data[i][2],
        json.data[i][3],
        idtiket,
        json.data[i][5],
        json.data[i][6],
      ]).draw( false );
    }
    $('#table-ticket tbody').on('click','tr',function(e){
      var ticketid = $(this).find('a').data('ticket');
      $.get('ticket/view-ticket.php?id='+ticketid, function(data) {
        $('#ticket-data').html(data);
      });
    });
  });

</script>
