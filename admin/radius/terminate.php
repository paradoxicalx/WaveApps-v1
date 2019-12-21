<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="t_nas">Router</label>
    <div class="col-sm-10">
      <select id="t_nas" class="form-control select2" style="width: 100%;">
        <?php
          $query = sqlQuAssoc("SELECT * FROM radius.nas");
          foreach ($query as $key) :
            $shortname = $key['shortname'];
            $secret = $key['secret'];
        ?>
        <option value="<?= $secret ?>"><?= $shortname ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="t_port">Port</label>
    <div class="col-sm-10">
      <input id="t_port" name="t_port" type="number" class="form-control" value="3799">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to terminate session!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="t_dc"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-danger btn-block" id="t_dc" value="Terminate" disabled/>
    </div>
  </div>
</form>

<script type="text/javascript">
$('.select2').select2();
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#t_dc").attr("disabled", false);
  } else {
    $("#t_dc").attr("disabled", true);
  }
});

$('#t_dc').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var sessid = newarray;

  $.post("radius/sql-proc.php?dc", {
    id: sessid,
    port: $('#t_port').val(),
    secret: $('#t_nas').val()
  },
  function(data) {
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $("#info").load( "../include/alert.php #callout-warning", function() {
        $("#callout-title-warning").html(json[0]['count'] + " session terminated");
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html(json[0]['count']+" session terminated");
      });
    }
  });
});
</script>
