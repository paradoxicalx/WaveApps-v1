<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/api-mikrotik.php");
$login->login_redir();

if (isset($_POST['oid_id'])) {
  $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices_oid WHERE `oid` = ".$_POST['oid_id']);
  $iprouter = long2ip($device[0]['ip']);
  $username = $device[0]['apiname'];
  $pass = $device[0]['apipass'];
  $port = 8728;
}

$API = new routeros_api();
$API->debug = false;
if ($API->connect($iprouter , $username , $pass, $port)) {
  $API->write("/system/resource/print",true);
  $READ = $API->read(false);
  $ARRAY = $API->parse_response($READ);
  $apiinfo = $ARRAY;
} else {
  $status = false;
  $apifail = ["status" => "wrong_api"];
  $apifail[] = ["error" => "Wrong API username or password !!", "col" => "useapi"];
  echo json_encode($apifail);
  exit;
}
$API->disconnect();

$API2 = new routeros_api();
$API2->debug = false;
if ($API2->connect($_POST['ip'] , $_POST['apiname'] , $_POST['apipass'], 8728)) {
  $API2->write("/system/identity/print",true);
  $READ2 = $API2->read(false);
  $ARRAY2 = $API2->parse_response($READ2);
  $apiinfo2 = $ARRAY2;
}
$API2->disconnect();

$insert_oid = SqlQuInsert("wavenet.tb_devices_oid",
  [
    "name" => $_POST['name'],
    "router-name" => $apiinfo2[0]['name'],
    "router-version" => $apiinfo[0]['version'],
    "total-memory" =>  $apiinfo[0]['total-memory'],
    "cpu" => $apiinfo[0]['cpu'],
    "cpu-count" => $apiinfo[0]['cpu-count'],
    "cpu-frequency" => $apiinfo[0]['cpu-frequency'],
    "total-hdd" => $apiinfo[0]['total-hdd-space'],
    "free-hdd" => $apiinfo[0]['free-hdd-space'],
    "architecture-name" => $apiinfo[0]['architecture-name'],
    "board-name" => $apiinfo[0]['board-name'],
  ]);

?>
