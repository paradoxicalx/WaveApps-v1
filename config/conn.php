<?php

$link = mysqli_connect("localhost", "dhedhy", "dhedhy//15", "wavenet");

if (!$link) {
  die ("DB Connection Failed".mysqli_connect_errno(). " - ".
  mysqli_connect_error() );
  exit();
}



require_once($_SERVER['DOCUMENT_ROOT']."/config/variable.php");
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/converter.php");

date_default_timezone_set($timezone);
require_once($_SERVER['DOCUMENT_ROOT']."/config/library.php");
require_once($_SERVER['DOCUMENT_ROOT']."/config/ClassLogin.php");

$login = new Login();
?>
