<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="password">Password</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input id="password" name="password" type="text" class="form-control" placeholder="Not Change">
        <span class="input-group-btn">
          <button type="button" class="btn btn-default btn-flat" id="generatePassword">Generate</button>
        </span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="group">Group Service</label>
    <div class="col-sm-10">
      <select id="group" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT DISTINCT groupname FROM radius.radusergroup");
          foreach ($query as $key) :
            $group = $key['groupname'];
        ?>
        <option><?= $group ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="group">Static IPv4</label>
    <div class="col-sm-10">
      <select id="ipv4" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT * FROM wavenet.tb_ipmaster WHERE `usage` = 'product' ORDER BY `subnet`");
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
    <label class="col-sm-2 control-label" for="username">Download Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="dl-limit" name="dl-limit" type="text" class="form-control" placeholder="Not Change">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Upload Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="up-limit" name="up-limit" type="text" class="form-control" placeholder="Not Change">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Rx/Tx Rate</label>
    <div class="col-sm-10">
			<input id="rate" name="rate" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Rx/Tx Burst Rate</label>
    <div class="col-sm-10">
			<input id="burst" name="burst" type="text" class="form-control" placeholder="Not Change" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Rx/Tx Threshold</label>
    <div class="col-sm-10">
			<input id="threshold" name="threshold" type="text" class="form-control" placeholder="Not Change" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Rx/Tx Burst Time</label>
    <div class="col-sm-10">
			<input id="bursttime" name="bursttime" type="text" class="form-control" placeholder="Not Change" disabled>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to update user data!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newuser"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="edituser" value="Save Change" disabled/>
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
  allowClear: true
});
$('#generatePassword').on('click', function() {
  $("#password").val(generatePassword());
});
$("#rate").keyup(function(){
  if( $(this).val().length === 0 ) {
      $("#burst,#threshold,#bursttime").attr("disabled", true);
      $("#burst,#threshold,#bursttime").val("");
    } else {
      $("#burst,#threshold,#bursttime").attr("disabled", false);
    }
});
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#edituser").attr("disabled", false);
  } else {
    $("#edituser").attr("disabled", true);
  }
});
var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" radius user. Some data cannot be updated");
  $("#username,#ipv4").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" radius user selected.");
  $("#username,#ipv4").attr("disabled", false);
}
$('#edituser').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var memberid = newarray;

  $.post("radius/sql-proc.php?e=user", {
    id: memberid,
    password: $("#password").val(),
    group: $("#group").val(),
    ipv4: $("#ipv4").val(),
    dllimit: $("#dl-limit").val(),
    uplimit: $("#up-limit").val(),
    rate: $("#rate").val(),
    burst: $("#burst").val(),
    threshold: $("#threshold").val(),
    bursttime: $("#bursttime").val()
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
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success update "+json[0]['count']+" Radius User!");
      });
    } else if (status == "warning") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("danger");
      $("#info").load( "../include/alert.php #callout-danger", function() {
        $('#callout-title-danger').html("Only update "+json[0]['count']+" Member! We have error here! ");
      });
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
