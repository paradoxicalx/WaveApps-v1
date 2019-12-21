<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices_oid WHERE `id` =".$_GET['id']);
$oiddata = json_decode($device[0]['oid'], true);
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-edituser">
  <div class="form-group">
    <label class="col-sm-2 control-label">Identity</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" value="<?= $device[0]['router-name'] ; ?>" disabled>
      <input type="hidden" id="device-id" value="<?= $device[0]['id'] ; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="community">SNMP Community</label>
    <div class="col-sm-5">
      <input id="community" value="<?= $device[0]['comunity'] ; ?>" type="text" class="form-control">
    </div>
    <div class="col-sm-5">
      <div class="form-group">
        <label class="col-sm-3 control-label" for="version">Version</label>
        <div class="col-sm-9">
          <select id="version" class="form-control select2" style="width: 100%;">
            <?php for ($i=1; $i < 4; $i++) : ?>
              <?php $selected = ""; if ($device[0]['snmp_version'] == $i) { $selected = "selected"; } ?>
              <option value="<?=$i?>" <?=$selected?>>v<?=$i?></option>
            <?php endfor ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="rx-oid">Receive OID</label>
    <div class="col-sm-10">
      <input id="rx-oid" value="<?= $oiddata['bytes-in'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="tx-oid">Transfer OID</label>
    <div class="col-sm-10">
      <input id="tx-oid" value="<?= $oiddata['bytes-out'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="memoryused">Memory Used OID</label>
    <div class="col-sm-10">
      <input id="memoryused" value="<?= $oiddata['used-memory'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="uptimeoid">Uptime OID</label>
    <div class="col-sm-10">
      <input id="uptimeoid" value="<?= $oiddata['uptime'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="voltageoid">Voltage OID</label>
    <div class="col-sm-10">
      <input id="voltageoid" value="<?= $oiddata['voltage'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="tempoid">Temperature OID</label>
    <div class="col-sm-10">
      <input id="tempoid" value="<?= $oiddata['temperature'] ; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php for ($i=0; $i < $device[0]['cpu-count']; $i++) : ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="cpu<?= $i ; ?>oid">CPU<?= $i ; ?> Usage OID</label>
      <div class="col-sm-10">
        <input id="cpu<?= $i ; ?>oid" value="<?= $oiddata['cpu'][$i] ; ?>" type="text" class="form-control">
      </div>
    </div>
  <?php endfor ?>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="saveoid" value="Save"/>
    </div>
  </div>
</form>

<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select Routerboard'
});

$('#saveoid').on('click', function() {
  $.post("dashboard/sql-proc.php?edit-devices", {
    id: $("#device-id").val(),
    community: $("#community").val(),
    email: $("#email").val(),
    version: $("#version").val(),
    rxoid: $("#rx-oid").val(),
    txoid: $("#tx-oid").val(),
    memoryused: $("#memoryused").val(),
    uptimeoid: $("#uptimeoid").val(),
    voltageoid: $("#voltageoid").val(),
    tempoid: $("#tempoid").val(),
    cpucount: <?=$device[0]['cpu-count'];?>,
    <?php for ($i=0; $i < $device[0]['cpu-count']; $i++) : ?>
    cpu<?=$i;?>: $("#cpu<?=$i;?>oid").val(),
    <?php endfor ?>
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
        $('#callout-title-success').html("SNMP setting saved!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
