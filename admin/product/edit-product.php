<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-editproduct">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control" placeholder="Not change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="price">Price</label>
    <div class="col-sm-10">
      <input id="price" name="price" type="text" class="form-control rupiah" placeholder="Not change">
    </div>
  </div>
  <?php if ($_GET['s'] == "service") :?>
    <div class="form-group">
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
  <?php elseif ($_GET['s'] == "stuff") :?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="number">Number</label>
      <div class="col-sm-10">
        <input id="number" name="number" type="text" class="form-control" placeholder="Not change">
      </div>
    </div>
  <?php endif ?>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description" placeholder="Not change"></textarea>
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
    <label class="col-sm-2 control-label" for="editproduct"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editproduct" value="Save" disabled/>
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
  placeholder: 'Not change',
  allowClear: true
});
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#editproduct").attr("disabled", false);
  } else {
    $("#editproduct").attr("disabled", true);
  }
});
var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" product. Some data cannot be updated");
  $("#name,#rgroup,#number").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" product selected.");
  $("#name,#rgroup,#number").attr("disabled", false);
}

$('#editproduct').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var productid = newarray;
  if($("#price").val()) {
    var price = convertToAngka($("#price").val());
  } else {
    var price = $("#price").val();
  }
  $.post("product/sql-proc.php?e=edit", {
    id: productid,
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
        $("#callout-title-warning").html(json[0]['error']);
      });
    } else if (status == "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $(".has-error").removeClass("has-error");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success update "+json[0]['count']+" product!");
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

$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});
</script>
