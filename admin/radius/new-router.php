<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="shortname">Name</label>
    <div class="col-sm-10">
      <input id="shortname" name="shortname" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="nasname">Address</label>
    <div class="col-sm-10">
      <input id="nasname" name="nasname" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="secret">Secret</label>
    <div class="col-sm-10">
      <input id="secret" name="secret" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="type">Type</label>
    <div class="col-sm-10">
      <select id="type" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option>cisco</option>
        <option>computone</option>
        <option>livingston</option>
        <option>juniper</option>
        <option>max40xx</option>
        <option>multitech</option>
        <option>netserver</option>
        <option>pathras</option>
        <option>patton</option>
        <option>portslave</option>
        <option>tc</option>
        <option>usrhiper</option>
        <option>other </option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="port">Port</label>
    <div class="col-sm-10">
      <input id="ports" name="ports" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="server">Server</label>
    <div class="col-sm-10">
      <input id="server" name="server" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="community">Community</label>
    <div class="col-sm-10">
      <input id="community" name="community" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newrouter"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newrouter" value="Add Router" />
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript" src="../assets/js/randompass.js"></script>
<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});

$('#newrouter').on('click', function() {
  $.post("radius/sql-proc.php?n=router", {
    shortname: $("#shortname").val(),
    nasname: $("#nasname").val(),
    type: $("#type").val(),
    ports: $("#ports").val(),
    secret: $("#secret").val(),
    server: $("#server").val(),
    community: $("#community").val(),
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
        $('#callout-title-success').html("Success Add New Member!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});
</script>
