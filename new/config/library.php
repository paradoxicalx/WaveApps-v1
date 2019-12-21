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
  echo "popup-login";
}

function login_success(){
  echo "login-succsess";
}
?>
