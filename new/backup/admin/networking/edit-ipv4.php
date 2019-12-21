<?php require "../../assets/func/sqlQu.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-newipv4">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="identity ">Identity </label>
    <div class="col-sm-10">
      <input id="identity" name="identity" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="notes">Notes</label>
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
              <label>I understand. Continue to update user data!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="editipv4"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editipv4" value="Save" disabled/>
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
$("#agree").on('click', function() {
  if ($('input#agree').is(':checked')) {
    $("#editipv4").attr("disabled", false);
  } else {
    $("#editipv4").attr("disabled", true);
  }
});
var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" IPv4. Some data cannot be updated");
  $("#identity").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" IPv4 selected.");
  $("#identity").attr("disabled", false);
}

$('#editipv4').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var ipid = newarray;

  $.post("networking/sql-proc.php?e=ipv4", {
    id: ipid,
    identity: $("#identity").val(),
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
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success update "+json[0]['count']+" IPv4!");
      });
    } else if (status == "warning") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("danger");
      $("#info").load( "../include/alert.php #callout-danger", function() {
        $('#callout-title-danger').html("Only update "+json[0]['count']+" IPv4! We have error here! ");
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
