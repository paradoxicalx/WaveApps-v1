function TableLogGen(url, rowfunc, cmplte) {

  tablelog = $('#table-log').DataTable({
    responsive: true,
    select: false,
    pageLength: 15,
    ajax: url,
    colReorder: false, //{"realtime": true}
    rowCallback: rowfunc,
    bAutoWidth: false,
    colReorder: true,
    fnInitComplete: cmplte,
    order: [
      [
        0, "dsc"
      ]
    ],
    dom: "<'row'<'col-sm-12'>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-6'i> <'col-sm-6'p>>",
    buttons: [{
        extend: 'copyHtml5',
        text: '<a class="far fa-copy"></a>',
        titleAttr: 'Copy to Clipboard'
      },
      {
        extend: 'excelHtml5',
        text: '<a class="far fa-file-excel"></a>',
        titleAttr: 'Export to Excel'
      },
      {
        extend: 'csvHtml5',
        text: '<a class="fas fa-file-csv"></a>',
        titleAttr: 'Export to CSV'
      },
      {
        extend: 'pdfHtml5',
        text: '<a class="far fa-file-pdf"></a>',
        titleAttr: 'Export to PDF'
      },
      {
        extend: 'print',
        text: '<a class="fas fa-print"></a>',
        titleAttr: 'Print'
      },
      {
        extend: 'colvis',
        text: '<a class="fas fa-eye-slash"></a>',
        titleAttr: 'Column visibility',
      }
    ]
  });

  tablelog.buttons().container().appendTo('.btn-table');

  $('#tableSearch').keyup(function() {
    table.search($(this).val()).draw();
  })

  $('.table-length').on('click', function() {
    tablelog.page.len($(this).val()).draw();
  });

  $(".selectAll").on("click", function(e) {
    if ($(this).is(":checked")) {
      tablelog.rows().select();
    } else {
      tablelog.rows().deselect();
    }
  });

  tablelog
    .on('select', function(e, dt, type, indexes) {
      var count = table.rows({
        selected: true
      }).count();
      if (count > 0) {
        $('.navbar-btn.disabled').addClass('transition btn-info');
        $('.navbar-btn.disabled').removeClass('disabled btn-default');
      }
    })
    .on('deselect', function(e, dt, type, indexes) {
      var count = table.rows({
        selected: true
      }).count();
      if (count <= 0) {
        $('.navbar-btn.transition').addClass('disabled btn-default');
        $('.navbar-btn.transition').removeClass('transition btn-info');
      }
    });

  this.ChangeAjaxUrl = function(newurl) {
    tablelog.ajax.url(newurl).load();
  }

}