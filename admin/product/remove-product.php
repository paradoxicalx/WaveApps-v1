<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<div class="box box-solid">
  <div class="box-body">
    <div class="box-group" id="accordion">
      <div class="panel box box-warning">
        <div class="box-header">
          <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed text-yellow">
              Remove Product Data
            </a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
          <div class="box-body">
            <div class="input-group input-group-sm">
              <select class="select2 form-control" id="select-remove" style="width: 100%;">
                <option value=""></option>
                <option>Price</option>
                <option>Radius</option>
                <option>Number/Code</option>
                <option>Description</option>
              </select>
              <span class="input-group-btn">
                <button type="button" class="btn btn-danger btn-flat" id="remove-data">Remove</button>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="panel box box-danger">
        <div class="box-header">
          <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed text-red" aria-expanded="false">
              Completely Remove Product
            </a>
          </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
          <div class="box-body">
            <div class="pretty p-default p-round p-thick  margin">
              <input type="checkbox" id="agree"/>
              <div class="state p-primary-o">
                <label>I understand. Continue to remove product!</label>
              </div>
            </div>
            <button id="remove-product" class="btn btn-block btn-danger btn-lg" disabled>
              <i class="fas fa-ban"></i> Remove Product
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>

<div class="debug-only">
  <hr>
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript">
$('.select2').select2({
  placeholder: 'Select data to remove',
  allowClear: true
});
var count = table.rows( { selected: true } ).count();
$('#modal-footer-default').text(+count+" product selected.");

$('input#agree').on('click', function() {
  if ($("#agree").is(':checked')) {
    $("#remove-product").attr("disabled", false);
  } else {
    $("#remove-product").attr("disabled", true);
  }
});

$('#remove-product').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var productid = newarray;

  $.post("product/sql-proc.php?r=remove", {
    id: productid
  },
  function(data) {
    $('.well.debug').append(data+"<br>");
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("error");
      $("#info").load( "../include/alert.php #callout-danger", function() {
        $("#callout-title-danger").html("Cannot Remove Product!");
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Remove "+json[0]['count']+" product!");
      });
    }
  });
})

$('#remove-data').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var productid = newarray;

  $.post("product/sql-proc.php?r=remove-data", {
    id: productid,
    data: $("#select-remove").val()
  },
  function(data) {
    $('.well.debug').append(data+"<br>");
    var json = JSON.parse(data);
    var status = json['status'];
    if (status == "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html(json[0]['data']);
      });
    }
  });
})

$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});

</script>
