<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();

include "../../assets/func/sql/product/list.php";
include "../../assets/func/sql/product/new.php";
include "../../assets/func/sql/product/edit.php";
include "../../assets/func/sql/product/remove.php";
?>
