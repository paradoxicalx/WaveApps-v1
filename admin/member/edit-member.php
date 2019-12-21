<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-edituser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Full Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="username">Username</label>
    <div class="col-sm-10">
      <input id="username" name="username" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
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
    <label class="col-sm-2 control-label" for="email">Email</label>
    <div class="col-sm-10">
      <input id="email" name="email" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="phone">Phone</label>
    <div class="col-sm-10">
      <input id="phone" name="phone" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="Lat">Location</label>
    <div class="col-sm-10">
      <div class="input-group">
        <span class="input-group-addon">Lat</span>
        <input id="lat" name="lat" type="text" class="form-control" placeholder="Not Change">
        <span class="input-group-addon">Lon</span>
  			<input id="long" name="long" type="text" class="form-control" placeholder="Not Change">
        <span class="input-group-btn">
          <button type="button" class="btn btn-default btn-flat" id="openmap">Open Map</button>
        </span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="address">Adress</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="address" name="address" placeholder="Not Change"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="group">Group/Role</label>
    <div class="col-sm-10">
      <select id="group" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option>admin</option>
        <option>customer</option>
        <option>partner</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="phone">Notes</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="notes" name="notes"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to change member data!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editmember" value="Save" disabled/>
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
$('#openmap').on('click', function() {
  $('#modal-map').modal('show');
  $('#modal-title-map').text("Pick Member Location");
  $('#modal-body-map').load("maps/pick.php");
});
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#editmember").attr("disabled", false);
  } else {
    $("#editmember").attr("disabled", true);
  }
});

var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" member. Some data cannot be updated");
  $("#name,#username,#email,#phone").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" Member selected.");
  $("#name,#username,#email,#phone").attr("disabled", false);
}

$('#editmember').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][1]);
  }
  var memberid = newarray;

  $.post("member/sql-proc.php?e=edit", {
    id: memberid,
    name: $("#name").val(),
    username: $("#username").val(),
    email: $("#email").val(),
    password: $("#password").val(),
    phone: $("#phone").val(),
    address: $("#address").val(),
    status: $("#status").val(),
    long: $("#long").val(),
    lat: $("#lat").val(),
    group: $("#group").val(),
    notes: $("#notes").val()
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
        $('#callout-title-success').html("Success update "+json[0]['count']+" Member!");
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
