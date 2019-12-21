<?php
require '../../assets/func/sesscek.php';
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
