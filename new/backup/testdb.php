<?php

$db_host = "10.10.1.6";
$db_user = "dhedhy";
$db_password = "dhedhy//15";
$db_name = "wavenet";

$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$link) {
	die ("DB Connection Failed".mysqli_connect_errno(). " - ".
		mysqli_connect_error() );
	exit();
}