<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login_status = $login->login_status();
if($login_status['status'] === false){
	header("location: ../");
	exit();
} elseif (isset($_GET['print-vcr'])) {
	echo file_get_contents("hotspot/vcr-print.php");
} else {
	require '../include/index.php';
}


?>
