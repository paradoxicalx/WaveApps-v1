<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newgroup">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="groupname">Group Name</label>
    <div class="col-sm-10">
      <input id="groupname" name="groupname" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="profile">Mikrotik Profile</label>
    <div class="col-sm-10">
			<input id="profile" name="profile" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="dl-limit">Download Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="dl-limit" name="dl-limit" type="text" class="form-control">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="up-limit">Upload Limit</label>
    <div class="col-sm-10">
      <div class="input-group">
				<input id="up-limit" name="up-limit" type="text" class="form-control">
        <span class="input-group-addon">bytes</span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="profile">Max. Session</label>
    <div class="col-sm-10">
			<input id="maxsess" name="maxsess" type="number" class="form-control" placeholder="Unlimited">
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
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newgroup"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newgroup" value="Create Radius Group" />
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript">
$("#rate").keyup(function(){
  if( $(this).val().length === 0 ) {
      $("#burst,#threshold,#bursttime").attr("disabled", true);
    } else {
      $("#burst,#threshold,#bursttime").attr("disabled", false);
    }
});
$('#newgroup').on('click', function() {
  $.post("radius/sql-proc.php?n=group", {
    groupname: $("#groupname").val(),
    profile: $("#profile").val(),
    dllimit: $("#dl-limit").val(),
    uplimit: $("#up-limit").val(),
    rate: $("#rate").val(),
    burst: $("#burst").val(),
    threshold: $("#threshold").val(),
    bursttime: $("#bursttime").val(),
    maxsess: $("#maxsess").val()
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
