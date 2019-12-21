<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<div class="box box-solid">
  <div class="form-group">
    <input id="confirm" type="text" class="form-control" placeholder="Write 'REMOVE' here to continue remove invoice !!" style="text-align:center">
  </div>
  <div class="form-group">
    <button id="remove-invoice" class="btn btn-block btn-danger btn-lg" disabled>
      <i class="fas fa-ban"></i> Remove Invoice
    </button>
  </div>
</div>

<script type="text/javascript">
  var count = table.rows( { selected: true } ).count();
  $('#modal-footer-default').text(+count+" invoice selected.");

  $('#confirm').on('keyup', function() {
    if ($('#confirm').val() === "REMOVE") {
      $("#remove-invoice").attr("disabled", false);
    } else {
      $("#remove-invoice").attr("disabled", true);
    }
  });

  $('#remove-invoice').on('click', function() {
    rowData = table.rows({selected:  true}).data().toArray();
    var newarray=[];
    for (var i=0; i < rowData.length ;i++){
      newarray.push(rowData[i][2]);
    }
    var invid = newarray;

    $.post("billing/sql-proc.php?qs=remove-invoice", {
      id: invid
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status != "success") {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("error");
        $("#info").load( "../include/alert.php #callout-danger", function() {
          $("#callout-title-danger").html(json[0]['error']);
        });
      } else {
        $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
        $("#info").load( "../include/alert.php #callout-success", function() {
          $('#callout-title-success').html("Success remove "+json[0]['count']+" invoice!");
        });
      }
    });

  });
</script>
