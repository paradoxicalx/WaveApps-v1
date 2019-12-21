
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="assets/js/jquery/jquery.min.js"></script>
  <link rel="stylesheet" href="assets/css/bootstrap/bootstrap4.min.css">
  <link rel="stylesheet" href="assets/css/ionicons/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/AdminLTE/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/css/iCheck/blue.css">
  <link rel="stylesheet" href="assets/css/my.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page random-background">
<div class="wrapper gradient">
  <div class="login-box" style="display: none;">
    <div class="login-logo">
      <a href="#" class="text-white"><b>Wave</b>Net</a>
    </div>
    <div class="login-box-body">
      <div class="login-box-msg"></div>
      <form method="">
        <div class="form-group has-feedback">
          <input name="username" type="text" class="form-control" id="username" placeholder="Username">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input name="password" type="password" class="form-control" id="password" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-8">
            <input id="rememberme" name="rememberme" type="checkbox" class="iCheck">
            <label for="rememberme" class="pointer"> Remember Me </label>
          </div>
          <div class="col-4">
            <button type="button" name="login" class="btn btn-block btn-primary" id="login">Login</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="assets/js/bootstrap/bootstrap4.min.js"></script>
<script src="assets/js/iCheck/icheck.min.js"></script>
<script src="assets/js/fingerprint.js"></script>
<script src="assets/js/myjs.js"></script>

<?php session_start(); ?>
<?php if (isset($_SESSION['sesid']) or isset($_COOKIE['sesid'])) {
  $its = "load" ;
} else {
  $its = "login" ;
} ?>

<script>
$('#login').on('click', function() {
  gologin();
});
function gologin(){
  $.post("config/syscek.php?its=<?= $its; ?>", {
    login: $("#login").val(),
    username: $("#username").val(),
    password: $("#password").val(),
    fingerprint: fingerprint,
    remember: document.getElementById("rememberme").checked
  },
  function(data) {
    var json = JSON.parse(data);
    if (json['status'] === "success") {
      $(location).attr('href','/'+json['group']);
    } else {
      $('.login-box').show();
      $('.login-box-msg').text(json['error']);
    }
  });
}

$(document).ready(function(){
  gologin();
  $('.login-box-msg').text(publicip);
  $('input').iCheck({
    checkboxClass: 'icheckbox_flat-blue',
    radioClass: 'iradio_flat-blue'
  });
  $( "#username" ).focus();
});
</script>


</body>

</html>
