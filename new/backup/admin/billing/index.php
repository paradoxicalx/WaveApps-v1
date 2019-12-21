<?php
require '../../assets/func/sesscek.php';
$page = $_GET['p'];
if ($page === 'account') {
  include 'account.php';
} elseif ($page === 'sales') {
  include 'sales.php';
} elseif ($page === 'trans') {
  include 'transaction.php';
} elseif ($page === 'report') {
  include 'report.php';
}
?>
