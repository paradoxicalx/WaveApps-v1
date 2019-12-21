<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="number">Number of User</label>
    <div class="col-sm-10">
      <input id="number" name="number" type="number" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="prefix">Prefix</label>
    <div class="col-sm-10">
      <input id="prefix" name="prefix" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Username Length</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="number" class="form-control" value="4">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="password">Password Length</label>
    <div class="col-sm-10">
      <input id="password" name="password" type="number" class="form-control" value="4">
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

<form class="form-horizontal" id="form-create">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newuser"></label>
    <div class="col-sm-10">
      <!-- <input type="button" class="btn btn-primary btn-block" id="newuser" value="Create" /> -->
      <button type="button" class="btn btn-primary btn-block" id="newuser">Create</button>
    </div>
  </div>
</form>

<button type="button" class="btn btn-primary btn-block hide itsok" id="btn-print"><i class="fas fa-ticket-alt mr10"></i>Print Voucher</button>
<button type="button" class="btn btn-warning btn-block hide itsok" id="btn-pdf"><i class="fas fa-file-pdf mr10"></i>Download PDF</button>
<button type="button" class="btn btn-default btn-block hide itsok" id="btn-csv"><i class="fas fa-file-word mr10"></i>Download CSV</button>
<div id="iframe_print">

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
  $('#newuser').attr("disabled", true);
  $('#newuser').html("<i class='fas fa-spinner fa-pulse mr10'></i>Generating user. Please wait.")

  $.post("hotspot/sql-proc.php?newmultiuser&id="+$('#hs-device').val(), {
    'prefix': $("#prefix").val(),
    'number': $("#number").val(),
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
    created_user_pass = json[0];
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
      $('#newuser').attr("disabled", false);
      $('#newuser').text("Create")
    } else {
      $("#form-newuser").hide()
      $("#form-newuser-optional").hide()
      $("#form-create").hide()
      $(".itsok").removeClass('hide')

      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Add New Hotspot User!");
      });
      $(".has-error").removeClass("has-error");
      getHSData($('#hs-device').val())
    }
  });
});

$('#btn-print').on('click', function() {
  localStorage.setItem("hs-vcr-print", JSON.stringify(created_user_pass));
  var win = window.open('https://apps.wavenet.id/admin/?print-vcr&mode=print', '_blank');
  if (win) {
    win.focus();
  }
})

$('#btn-pdf').on('click', function() {
  localStorage.setItem("hs-vcr-print", JSON.stringify(created_user_pass));
  var win = window.open('https://apps.wavenet.id/admin/?print-vcr&mode=pdf', '_blank');
  if (win) {
    win.focus();
  } else {
    alert('Please allow popups for this website');
  }
})

$('#btn-csv').on('click', function() {
  var userpasslist = created_user_pass.user_pass;
  var headers = {
    username: 'Username',
    password: "Password",
    dns: "DNS Name"
  };
  var csvitem = [];
  userpasslist.forEach((item) => {
    csvitem.push({
      username: item.name,
      password: item.password,
      dns: item.dns,
    });
  });
  var fileTitle = 'export';
  exportCSVFile(headers, csvitem, fileTitle);
})
</script>
