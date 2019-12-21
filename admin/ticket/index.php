<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
$ticket_asg = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new' OR `status` = 'pending') AND `assign` = ".$_SESSION['id']);
$count_asg = count($ticket_asg);
$c_open = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE `status` = 'open' OR `status` = 'new'");
$c_pending = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE `status` = 'pending'");
$c_critical = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `priority` = 'critical'");
$c_major = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `priority` = 'major'");
$c_minor = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `priority` = 'minor'");
$c_member = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `topic` = 'member'");
$c_networking = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `topic` = 'networking'");
$c_billing = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `topic` = 'billing'");
$c_other = sqlQuAssoc("SELECT COUNT(*) AS count FROM wavenet.tb_ticket WHERE (`status` = 'open' OR `status` = 'new') AND `topic` = 'other'");
?>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= $weburl ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
  </ol>
</section>

<section class="content">
  <div class="row container-data">
    <div class="col-md-3">
      <a class="btn btn-primary btn-block margin-bottom" id="newticket">Create New Ticket</a>
      <?php if ($count_asg > 0) : ?>
      <div class="box box-solid">
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li>
              <a class="pointer viewticket" data="assign">
                <i class="fas fa-ticket-alt"></i>
                You have <?= $count_asg ?> assigned tickets
                  <i class="pull-right fas text-red fa-info-circle faa-burst animated"></i>
              </a>
             </li>
          </ul>
        </div>
      </div>
      <?php endif ?>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Folders</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked l-menu">
            <li><a class="pointer viewticket selected" data="open"><i class="fas fa-envelope-open-text"></i> Open <span class="label label-danger pull-right copen"><?= $c_open[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="pending"><i class="far fa-envelope-open"></i> Pending <span class="label label-warning pull-right cpending"><?= $c_pending[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="closed"><i class="fas fa-envelope"></i> Closed</a></li>
            <li><a class="pointer viewticket" data="trash"><i class="fas fa-trash-alt"></i> Trash</a></li>
          </ul>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Priority</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked l-menu">
            <li><a class="pointer viewticket" data="critical"><i class="far fa-circle text-red"></i> Critical <span class="label label-danger pull-right"><?= $c_critical[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="major"><i class="far fa-circle text-yellow"></i> Major <span class="label label-warning pull-right"><?= $c_major[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="minor"><i class="far fa-circle text-light-blue"></i> Minor <span class="label label-primary pull-right"><?= $c_minor[0]['count'] ?></span></a></li>
          </ul>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Topic</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked l-menu">
            <li><a class="pointer viewticket" data="member"><i class="fas fa-user-tag"></i> <x>Member Complaint</x> <span class="label label-default pull-right"><?= $c_member[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="networking"><i class="fas fa-project-diagram"></i> <x>Networking</x> <span class="label label-default pull-right"><?= $c_networking[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="billing"><i class="fas fa-money-bill-wave"></i> <x>Payment/Billing</x> <span class="label label-default pull-right"><?= $c_billing[0]['count'] ?></span></a></li>
            <li><a class="pointer viewticket" data="other"><i class="fas fa-poll-h"></i> <x>Other</x> <span class="label label-default pull-right"><?= $c_other[0]['count'] ?></span></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9" id="ticlist">
    </div>
  </div>
</section>

<script type="text/javascript">
$.get('ticket/ticket-list.php?s=open', function(data) {
  $('#ticlist').html(data);
});
$('#newticket').on('click', function() {
  $.get('ticket/new-ticket.php', function(data) {
    $('#ticlist').html(data);
  });
});
$('.viewticket').on('click', function() {
  var type = $(this).attr("data");
  $.get('ticket/ticket-list.php?s='+type, function(data) {
    $('#ticlist').html(data);
  });
  $(".l-menu").find('.selected').removeClass('selected');
  $(this).addClass('selected')
});
</script>
