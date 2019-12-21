function Table2Gen(url, rowfunc) {

   table2 = $('#table2').DataTable( {
    responsive: true,
    select: false,
    pageLength: 10,
    ajax: url,
    ordering: true,
    colReorder: false, //{"realtime": true},
    rowCallback: rowfunc,
    bAutoWidth: false,
    order: [[
            0, "asc"
          ]],
    columnDefs: [
                  { "width": 10, "targets": 0 }
                ],
    dom:  "< 'row' <'col-sm-12'rB> >" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-6'i> <'col-sm-6'p> >",
    buttons: [
              {
                extend:    'copyHtml5',
                text:      '<a class="far fa-copy"></a>',
                titleAttr: 'Copy to Clipboard'
              },
              {
                extend:    'excelHtml5',
                text:      '<a class="far fa-file-excel"></a>',
                titleAttr: 'Export to Excel'
              },
              {
                extend:    'csvHtml5',
                text:      '<a class="fas fa-file-csv"></a>',
                titleAttr: 'Export to CSV'
              },
              {
                extend:    'pdfHtml5',
                text:      '<a class="far fa-file-pdf"></a>',
                titleAttr: 'Export to PDF'
              },
              {
                extend:    'print',
                text:      '<a class="fas fa-print"></a>',
                titleAttr: 'Print'
              },
              {
                extend:    'colvis',
                text:      '<a class="fas fa-eye-slash"></a>',
                titleAttr: 'Column visibility',
              }
            ]
  });

  table2.buttons().container().appendTo( '.btn-table2' );

  $('#tableSearch').keyup(function(){
    table2.search($(this).val()).draw() ;
  })

  $('.table-length').on('click', function() {
    table2.page.len($(this).val()).draw() ;
  });

  this.ChangeAjaxUrl = function(newurl) {
    table2.ajax.url(newurl).load();
  }

}
