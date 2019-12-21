<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-addwallet">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="member">Member</label>
    <div class="col-sm-10">
      <select id="member" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_user WHERE `status` = 'active' AND `deleted` = '0' ");
          foreach ($query as $key) :
            $name = $key['name'];
            $id = $key['id']
        ?>
        <option value="<?= $id ?>"><?= $id." - ".$name ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group f-all">
    <label class="col-sm-2 control-label" for="amount">Amount</label>
    <div class="col-sm-10">
      <input id="amount" name="amount" type="text" class="form-control rupiah">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="payto">Pay to</label>
    <div class="col-sm-10">
      <select id="payto" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_account WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $name = $key['name'];
            $id = $key['id']
        ?>
        <option value="<?= $id ?>"><?= $name ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="save"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="save" value="Save" />
    </div>
  </div>
</form>

<script type="text/javascript" src="../assets/js/currency/currency.js"></script>
<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});
$('#save').on('click', function() {
  var amount = convertToAngka($("#amount").val());
  if (!$.isNumeric(amount)) {
    var amount = 0;
  }
  $.post("billing/sql-proc.php?qs=addwallet", {
    member: $("#member").val(),
    payto: $("#payto").val(),
    amount: amount
  },
  function(data) {
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
        $('#callout-title-success').html("Success Top Up Member Wallet!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
