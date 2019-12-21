<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();

include "../../assets/func/sql/ticket/list-table.php";
include "../../assets/func/sql/ticket/new-ticket.php";
include "../../assets/func/sql/ticket/reply-ticket.php";
include "../../assets/func/sql/ticket/edit-reply.php";
?>
