<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newtrans">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="type">Type</label>
    <div class="col-sm-10">
      <select id="type" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="deposit" data-icon="fas fa-plus text-green">Deposit</option>
        <option value="expenses" data-icon="fas fa-minus text-red">Expenses</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="account">Account</label>
    <div class="col-sm-10">
      <select id="account" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $name = $key['name'];
            $id = $key['id'];
            $balance = $key['balance'];
        ?>
        <option value="<?= $id ?>"><?= $name ?> - <?= rupiah($balance) ; ?></option>
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
  allowClear: true,
  templateSelection: formatText,
  templateResult: formatText
});
function formatText (icon) {
  if (!$(icon.element).data('icon')) { return icon.text; }
  return $('<span><i class="' + $(icon.element).data('icon') + '"></i>&ensp;' + icon.text + '</span>');
};

$('#save').on('click', function() {
  var amount = convertToAngka($('#amount').val());
  $.post("billing/sql-proc.php?qt=new-transaction", {
    account: $('#account').val(),
    type: $('#type').val(),
    description: $('#description').val(),
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
        $('#callout-title-success').html("Transaction Completed!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
