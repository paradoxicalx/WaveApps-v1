<?php require "../../assets/func/sqlQu.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-account">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="memberlink">Linked Member</label>
    <div class="col-sm-10">
      <select id="memberlink" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_user WHERE `status` = 'active' AND `deleted` = '0' AND `group` = 'admin'");
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
    <label class="col-sm-2 control-label" for="balance">Initial Balance</label>
    <div class="col-sm-10">
      <input id="balance" name="balance" type="text" class="form-control rupiah">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="number">Bank Account</label>
    <div class="col-sm-10">
      <input id="number" name="number" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="phone">Phone</label>
    <div class="col-sm-10">
      <input id="phone" name="phone" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="bankurl">Bank URL</label>
    <div class="col-sm-10">
      <input id="bankurl" name="bankurl" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newaccount"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newaccount" value="Add Account" />
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
  placeholder: 'Optional',
  allowClear: true
});
$('#newaccount').on('click', function() {
  var balance = convertToAngka($("#balance").val());
  $.post("billing/sql-proc.php?n=account", {
    name: $("#name").val(),
    memberlink: $("#memberlink").val(),
    balance: balance,
    number: $("#number").val(),
    phone: $("#phone").val(),
    bankurl: $("#bankurl").val(),
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
        $('#callout-title-success').html("Success Add New Account!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
