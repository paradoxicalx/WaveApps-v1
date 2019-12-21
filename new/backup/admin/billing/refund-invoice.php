<?php require "../../assets/func/sqlQu.php"; ?>
<div id="info"></div>
<form class="form-horizontal" id="form-account">
  <div class="form-group">
    <label class="col-sm-3 control-label" for="refundfrom">From Account</label>
    <div class="col-sm-9">
      <select id="refundfrom" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
          $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_account WHERE `deleted` = '0'");
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
    <label class="col-sm-3 control-label" for="makerefund"></label>
    <div class="col-sm-9">
      <p class="text-muted well well-sm no-shadow text-center text-red">
        Selected account balance will be taken automatically.
      </p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-3"></div>
    <div class="col-sm-9">
      <div class="pretty p-default p-round p-thick">
          <input type="checkbox" id="agree"/>
          <div class="state p-primary-o">
              <label>I understand. Continue to refund payment!</label>
          </div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" for="makerefund"></label>
    <div class="col-sm-9">
      <input type="button" class="btn btn-primary btn-block" id="makerefund" value="Refund" disabled/>
    </div>
  </div>
</form>

<script type="text/javascript">
var count = table.rows( { selected: true } ).count();
$('#modal-footer-default').text(+count+" invoice selected.");

$('.select2').select2({
  placeholder: 'Select data to remove',
  allowClear: true
});
$('input#agree').on('click', function() {
  if ($("#agree").is(':checked')) {
    $("#makerefund").attr("disabled", false);
  } else {
    $("#makerefund").attr("disabled", true);
  }
});
</script>

<?php if (isset($_GET['invid'])) :?>
  <script type="text/javascript">
    var newarray=[];
    newarray.push(<?= $_GET['invid'] ; ?>);
    var invid = newarray;
  </script>
<?php endif ?>

<?php if (!isset($_GET['invid'])) : ?>
  <script type="text/javascript">
  var count = table.rows( { selected: true } ).count();
  var rowData = table.rows({selected:  true}).data().toArray();
  var total = 0;
  for (var i=0; i < rowData.length ;i++){
    var total = convertToAngka(rowData[i][4])+total;
  }
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][2]);
  }
  var invid = newarray;
  </script>
<?php endif ?>

<script type="text/javascript">
$('#makerefund').on('click', function() {
  $.post("billing/sql-proc.php?qs=refund-invoice", {
    id: invid,
    refundfrom: $("#refundfrom").val()
  },
  function(data) {
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
        $('#callout-title-success').html("Refund is complete!");
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
