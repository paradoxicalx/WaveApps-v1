<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
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
    <label class="col-sm-2 control-label" for="username">Username</label>
    <div class="col-sm-10">
      <input id="username" name="username" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="password">Password</label>
    <div class="col-sm-10">
      <div class="input-group">
        <input id="password" name="password" type="text" class="form-control">
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
    <label class="col-sm-2 control-label" for="optional"></label>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
        <input type="checkbox" id="optional">
        <div class="state p-primary-o">
          <label>Optional Configuration</label>
        </div>
      </div>
    </div>
  </div>
</form>

<form class="form-horizontal" id="form-newuser-optional" style="display: none;">
  <div class="form-group">
    <hr>
    <label class="col-sm-2 control-label" for="ipv4">Static IPv4</label>
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
    <label class="col-sm-2 control-label" for="dl-limit">Download Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="dl-limit" name="dl-limit" type="text" class="form-control" placeholder="Use Group Setting">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="up-limit">Upload Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="up-limit" name="up-limit" type="text" class="form-control" placeholder="Use Group Setting">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>

  <div class="form-group">
    <hr>
    <label class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
      <label>Mikrotik Rate Limit
        <a target="_blank" href="https://wiki.mikrotik.com/wiki/Manual:Queue">
          <i class="fas fa-question-circle"></i>
        </a>
      </label>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="rate">Rx/Tx Rate</label>
    <div class="col-sm-10">
			<input id="rate" name="rate" type="text" class="form-control" placeholder="Ex : 64k/64k">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="burst">Rx/Tx Burst Rate</label>
    <div class="col-sm-10">
			<input id="burst" name="burst" type="text" class="form-control" placeholder="Ex: 256k/256k" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="threshold">Rx/Tx Threshold</label>
    <div class="col-sm-10">
			<input id="threshold" name="threshold" type="text" class="form-control" placeholder="Ex: 128k/128k" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="bursttime">Rx/Tx Burst Time</label>
    <div class="col-sm-10">
			<input id="bursttime" name="bursttime" type="text" class="form-control" placeholder="Ex: 10/10" disabled>
    </div>
  </div>
</form>

<form class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newuser"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newuser" value="Create Radius User" />
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
$('#generatePassword').on('click', function() {
  $("#password").val(generatePassword());
});
$('input#optional').on('click', function() {
  if ($("#optional").is(':checked')) {
    $( "#form-newuser-optional" ).show( "slow" );
  } else {
    $( "#form-newuser-optional" ).hide( "slow" );
  }
});
$("#rate").keyup(function(){
  if( $(this).val().length === 0 ) {
      $("#burst,#threshold,#bursttime").attr("disabled", true);
    } else {
      $("#burst,#threshold,#bursttime").attr("disabled", false);
    }
});
$('#newuser').on('click', function() {
  $.post("radius/sql-proc.php?n=user", {
    member: $("#member").val(),
    username: $("#username").val(),
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
