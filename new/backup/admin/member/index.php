<?php
$page = $_GET['p'];
if ($page === 'default') {
  include 'member-list.php';
} elseif ($page === 'profile') {
  include 'profile.php';
} else {
  include 'member-list.php';
}
?>
