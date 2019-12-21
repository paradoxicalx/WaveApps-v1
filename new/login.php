<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login_status = $login->login_status();
if($login_status['status'] === true){
	header("Location: ".$login_status['group']); 
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Form Login</title>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>
<form>
	<h1>Admin Log In</h1>
	<div id="msg"></div>
	Username : <input type="text" id="username">
	<br>
	Password : <input type="password" id="password">
	<br>
  fingerprint : <input type="text" id="fingerprint" id="fingerprint">
	<br>
	<label for="rmb">
		<input type="checkbox" id="remember" >
		Remember Me
	</label>
	<br>
	<button type="button" id="login" value="1">Log In</button>
</form>

</body>
</html>

<script src="assets/js/fingerprint.js"></script>
<script type="text/javascript">
  $('#fingerprint').val(fingerprint);
  // Gologin(false);
  $('#login').on('click', function() {
    Gologin(true);
  });
  function Gologin(login) {
    $.post("login-process.php", {
      username: $("#username").val(),
      password: $("#password").val(),
      remember: document.getElementById("remember").checked,
      fingerprint: fingerprint,
      login: login
    },
    function(data) {
      res = JSON.parse(data);
      $('#msg').text(res['msg']);
      if (res['status'] == true) {
        $(location).attr('href',res['group']);
      }
      console.log(res);
    });
  }
</script>
