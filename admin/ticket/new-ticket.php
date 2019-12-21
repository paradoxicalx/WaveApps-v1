<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<link rel="stylesheet" href="../assets/css/bootstrap/bootstrap3-wysihtml5.min.css">
<div class="box box-primary">
  <div class="box-header text-center with-border">
    <h3 class="box-title">Create New Ticket</h3>
  </div>
  <div class="box-body">
    <div id="info"></div>
    <form class="form-horizontal" id="form-ticket" enctype="multipart/form-data">
      <div class="form-group">
        <label class="col-sm-2 control-label" for="topic">Topic :</label>
        <div class="col-sm-10">
          <select id="topic" name="topic" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <option value="member">Member Complaint</option>
            <option value="networking">Networking</option>
            <option value="billing">Payment/Billing</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div class="form-group topicoption hide">
        <label class="col-sm-2 control-label" for="member">Member :</label>
        <div class="col-sm-10">
          <select id="member" name="member" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <?php
              $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_user WHERE `status` = 'active' AND `deleted` = '0' ");
              foreach ($query as $key) :
                $name = $key['name'];
                $id = $key['id']
            ?>
            <option value="<?= $id ?>"><?= $id." - ".$name ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="form-group topicoption hide">
        <label class="col-sm-2 control-label" for="device">Device :</label>
        <div class="col-sm-10">
          <select id="device" name="device" class="form-control select2" style="width: 100%;">
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
      <div class="form-group topicoption hide">
        <label class="col-sm-2 control-label" for="invoice">Invoice Number :</label>
        <div class="col-sm-10">
          <select id="invoice" name="invoice" class="form-control select2" style="width: 100%;">
            <option value=""></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="assign">Assignment :</label>
        <div class="col-sm-10">
          <select id="assign" name="assign" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <?php
              $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_user WHERE `status` = 'active' AND `deleted` = '0' AND `group` = 'admin'");
              foreach ($query as $key) :
                $name = $key['name'];
                $id = $key['id']
            ?>
            <option value="<?= $id ?>"><?= $id." - ".$name ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="priority">Priority :</label>
        <div class="col-sm-10">
          <select id="priority" name="priority" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <option value="critical">Critical</option>
            <option value="major">Major</option>
            <option value="minor">Minor</option>
          </select>
        </div>
      </div>
      <hr>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="title">Title :</label>
        <div class="col-sm-10">
          <input id="title" name="title" type="text" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="content">Messages :</label>
        <div class="col-sm-10">
          <textarea id="content" name="content" style="height: 200px; width:100%"></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="priority"></label>
        <div class="col-sm-10">
          <div class="btn btn-default btn-file">
            <i class="fas fa-paperclip"></i> <span id="file-name">Attachment</span>
            <input type="file" name="file" id="file" />
          </div>
          <button type="button" class="btn btn-danger" id="clear-file" style="display:none"><i class="fas fa-times"></i></button>
        </div>
      </div>
    </form>
  </div>
  <div class="box-footer">
    <div class="pull-right">
      <button class="btn btn-success create-ticket"><i class="fas fa-paper-plane"></i> Create Ticket</button>
    </div>
    <button class="btn btn-primary" id="discard"><i class="fas fa-ban"></i> Discard</button>
  </div>
</div>

<script src="../assets/js/bootstrap/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript">
$(function () {
  $("#content").wysihtml5();
  $('.bootstrap-wysihtml5-insert-image-url').closest('.form-group').removeClass('form-group');
});
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});
$( "#file" ).change(function(e) {
  var namefile = e.target.files[0].name;
  $("#file-name").text(namefile);
  $("#clear-file").show();
});
$('#topic').on('change', function() {
  $('.topicoption').removeClass('show').addClass('hide');
  $("#member,#device,#invoice,#billing").val('').trigger("change");
  if ($('#topic').val() === "member") {
    $('#member').closest('.form-group').removeClass('hide').addClass('show');
  }
  if ($('#topic').val() === "networking") {
    $('#device').closest('.form-group').removeClass('hide').addClass('show');
  }
  if ($('#topic').val() === "billing") {
    $('#member').closest('.form-group').removeClass('hide').addClass('show');
    $('#invoice').closest('.form-group').removeClass('hide').addClass('show');
  }
});
$('#member').on('change', function() {
  if ($('#member').val() != "") {
    $.post("ticket/sql-proc.php?select-invoice", {
      id: $('#member').val()
    },
    function(data) {
      var json = JSON.parse(data);
      for (var i = 0; i < json.length; i++) {
        $('#invoice').append("<option value='"+json[0]['id']+"'>"+json[0]['id']+" - "+json[0]['identity']+"</option>");
      }
    });
  }
});
$('#clear-file').on('click', function() {
  $("#file").val("");
  $("#file-name").text("Attachment");
  $("#clear-file").hide();
});
$('#discard').on('click', function() {
  $.get('ticket/ticket-list.php?s=open', function(data) {
    $('#ticlist').html(data);
  });
});
$('.create-ticket').on('click', function(event) {
  event.preventDefault();
  var form = $('#form-ticket')[0];
  var data = new FormData(form);
  // data.append("CustomField", "This is some extra data, testing");
  $(".create-ticket").prop("disabled", true);
  $.ajax({
    type: "POST",
    enctype: 'multipart/form-data',
    url: "ticket/sql-proc.php?n=new-ticket",
    data: data,
    processData: false,
    contentType: false,
    cache: false,
    timeout: 600000,
    success: function (data) {
      $(".create-ticket").prop("disabled", false);
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
          $('#callout-title-success').html("New Ticket Created!");
        });
        $(".has-error").removeClass("has-error");
      }
    }
  });
});

</script>
