<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login_status = $login->login_status();
if($login_status['status'] === true){
	header("Location: ".$login_status['group']);
	exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="assets/js/jquery/jquery.min.js"></script>
  <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/AdminLTE/AdminLTE.min.css">
  <link rel="stylesheet" href="assets/css/my.css">
	<link rel="stylesheet" href="assets/css/pretty-checkbox/pretty-checkbox.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page random-background">
<div class="wrapper gradient">

</div>
<div id="modal-login" class="modal fade" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header black">
        <!-- <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button> -->
        <h4 class="modal-title" id="modal-title-login">Login</h4>
      </div>
      <div class="modal-body" id="modal-body-login">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="username">Username</label>
            <div class="col-sm-9">
              <input id="username" name="username" type="text" class="form-control" autofocus>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="password">Password</label>
            <div class="col-sm-9">
              <input id="password" name="password" type="password" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
              <div class="pretty p-default p-round p-thick">
                  <input type="checkbox" id="remember"/>
                  <div class="state p-primary-o">
                      <label>Remember me for a day !!</label>
                  </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="login"></label>
            <div class="col-sm-9">
              <input type="button" class="btn btn-success btn-block" id="login" value="Login" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer black" id="modal-footer-login">
				<i class="pull-left" id='errinfo'></i>
				<?= $shortcompany ; ?>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/bootstrap/bootstrap.min.js"></script>
<script src="assets/js/fingerprint.js"></script>
<script src="assets/js/myjs.js"></script>
<script type="text/javascript">
	$('#modal-login').on('show.bs.modal', function (event) {
	  var windowHeight = $(window).height();
	  var boxHeight = $('#modal-login').find('.modal-dialog').height();
	  $('#modal-login').find('.modal-dialog').css({'margin-top' : ((windowHeight - boxHeight)/4)});
	  $('#modal-title-login').text('Login').removeClass("text-red text-green text-orange");
	})
	$('#modal-login').modal('show');
	$('#login').on('click', function() {
		Gologin(true);
	});
	$('input').keypress(function(e){
    if(e.which == 13){
      Gologin(true);
    }
  });
	function Gologin(login) {
		$.post("config/login-process.php", {
			username: $("#username").val(),
			password: $("#password").val(),
			remember: document.getElementById("remember").checked,
			fingerprint: fingerprint,
			login: login
		},
		function(data) {
			res = JSON.parse(data);
			if (res['status'] == true) {
				$(location).attr('href',res['group']);
			} else {
				var color = "text-red text-green text-orange";
				if (res['mark']) {
					var count = res['mark']['count']+'/5';
					$('#modal-title-login').text('Failed : '+res['msg']+' '+count).removeClass(color).addClass("text-orange");
				} else {
					$('#modal-title-login').text(res['msg']).removeClass(color).addClass("text-red");
					$('#errinfo').text('countdown : '+res['count']);
				}
			}
		});
	}
</script>

</body>
</html>
