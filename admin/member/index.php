<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();
$page = $_GET['p'];
if ($page === 'default') {
  include 'member-list.php';
} elseif ($page === 'profile') {
  include 'profile.php';
} else {
  include 'member-list.php';
}
?>
