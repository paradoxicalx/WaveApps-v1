<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();

require "../../assets/func/valid-input.php";

include "../../assets/func/sql/member/list-table.php";
include "../../assets/func/sql/member/new-member.php";
include "../../assets/func/sql/member/edit-member.php";
include "../../assets/func/sql/member/set-status.php";
include "../../assets/func/sql/member/remove-member.php";

?>
