<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
$message = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket_reply WHERE `id` = ".$_GET['id']);
?>
<link rel="stylesheet" href="../assets/css/bootstrap/bootstrap3-wysihtml5.min.css">
<div id="info"></div>
<form class="form-horizontal" id="form-editreply" enctype="multipart/form-data">
  <div class="input-group ">
    <input type="button" class="btn btn-default btn-block switchtoggle" value="Remove Messages" />
    <span class="input-group-btn">
      <input class="btoggle" id="remove-reply" name="remove-reply" type="checkbox" data-on="YES" data-off="NO" data-onstyle="danger">
    </span>
  </div>
  <hr>
  <div id="editform">
    <div class="input-group mtop10">
      <input type="button" class="btn btn-default btn-block switchtoggle" value="Hide From Customer" />
      <span class="input-group-btn">
        <input class="btoggle" id="set-hide" name="set-hide" type="checkbox"
        <?php if ($message[0]['hide'] === "1"): ?>
          checked
        <?php endif; ?>
        data-on="YES" data-off="NO" data-onstyle="warning">
      </span>
    </div>
    <div class="input-group mtop10">
      <input type="button" class="btn btn-default btn-block switchtoggle" value="Remove Attachment" />
      <span class="input-group-btn">
        <input class="btoggle" id="rem-attach" name="rem-attach" type="checkbox" data-on="YES" data-off="NO" data-onstyle="warning">
      </span>
    </div>
    <div class="input-group mtop10">
      <input type="button" class="btn btn-default btn-block switchtoggle" value="Remove Captured Devices" />
      <span class="input-group-btn">
        <input class="btoggle" id="rem-device" name="rem-device" type="checkbox" data-on="YES" data-off="NO" data-onstyle="warning">
      </span>
    </div>
    <hr>
    <textarea id="reply-edit" name="reply-edit" style="height: 200px; width:100%"><?= $message[0]['content'] ?></textarea>
    <div class="mtop10">
      <div class="btn btn-default btn-file btn-block">
        <i class="fas fa-paperclip"></i> <span id="file-name-new">Attachment</span>
        <input type="file" name="file-new" id="file-new" />
      </div>
    </div>
    <div class="mtop10">
      <select id="e-att-device" name="e-att-device" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <?php
        $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_devices WHERE `deleted` = '0' ");
        foreach ($query as $key) :
          $name = $key['name'];
          $id = $key['id']
          ?>
          <option value="<?= $id ?>"><?= $id." - ".$name ?></option>
        <?php endforeach ?>
      </select>
    </div>
  </div>
  <div class="mtop10">
    <button class="btn btn-success btn-block" id="send-edit"><i class="fas fa-paper-plane"></i> Save</button>
  </div>
</form>

<script src="../assets/js/bootstrap/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript">
  $('.btoggle').bootstrapToggle();
  $('#e-att-device').select2({ placeholder: 'Capture Device Info'});
  $(function () {$("#reply-edit").wysihtml5();});
  $('.switchtoggle').on("click", function(){
    var ttoggle = $(this).next('span').find('.btoggle');
    if ($(this).hasClass('on')) {
      ttoggle.prop('checked', false).change();
      $(this).removeClass('on');
    } else {
      ttoggle.prop('checked', true).change();
      $(this).addClass('on');
    }
  });
  $('#remove-reply').change(function() {
    if ($(this).prop('checked')) {
      $('#editform').hide('fast');
    } else {
      $('#editform').show('fast');
    }
  })
  $( "#file-new" ).change(function(e) {
    var namefile = e.target.files[0].name;
    $("#file-name-new").text(namefile);
  });

  $('#send-edit').on('click', function(event) {
    event.preventDefault();
    var form = $('#form-editreply')[0];
    var data = new FormData(form);
    data.append("id", "<?= $_GET['id'] ?>");
    $("#send-edit").prop("disabled", true);
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: "ticket/sql-proc.php?reply-edit",
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function (data) {
        $("#send-edit").prop("disabled", false);
        var json = JSON.parse(data);
        var status = json['status'];
        if (status != "success") {
          $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
          $("#info").load( "../include/alert.php #callout-warning", function() {
            $("#callout-title-warning").html(json[0]['error']);
          });
        } else {
          $(".modal-header,.modal-footer").removeClass("warning");
          $('.msg').remove();
          getReply();
          $('#modal-default').modal('hide');
        }
      }
    });
  });
</script>
