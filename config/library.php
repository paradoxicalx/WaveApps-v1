<?php
// function create_alert($type, $pesan, $header=null){
// 	$_SESSION['adm-type'] = $type;
// 	$_SESSION['adm-message'] = $pesan;
//
// 	if($header!==null){
// 		header("location:".$header);
// 		exit();
// 	}
// }

// function show_alert(){
// 	if(isset($_SESSION['adm-type'])){
// 		$type = ucfirst($_SESSION['adm-type']);
// 		unset($_SESSION['adm-type']);
// 		$message = $_SESSION['adm-message'];
// 		unset($_SESSION['adm-message']);
//
// 		return "
// 		<div class='alert alert-$type'>
// 			<strong>$type</strong>
// 			<br>
// 			$message
// 		</div>
// 		";
// 	}
// }

function login_failed(){
  echo "<script type='text/javascript'>$('#modal-login').modal('show');</script>";
  exit;
}

function login_success(){}

function PingTCP($ip, $port=80) {
  if (!$socket = @fsockopen($ip, $port, $errno, $errstr, 2)) {
    return false;
  } else {
    return true;
    fclose($socket);
  }
}

function PingUDP($ip, $port=80) {
  if (!$socket = @fsockopen("udp://".$ip, $port, $errno, $errstr, 2)) {
    return false;
  } else {
    return true;
    fclose($socket);
  }
}

function PingICMP($ip) {
  exec("ping -c 1 " . $ip . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
  $result = substr($ping_time[0], strpos($ping_time[0], "=")+1);
  return $result;
}
?>
