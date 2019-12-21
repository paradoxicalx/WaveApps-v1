<div id="info1"></div>
<div class="row">
  <div class="col-sm-6">
    <form class="form-horizontal">
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
        <label class="col-sm-3 control-label" for="identity">Identity</label>
        <div class="col-sm-9">
          <input id="identity" name="identity" type="text" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="date">Invoice Date</label>
        <div class="col-sm-9">
          <input id="date" name="date" type="text" class="form-control" data-format="yyyy-MM-dd">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="duedate">Payment Terms</label>
        <div class="col-sm-9">
          <div class="input-group ">
            <input id="duedate" name="duedate" type="text" class="form-control">
            <span class="input-group-btn">
              <button type="button" class="btn btn-block btn-flat" id="btn-duedate">Days</button>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="notes">Notes</label>
        <div class="col-sm-9">
          <input id="notes" name="notes" type="text" class="form-control">
        </div>
      </div>
    </form>
  </div>
  <div class="col-sm-6">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-3 control-label" for="optional"></label>
        <div class="col-sm-9">
          <div class="pretty p-default p-round p-thick margin">
            <input type="checkbox" / id="optional">
            <div class="state p-primary-o">
              <label>Custom Product</label>
            </div>
          </div>
        </div>
      </div>
      <div id="default-product">
        <div class="form-group">
          <label class="col-sm-3 control-label" for="serviceuse">Service Used</label>
          <div class="col-sm-9">
            <div class="input-group ">
              <select id="serviceuse" class="form-control select2" style="width: 100%;">
              </select>
              <span class="input-group-btn">
                <button type="button" class="btn btn-block btn-primary btn-flat" id="btn-serviceuse"><i class="fas fa-cart-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="product">Product</label>
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
      <div id="cusom-item" style="display: none;">
        <div class="form-group">
          <label class="col-sm-3 control-label" for="cus-item-name">Product Name</label>
          <div class="col-sm-9">
            <input id="cus-item-name" name="cus-item-name" type="text" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label" for="cus-item-price">Product Price</label>
          <div class="col-sm-9">
            <div class="input-group ">
              <input id="cus-item-price" name="cus-item-price" type="text" class="form-control rupiah">
              <span class="input-group-btn">
                <button type="button" class="btn btn-block btn-primary btn-flat" id="btn-cus-item"><i class="fas fa-cart-plus"></i></button>
              </span>
            </div>
          </div>
        </div>
      </div>
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
    </form>
  </div>
</div>

<script type="text/javascript">
$('input#optional').on('click', function() {
  if ($("#optional").is(':checked')) {
    $( "#default-product" ).hide( "slow" );
    $( "#cusom-item" ).show( "slow" );
  } else {
    $( "#cusom-item" ).hide( "slow" );
    $( "#default-product" ).show( "slow" );
  }
});

$('#date').datepicker({
  format: "yyyy-mm-dd",
  autoclose: true,
  todayHighlight: true
});

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

$('#member').on('change', function() {
  $('#serviceuse').html("<option></option>");
  $.post("billing/sql-proc.php?qs=get-member-service", {
    member: $("#member").val()
  },
  function(data) {
    var json = JSON.parse(data);
    for (var i = 0; i < json.length; i++) {
      $('#serviceuse').append($("<option></option>").attr("value",json[i]['groupname']).text(json[i]['groupname']+" - "+json[i]['username']));
    }
  });
});

var btnremove = "<i class='fas fa-backspace pointer text-red rem-item'></i>";

$('#btn-product').on('click', function() {
  var name = $('#product').find(':selected').data('name');
  var price = $('#product').find(':selected').data('price');
  var qty = "<input class='qty' type='text' value='1' style='width:50px; height:20px; text-align:center;'>";
  var discount = "<input class='disc rupiah' type='text' value='Rp. 0' style='height:20px; width:120px;'>";
  if (!name) {

  } else {
    tableinv.row.add([ name, qty, price, discount, price, btnremove ]).draw( false );
    aftedadditem();
  }
  calc();
});

$('#btn-serviceuse').on('click', function() {
  var serviselected = $("#serviceuse").find(':selected').val();
  if (serviselected) {
    $.post("billing/sql-proc.php?qs=add-service-used", {
      service: serviselected
    },
    function(data) {
      var json = JSON.parse(data);
      var name = json[0]['name'];
      var price = convertToRupiah(json[0]['price']);
      var qty = "<input class='qty' type='text' value='1' style='width:50px; height:20px; text-align:center;'>";
      var discount = "<input class='disc rupiah' type='text' value='Rp. 0' style='height:20px; width:120px;'>";
      if (!name) {
        console.log("empty value");
      } else {
        tableinv.row.add([ name, qty, price, discount, price, btnremove ]).draw( false );
      }
      aftedadditem();
    });
  }
});

$('#btn-cus-item').on('click', function() {
  var name = $('#cus-item-name').val();
  var price = $('#cus-item-price').val();
  var qty = "<input class='qty' type='text' value='1' style='width:50px; height:20px; text-align:center;'>";
  var discount = "<input class='disc rupiah' type='text' value='Rp. 0' style='height:20px; width:120px;'>";
  if (!name) {
    console.log("empty value");
  } else if (price != "") {
    tableinv.row.add([ name, qty, price, discount, price, btnremove ]).draw( false );
    aftedadditem();
  }
});

$('#tax,#duedate').on('keyup', function() {
  if (!$.isNumeric($('#tax').val())) {
    $('#tax').val('0').trigger('change');
  }
  if (!$.isNumeric($('#duedate').val())) {
    $('#duedate').val('0').trigger('change');
  }
});

$('#shipping,#tax').on('keyup', function() {
  calc();
});

</script>
