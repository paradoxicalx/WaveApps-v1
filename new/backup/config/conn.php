<?php
$link = mysqli_connect("localhost", "dhedhy", "dhedhy//15", "wavenet");
if (!$link) {
  die ("DB Connection Failed".mysqli_connect_errno(). " - ".
  mysqli_connect_error() );
  exit();
}
?>
