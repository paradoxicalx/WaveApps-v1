<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<div class="row">
  <div class="col-lg-6">
    <button id="activated" class="btn btn-block btn-success btn-lg">
      <i class="far fa-check-circle"></i> Activated Member
    </button>
  </div>
  <div class="col-lg-6">
    <button id="nonactivated" class="btn btn-block btn-danger btn-lg">
      <i class="fas fa-ban"></i> Non Activated Member
    </button>
  </div>
</div>

<div class="debug-only">
  <hr>
  <p class="text-muted well debug"></p>
  <button type="button" name="button" class="btn clear debug">Clear Debug Result</button>
  <button type="button" name="button" class="btn random debug">Random Input Data</button>
</div>

<script type="text/javascript">

var count = table.rows( { selected: true } ).count();
$('#modal-footer-default').text(+count+" Member selected.");

$('#activated').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][1]);
  }
  var memberid = newarray;

  $.post("member/sql-proc.php?s=activated", {
    id: memberid,
    status: "active"
  },
  function(data) {
    $('.well.debug').append(data+"<br>");
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $("#info").load( "../include/alert.php #callout-warning", function() {
        $("#callout-title-warning").html("Failed update member status");
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Activated "+json[0]['count']+" Member!");
      });
    }
  });
})

$('#nonactivated').on('click', function() {
  rowData = table.rows({selected:  true}).data().toArray();
  var newarray=[];
  for (var i=0; i < rowData.length ;i++){
    newarray.push(rowData[i][1]);
  }
  var memberid = newarray;
  $.post("member/sql-proc.php?s=nonactivated", {
    id: memberid,
    status: "inactive"
  },
  function(data) {
    console.log(data);
    $('.well.debug').append(data+"<br>");
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $("#info").load( "../include/alert.php #callout-warning", function() {
        $("#callout-title-warning").html("Failed : Member have active service");
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");;
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Non Activated "+json[0]['count']+" Member!");
      });
    }
  })
})


// Debug Only
$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});

</script>
