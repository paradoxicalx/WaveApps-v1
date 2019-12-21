<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>

<table id="table-information" class="table table-bordered table-striped" style="width:100%">
  <tbody class="memberinfo">
    <tr>
      <td>Status</td>
      <td id="i-status"></td>
    </tr>
    <tr>
      <td>Login ID</td>
      <td id="i-loginid"></td>
    </tr>
    <tr>
      <td>Member Name</td>
      <td id="i-member"></td>
    </tr>
    <tr>
      <td>Member ID</td>
      <td id="i-memberid"></td>
    </tr>
    <tr>
      <td>Group</td>
      <td id="i-group"></td>
    </tr>
    <tr>
      <td>Static IPv4</td>
      <td id="i-ip"></td>
    </tr>
    <tr>
      <td>Download</td>
      <td id="i-download"></td>
    </tr>
    <tr>
      <td>Upload</td>
      <td id="i-upload"></td>
    </tr>
    <tr>
      <td>Date Add</td>
      <td id="i-date"></td>
    </tr>
    <tr>
      <td>Download Limit</td>
      <td id="i-dlimit"></td>
    </tr>
    <tr>
      <td>Upload Limit</td>
      <td id="i-ulimit"></td>
    </tr>
    <tr>
      <td>Rate Limit</td>
      <td id="i-rlimit"></td>
    </tr>
    <tr>
      <td>Password</td>
      <td id="i-password"></td>
    </tr>
  </tbody>
</table>

<script type="text/javascript">
  rowData = table.rows({selected:  true}).data().toArray();
  if ( rowData[0][14] == "Enable") {
    var span_enable = "<span class='label label-success mr10'>Enable</span>";
  } else {
    var span_enable = "<span class='label label-danger mr10'>Disable</span>";
  };
  if ( rowData[0][1] == true) {
    var span_status = "<span class='label label-info'>Online</span>";
  } else {
    var span_status = "<span class='label label-warning'>Offline</span>";
  };
  var status = span_enable + span_status;
  $('#i-status').html(status)
  $('#i-loginid').text(rowData[0][2])
  $('#i-member').text(rowData[0][3])
  $('#i-memberid').text(rowData[0][4])
  $('#i-group').text(rowData[0][5])
  $('#i-ip').text(rowData[0][6])
  $('#i-download').text(rowData[0][7])
  $('#i-upload').text(rowData[0][8])
  $('#i-date').text(rowData[0][9])
  $('#i-dlimit').text(rowData[0][10])
  $('#i-ulimit').text(rowData[0][11])
  $('#i-rlimit').text(rowData[0][12])
  $('#i-password').text(rowData[0][13])
</script>
