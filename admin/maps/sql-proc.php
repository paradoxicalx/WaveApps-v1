<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();

include "../../assets/func/sql/maps/location.php";
include "../../assets/func/sql/maps/new-location.php";
include "../../assets/func/sql/maps/edit-location.php";
?>
