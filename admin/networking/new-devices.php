<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newdevices">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name ">Name </label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="type ">Type </label>
    <div class="col-sm-10">
      <select id="type" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT DISTINCT type FROM wavenet.tb_devices WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $group = $key['type'];
        ?>
        <option><?= $group ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="model ">Model </label>
    <div class="col-sm-10">
      <select id="model" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT DISTINCT model FROM wavenet.tb_devices WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $group = $key['model'];
        ?>
        <option><?= $group ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="catagory ">Catagory </label>
    <div class="col-sm-10">
      <select id="catagory" class="form-control select2x" style="width: 100%;">
        <option value=""></option>
        <option value="cpe">CPE</option>
        <option value="server">Server</option>
        <option value="distribution">Distribution</option>
        <option value="vm">Virtual Server</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="ip">IP Address</label>
    <div class="col-sm-10">
      <select id="ip" class="form-control select2x" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT * FROM wavenet.tb_ipmaster WHERE `usage` = 'devices' ORDER BY `subnet`");
          foreach ($query as $key) :
            $id = $key['id'];
            $identity = $key['identity'];
            $subnet = $key['subnet'];
            $netmask = $key['netmask'];
            $cidr = netmask2cidr(long2ip($netmask));
        ?>
        <optgroup label='<?=  long2ip($subnet)."/".$cidr ." - ". $identity; ?>'>
          <?php
            $query2 = sqlQuAssoc("SELECT ipaddress FROM wavenet.tb_iplist WHERE `master` = '$id' AND `used` = '0'");
            foreach ($query2 as $key2) :
              $ip = long2ip($key2['ipaddress']);
          ?>
              <option value='<?= $ip ; ?>'><?= $ip ; ?></option>
          <?php endforeach ?>
        </optgroup>
        <?php endforeach ?>
      </select>
    </div>
  </div>
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
  <div class="form-group">
    <label class="col-sm-2 control-label" for="area ">Area </label>
    <div class="col-sm-10">
      <select id="area" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT DISTINCT area FROM wavenet.tb_devices WHERE `deleted` = '0'");
          foreach ($query as $key) :
            $group = $key['area'];
        ?>
        <option><?= $group ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="useapi"/>
          <div class="state p-primary-o">
              <label>Enable API service</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="apiname ">API Username </label>
    <div class="col-sm-10">
      <input id="apiname" name="apiname" type="text" class="form-control" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="apipass ">API Password </label>
    <div class="col-sm-10">
      <input id="apipass" name="apipass" type="password" class="form-control" disabled>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="autocheck"/>
          <div class="state p-primary-o">
              <label>Enable automatic status check</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="adddevices" value="Add Devices"/>
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
  allowClear: true,
  tags: true
});
$('.select2x').select2({
  placeholder: 'Select an option',
  allowClear: true,
  tags: false
});
$("#useapi").on('click', function() {
  if ($('input#useapi').is(':checked')) {
    $("#apiname,#apipass").attr("disabled", false);
  } else {
    $("#apiname,#apipass").attr("disabled", true);
  }
});

$('#adddevices').on('click', function() {
  $.post("networking/sql-proc.php?n=devices", {
    name: $("#name").val(),
    area: $("#area").val(),
    type: $("#type").val(),
    model: $("#model").val(),
    catagory: $("#catagory").val(),
    ip: $("#ip").val(),
    member: $("#member").val(),
    useapi: document.getElementById("useapi").checked,
    apiname: $("#apiname").val(),
    apipass: $("#apipass").val(),
    autocheck: document.getElementById("autocheck").checked
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
        $('#callout-title-success').html("Success Add New Devices!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});

// Debug Only
$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});
</script>
