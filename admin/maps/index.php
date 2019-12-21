<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();

if ($logedin === false) {
  exit;
}
$page = $_GET['p'];
if ($page === 'pick') {
  include 'pick.php';
} elseif ($page === 'location') {
  include 'location.php';
} elseif ($page === 'inspection') {
  include 'inspection.php';
}
?>
