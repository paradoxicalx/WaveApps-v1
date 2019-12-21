<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<section class="invoice printall-button" style="display: none;">
  <div class="row no-print">
    <div class="col-xs-12">
      <button type="button" class="btn btn-primary print btn-block"></button>
    </div>
  </div>
</section>
<div id="invpage">
  <clone id="inv0">
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <!-- <i class="fa fa-globe"></i>  -->
            <?= $companyname ; ?>
            <small class="pull-right inv-date"></small>
          </h2>
        </div>
      </div>
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong><?= $shortcompany ; ?></strong><br>
            <d><?= $companyaddress ; ?></d><br>
            Phone: <?= $companyphone ; ?><br>
            Email: <?= $companymail ; ?>
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong id="member-name"></strong><br>
            <d id="member-address"></d><br>
            Phone: <d id="member-phone"></d><br>
            Email: <d id="member-email"></d>
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          <b><d id="invoice-identity"></d></b><br>
          <b>Invoice Number : </b> #<d id="invoice-number"></d><br>
          <b>Account: </b> <d id="member-id"></d><br>
          <b>Payment Due: </b> <d id="invoice-due"></d><br>
          <b>Status: </b> <d id="invoice-status"></d>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <table id="inv-item-list" class="table table-striped">
            <thead>
              <tr>
                <th>Qty</th>
                <th>Product</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody id="product-item">
            </tbody>
          </table>
        </div>
      </div>

      <hr>
      <div class="row">
        <div class="col-sm-6">
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"></td>
              </tr>
              <tr>
                <th>Tax (<d id="taxpercent"></d>%)</th>
                <td id="tax"></td>
              </tr>
              <tr>
                <th>Shipping:</th>
                <td id="shipping"></td>
              </tr>
              <tr>
                <th>Total:</th>
                <td><b id="total"></b></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-sm-6">
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Payment Methods: <br>
            <i class="fas fa-university"></i> Bank Transfer : <?= $bankaccount ; ?> <br>
            <i class="fas fa-money-bill-alt"></i> Cash : <?= $companyaddress ; ?> <br>
            Contact for other payment information
          </p>
        </div>
      </div>

      <hr>
      <div class="row">
        <div class="col-sm-6" style="width:100%; float:left;">
          <p class="text-muted well well-sm no-shadow" id="notes" style="margin-top: 10px;"></p>
        </div>
      </div>

    </section>
    <div class="pagebreak"> </div>
  </clone>
</div>
<section class="invoice button-area">
  <div class="row no-print">
    <div class="col-xs-12">
      <button type="button" class="btn btn-primary print"><i class="fa fa-print"></i> Print</button>
      <button type="button" class="btn btn-info invedit" style="display: none;"><i class="far fa-edit"></i> Edit</button>
      <button type="button" class="btn btn-success pull-right makepay" style="display: none;"><i class="fa fa-credit-card"></i> Submit Payment</button>
      <button type="button" class="btn btn-warning pull-right btnrefund" style="display: none;"><i class="fas fa-funnel-dollar"></i> Refund Payment</button>
    </div>
  </div>
</section>
<section class="invoice printall-button" style="display: none;">
  <div class="row no-print">
    <div class="col-xs-12">
      <button type="button" class="btn btn-primary print btn-block"></button>
    </div>
  </div>
</section>

<script src="../../assets/js/print/jQuery.print.min.js"></script>

<?php if (isset($_GET['id'])) :?>
  <script type="text/javascript">
    var newarray=[];
    newarray.push(<?= $_GET['id'] ; ?>);
    var invid = newarray;
  </script>
<?php endif ?>

<?php if (!isset($_GET['id'])) : ?>
  <script type="text/javascript">
    rowData = table.rows({selected:  true}).data().toArray();
    var newarray=[];
    for (var i=0; i < rowData.length ;i++){
      newarray.push(rowData[i][2]);
    }
    var invid = newarray;
  </script>
<?php endif ?>

