<?php
session_start();
require($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login_status = $login->login_status();
if($login_status['status'] === true){
	header("location: /".$login_status['group']);
	exit();
}
else{
	include 'login.php';
}
?>
