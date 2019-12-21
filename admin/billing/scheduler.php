<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
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
    <div class="col-md-12">
      <div id="alert"></div>
      <div class="box box-info">
        <div class="box-header" data-widget="collapse">
          <i class="fas fa-marker"></i>
          <h3 class="box-title">Invoice Maker</h3>
          <div class="box-tools pull-right btn-table">
            <button type="button" class="btn btn-block btn-success btn-flat">Create New Scheduler</button>
          </div>
        </div>
        <div class="box-body">
          <table id="table-inv-maker" class="table table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Identity</th>
                <th>Date</th>
                <th bgcolor="#e6e6ff">Service Name</th>
                <th>Total Bill</th>
                <th>Next Run</th>
                <th>Scheduled</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="box box-danger">
        <div class="box-header" data-widget="collapse">
          <i class="fas fa-calendar-alt"></i>
          <h3 class="box-title">Expired Invoice</h3>
          <div class="box-tools pull-right btn-table">
            <button type="button" class="btn btn-block btn-success btn-flat" id="new-exp">Create New Scheduler</button>
          </div>
        </div>
        <div class="box-body">
          <div class="box-header">
            <a>New scheduler logic</a>
          </div>
          <table id="table-exp-inv" class="table table-striped nowrap" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Identity</th>
                <th bgcolor="#e6e6ff">Used Service</th>
                <th bgcolor="#e6e6ff">Purchased Stuff</th>
                <th bgcolor="#e6e6ff">Member</th>
                <th bgcolor="#ffe6e6">Next Days</th>
                <th bgcolor="#ffe6e6">Fixed Date</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
tableoption = {
  responsive: true,
  select: true,
  paging: false,
  ordering: false,
  dom:  "<'row'<'col-sm-12'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'> <'col-sm-6'>>",
};
$('#table-inv-maker,#table-exp-inv').DataTable(tableoption);

</script>
