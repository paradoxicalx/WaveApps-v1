<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();

$page = $_GET['p'];
if ($page === 'account') {
  include 'account.php';
} elseif ($page === 'sales') {
  include 'sales.php';
} elseif ($page === 'trans') {
  include 'transaction.php';
} elseif ($page === 'report') {
  include 'report.php';
}elseif ($page === 'scheduler') {
  include 'scheduler.php';
}
?>
