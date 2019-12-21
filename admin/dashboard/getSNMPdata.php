<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");

$id = $_POST['id'];
$lastrx = $_POST['lastrx'];
$lasttx = $_POST['lasttx'];

function getSNMPdata($ip, $comunity, $oid, $first=0) {
  $session_snmp = new SNMP(SNMP::VERSION_1, $ip, $comunity);
  $result = $session_snmp->get($oid);
  if ($first === 1) {
    if ($session_snmp->getError()) {
      $report = ["status" => false, "comment" => "Failed to get SNMP data"];
      echo json_encode($report);
      exit;
    }
  }
  $out = substr($result, strpos($result, ":")+2);
  return $out;
}

$device= mysqli_query($link, "SELECT * FROM wavenet.tb_devices WHERE `oid` = '$id' ");
$fetch = mysqli_fetch_all($device,MYSQLI_ASSOC);
$oid= mysqli_query($link, "SELECT * FROM wavenet.tb_devices_oid WHERE `id` = '$id' ");
$fetch_oid = mysqli_fetch_all($oid,MYSQLI_ASSOC);
$oid = json_decode($fetch_oid[0]['oid'], true);

$lastupdate = strtotime($fetch_oid[0]['last_update'])+86400;
$now = time();
if ($lastupdate < $now) {
  $need_update = "true";
} else {
  $need_update = "false";
}

$identity = $fetch_oid[0]['router-name'];
$timezone = $fetch_oid[0]['timezone'];
$board_name = $fetch_oid[0]['board-name'];
$rx1 = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['bytes-in'], 1);
$tx1 = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['bytes-out']);
sleep(1);
$rx2 = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['bytes-in']);
$tx2 = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['bytes-out']);

$memory_used = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['used-memory']);
$memory_total = $fetch_oid[0]['total-memory'];
$voltage = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['voltage']);
$temperature = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['temperature']);
$uptime = getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['uptime']);
$uptime_str = substr(substr($uptime, strpos($uptime, ")")+2), 0, -3);
$expl_time = explode(" ", $uptime_str);
if ($expl_time[1] == "days,") {
  $dl_average = $rx1/$expl_time[0];
  $ul_average = $tx1/$expl_time[0];
} else {
  $dl_average = $rx1;
  $ul_average = $tx1;
}


for ($i=0; $i < $fetch_oid[0]['cpu-count']; $i++) {
  $cpu[] = [getSNMPdata(long2ip($fetch[0]['ip']), $fetch_oid[0]['comunity'], $oid['cpu'][$i])];
}

$transfer = round((($rx2 - $rx1)) * 8 , 2);
$receive = round((($tx2 - $tx1)) * 8 , 2);
$report = ["status" => true,
            "identity" => "$identity",
            "board_name" => "$board_name",
            "rx" => "$receive",
            "tx" => "$transfer",
            "memory_used" => "$memory_used",
            "memory_total" => "$memory_total",
            "cpu" => $cpu,
            "uptime" => $uptime_str,
            "voltage" => $voltage,
            "temperature" => $temperature,
            "dl" => "$rx1",
            "ul" => "$tx1",
            "dl_average" => "$dl_average",
            "ul_average" => "$ul_average",
            "total_hdd" => $fetch_oid[0]['total-hdd'],
            "free_hdd" => $fetch_oid[0]['free-hdd'],
            "update" => "$need_update",
            "timezone" => "$timezone"
          ];
echo json_encode($report);

?>
