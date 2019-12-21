<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login->logout();
header("Location: ../");
exit;
?>
