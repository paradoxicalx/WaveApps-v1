<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="callout callout-info" id="info">
  <h4 class="text-center"><i class="fas fa-spinner fa-spin"></i><span style="margin-left: 10px">
    Load device Information. Please wait..
  </span></h4>
  <p class="text-center"></p>
</div>

<div class="nav-tabs-custom" id="apidata" style="display:none">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_1" data-toggle="tab">RouterOS</a></li>
    <li><a href="#tab_2" data-toggle="tab">Interface</a></li>
    <li><a href="#tab_3" data-toggle="tab">Wireless</a></li>
    <li><a href="#tab_4" data-toggle="tab">IP Address</a></li>
    <li><a href="#tab_5" data-toggle="tab">IP Routes</a></li>
    <li><a href="#tab_6" data-toggle="tab">Neighbor</a></li>
    <li><a href="#tab_7" data-toggle="tab">DHCP</a></li>
    <li><a href="#tab_8" data-toggle="tab">Queues</a></li>
    <li><a href="#tab_9" data-toggle="tab">Logs</a></li>
    <?php if (!isset($_GET['file'])) : ?>
      <li><a href="#tab_10" data-toggle="tab">Traffic Monitor</a></li>
    <?php endif ?>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab_1">
      <table id="table-routeros" class="table table-bordered table-striped" style="width:100%">
        <thead><tr><th>Name</th><th>Value</th></tr></thead>
        <tbody class="deviceinfo"></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_2">
      <table id="table-interface" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Speed</th>
            <th>Running</th>
            <th>Mac Address</th>
            <th>Last Up</th>
            <th>RX Bytes</th>
            <th>TX Bytes</th>
            <th class="none">RX Packet</th>
            <th class="none">TX Packet</th>
            <th class="none">MTU</th>
            <th>Disabled</th>
            <th>Comment</th>
            <th class="none">Bytes IN OID</th>
            <th class="none">Bytes OUT OID</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_3">
      <table id="table-wireless" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Mac Address</th>
            <th>Mode</th>
            <th>SSID</th>
            <th>Frequency</th>
            <th>Band</th>
            <th>Channel Width</th>
            <th>Scan List</th>
            <th>Wireless Protocol</th>
            <th>Hide SSID</th>
            <th class="none">Type</th>
            <th class="none">Radio Name</th>
            <th class="none">Frequency Mode</th>
            <th class="none">Rate Set</th>
            <th class="none">TX Power Mode</th>
            <th>Default Authentication</th>
            <th>Default Forwarding</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <hr><div class="margin"><h4>Registration Table</h4></div>
      <table id="table-wireless-registration" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Radio Name</th>
            <th>Last Ip</th>
            <th>Comment</th>
            <th>Uptime</th>
            <th>Signal Strength</th>
            <th>CCQ</th>
            <th>RX Rate</th>
            <th>TX Rate</th>
            <th>Bytes</th>
            <th>Interface</th>
            <th>Mac Address</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_4">
      <table id="table-ip" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Address</th>
            <th>Network</th>
            <th>Interface</th>
            <th>Actual Interface</th>
            <th>Disabled</th>
            <th>Comment</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_5">
      <table id="table-iproute" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Dst Address</th>
            <th>Gateway</th>
            <th>Active</th>
            <th>Gateway Status</th>
            <th>Check</th>
            <th>Distance</th>
            <th>Scope</th>
            <th>Target Scope</th>
            <th>Routing Mark</th>
            <th>Static</th>
            <th>Disabled</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_6">
      <table id="table-neighbor" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Interface</th>
            <th>Address</th>
            <th>Mac Address</th>
            <th>Identity</th>
            <th>Platform</th>
            <th>Uptime</th>
            <th>Board</th>
            <th>Interface Name</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_7">
      <table id="table-dhcp" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Interface</th>
            <th>Lease Time</th>
            <th>Address Pool</th>
            <th>Disabled</th>
            <th>Lease Script</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <hr><div class="margin"><h4>DHCP Leases</h4></div>
      <table id="table-dhcp-leases" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Address</th>
            <th>Status</th>
            <th>Server</th>
            <th>Host Name</th>
            <th>Mac Address</th>
            <th>Comment</th>
            <th>Expires After</th>
            <th>Client ID</th>
            <th>Active Address</th>
            <th>Active Mac Address</th>
            <th>Static</th>
            <th>Disabled</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_8">
      <div class="margin"><h4>Queues Simple</h4></div>
      <table id="table-queues-simple" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Target</th>
            <th>Parent</th>
            <th>Rate</th>
            <th>Limit At</th>
            <th>Max Limit</th>
            <th>Burst Limit</th>
            <th>Burst Threshold</th>
            <th>Burst Time</th>
            <th>Comment</th>
            <th>Packet Marks</th>
            <th class="none">Priority</th>
            <th class="none">Bytes</th>
            <th class="none">Packets</th>
            <th>Dynamic</th>
            <th>Disabled</th>
            <th class="none">Queue</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <hr><div class="margin"><h4>Queues Tree</h4></div>
      <table id="table-queues-tree" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Parent</th>
            <th>Packet Mark</th>
            <th>Limit At</th>
            <th>Max Limit</th>
            <th>Burst Limit</th>
            <th>Burst Threshold</th>
            <th>Burst Time</th>
            <th>Priority</th>
            <th>Bytes</th>
            <th>Packets</th>
            <th>Rate</th>
            <th>Packet Rate</th>
            <th>Bucket Size</th>
            <th>Disabled</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_9">
      <table id="table-log-device" class="table table-bordered table-striped nowrap" style="width:100%">
        <thead>
          <tr>
            <th>Time</th>
            <th>Topic</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="tab-pane" id="tab_10">
      <?php include 'device-tool.php'; ?>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('.select2').select2({
    placeholder: 'Select Interface'
  });
  if (!devicesid) {
    rowData = table.rows({selected:  true}).data().toArray();
    var devicesid = rowData[0][0];
  }
    var url = "networking/sql-proc.php?i=info";
  <?php if (isset($_GET['file'])) : ?>
    var url = "<?= $weburl ?>/admin/ticket/file/<?= $_GET['file'] ?>";
  <?php endif ?>
  $.post(url, {
    id: devicesid,
  }, function(data){
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $('#info').removeClass('callout-info').addClass('callout-danger');
      $('#info').find('span').text(json[0]['message']);
      $('#info').find('p').text("Please check device ip address and API login data");
    } else {
      tableoption = {
        responsive: true,
        select: true,
        paging: false,
        ordering: false,
        dom:  "<'row'<'col-sm-12'>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'> <'col-sm-6'>>",
      };
      tableoption2 = {
        responsive: true,
        select: true,
        paging: true,
        ordering: false,
        dom:  "<'row'<'col-sm-12'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i> <'col-sm-6'p>>",
      };
      tableoption3 = {
        responsive: true,
        select: true,
        paging: true,
        ordering: true,
        dom:  "<'row'<'col-sm-12'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i> <'col-sm-6'p>>",
      };
      // RouterOS.
      $.each(json[0]['routeros'], function(i, val) {
        $('#table-routeros').append("<tr><td>"+i+"</td><td>"+val+"</td></tr>");
      });
      // Interface.
      $.each(json[0]['interface'], function(i, val) {
        var data = '';
        $.each(json[0]['interface'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "running" ) {
            if (valx == "true") {
              valx = "<i class='fas fa-check-circle text-green'></i>";
            } else {
              valx = "<i class='fas fa-times-circle text-red'></i>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else if (ix == "disabled") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-interface').append("<tr>"+ data +"</tr>");
        $('#interface-list').append("<option value='"+json[0]['interface'][i]['name']+"'>"+
                                      json[0]['interface'][i]['name'] +"</option>");
      });
      // Wireless.
      $.each(json[0]['wireless'], function(i, val) {
        var data = '';
        $.each(json[0]['wireless'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          data = data+"</td><td>"+valx+"</td>";
        });
        $('#table-wireless').append("<tr>"+data+"</tr>");
      });
      //Wireless Registration.
      $.each(json[0]['wireless-registration'], function(i, val) {
        var data = '';
        $.each(json[0]['wireless-registration'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          data = data+"</td><td>"+valx+"</td>";
        });
        $('#table-wireless-registration').append("<tr>"+data+"</tr>");
      });
      // IP Address.
      $.each(json[0]['ip-address'], function(i, val) {
        var data = '';
        $.each(json[0]['ip-address'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-ip').append("<tr>"+data+"</tr>");
      });
      // IP Route.
      $.each(json[0]['ip-route'], function(i, val) {
        var data = '';
        $.each(json[0]['ip-route'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else if (ix == "active") {
            if (valx == "true") {
              valx = "<i class='fas fa-check-circle text-green'></i>";
            } else {
              valx = "<i class='fas fa-times-circle text-red'></i>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else if (ix == "static") {
            if (valx == "true") {
              valx = "<span class='label label-success'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          }
          else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-iproute').append("<tr>"+data+"</tr>");
      });
      // IP Neighbor.
      $.each(json[0]['ip-neighbor'], function(i, val) {
        var data = '';
        $.each(json[0]['ip-neighbor'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          data = data+"</td><td>"+valx+"</td>";
        });
        $('#table-neighbor').append("<tr>"+data+"</tr>");
      });
      // DHCP.
      $.each(json[0]['dhcp-server'], function(i, val) {
        var data = '';
        $.each(json[0]['dhcp-server'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-dhcp').append("<tr>"+data+"</tr>");
      });
      // DHCP Leases
      $.each(json[0]['dhcp-lease'], function(i, val) {
        var data = '';
        $.each(json[0]['dhcp-lease'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else if (ix == "status") {
            if (valx == "bound") {
              valx = "<span class='label label-success'>bound</span>";
            } else {
              valx = "<span class='label label-default'>"+valx+"</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else if (ix == "dynamic") {
            if (valx == "true") {
              valx = "<span class='label label-default'>NO</span>";
            } else {
              valx = "<span class='label label-success'>YES</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-dhcp-leases').append("<tr>"+data+"</tr>");
      });
      // Queues simple.
      $.each(json[0]['queue-simple'], function(i, val) {
        var data = '';
        $.each(json[0]['queue-simple'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled" || ix == "dynamic") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-queues-simple').append("<tr>"+data+"</tr>");
      });
      // Queues tree.
      $.each(json[0]['queue-tree'], function(i, val) {
        var data = '';
        $.each(json[0]['queue-tree'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          if (ix == "disabled" || ix == "dynamic") {
            if (valx == "true") {
              valx = "<span class='label label-danger'>YES</span>";
            } else {
              valx = "<span class='label label-default'>NO</span>";
            }
            data = data+"</td><td class='text-center'>"+valx+"</td>";
          } else {
            data = data+"</td><td>"+valx+"</td>";
          }
        });
        $('#table-queues-tree').append("<tr>"+data+"</tr>");
      });
      // IP Logs.
      $.each(json[0]['log'], function(i, val) {
        var data = '';
        $.each(json[0]['log'][i], function(ix, valx) {
          if (!valx || valx <= 0 || (valx) == "NAN ") {valx = "-";}
          data = data+"</td><td>"+valx+"</td>";
        });
        $('#table-log-device').append("<tr>"+data+"</tr>");
      });

      $('#table-routeros').DataTable(tableoption);
      $('#table-interface').DataTable(tableoption2);
      $('#table-wireless').DataTable(tableoption2);
      $('#table-wireless-registration').DataTable(tableoption2);
      $('#table-ip').DataTable(tableoption2);
      $('#table-iproute').DataTable(tableoption2);
      $('#table-neighbor').DataTable(tableoption2);
      $('#table-dhcp').DataTable(tableoption);
      $('#table-dhcp-leases').DataTable(tableoption2);
      $('#table-log-device').DataTable(tableoption2);
      $('#table-queues-simple').DataTable(tableoption3);
      $('#table-queues-tree').DataTable(tableoption3);

      $('#info').hide();
      $('#apidata').show();
    }
  });

  $(document).ready(function() {
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().responsive.recalc();
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    } );
});
</script>
