<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
$id = $_GET['id'];
$ticket_data = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = '$id'");
$creator_data = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$ticket_data[0]['creator']);
$member_data = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$ticket_data[0]['member']);
$assign_data = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$ticket_data[0]['assign']);
$device_data = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = ".$ticket_data[0]['device']);
if ($ticket_data[0]['priority'] === "critical") {$box = "danger";}
if ($ticket_data[0]['priority'] === "major") {$box = "warning";}
if ($ticket_data[0]['priority'] === "minor") {$box = "info";}
if ($ticket_data[0]['status'] === "new" or $ticket_data[0]['status'] === "open") {$label = "label-info";}
if ($ticket_data[0]['status'] === "closed") {$label = "label-success";}
if ($ticket_data[0]['status'] === "pending") {$label = "label-warning";}
if ($ticket_data[0]['status'] === "trash") {$label = "label-default";}
if (!isset($creator_data[0]['image']) or $creator_data[0]['image'] == "" or empty($creator_data[0]['image'])) {
   $creatorimg = "https://api.adorable.io/avatars/128/".$creator_data[0]['id'];
 } else {
   $creatorimg = "$weburl/image/userimg/".$creator_data[0]['image'];
 }
?>
<link rel="stylesheet" href="../assets/css/bootstrap/bootstrap3-wysihtml5.min.css">
<div class="box box-<?= $box ?>">
  <div class="box-header">
    <h3 class="box-title">
      <div class="btn-group">
        <button type="button" id="set-ticket" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog fa-spin text-purple"></i></button>
        <ul class="dropdown-menu" role="menu">
          <li class="dropdown-submenu">
            <a class="pointer" tabindex="-1"><i class="fas fa-folder"></i> Move Folder <span class="caret"></span></a>
            <ul class="dropdown-menu sub">
              <li class="set-folder pointer" data-stat="open"><a tabindex="-1"><i class="fas fa-envelope-open-text"></i> Open</a></li>
              <li class="set-folder pointer" data-stat="pending"><a tabindex="-1"><i class="far fa-envelope-open"></i> Pending</a></li>
              <li class="set-folder pointer" data-stat="trash"><a tabindex="-1"><i class="fas fa-trash-alt"></i> Trash</a></li>
            </ul>
          </li>
          <?php if ($ticket_data[0]['status'] != "closed") : ?>
          <li class="dropdown-submenu">
            <a class="pointer" tabindex="-1"><i class="far fa-circle"></i> Change Priority <span class="caret"></span></a>
            <ul class="dropdown-menu sub">
              <li class="set-priority pointer" data-prio="critical"><a tabindex="-1"><i class="far fa-circle text-red"></i> Critical</a></li>
              <li class="set-priority pointer" data-prio="major"><a tabindex="-1"><i class="far fa-circle text-yellow"></i> Major</a></li>
              <li class="set-priority pointer" data-prio="minor"><a tabindex="-1"><i class="far fa-circle text-blue"></i> Minor</a></li>
            </ul>
          </li>
          <?php endif ?>
        </ul>
      </div>
      <span id="statview" class='label <?= $label ?>'><?= $ticket_data[0]['status'] ?></span>
      <span id="prioview" class='label label-<?= $box ?>'><?= $ticket_data[0]['priority'] ?></span>
      <span id="topicview" class='label label-default'></span>
    </h3>
    <div class="box-tools pull-right godown">
      <a class="pointer scroll-dwon"><i class="fas fa-arrow-circle-down faa-float animated"></i></a>
    </div>
  </div>
  <div class="box-body">
    <ul class="timeline timeline-inverse">
      <li>
        <div class="timeline-item" style="background-color: rgba(91, 215, 91, 0.2);">
          <div class="timeline-body">
            <div class="row">
              <div class="col-md-7">
                <div style="margin-bottom: 10px;">
                  <b>Ticket Number :</b> <?= $ticket_data[0]['ticket_id'] ?> <br>
                  <?php if ($ticket_data[0]['topic'] === "member") : ?>
                    <b>Related Member :</b> <a class="pointer openmember"><?= $member_data[0]['name'] ?></a> <br>
                  <?php endif ?>
                  <?php if ($ticket_data[0]['topic'] === "networking") : ?>
                    <b>Device :</b> <a class="pointer opendevice"><?= $device_data[0]['name'] ?></a> <br>
                  <?php endif ?>
                  <?php if ($ticket_data[0]['topic'] === "billing") : ?>
                    <b>Related Member :</b> <a class="pointer openmember"><?= $member_data[0]['name'] ?></a> <br>
                    <b>Invoice Number :</b> <a class="pointer openinvoice">#<?= $ticket_data[0]['invoice'] ?></a> <br>
                  <?php endif ?>
                  <span id="assgview"><b>Assignment :</b> <?= $assign_data[0]['name'] ?></span> <br>
                  <b>Title :</b> <?= $ticket_data[0]['title'] ?> <br>
                </div>
              </div>
              <div class="col-md-5">
                <div class="input-group" id="assign-box">
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
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" id="save-assign"><i class="fas fa-save"></i></button>
                    </span>
                </div>
              </div>
            </div>
    		  </div>
        </div>
      </li>
      <li>
    		<img src="<?= $creatorimg ?>" class="mini-usr-img">
    		<div class="timeline-item">
    		  <span class="time"><i class="far fa-clock"></i> <?= $ticket_data[0]['date'] ?></span>
    		  <h3 class="timeline-header"><a class="pointer"><?= $creator_data[0]['name'] ?></a></h3>
    		  <div class="timeline-body">
    			<?= $ticket_data[0]['content'] ?>
    		  </div>
          <?php if (!empty($ticket_data[0]['file'])) : ?>
    		  <div class="timeline-footer">
    			     <a class="btn btn-primary btn-xs" target='_blank' href="<?= $weburl ?>/admin/ticket/file/<?=$ticket_data[0]['file'] ?>">
                 <i class="fas fa-paperclip"></i> <?=$ticket_data[0]['file'] ?>
               </a>
    		  </div>
          <?php endif?>
    		</div>
  	  </li>
      <br>
      <li id="reply-temp" style="display:none">
      	<img src="" class="mini-usr-img">
      	<div class="timeline-item">
      	  <span class="time"><i class="far fa-clock"></i></span>
      	  <h3 class="timeline-header"><a class="pointer"></a></h3>
      	  <div class="timeline-body"></div>
      	  <div class="timeline-footer" style="display:none"></div>
      	</div>
      </li>
    </ul>
    <hr>
    <div id="info-reply" class="reply-area"></div>
    <div class="reply-area" id="reply-area">
      <form class="form-horizontal" id="form-reply-ticket" enctype="multipart/form-data">
        <div class="row">
          <div class="col-lg-4">
            <div class="box box-solid text-center">
              <div class="box-header">
                <h3 class="box-title">Reply Ticket</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="box box-warning">
              <div class="input-group ">
                <input type="button" class="btn btn-default btn-block switchtoggle" value="Hide From Customer" />
                <span class="input-group-btn">
                  <input class="btoggle" id="set-hide" name="set-hide" type="checkbox" data-on="YES" data-off="NO" data-onstyle="warning">
                </span>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="box box-danger">
              <div class="input-group ">
                <input type="button" class="btn btn-default btn-block switchtoggle" value="Mark Ticket Closed" />
                <span class="input-group-btn">
                  <input class="btoggle" id="set-closed" name="set-closed" type="checkbox" data-on="YES" data-off="NO" data-onstyle="danger">
                </span>
              </div>
            </div>
          </div>
        </div>
        <textarea id="reply-text" name="reply-text" style="height: 200px; width:100%"></textarea>
        <div class="row">
          <div class="col-lg-4 mtop10">
            <div class="btn btn-default btn-file btn-block">
              <i class="fas fa-paperclip"></i> <span id="file-name">Attachment</span>
              <input type="file" name="file" id="file" />
            </div>
          </div>
          <div class="col-lg-4 mtop10">
            <select id="att-device" name="att-device" class="form-control select2" style="width: 100%;">
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
          <div class="col-lg-4 mtop10">
            <button class="btn btn-success btn-block" id="send-reply"><i class="fas fa-paper-plane"></i> Send</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="box-footer">
  </div>
