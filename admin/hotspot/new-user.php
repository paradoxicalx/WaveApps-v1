<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Username</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="password">Password</label>
    <div class="col-sm-10">
      <input id="password" name="password" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="profile ">User Profile </label>
    <div class="col-sm-10">
      <select id="profile" class="form-control select2" style="width: 100%;">
        <option value=""></option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="server ">Server </label>
    <div class="col-sm-10">
      <select id="server" class="form-control select2" style="width: 100%;">
        <option value=""></option>
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
    <label class="col-sm-2 control-label" for="comment">Comment</label>
    <div class="col-sm-10">
      <input id="comment" name="comment" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="address">Address</label>
    <div class="col-sm-10">
      <input id="address" name="address" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="limit-bytes-in">Limit Upload</label>
    <div class="col-sm-10">
      <input id="limit-bytes-in" name="limit-bytes-in" type="number" class="form-control" placeholder="Format in Bytes">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="limit-bytes-out">Limit Download</label>
    <div class="col-sm-10">
      <input id="limit-bytes-out" name="limit-bytes-out" type="number" class="form-control" placeholder="Format in Bytes">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="limit-bytes-total">Limit Total</label>
    <div class="col-sm-10">
      <input id="limit-bytes-total" name="limit-bytes-total" type="number" class="form-control" placeholder="Format in Bytes">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="limit-uptime">Limit Uptime</label>
    <div class="col-sm-10">
      <input id="limit-uptime" name="limit-uptime" type="text" class="form-control" placeholder="Ex: 1d 00:00:00">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="mac-address">Mac Address</label>
    <div class="col-sm-10">
      <input id="mac-address" name="mac-address" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="routes">Routes</label>
    <div class="col-sm-10">
      <input id="routes" name="routes" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="email">Email</label>
    <div class="col-sm-10">
      <input id="email" name="email" type="text" class="form-control">
    </div>
  </div>
</form>

<form class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newuser"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newuser" value="Create" />
    </div>
  </div>
</form>

<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});
$('input#optional').on('click', function() {
  if ($("#optional").is(':checked')) {
    $( "#form-newuser-optional" ).show( "slow" );
  } else {
    $( "#form-newuser-optional" ).hide( "slow" );
  }
});

for (var i = 0; i < hsdata.server.length; i++) {
  $('#server').append($("<option></option>").attr("value",hsdata.server[i]['name']).text(hsdata.server[i]['name']));
}
for (var i = 0; i < hsdata.user_profile.length; i++) {
  $('#profile').append($("<option></option>").attr("value",hsdata.user_profile[i]['name']).text(hsdata.user_profile[i]['name']));
}

$('#newuser').on('click', function() {
  $.post("hotspot/sql-proc.php?newuser&id="+$('#hs-device').val(), {
    'server': $("#server").val(),
    'name': $("#name").val(),
    'password': $("#password").val(),
    'profile': $("#profile").val(),
    'comment': $("#comment").val(),
    'address': $("#address").val(),
    'limit-bytes-in': $("#limit-bytes-in").val(),
    'limit-bytes-out': $("#limit-bytes-out").val(),
    'limit-bytes-total': $("#limit-bytes-total").val(),
    'limit-uptime': $("#limit-uptime").val(),
    'mac-address': $("#mac-address").val(),
    'routes': $("#routes").val(),
    'email': $("#email").val(),
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
        $('#callout-title-success').html("Success Add New Hotspot User!");
      });
      $(".has-error").removeClass("has-error");
      getHSData($('#hs-device').val())
    }
  });
});
</script>