<script type="text/javascript">
$.post("billing/sql-proc.php?qs=invoice-print", {
  invid: invid
},
function(data) {
  var json = JSON.parse(data);
  var html = $('#inv0');
  $('#inv0').remove();
  for (var i = 0; i < json.length; i++) {
    $(html).clone().appendTo('#invpage').prop('id', 'inv'+i );
    $('#inv'+i).find('.inv-date').text("Date: "+json[i]['date']);
    $('#inv'+i).find('#member-name').text(json[i]['name']);
    $('#inv'+i).find('#member-address').text(json[i]['address']);
    $('#inv'+i).find('#member-phone').text(json[i]['phone']);
    $('#inv'+i).find('#member-email').text(json[i]['email']);
    $('#inv'+i).find('#invoice-number').text(json[i]['invid']);
    $('#inv'+i).find('#member-id').text(json[i]['memberid']);
    $('#inv'+i).find('#invoice-due').text(json[i]['duedate']);
    $('#inv'+i).find('#invoice-status').text(json[i]['status']);
    $('#inv'+i).find('#invoice-identity').text(json[i]['identity']);
    $('#inv'+i).find('#notes').text(json[i]['notes']);
    $('#inv'+i).find('#subtotal').text(convertToRupiah(json[i]['subtotal']));
    $('#inv'+i).find('#taxpercent').text(json[i]['tax']);
    $('#inv'+i).find('#tax').text(convertToRupiah((json[i]['subtotal']/100)*json[i]['tax']));
    $('#inv'+i).find('#shipping').text(convertToRupiah(json[i]['shipping']));
    $('#inv'+i).find('#total').text(convertToRupiah(json[i]['total']));
    var item = JSON.parse(json[i]['item']);
    for (var x = 0; x < item.length; x++) {
    	$('#inv'+i).find('#product-item').append("<tr><td>"+item[x]['qty']+
    	"</td><td>"+item[x]['name']+
    	"</td><td>"+convertToRupiah(item[x]['price'])+
    	"</td><td>"+convertToRupiah(item[x]['discount'])+
    	"</td><td>"+convertToRupiah(item[x]['totalprice'])+
    	"</td></tr>");
    }
    $('#inv-item-list').DataTable({
      language : { "zeroRecords": " " },
      ordering: false,
      responsive: true,
      dom:  "<'row'<'col-sm-12'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'> <'col-sm-6'>>",
    });
  }
  <?php if (isset($_GET['id'])) :?>
    if (json[0]['status'] == "Unpaid") {
      $(".makepay").show();
      $(".invedit").show();
    }
    if (json[0]['status'] == "Paid") {
      $(".btnrefund").show();
    }
    $('.makepay').on('click', function() {
      $('#modal-payment').modal('show');
      $(".modal-dialog").removeClass("modal-lg");
      $('#modal-title-payment').text("Make Payment");
      $('#modal-body-payment').load("billing/make-payment.php?invid="+json[0]['invid']);
    });
    $('.btnrefund').on('click', function() {
      $('#modal-payment').modal('show');
      $('#modal-payment').find(".modal-header,.modal-footer").removeClass("error warning success").addClass("warning");
      $(".modal-dialog").removeClass("modal-lg");
      $('#modal-title-payment').text("Refund Invoice");
      $('#modal-body-payment').load("billing/refund-invoice.php?invid="+json[0]['invid']);
    });
  <?php endif ?>
});

<?php if (!isset($_GET['id'])) : ?>
  $(".button-area").hide();
  $(".printall-button").show();
  var count = table.rows( { selected: true } ).count();
  $('.btn.print').html("<i class='fa fa-print' style='margin-right:5px'></i> Print "+count+" Invoice");
<?php endif ?>

$('.print').on('click', function() {
  $("#invpage").print({
    globalStyles: true,
    mediaPrint: false,
    stylesheet: null,
    noPrintSelector: ".no-print",
    iframe: true,
    append: null,
    prepend: null,
    manuallyCopyFormValues: true,
    deferred: $.Deferred(),
    timeout: 750,
    title: null,
    doctype: '<!doctype html>'
  });
});

$('.invedit').on('click', function() {
  var rowData =table.rows({selected:  true}).data().toArray();
  var invid =rowData[0][2];
  $('#modal-invoice').modal('show');
  $(".modal-dialog").removeClass("modal-lg");
  $('#modal-invoice').find(".modal-content").css("background-color", "#E8E8E8");
  $('#modal-title-invoice').text("Edit Invoice - #"+invid);
  $('#modal-body-invoice').load("billing/invoice-edit.php?id="+invid);
});
</script>