</div>
<div id="modal-view" class="modal modal-wide fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-view"></h4>
      </div>
      <div class="modal-body" id="modal-body-view">
      </div>
      <div class="modal-footer" id="modal-footer-view">
      </div>
    </div>
  </div>
</div>
<div id="modal-payment" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-payment"></h4>
      </div>
      <div class="modal-body" id="modal-body-payment">
      </div>
      <div class="modal-footer" id="modal-footer-payment">
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/bootstrap/bootstrap3-wysihtml5.all.min.js"></script>
<script type="text/javascript">
  $('.btoggle').bootstrapToggle();
  $("#topicview").text($(".viewticket[data=<?= $ticket_data[0]['topic'] ?>] x").text());
  function getReply() {
    $.post("ticket/sql-proc.php?read-reply", {
      id: "<?= $ticket_data[0]['id'] ?>"
    },
    function(data) {
      var temp = $('#reply-temp').html();
      var json = JSON.parse(data);
      for (var i = 0; i < json.length; i++) {
        $('.timeline').append("<li id='reply-"+i+"' class='msg'>"+temp+"</li>");
        $('#reply-'+i).find('img').attr('src', json[i]['image']);
        $('#reply-'+i+' .timeline-item h3 a').text(json[i]['name']);
        if (json[i]['edited'] === "1") {
          $('#reply-'+i+' .timeline-item h3').append("<i>edited</i>");
        }
        $('#reply-'+i+' .timeline-item .time').append(" "+json[i]['date']);
        if (json[i]['hide'] === "1") {
          var meshide = "<span class='label label-warning pull-right'>Visible for admin only</span><br>";
          $('#reply-'+i+' .timeline-body').html(meshide+json[i]['content']);
        } else {
          $('#reply-'+i+' .timeline-body').html(json[i]['content']);
        }
        if (json[i]['file'] != "") {
          var url = "<?= $weburl ?>/admin/ticket/file/";
          $('#reply-'+i+' .timeline-footer').append("<a class='btn btn-primary btn-xs' target='_blank' href='"+url+json[i]['file']+"'><i class='fas fa-paperclip'></i> "+json[i]['file']+"</a>");
          $('#reply-'+i+' .timeline-footer').show();
        }
        if (json[i]['ro_capture'] != "") {
          var onklik = '"'+json[i]['ro_capture']+'", "'+json[i]['date']+'"';
          $('#reply-'+i+' .timeline-footer').append("<a class='btn btn-info btn-xs' onclick='viewCapDevice("+onklik+")'><i class='fas fa-file-medical-alt'></i> View captured devices</a>");
          $('#reply-'+i+' .timeline-footer').show();
        }
        if (json[i]['option'] === "enable") {
          $('#reply-'+i+' .timeline-footer').append("<a/><a class='btn btn-default btn-xs pull-right' onclick='editReply("+json[i]['id']+")'><i class='fas fa-pencil-alt'></i>Edit</a>");
          $('#reply-'+i+' .timeline-footer').show();
        }
      }
    });
  }
  getReply();

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
  $('.dropdown-submenu a.pointer').on("click", function(e){
    $('.sub').hide();
    $(this).next('ul').toggle('fast');
    e.stopPropagation();
    e.preventDefault();
  });
  $('#set-ticket').on("click", function(){
    $('.sub').hide();
  });
  $(function () {$("#reply-text").wysihtml5();});
  $('#set-status').select2({placeholder: 'Change Status'});
  $('#att-device').select2({ placeholder: 'Capture Device Info'});
  $('#assign').select2({
    placeholder: 'Change Assignment',
  });
  $('#save-assign').on("click", function(){
    $.post("ticket/sql-proc.php?r=change-assign", {
      id: "<?= $ticket_data[0]['id'] ?>",
      assign: $("#assign").val(),
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status === "success") {
        $('#assgview').html("<b>Assignment :</b> "+json[0]['assgname']);
      }
    });
  });
  $('.set-folder').on("click", function(){
    $.post("ticket/sql-proc.php?r=change-status", {
      id: "<?= $ticket_data[0]['id'] ?>",
      status: $(this).data('stat'),
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status === "success") {
        $('#statview').removeClass('label-info label-default label-warning');
        if (json[0]['status'] == "open") {$('#statview').addClass('label-info');}
        if (json[0]['status'] == "trash") {$('#statview').addClass('label-default');}
        if (json[0]['status'] == "pending") {$('#statview').addClass('label-warning');}
        $('#statview').text(json[0]['status'])
        $('#reply-area').show();
        $('#assign-box').show();
      }
    });
  });
  $('.set-priority').on("click", function(){
    $.post("ticket/sql-proc.php?r=change-priority", {
      id: "<?= $ticket_data[0]['id'] ?>",
      priority: $(this).data('prio'),
    },
    function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status === "success") {
        $('#prioview').removeClass('label-info label-danger label-warning');
        if (json[0]['status'] == "critical") {$('#prioview').addClass('label-danger');}
        if (json[0]['status'] == "major") {$('#prioview').addClass('label-warning');}
        if (json[0]['status'] == "minor") {$('#prioview').addClass('label-info');}
        $('#prioview').text(json[0]['status'])
      }
    });
  });
  $('#send-reply').on('click', function(event) {
    event.preventDefault();
    var form = $('#form-reply-ticket')[0];
    var data = new FormData(form);
    data.append("id", "<?= $ticket_data[0]['id'] ?>");
    $("#send-reply").prop("disabled", true);
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: "ticket/sql-proc.php?r=reply-ticket",
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function (data) {
        $("#send-reply").prop("disabled", false);
        var json = JSON.parse(data);
        var status = json['status'];
        if (status != "success") {
          $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
          $(".has-error").removeClass("has-error");
          $("#info-reply").load( "../include/alert.php #callout-warning", function() {
            $('#callout-title-warning').html(json[0]['error']);
          });
        } else {
          if ($('#set-closed').is(':checked')) {
            $('#reply-area').hide();
            $('#assign-box').hide();
          }
          $("#info-reply").load( "../include/alert.php #callout-success", function() {
            $('#callout-title-success').html("Successfully reply to ticket!");
          });
          $('.timeline').append("<li id='new-"+json[0]['id']+"'>"+
              "<img src='<?= $_SESSION['avatar'] ?>' class='mini-usr-img'>"+
              "<div class='timeline-item'>"+
              "<span class='time'><i class='far fa-clock'></i>"+json[0]['data'][0]['date']+"</span>"+
              "<h3 class='timeline-header'><a class='pointer'><?= $_SESSION['name'] ?></a></h3>"+
          	  "<div class='timeline-body'>"+json[0]['data'][0]['content']+"</div>"+
              "<div class='timeline-footer'><a/><a class='btn btn-default btn-xs pull-right' onclick='editReply("+
                  json[0]['id']+")'><i class='fas fa-pencil-alt'></i>Edit</a></div>"+
          	  "</div></li>");
          if (json[1]) {
            var url = "<?= $weburl ?>/admin/ticket/file/";
            $("#new-"+json[0]['id']+" .timeline-item .timeline-footer").prepend("<a class='btn btn-primary btn-xs' target='_blank' href='"+
                    url+json[1]['filename']+"'><i class='fas fa-paperclip'></i> "+json[1]['filename']+"</a>");
          }
          if (json[0]['data'][0]['ro_capture'] != "") {
            var onklik = '"'+json[0]['data'][0]['ro_capture']+'", "'+json[0]['data'][0]['date']+'"';
            $("#new-"+json[0]['id']+" .timeline-item .timeline-footer").append("<a class='btn btn-info btn-xs' onclick='viewCapDevice("+onklik+
                ")'><i class='fas fa-file-medical-alt'></i> View captured devices</a>");
          }
          if (json[0]['data'][0]['hide'] === "1") {
            var meshide = "<span class='label label-warning pull-right'>Visible for admin only</span><br>";
            $("#new-"+json[0]['id']+" .timeline-item .timeline-body").prepend(meshide);
          }
        }
      }
    });
  });
  if ($('#statview').text() === "closed" || $('#statview').text() === "trash") {
    $('#reply-area').hide();
    $('#assign-box').hide();
  }
  $(".scroll-dwon").click(function() {
    $("html, body").animate({ scrollTop: $(document).height() }, 1000);
  });
  $( "#file" ).change(function(e) {
    var namefile = e.target.files[0].name;
    $("#file-name").text(namefile);
    $("#clear-file").show();
  });
  <?php if ($ticket_data[0]['topic'] === "networking") : ?>
  $(".opendevice").on('click', function() {
    devicesid = <?= $device_data[0]['id'] ?>;
    $('#modal-view').modal('show');
    $('#modal-title-view').text("Devices Info");
    $('#modal-body-view').load("networking/info-devices.php");
  });
  $("#modal-view").on("hide.bs.modal", function() {
    if (trafficDeviceData) {
      clearInterval(trafficDeviceData);
    }
  });
  <?php endif ?>
  <?php if ($ticket_data[0]['topic'] === "member") : ?>
  $(".openmember").on('click', function() {
    $('#modal-view').modal('show');
    $('#modal-title-view').text("Member Profile");
    $('#modal-body-view').load("member/profile.php");
  });
  <?php endif ?>
  <?php if ($ticket_data[0]['topic'] === "billing") : ?>
  $(".openmember").on('click', function() {
    $('#modal-view').modal('show');
    $('#modal-title-view').text("Member Profile");
    $('#modal-body-view').load("member/profile.php");
  });
  $(".openinvoice").on('click', function() {
    $('#modal-view').modal('show');
    $('#modal-title-view').text("Invoice Detail");
    $('#modal-body-view').load("billing/invoice-detail.php?id=<?= $ticket_data[0]['invoice'] ?>");
  });
  <?php endif ?>

  function viewCapDevice(item, date) {
    $('#modal-view').modal('show');
    $('#modal-title-view').text("Captured Devices - "+date);
    $('#modal-body-view').load("networking/info-devices.php?file="+item);
  }
  function editReply(id) {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Edit Messages");
    $('#modal-body-default').load("ticket/edit-reply.php?id="+id);
  }
</script>
