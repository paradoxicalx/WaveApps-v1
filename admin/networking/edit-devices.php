<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newipv4">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name ">Name </label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control" placeholder="Not Change">
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
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to change device data!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editdevices" value="Save" disabled/>
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
  placeholder: 'Not Change',
  allowClear: true,
  tags: true
});
$('.select2x').select2({
  placeholder: 'Not Change',
  allowClear: true,
  tags: false
});
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#editdevices").attr("disabled", false);
  } else {
    $("#editdevices").attr("disabled", true);
  }
});
$("#useapi").on('click', function() {
  if ($('input#useapi').is(':checked')) {
    $("#apiname,#apipass").attr("disabled", false);
  } else {
    $("#apiname,#apipass").attr("disabled", true);
  }
});
var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" devices. Some data cannot be updated");
  $("#name,#ip").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" devices selected.");
  $("#name,#ip").attr("disabled", false);
}

$('#editdevices').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var devicesid = newarray;

  $.post("networking/sql-proc.php?e=devices", {
    id: devicesid,
    name: $("#name").val(),
    area: $("#area").val(),
    type: $("#type").val(),
    model: $("#model").val(),
    catagory: $("#catagory").val(),
    ip: $("#ip").val(),
    member: $("#member").val()
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
        $("#callout-title-warning").html(json[0]['error']);
      });
    } else if (status == "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $(".has-error").removeClass("has-error");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success update "+json[0]['count']+" Devices!");
      });
    } else if (status == "warning") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("danger");
      $(".has-error").removeClass("has-error");
      $("#info").load( "../include/alert.php #callout-danger", function() {
        $('#callout-title-danger').html(json[0]['error']);
      });
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
