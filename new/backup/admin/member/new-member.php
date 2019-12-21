<?php require "../../assets/func/sesscek.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Full Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control">
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
    <label class="col-sm-2 control-label" for="email">Email</label>
    <div class="col-sm-10">
      <input id="email" name="email" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="phone">Phone</label>
    <div class="col-sm-10">
      <input id="phone" name="phone" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="Lat">Location</label>
    <div class="col-sm-10">
      <div class="input-group">
        <span class="input-group-addon">Lat</span>
        <input id="lat" name="lat" type="text" class="form-control">
        <span class="input-group-addon">Lng</span>
  			<input id="long" name="long" type="text" class="form-control">
        <span class="input-group-btn">
          <button type="button" class="btn btn-default btn-flat" id="openmap">Open Map</button>
        </span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="address">Adress</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="address" name="address"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="status">Status</label>
    <div class="col-sm-10">
      <select id="status" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option>active</option>
        <option>inactive</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="group">Group/Role</label>
    <div class="col-sm-10">
      <select id="group" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="admin">Admin</option>
        <option value="customer">Customer</option>
        <option value="partner">Partner</option>
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
    <label class="col-sm-2 control-label" for="newmember"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newmember" value="Create Member" />
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript" src="../assets/js/randompass.js"></script>
<script>
  $('.select2').select2({
    placeholder: 'Select an option',
    allowClear: true
  });
  $('#generatePassword').on('click', function() {
    $("#password").val(generatePassword());
  });
  $('#openmap').on('click', function() {
    $('#modal-map').on('show.bs.modal', function (event) {
      $('.modal-map.modal-title').text("Pick Member Location");
      $('.modal-map.modal-body').load("../maps/pick-location.php");
    })
    $('#modal-map').modal('show');
  });
  $('#newmember').on('click', function() {
    $.post("member/sql-proc.php?n=new", {
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

  // Debug Only
  $('.debug.clear').on('click', function() {
    $('.well.debug').empty();
  });
  $('.debug.random').on('click', function() {
    $('input, textarea').val(generatePassword());
  });
</script>
