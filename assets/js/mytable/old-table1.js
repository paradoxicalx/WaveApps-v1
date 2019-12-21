var table = $('#table1').DataTable( {
  responsive: true,
  colReorder: {realtime: true},
  select: true,
  pageLength: 10,
  ajax: "test/test.php?q=",
  dom: "< 'row' <'col-sm-12'rB> >" +
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
      titleAttr: 'Column visibility'
    }
  ]
});

table.buttons().container().appendTo( '.btn-table' );

$('#tableSearch').keyup(function(){
  table.search($(this).val()).draw() ;
})

$('.table-length').on('click', function() {
  table.page.len($(this).val()).draw() ;
});
