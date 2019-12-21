<?php require "../../assets/func/sqlQu.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-newuser">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="shortname">Name</label>
    <div class="col-sm-10">
      <input id="shortname" name="shortname" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="nasname">Address</label>
    <div class="col-sm-10">
      <input id="nasname" name="nasname" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="secret">Secret</label>
    <div class="col-sm-10">
      <input id="secret" name="secret" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="type">Type</label>
    <div class="col-sm-10">
      <select id="type" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option>cisco</option>
        <option>computone</option>
        <option>livingston</option>
        <option>juniper</option>
        <option>max40xx</option>
        <option>multitech</option>
        <option>netserver</option>
        <option>pathras</option>
        <option>patton</option>
        <option>portslave</option>
        <option>tc</option>
        <option>usrhiper</option>
        <option>other </option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="port">Port</label>
    <div class="col-sm-10">
      <input id="ports" name="ports" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="server">Server</label>
    <div class="col-sm-10">
      <input id="server" name="server" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="community">Community</label>
    <div class="col-sm-10">
      <input id="community" name="community" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description" placeholder="Not Change"></textarea>
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
    <label class="col-sm-2 control-label" for="editrouter"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editrouter" value="Save Change" disabled/>
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Not Change',
  allowClear: true
});
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#editrouter").attr("disabled", false);
  } else {
    $("#editrouter").attr("disabled", true);
  }
});
var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" router. Some data cannot be updated");
  $("#shortname,#nasname").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" router selected.");
  $("#shortname,#nasname").attr("disabled", false);
}
$('#editrouter').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var routerid = newarray;

  $.post("radius/sql-proc.php?e=router", {
    id: routerid,
    shortname: $("#shortname").val(),
    nasname: $("#nasname").val(),
    type: $("#type").val(),
    ports: $("#ports").val(),
    secret: $("#secret").val(),
    server: $("#server").val(),
    community: $("#community").val(),
    description: $("#description").val()
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
        $('#callout-title-success').html("Success update "+json[0]['count']+" Router!");
      });
    } else if (status == "warning") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("danger");
      $("#info").load( "../include/alert.php #callout-danger", function() {
        $('#callout-title-danger').html("Only update "+json[0]['count']+" router! We have error here! ");
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
