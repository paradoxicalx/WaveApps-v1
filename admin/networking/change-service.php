<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-changeservice">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="useapi ">API Service </label>
    <div class="col-sm-10">
      <select id="useapi" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="enable">Enable</option>
        <option value="disable">Disable</option>
      </select>
    </div>
  </div>
  <div class="apilogin" style="display: none;">
    <div class="form-group">
      <label class="col-sm-2 control-label" for="apiname ">Username </label>
      <div class="col-sm-10">
        <input id="apiname" name="apiname" type="text" class="form-control" placeholder="Not Change">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="apipass ">Password </label>
      <div class="col-sm-10">
        <input id="apipass" name="apipass" type="text" class="form-control" placeholder="Not Change">
      </div>
    </div>
  </div>
  <hr>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="autocheck ">Auto Check </label>
    <div class="col-sm-10">
      <select id="autocheck" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="enable">Enable</option>
        <option value="disable">Disable</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="changeservice" value="Save"/>
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript">
$( "#useapi" ).change(function() {
  if ($("#useapi option:selected").val() === "enable") {
    $( ".apilogin" ).show( "slow" );
  } else {
    $( ".apilogin" ).hide( "slow" );
  }
});
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true,
});
var count = table.rows( { selected: true } ).count();
$('#modal-footer-default').text(+count+" devices selected.");

$('#changeservice').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var devicesid = newarray;

  $.post("networking/sql-proc.php?c=service", {
    id: devicesid,
    useapi: $("#useapi").val(),
    apiname: $("#apiname").val(),
    apipass: $("#apipass").val(),
    autocheck: $("#autocheck").val()
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
        $('#callout-title-success').html("Success update "+json[0]['count']+" Device!");
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
