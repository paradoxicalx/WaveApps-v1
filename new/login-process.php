<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
session_start();

if(isset($_POST['login']) && $_POST['login'] === "true"){
	$username = $_POST['username'];
	$password = $_POST['password'];
  $fingerprint = $_POST['fingerprint'];
  $remember = $_POST['remember'];

  if ($login->check_max_login($fingerprint)) {
		$out = ["status" => false, "msg" => "You reach the maximum allowable login limit !!"];
		echo json_encode($out);
    exit;
  }

	$cek = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE `username` = '$username'");
	if (mysqli_num_rows($cek) > 0) {
    $row = mysqli_fetch_all($cek,MYSQLI_ASSOC);
    $isi = $row[0];
		$password_db = $isi['password'];

		if(password_verify($password, $password_db)){
			$expired = 0;
			if( $remember === "true" ){
        $expired = '+1 day';
			}
      if ($login->login_success($username, $expired, $fingerprint) === true) {
        $out = ["status" => true, "msg" => "Login Success. Redirecting..", "group" => $_SESSION['group']];
      } else {
        $out = ["status" => false, "msg" => "Login failed. Contact administrator !!"];
      }
		}
		else{
			$login->mark_fail_login($username, $fingerprint);
      $out = ["status" => false, "msg" => "Wrong Password !!"];
		}

	}
	else{
		$login->mark_fail_login($username, $fingerprint);
    $out = ["status" => false, "msg" => "Username Not Found !!"];
	}
} else {
  $out = ["status" => false, "msg" => "First Load page !!"];
}
echo json_encode($out);
