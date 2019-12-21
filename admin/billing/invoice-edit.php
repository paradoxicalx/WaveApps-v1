<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div id="info1"></div>
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
        <form class="form-horizontal" id="form-editinvoice">
          <div class="col-sm-6 invoice-col">
            <div class="form-group">
              <label class="col-sm-3 control-label" for="identity">Identity</label>
              <div class="col-sm-9">
                <input id="identity" name="identity" type="text" class="form-control" data-format="yyyy-MM-dd">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="member">Member</label>
              <div class="col-sm-9">
                <select id="member" class="form-control select2" style="width: 100%;">
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
            <div class="form-group">
              <label class="col-sm-3 control-label" for="date">Invoice Date</label>
              <div class="col-sm-9">
                <input id="date" name="date" type="text" class="form-control" data-format="yyyy-MM-dd">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="duedate">Due Date</label>
              <div class="col-sm-9">
                <input id="duedate" name="duedate" type="text" class="form-control" data-format="yyyy-MM-dd">
              </div>
            </div>
          </div>
          <div class="col-sm-6 invoice-col">
            <div class="form-group">
              <label class="col-sm-3 control-label" for="tax">Tax</label>
              <div class="col-sm-9">
                <div class="input-group ">
                  <input id="tax" name="tax" type="text" class="form-control">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-block btn-flat" id="btn-tax" style="width:42px;"><i class="fas fa-percent"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="shipping">Shipping</label>
              <div class="col-sm-9">
                <input id="shipping" name="shipping" type="text" class="form-control rupiah">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="notes">Notes</label>
              <div class="col-sm-9">
                <input id="notes" name="notes" type="text" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="product">Add Product</label>
              <div class="col-sm-9">
                <div class="input-group ">
                  <select id="product" class="form-control select2" style="width: 100%;">
                    <option value=""></option>
                    <optgroup label='Service'>
                      <?php
                      $query = sqlQuAssoc("SELECT id,name,price FROM wavenet.tb_product WHERE `deleted` = '0' AND type='service'");
                      foreach ($query as $key) :
                      $name = $key['name'];
                      $id = $key['id'];
                      $price = rupiah($key['price'])
                      ?>
                      <option value="<?= $id ?>" data-price="<?= $price ; ?>" data-name="<?= $name ; ?>"><?= $name ." - ".$price ?></option>
                    <?php endforeach ?>
                  </optgroup>
                  <optgroup label='Stuff'>
                    <?php
                    $query = sqlQuAssoc("SELECT id,name,price FROM wavenet.tb_product WHERE `deleted` = '0' AND type='stuff'");
                    foreach ($query as $key) :
                    $name = $key['name'];
                    $id = $key['id'];
                    $price = rupiah($key['price'])
                    ?>
                    <option value="<?= $id ?>" data-price="<?= $price ; ?>" data-name="<?= $name ; ?>"><?= $name ." - ".$price ?></option>
                  <?php endforeach ?>
                </optgroup>
              </select>
              <span class="input-group-btn">
                <button type="button" class="btn btn-block btn-primary btn-flat" id="btn-product"><i class="fas fa-cart-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

      <div class="row">
        <div class="col-sm-12">
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
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6" style="width:100%; float:left;">
          <hr>
          <input type="button" class="btn btn-primary btn-block" id="saveedit" value="Save"/>
        </div>
      </div>

    </section>
  </clone>
</div>

