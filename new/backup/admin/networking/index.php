<?php
require '../../assets/func/sesscek.php';
$page = $_GET['p'];
if ($page === 'ipv4') {
  include 'ipv4.php';
} elseif ($page === 'devices') {
  include 'devices.php';
} elseif ($page === 'snmp') {
  include 'snmp.php';
}
?>
