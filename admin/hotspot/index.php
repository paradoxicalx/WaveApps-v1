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
    <div class="col-md-3">

      <form class="form-horizontal" id="form-account">
        <div class="form-group">
          <div class="col-sm-12">
            <select id="hs-device" class="form-control select2" style="width: 100%;">
              <option value=""></option>
              <?php
              $query = sqlQuAssoc("SELECT id,name FROM wavenet.tb_devices WHERE `deleted` = '0' AND `status` = 'up'");
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
          <div class="col-sm-12">
            <button type="button" class="btn btn-block btn-success" id="hs-reload" disabled><i class="fas fa-sync-alt"></i> Reload</button>
          </div>
        </div>
        <div id="alert"></div>
      </form>

      <div class="box box-solid">
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked l-menu">
            <li><a class="pointer hs home selected" data="home"><i class="fas fa-house-damage"></i> Home</a></li>
            <li><a class="pointer hs" data="server"><i class="fas fa-server"></i> Server</a></li>
            <li><a class="pointer hs" data="server-profile"><i class="fas fa-filter"></i> Server Profile</a></li>
            <li><a class="pointer hs" data="user"><i class="far fa-user"></i> User</a></li>
            <li><a class="pointer hs" data="user-profile"><i class="fas fa-id-card-alt"></i> User Profile</a></li>
            <li><a class="pointer hs" data="ip-binding"><i class="fas fa-user-lock"></i> IP Bindings</a></li>
            <li><a class="pointer hs" data="walled-garden"><i class="fas fa-traffic-light"></i> Walled Garden</a></li>
            <li><a class="pointer hs" data="coockie"><i class="fas fa-cookie-bite"></i> Cookies</a></li>
          </ul>
        </div>
      </div>

      <div class="box box-solid hide box-user-edit">
        <div class="box-header with-border" style="background-color: #d9d9d9;">
          <h3 class="box-title">New User</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked lx-menu">
            <li><a class="pointer" id="new-user"><i class="fas fa-user-plus"></i> Create New</a></li>
            <li><a class="pointer" id="new-multi-user"><i class="fas fa-user-friends"></i> Multiple User</a></li>
          </ul>
        </div>
      </div>

      <div class="box box-solid hide box-user-edit">
        <div class="box-header with-border" style="background-color: #d9d9d9;">
          <h3 class="box-title">Edit Selected User</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked lx-menu">
            <li><a class="pointer" id="print-user" data-mode="print"><i class="fas fa-print"></i> Print Voucher</a></li>
            <li><a class="pointer" id="pdf-user" data-mode="pdf"><i class="fas fa-file-pdf"></i> Create Voucher PDF</a></li>
            <li><a class="pointer" id="remove-user"><i class="fas fa-trash text-red"></i> Remove</a></li>
            <li><a class="pointer" id="disable-user"><i class="fas fa-user-slash text-orange"></i> Disable</a></li>
          </ul>
        </div>
      </div>

    </div>
    <div class="col-md-9" id="hs-content">
    </div>
  </div>
</section>

<script type="text/javascript">
  $.get('hotspot/home.php', function(data) {
    $('#hs-content').html(data);
  });

  var last_device = localStorage.getItem("hs-device");
  $('#hs-device').val(last_device);
  if (last_device > 0) {
    getHSData($('#hs-device').val());
  } else {

  }

  $('.hs').on('click', function() {
    var pge = $(this).attr("data");
    $.get("hotspot/"+pge+".php", function(data) {
      $('#hs-content').html(data);
    });
    $(".l-menu").find('.selected').removeClass('selected');
    $(this).addClass('selected')
    $(".box-user-edit").addClass('hide')
  });

  $('#hs-device').on('change', function() {
    if (!$(this).val()) {
      $('#hs-reload').attr('disabled', true);
    } else {
      getHSData($('#hs-device').val());
    }
  });
  $('#hs-reload').on('click', function() {
    getHSData($('#hs-device').val());
  });

  function getHSData(device) {
    localStorage.setItem("hs-device", device);
    $.get("hotspot/sql-proc.php?getapi&id="+device, function(data) {
      var json = JSON.parse(data);
      var status = json['status'];
      if (status == "success") {
        hsdata = json[0];
        $(".hs.selected").click();
        $("#alert").html("");
        $('#hs-reload').attr('disabled', false);
      } else {
        $("#alert").load( "../include/alert.php #callout-warning", function() {
          $('#callout-title-warning').html("Failed: Please check ip address or api login data");
        });
      }
    });
  }

  $("#new-user").on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Add New Hotspot User");
    $('#modal-body-default').load("hotspot/new-user.php");
  });
  $("#new-multi-user").on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Add Multiple Hotspot User");
    $('#modal-body-default').load("hotspot/new-multi-user.php");
  });
  $("#print-user,#pdf-user").on('click', function() {
    rowData = table.rows({selected:  true}).data().toArray();
    var newarray = new Array()
    for (var i=0; i < rowData.length ;i++){
      let server = hsdata.server.find(o => o.name === rowData[i][4]);
      let server_profile = hsdata.server_profile.find(o => o.name === server.profile);
      var dns = server_profile['dns-name'];
      newarray.push({
        name: rowData[i][2],
        password: rowData[i][3],
        dns: dns,
      });
    }
    var userarr = new Array({user_pass: newarray});
    localStorage.setItem("hs-vcr-print", JSON.stringify(userarr[0]));
    if ($(this).data("mode") == "print") {
      var win = window.open('https://apps.wavenet.id/admin/?print-vcr&mode=print', '_blank');
      if (win) {
        win.focus();
      }
    } else {
      var win = window.open('https://apps.wavenet.id/admin/?print-vcr&mode=pdf', '_blank');
      if (win) {
        win.focus();
      } else {
        alert('Please allow popups for this website');
      }
    }
  });
</script>
