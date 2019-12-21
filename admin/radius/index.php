<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();

if ($logedin === false) {
  exit;
}
$page = $_GET['p'];
if ($page === 'user') {
  include 'user.php';
} elseif ($page === 'group') {
  include 'group.php';
} elseif ($page === 'router') {
  include 'router.php';
} elseif ($page === 'session') {
  include 'session.php';
}
?>
