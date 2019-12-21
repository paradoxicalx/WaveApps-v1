<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/api-mikrotik.php");
$login->login_redir();

include "../../assets/func/sql/networking/ipv4/list-master.php";
include "../../assets/func/sql/networking/ipv4/list-ipv4.php";
include "../../assets/func/sql/networking/ipv4/new-ipv4.php";
include "../../assets/func/sql/networking/ipv4/edit-ipv4.php";
include "../../assets/func/sql/networking/ipv4/remove-ipv4.php";

include "../../assets/func/sql/networking/devices/list-devices.php";
include "../../assets/func/sql/networking/devices/new-devices.php";
include "../../assets/func/sql/networking/devices/edit-devices.php";
include "../../assets/func/sql/networking/devices/remove-devices.php";
include "../../assets/func/sql/networking/devices/change-service.php";
include "../../assets/func/sql/networking/devices/device-info.php";
include "../../assets/func/sql/networking/devices/device-monitor.php";
include "../../assets/func/sql/networking/devices/check-device.php";
?>
