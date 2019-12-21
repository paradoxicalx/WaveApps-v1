<div class="row">
  <div class="col-sm-6">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-membergroup">Member Group</label>
        <div class="col-sm-9">
          <select id="m-membergroup" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <option value="all">All Member</option>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
            <option value="partner">Partner</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-radgroup">Radius Group</label>
        <div class="col-sm-9">
          <select id="m-radgroup" class="form-control select2" style="width: 100%;">
            <option value=""></option>
            <option value="all">All Group</option>
            <?php
              $query = sqlQuAssoc("SELECT DISTINCT groupname FROM radius.radusergroup");
              foreach ($query as $key) :
                $name = $key['groupname'];
            ?>
            <option value="<?= $name ?>"><?= $name ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-identity">Identity</label>
        <div class="col-sm-9">
          <input id="m-identity" name="m-identity" type="text" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-date">Invoice Date</label>
        <div class="col-sm-9">
          <input id="m-date" name="m-date" type="text" class="form-control" data-format="yyyy-MM-dd">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-duedate">Payment Terms</label>
        <div class="col-sm-9">
          <div class="input-group ">
            <input id="m-duedate" name="m-duedate" type="text" class="form-control">
            <span class="input-group-btn">
              <button type="button" class="btn btn-block btn-flat" id="btn-duedate">Days</button>
            </span>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-sm-6">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-qty">Qty</label>
        <div class="col-sm-9">
          <input id="m-qty" name="m-qty" type="text" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-discount">Discount</label>
        <div class="col-sm-9">
          <input id="m-discount" name="m-discount" type="text" class="form-control rupiah">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-tax">Tax</label>
        <div class="col-sm-9">
          <div class="input-group ">
            <input id="m-tax" name="m-tax" type="text" class="form-control">
            <span class="input-group-btn">
              <button type="button" class="btn btn-block btn-flat" id="btn-tax" style="width:42px;"><i class="fas fa-percent"></i></button>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-shipping">Shipping</label>
        <div class="col-sm-9">
          <input id="m-shipping" name="m-shipping" type="text" class="form-control rupiah">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="m-notes">Notes</label>
        <div class="col-sm-9">
          <input id="m-notes" name="m-notes" type="text" class="form-control">
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
$('#m-date').datepicker({
  format: "yyyy-mm-dd",
  autoclose: true,
  todayHighlight: true
});

$('#m-membergroup,#m-radgroup').on('change', function() {
  var membergroup = $('#m-membergroup').find(':selected').val();
  var radgroup = $('#m-radgroup').find(':selected').val();
  if (membergroup != "" && radgroup != "") {
    GenMultiMember(membergroup, radgroup);
  }
});

$('#m-tax,#m-duedate,#m-qty').on('keyup', function() {
  if (!$.isNumeric($('#m-tax').val())) {
    $('#m-tax').val('0').trigger('change');
  }
  if (!$.isNumeric($('#m-duedate').val())) {
    $('#m-duedate').val('0').trigger('change');
  }
  if (!$.isNumeric($('#m-qty').val())) {
    $('#m-qty').val('0').trigger('change');
  }
});

$(".selectAll").on( "click", function(e) {
  if ($(this).is( ":checked" )) {
    tablemulinv.rows().select();
  } else {
    tablemulinv.rows().deselect();
  }
});

$('#m-qty,#m-discount,#m-shipping,#m-tax').donetyping(function(callback){
  updateData();
});

function GenMultiMember (memg, radg) {
  var url = "billing/sql-proc.php?qs=multi-member-data&mgroup="+memg+"&radgroup="+radg;
  tablemulinv = $('#table-mulinv').DataTable({
                responsive: true,
                destroy: true,
                select: true,
                colReorder: false,
                ajax: url,
                dom:  "<'row'<'col-sm-12'> >" +
                      "<'row'<'col-sm-12'tr>>" +
                      "<'row'<'col-sm-12'p> >",
                columnDefs: [
                              {"orderable": false, "targets": 0 },
                              { "width": 10, "targets": 0 },
                              {targets: 7,className: 'text-center'}
                            ],
                rowCallback: function ( row, data, index ) {
                              tablemulinv.cell( row, 7 ).data("<i class='fas fa-backspace pointer text-red rem-mulitem'></i>");
                            },
                fnInitComplete: function(oSettings, json) {
                                CalcMulti();
                                updateData();
                              }
              });
  // tablemulinv.ajax.reload();

  tablemulinv.on( 'order.dt search.dt', function () {
      tablemulinv.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      });
  }).draw();

  $('#table-mulinv tbody').on('click', '.rem-mulitem', function () {
      if($(this).closest('table').hasClass("collapsed")) {
        var child = $(this).parents("tr.child");
        row = $(child).prevAll(".parent");
      } else {
        row = $(this).parents('tr');
      }
      tablemulinv.row(row).remove().draw();
      CalcMulti();
  });

  tablemulinv
    .on( 'select', function ( e, dt, type, indexes ) {
      CalcMulti();
    })
    .on( 'deselect', function ( e, dt, type, indexes ) {
      CalcMulti();
    });

}

function updateData() {
  var qty = $('#m-qty').val();
  if (!$('#m-qty').val()) {var qty = 0;}

  var discount = convertToAngka($('#m-discount').val());
  if(!$.isNumeric(discount)) {discount = 0;}

  var tax = $('#m-tax').val();
  if (!$('#m-tax').val()) {var tax = 0;}

  var shipp = convertToAngka($('#m-shipping').val());
  if(!$.isNumeric(shipp)) {shipp = 0;}

  var allDatas = tablemulinv.data();
  for (var i = 0; i < allDatas.length; i++) {
    var price = convertToAngka(allDatas[i][4]);
    var subtotal = price*qty-discount;
    var taxs = (subtotal/100)*tax;
    allDatas[i][5] = convertToRupiah(subtotal);
    allDatas[i][6] = convertToRupiah(subtotal+taxs+shipp);
  }
  tablemulinv.clear().rows.add(allDatas).draw();
}

function CalcMulti() {
  var tablemulcount = tablemulinv.rows().count();
  var selectedcount = tablemulinv.rows({ selected: true }).count();
  var mulrows = tablemulinv.rows( { selected: true } ).indexes();
  var datatotal = tablemulinv.cells( mulrows, 6, { page: 'all' } ).data();
  var total = 0;
  for (var i = 0; i < datatotal.length; i++) {
    total = convertToAngka(datatotal[i]) + total;
  }

  if (total > 0) {
    $("#btn-create-mulinv").attr("disabled", false);
    $("#btn-create-mulinv").addClass("btn-success");
  } else {
    $("#btn-create-mulinv").attr("disabled", true);
    $("#btn-create-mulinv").removeClass("btn-success");
  }

  $('.info-select').text(selectedcount+" / "+tablemulcount);
  $('.info-total').text(convertToRupiah(total));
  $("#btn-create-mulinv").text("Create "+selectedcount+" Invoice");
}

</script>
