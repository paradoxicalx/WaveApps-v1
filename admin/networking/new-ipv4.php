<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info"></div>
<form class="form-horizontal" id="form-newipv4">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="identity ">Identity </label>
    <div class="col-sm-10">
      <input id="identity" name="identity" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="ipaddress">IP Address</label>
    <div class="col-sm-10">
      <input id="ipaddress" name="ipaddress" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="netmask ">Netmask </label>
    <div class="col-sm-10">
      <select id="netmask" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="255.0.0.0">8 (255.0.0.0 - 16777214 host)</option>
        <option value="255.128.0.0">9 (255.128.0.0 - 8388606 host)</option>
        <option value="255.192.0.0">10 (255.192.0.0 - 4194302 host)</option>
        <option value="255.224.0.0">11 (255.224.0.0 - 2097150 host)</option>
        <option value="255.240.0.0">12 (255.240.0.0 - 1048574 host)</option>
        <option value="255.248.0.0">13 (255.248.0.0 - 524286 host)</option>
        <option value="255.252.0.0">14 (255.252.0.0 - 262142 host)</option>
        <option value="255.254.0.0">15 (255.254.0.0 - 131070 host)</option>
        <option value="255.255.0.0">16 (255.255.0.0 - 65534 host)</option>
        <option value="255.255.128.0">17 (255.255.128.0 - 32766 host)</option>
        <option value="255.255.192.0">18 (255.255.192 - 16382 host)</option>
        <option value="255.255.224.0">19 (255.255.224 - 8190 host)</option>
        <option value="255.255.240.0">20 (255.255.240 - 4094 host)</option>
        <option value="255.255.248.0">21 (255.255.248.0 - 2046 host)</option>
        <option value="255.255.252.0">22 (255.255.252.0 - 1022 host)</option>
        <option value="255.255.254.0">23 (255.255.254.0 - 510 host)</option>
        <option value="255.255.255.0">24 (255.255.255.0 - 254 host)</option>
        <option value="255.255.255.128">25 (255.255.255.128 - 126 host)</option>
        <option value="255.255.255.192">26 (255.255.255.192 - 62 host)</option>
        <option value="255.255.255.224">27 (255.255.255.224 - 30 host)</option>
        <option value="255.255.255.240">28 (255.255.255.240 - 14 host)</option>
        <option value="255.255.255.248">29 (255.255.255.248 - 6 host)</option>
        <option value="255.255.255.252">30 (255.255.255.252 - 2 host)</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="usage ">Usage </label>
    <div class="col-sm-10">
      <select id="usage" class="form-control select2" style="width: 100%;">
        <option value=""></option>
        <option value="product">Product</option>
        <option value="devices">Devices</option>
        <option value="static">Static</option>
        <option value="pool">Pool</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="notes">Notes</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="notes" name="notes"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="newipv4"></label>
    <div class="col-sm-10">
      <input type="button" class="btn btn-primary btn-block" id="newipv4" value="Add IPv4" />
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
$('.select2').select2({
  placeholder: 'Select an option',
  allowClear: true
});

var subnet = 0;
var bcast = 0;
var count = 0;
var countcek = 0;
var ipexist = 0;
var duplicate = 0;
var iplist;
var newip = [];
$('#newipv4').on('click', function() {
  subnet = 0;
  bcast = 0;
  count = 0;
  countcek = 0;
  ipexist = 0;
  duplicate = 0;
  iplist;
  newip = [];
  $.post("networking/sql-proc.php?n=ipv4", {
    identity: $("#identity").val(),
    ipaddress: $("#ipaddress").val(),
    netmask: $("#netmask").val(),
    usage: $("#usage").val(),
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
        $('#callout-title-warning').html(json[0]['error']);
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("info");
      $(".has-error").removeClass("has-error");
      iplist = JSON.parse(data);
      subnet = iplist[0]['subnet'];
      bcast = iplist[0]['bcast'];
      count = iplist[0]['count']+1;
      var countcek = 0;
      ipexist = iplist[0]['ipexist'];
      duplicate = 0;
      CekIPs();
    }
  });
});

function CekIPs() {
  setTimeout(function(){
    if (subnet <= bcast) {
      $.map(ipexist, function(value, key) {
        if (value == subnet) {
          duplicate++
        }
      });
      subnet++;
      countcek++;
      newip.push(subnet);
      if (duplicate == 0) {
        $("#info").load( "../include/alert.php #callout-info", function() {
          $('#callout-title-info').html("Check and Generate new IPv4 - "+countcek+"/ "+count);
        });
      } else {
        $("#info").load( "../include/alert.php #callout-warning", function() {
          $('#callout-title-warning').html("Check and Generate new IPv4 - "+countcek+"/ "+count+"<br> Error : "+duplicate+" IP/s Conflict");
        });
      }
      CekIPs();
    } else if (subnet > bcast && duplicate == 0) {
      $("#info").load( "../include/alert.php #callout-info", function() {
        $('#callout-title-info').html("Create New IPv4. Please Wait!");
      });
      AddNewIP();
    }
  }, 1);
}

function AddNewIP() {
  $.post("networking/sql-proc.php?n=ipv4-add", {
    identity: $("#identity").val(),
    ipaddress: $("#ipaddress").val(),
    netmask: $("#netmask").val(),
    usage: $("#usage").val(),
    notes: $("#notes").val()
  },
  function(data) {
    $('.well.debug').append(data+"<br>");
    var stat = JSON.parse(data);
    var status = stat['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $("#info").load( "../include/alert.php #callout-warning", function() {
        $("#callout-title-warning").html("Failed Create New IPv4");
      });
    } else if (status == "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Success Create New IPv4");
      });
    }
  });
}

$('.debug.clear').on('click', function() {
  $('.well.debug').empty();
});
$('.debug.random').on('click', function() {
  $('input, textarea').val(generatePassword());
});
</script>
