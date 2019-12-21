<div class="content-wrapper content-container">
  <section class="content-header">
    <h1></h1>
    <ol class="breadcrumb"></ol>
  </section>
  <section class="content">
    <div class="text-center">
      <img src="../assets/img/404.png" alt="error" height="250px" width="250px">
      <h1><i class="fas fa-exclamation-triangle text-yellow"></i></h1>
      <h3>Default Page</h3>
      <p>Ini adalah halaman 'default', content yang anda cari tidak ditemukan atau sedang dalam perbaikan.</p>
    </div>
  </section>
</div>

<script type="text/javascript">
$.get('dashboard?p=default', function(data) {
  $('.content-container').html(data);
  copyDataMenu('.menu.dashboard');
});
</script>
