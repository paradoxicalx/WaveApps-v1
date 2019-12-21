<?php
session_start();
print_r($_SESSION);

require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->login_redir();
?>

home
