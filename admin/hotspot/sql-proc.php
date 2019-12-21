<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/api-mikrotik.php");
$login->login_redir();

include "../../assets/func/sql/hotspot/getapi.php";
include "../../assets/func/sql/hotspot/new-user.php";
