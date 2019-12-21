<?php require "../../assets/func/sqlQu.php"; ?>
<?php require "../../assets/func/converter.php"; ?>
<div class="row">
<div class="col-md-12">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Single Invoice</a></li>
      <li><a href="#tab_2" data-toggle="tab">Multiple Invoice</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
        <?php include "single-invoice.php" ?>
        <table id="table-inv" class="table table-bordered table-striped well " style="width:100%">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Discount</th>
              <th>Total Price</th>
              <th></th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="6"><hr style="margin:0;"></th>
            </tr>
            <tr>
              <th class="text-right">Subtotal :</th>
              <th colspan="4" class="text-right" id="subtotal">Rp. 0</th>
            </tr>
            <tr>
              <th class="text-right">Total :</th>
              <th colspan="4" class="text-right" id="total">Rp. 0</th>
            </tr>
          </tfoot>
        </table>
        <hr>
        <button type="button" class="btn btn-flat btn-block" id="btn-create-inv" disabled>Create Invoice</button>
      </div>
      <div class="tab-pane" id="tab_2">
        <?php include "multi-invoice.php" ?>
        <table id="table-mulinv" class="table table-bordered table-striped well " style="width:100%">
          <thead>
            <tr>
              <th><input type="checkbox" class="selectAll"></th>
              <th>Member Name</th>
              <th>Radius Name</th>
              <th>Service</th>
              <th>Price</th>
              <th>Subtotal</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
        </table>
        <hr>
        <div class="row">
          <div class="col-sm-6">
            <table id="table-mulinv-info" class="table table-bordered table-striped" style="width:100%">
              <thead>
                <tr>
                  <th colspan="2" class="text-center">Information</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Selected data :</td>
                  <td><span class="label label-success info-select">0 / 0</span></td>
                </tr>
                <tr>
                  <td>Total selected value :</td>
                  <td><span class="label label-warning info-total">Rp. 0</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-6">
            <div id="info2" class="margin"></div>
            <button type="button" class="btn btn-flat btn-block" id="btn-create-mulinv" style="width:97%;margin-left:10px;"disabled>Create Invoice</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../assets/js/currency/currency.js"></script>
<script type="text/javascript">
$('.select2').select2({
  tags: "true",
  placeholder: 'Select an option',
  allowClear: true
});

$(document).ready(function() {
  tableinv = $('#table-inv').DataTable({
                responsive: true,
                colReorder: false,
                dom:  "< 'row' <'col-sm-12'> >" +
                      "<'row'<'col-sm-12'tr>>" +
                      "<'row'<'col-sm-6'> <'col-sm-6'> >",
                columnDefs: [
                      {targets: 4,className: 'text-right'},
                      {targets: 5,className: 'text-center'}
                      ]
              });
});

$('#btn-create-inv').on('click', function() {
  var member = $("#member").find(':selected').val();
  var identity = $("#identity").val();
  var date = $("#date").val();
  var duedate = $("#duedate").val();
  var notes = $("#notes").val();
  var tax = $("#tax").val();
  var shipping = convertToAngka($("#shipping").val());

  var total = convertToAngka($('#total').text());
  var subtotal = convertToAngka($('#subtotal').text());
  var item = [];
  $('#table-inv > tbody  > tr').each(function(row, tr){
    var itemtblist = tableinv.row(this).data();
    item.push({
            name: itemtblist[0],
            price: convertToAngka(itemtblist[2]),
            qty: $(tr).find('.qty').val(),
            discount: convertToAngka($(tr).find('.disc').val()),
            totalprice:  convertToAngka(itemtblist[4]),
        });
  });
  var isitem = JSON.stringify(item);
  $.post("billing/sql-proc.php?qs=create-single-invoice", {
    member: member,
    identity: identity,
    date: date,
    duedate: duedate,
    notes: notes,
    shipping: shipping,
    tax: tax,
    total: total,
    subtotal: subtotal,
    item: isitem
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
      $("#info1").load( "../include/alert.php #callout-warning", function() {
        $('#callout-title-warning').html(json[0]['error']);
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info1").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("New Invoice Created!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});

$('#btn-create-mulinv').on('click', function() {
  $("#btn-create-mulinv").attr("disabled", true);
  $("#btn-create-mulinv").text("Creating invoice in progress!");

  var dataallinv = tablemulinv.rows( { selected: true } ).data().toArray();
  var identity = $("#m-identity").val();
  var date = $("#m-date").val();
  var duedate = $("#m-duedate").val();
  var notes = $("#m-notes").val();
  var qty = $("#m-qty").val();
  var tax = $('#m-tax').val();
  var discount = convertToAngka($("#m-discount").val());
  if(!$.isNumeric(discount)) {
    discount = 0;
  }
  var shipping = convertToAngka($('#m-shipping').val());
  if(!$.isNumeric(shipping)) {
    shipping = 0;
  }

  $.post("billing/sql-proc.php?qs=create-multi-invoice", {
    dataallinv: dataallinv,
    tax: tax,
    shipping: shipping,
    identity: identity,
    date: date,
    duedate: duedate,
    notes: notes,
    qty: qty,
    discount: discount
  },
  function(data) {
    var json = JSON.parse(data);
    var status = json['status'];
    if (status != "success") {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $(".has-error").removeClass("has-error");
      $.each( json, function( key, value ) {
        $("#m-"+json[key]['col']).closest(".form-group").addClass("has-error");
        key++
      });
      $("#info2").load( "../include/alert.php #callout-warning", function() {
        $('#callout-title-warning').html(json[0]['error']);
      });
    } else {
      $(".modal-header,.modal-footer").removeClass("error warning success").addClass("success");
      $("#info2").load( "../include/alert.php #callout-success", function() {
        $('#callout-title-success').html("Create multiple invoice, completed!");
      });
      $(".has-error").removeClass("has-error");
    }
    $("#btn-create-mulinv").text("Complete!");
  });

});
</script>
