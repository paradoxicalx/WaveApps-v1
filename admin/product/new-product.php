<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newproduct">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="type">Type</label>
    <div class="col-sm-10">
      <select id="type" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="service">Service</option>
        <option value="stuff">Stuff</option>
      </select>
    </div>
  </div>
  <div class="form-group f-all">
    <label class="col-sm-2 control-label" for="name">Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group f-all">
    <label class="col-sm-2 control-label" for="price">Price</label>
    <div class="col-sm-10">
      <input id="price" name="price" type="text" class="form-control rupiah">
    </div>
  </div>
  <div class="form-group f-service">
    <label class="col-sm-2 control-label" for="rgroup">Radius</label>
    <div class="col-sm-10">
      <select id="rgroup" class="form-control select2" style="width: 100%;">
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
  <div class="form-group f-stuff">
    <label class="col-sm-2 control-label" for="number">Number</label>
    <div class="col-sm-10">
      <input id="number" name="number" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group f-all">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newproduct"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newproduct" value="Add Product" disabled/>
    </div>
  </div>
</form>

<div class="debug-only">
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript" src="../assets/js/randompass.js"></script>
<script type="text/javascript" src="../assets/js/currency/currency.js"></script>
<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});
$('.f-all,.f-service,.f-stuff').hide();
$("#type").change(function(){
	if ( $(this).val() == "service" ) {
		$('.f-stuff').hide();
    $('.f-all,.f-service').show();
    $("#newproduct").attr("disabled", false);
    $("#number").val("");
  }
  if( $(this).val() == "stuff" ) {
    $("#rgroup").val(null).trigger("change");
		$('.f-service').hide();
    $('.f-all,.f-stuff').show();
    $("#newproduct").attr("disabled", false);
  }
  if( $(this).val() == "" ) {
		$('.f-all,.f-service,.f-stuff').hide();
    $("#newproduct").attr("disabled", true);
  }
});

$('#newproduct').on('click', function() {
  var price = convertToAngka($("#price").val());
  $.post("product/sql-proc.php?n=product", {
    type: $("#type").val(),
    name: $("#name").val(),
    price: price,
    rgroup: $("#rgroup").val(),
    number: $("#number").val(),
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
        $('#callout-title-warning').html(json[0]['error']);
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Add New Product!");
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
