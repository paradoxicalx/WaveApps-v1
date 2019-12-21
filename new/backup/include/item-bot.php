<script src="../assets/js/bootstrap/bootstrap.min.js"></script>
<script src="../assets/js/select2/select2.full.min.js"></script>
<script src="../assets/js/jquery/jquery.slimscroll.min.js"></script>
<script src="../assets/js/fastclick/fastclick.js"></script>
<script src="../assets/js/AdminLTE/adminlte.min.js"></script>
<script src="../assets/js/AdminLTE/demo.js"></script>
<script src="../assets/js/inputmask/jquery.inputmask.bundle.min.js"></script>
<script src="../assets/js/currency/currency.js"></script>
<script src="../assets/js/myjs.js"></script>
<!-- <script src="../assets/js/iziModal/iziModal.min.js"></script> -->

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
    $.fn.dataTable.ext.errMode = 'none';
  })
</script>

<script src="../assets/js/fingerprint.js"></script>
<script type="text/javascript">
moment.locale('id');
var datetime = null,
        date = null;
var update = function () {
    date = moment(new Date())
    datetime.html("<b>"+date.format('dddd, Do MMMM YYYY, h:mm:ss')+"</b>");
    ticktime = parseInt($('#ticktime').text());
    if (ticktime > 0) {
      $('#ticktime').text(ticktime-1);
    }
};
$(document).ready(function(){
  datetime = $('#clock')
  update();
  setInterval(update, 1000);

  // function UpdateData () {
  //   $.ajax({
  //     type: "POST",
  //     url: "../config/update-session-data.php",
  //     data: {fingerprint:fingerprint},
  //     success: function(data){
  //       var ses_data = JSON.parse(data);
  //       if (ses_data['accbal'] > 0) {
  //         $('#s-saldo').text(convertToRupiah(ses_data['accbal']));
  //       } else if (ses_data['wallet']) {
  //         $('#s-saldo').text(convertToRupiah(ses_data['wallet']));
  //       } else {
  //         $('#s-saldo').text(convertToRupiah(0));
  //       }
  //       if (ses_data['ticktime']) {
  //         $('#ticktime').text(ses_data['ticktime']);
  //       } else {
  //         $('#ticktime').text("0");
  //       }
  //       if (publicip) {
  //         $('#publicip').text("Your IP : "+publicip);
  //       }
  //     }
  //    });
  // }
  // UpdateData ();
  // $('.content-container,.modal').on('DOMSubtreeModified',function(event) {
  //   UpdateData();
  // });
});
</script>
