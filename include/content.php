<div class="content-wrapper content-container">
  <section class="content-header">
    <h1></h1>
    <ol class="breadcrumb"></ol>
  </section>
  <section class="content">
    <div class='loader'>
      <div class='loader--dot'></div>
      <div class='loader--dot'></div>
      <div class='loader--dot'></div>
      <div class='loader--dot'></div>
      <div class='loader--dot'></div>
      <div class='loader--dot'></div>
    </div>
  </section>
</div>

<script type="text/javascript">
$.get('dashboard?p=default', function(data) {
  $('.content-container').html(data);
  copyDataMenu('.menu.dashboard');
});
</script>
