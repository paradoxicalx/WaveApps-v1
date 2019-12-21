<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-transfer">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="from">From</label>
    <div class="col-sm-10">
      <select id="from" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_account WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $fname = $key['name'];
            $fid = $key['id'];
        ?>
        <option value="<?= $fid ?>"><?= $fname ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="to">To</label>
    <div class="col-sm-10">
      <select id="to" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_account WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $tname = $key['name'];
            $tid = $key['id'];
        ?>
        <option value="<?= $tid ?>"><?= $tname ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="amount">Amount</label>
    <div class="col-sm-10">
      <input id="amount" name="amount" type="text" class="form-control rupiah">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="sendtrans"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="sendtrans" value="Transfer Balance"/>
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript" src="../assets/js/currency/currency.js"></script>
<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});

$('#sendtrans').on('click', function() {
  var amount = "";
  if ($("#amount").val()) {
    var amount = convertToAngka($("#amount").val());
  }
  $.post("billing/sql-proc.php?t=transfer", {
    from: $("#from").val(),
    to: $("#to").val(),
    amount: amount,
    description: $("#description").val()
  },
  function(data) {
    $('.well.debug').append(data+"<br>");
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $(".has-error").removeClass("has-error");
      $.each( json, function( key, value ) {
        $("#"+json[key]['col']).closest(".form-group").addClass("has-error");
        key++
      });
      $("#info").load( "../include/alert.php #callout-warning", function() {
        $('#callout-title-warning').html(json[0]['error']);
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Successfully transfer balance!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
