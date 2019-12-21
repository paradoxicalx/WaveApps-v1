<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();

if ($logedin === false) {
  exit;
}
$page = $_GET['p'];
if ($page === 'ipv4') {
  include 'ipv4.php';
} elseif ($page === 'devices') {
  include 'devices.php';
} elseif ($page === 'snmp') {
  include 'snmp.php';
}
?>
