<script src="../assets/js/bootstrap/bootstrap.min.js"></script>
<script src="../assets/js/select2/select2.full.min.js"></script>
<script src="../assets/js/jquery/jquery.slimscroll.min.js"></script>
<script src="../assets/js/fastclick/fastclick.js"></script>
<script src="../assets/js/AdminLTE/adminlte.min.js"></script>
<script src="../assets/js/AdminLTE/demo.js"></script>
<script src="../assets/js/inputmask/jquery.inputmask.bundle.min.js"></script>
<script src="../assets/js/currency/currency.js"></script>
<script src="../assets/js/myjs.js"></script>
<script src="../assets/js/chartjs/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-duration-format/1.3.0/moment-duration-format.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap-toggle.min.js"></script>
<script src="../assets/js/html2pdf/html2pdf.bundle.min.js"></script>
<!-- <script src="../assets/js/iziModal/iziModal.min.js"></script> -->

<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
    $.fn.dataTable.ext.errMode = 'none';
    $.getJSON("https://api.ipify.org/?format=json", function(e) {
      $('#publicip').text("Your IP : "+e.ip);
    });
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
    ticktime = ticktime-1;
    var duration = moment.duration(ticktime-1, 'seconds');
    var formatted = duration.format("hh:mm:ss");
    if (ticktime > 0) {
      $('#ticktime').text(formatted);
    }
};

$(document).ready(function(){
  datetime = $('#clock')
  update();
  setInterval(update, 1000);

  function UpdateData () {
    cookieprdx = Cookies.get("prdx");
    <?php if ($_SESSION['accbal'] > 0) : ?>
      $('#s-saldo').text(convertToRupiah(<?= $_SESSION['accbal'] ?>));
    <?php else : ?>
      $('#s-saldo').text(convertToRupiah(<?= $_SESSION['wallet'] ?>));
    <?php endif ?>
    ticktime = parseInt(<?= $_SESSION['ticktime'] ?>);
    if (<?= $_SESSION['fingerprint'] ?> != fingerprint) {
      window.location.href = "../logout.php";
    }
  }
  UpdateData ();
  $(document).on('click', function() {
    if (cookieprdx != Cookies.get("prdx")) {
      UpdateData();
    }
  });
});

</script>
