<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<div class="row">
  <div class="col-lg-6">
    <button id="enable" class="btn btn-block btn-success btn-lg">
      <i class="far fa-check-circle"></i> Enable User
    </button>
  </div>
  <div class="col-lg-6">
    <button id="disable" class="btn btn-block btn-danger btn-lg">
      <i class="fas fa-ban"></i> Disable User
    </button>
  </div>
</div>

<script type="text/javascript">
  var count = table.rows( { selected: true } ).count();
  $('#modal-footer-default').text(+count+" user selected.");

  $('#enable').on('click', function() {
    rowData = table.rows({selected:  true}).data().toArray();
    var newarray=[];
    for (var i=0; i < rowData.length ;i++){
      newarray.push(rowData[i][3]);
    }
    var memberid = newarray;

    $.post("radius/sql-proc.php?status", {
      id: memberid,
      status: "enable"
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status != "success") {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
        $("#info").load( "../include/alert.php #callout-warning", function() {
          $("#callout-title-warning").html(json[0].error);
        });
      } else {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
        $("#info").load( "../include/alert.php #callout-success", function() {
          $('#callout-title-success').html("Success Enable Member!");
        });
      }
    });
  })

  $('#disable').on('click', function() {
    rowData = table.rows({selected:  true}).data().toArray();
    var newarray=[];
    for (var i=0; i < rowData.length ;i++){
      newarray.push(rowData[i][3]);
    }
    var memberid = newarray;
    $.post("radius/sql-proc.php?status", {
      id: memberid,
      status: "disable"
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status != "success") {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
        $("#info").load( "../include/alert.php #callout-warning", function() {
          $("#callout-title-warning").html("Failed to disable user");
        });
      } else {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
        $("#info").load( "../include/alert.php #callout-success", function() {
          $('#callout-title-success').html("Success disable Member!");
        });
      }
    })
  })
</script>