<script type="text/javascript">
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

  $.post("billing/sql-proc.php?qs=invoice-print", {
    invid: invid
  },
  function(data) {
    var json = JSON.parse(data);
    var item = JSON.parse(json[0].item);

    $('#identity').val(json[0]['identity']);
    $('#member').val(json[0]['memberid']).change();
    $('#date').val(json[0]['date']);
    $('#tax').val(json[0]['tax']);
    $('#duedate').val(json[0]['duedate']);
    $('#notes').val(json[0]['notes']);
    $('#subtotal').text(convertToRupiah(json[0]['subtotal']));
    $('#total').text(convertToRupiah(json[0]['total']));
    $('#shipping').val(convertToRupiah(json[0]['shipping']));

    var btnremove = "<i class='fas fa-backspace pointer text-red rem-item'></i>";
    for (var i = 0; i < item.length; i++) {
      var qty = "<input class='qty' type='text' value='"+item[i].qty+"' style='width:50px; height:20px; text-align:center;'>";
      var discount = "<input class='disc rupiah' type='text' value='"+convertToRupiah(item[i].discount)+"' style='height:20px; width:120px;'>";
      tableinv.row.add([ item[i].name, qty, convertToRupiah(item[i].price), discount, convertToRupiah(item[i].totalprice), btnremove ]).draw( false );
      aftedadditem();
    }
  });

  function aftedadditem() {
    $('#table-inv tbody').on('click', '.rem-item', function () {
        // var tindex = tableinv.responsive.index();
        // tableinv.row( tindex(this) ).remove().draw();
        if($(this).closest('table').hasClass("collapsed")) {
          var child = $(this).parents("tr.child");
          row = $(child).prevAll(".parent");
        } else {
          row = $(this).parents('tr');
        }
        tableinv.row(row).remove().draw();
        calc();
    });
    $('#table-inv tbody').on('keyup', '.qty,.disc', function () {
      var data = tableinv.row($(this).parents('tr')).data();
      var rowindex = tableinv.row($(this).parents('tr')).index();
      var row = tableinv.row(rowindex).node();
      var qty = $($(this).parents('tr')).find('input.qty').val();
      var price = convertToAngka(data[2]);
      var disc = convertToAngka($($(this).parents('tr')).find('input.disc').val());
      var totalprice = qty*price-disc;
      tableinv.cell(row, 4).data(convertToRupiah(totalprice));
      calc();
    });
    $('.rupiah').inputmask("numeric", {
      prefix: ' Rp. ',
      radixPoint: ",",
      groupSeparator: ".",
      digits: 2,
      autoGroup: true,
      rightAlign: false,
      oncleared: function () { self.Value(''); }
    });
    tableinv.columns.adjust().responsive.recalc();
    calc();
  }

  function calc() {
    var countrow = tableinv.rows().count();
    subtotal = 0;
    for (var i = 0; i < countrow; i++) {
      var idx = tableinv.row(i).data();
      var subtotal = convertToAngka(idx[4])+subtotal;
    }
    if ($('#tax').val()) {
      var tx = $('#tax').val();
      var tax = (subtotal/100)*tx;
    } else {
      var tax = 0;
    }
    if ($('#shipping').val()) {
      var shipping = convertToAngka($('#shipping').val());
    } else {
      var shipping = 0;
    }
    var total = subtotal+shipping+tax;
    $('#subtotal').text(convertToRupiah(subtotal));
    $('#total').text(convertToRupiah(total));

    if (total > 0 && subtotal > 0) {
      $("#btn-create-inv").attr("disabled", false);
      $("#btn-create-inv").addClass("btn-success");
    } else {
      $("#btn-create-inv").attr("disabled", true);
      $("#btn-create-inv").removeClass("btn-success");
    }
  }

  $('#btn-product').on('click', function() {
    var name = $('#product').find(':selected').data('name');
    var price = $('#product').find(':selected').data('price');
    var qty = "<input class='qty' type='text' value='1' style='width:50px; height:20px; text-align:center;'>";
    var discount = "<input class='disc rupiah' type='text' value='Rp. 0' style='height:20px; width:120px;'>";
    if (!name) {

    } else {
      var btnremove = "<i class='fas fa-backspace pointer text-red rem-item'></i>";
      tableinv.row.add([ name, qty, price, discount, price, btnremove ]).draw( false );
      aftedadditem();
    }
    calc();
  });

  $('#shipping,#tax').on('keyup', function() {
    calc();
  });

});

$('.select2').select2();
$('#date,#duedate').datepicker({
  format: "yyyy-mm-dd",
  autoclose: true,
  todayHighlight: true
});

$('#saveedit').on('click', function() {
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
  $.post("billing/sql-proc.php?qs=edit-invoice", {
    id: invid,
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
        $('#callout-title-success').html("Invoice has changed!");
      });
      $(".has-error").removeClass("has-error");
    }
  });
});
</script>
