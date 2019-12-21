<?php require "../../assets/func/sqlQu.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-account">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="name">Name</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="memberlink">Linked Member</label>
    <div class="col-sm-10">
      <select id="memberlink" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_user WHERE `status` = 'active' AND `deleted` = '0' AND `group` = 'admin'");
          foreach ($query as $key) :
            $name = $key['name'];
            $id = $key['id']
        ?>
        <option value="<?= $id ?>"><?= $name ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="number">Bank Account</label>
    <div class="col-sm-10">
      <input id="number" name="number" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="phone">Phone</label>
    <div class="col-sm-10">
      <input id="phone" name="phone" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="bankurl">Bank URL</label>
    <div class="col-sm-10">
      <input id="bankurl" name="bankurl" type="text" class="form-control" placeholder="Not Change">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="description">Description</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to change account data!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="editaccount"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="editaccount" value="Save" disabled/>
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
    $("#editaccount").attr("disabled", false);
  } else {
    $("#editaccount").attr("disabled", true);
  }
});

var count = table.rows( { selected: true } ).count();
if (count > 1) {
  $('#modal-footer-default').text("You choose "+count+" account. Some data cannot be updated");
  $("#name").attr("disabled", true);
} else {
  $('#modal-footer-default').text(+count+" account selected.");
  $("#name").attr("disabled", false);
}

$('#editaccount').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][0]);
  }
  var accountid = newarray;

  $.post("billing/sql-proc.php?e=account", {
    id: accountid,
    name: $("#name").val(),
    memberlink: $("#memberlink").val(),
    number: $("#number").val(),
    phone: $("#phone").val(),
    bankurl: $("#bankurl").val(),
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
        $('#callout-title-success').html("Success update "+json[0]['count']+" Account!");
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
</script>
