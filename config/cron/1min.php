<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2);
require $_SERVER['DOCUMENT_ROOT']."/config/conn.php";

$now = date("Y-m-d H:i:s");

// Update device status..
$device = mysqli_query($link, "SELECT * FROM wavenet.tb_devices WHERE `deleted` = '0' AND `autocheck` = 'true'");
$device = mysqli_fetch_all($device,MYSQLI_ASSOC);
foreach ($device as $key) {
  $id = $key['id'];
  $status = $key['status'];
  if ( PingICMP(long2ip($key['ip'])) ) {
    if ($status === "down" or $status === "unknown") {
      $message = $key['name']." - Up";
      mysqli_query($link, "UPDATE wavenet.tb_devices SET `status`='up', `lastup`='$now' WHERE `id` = '$id'");
      mysqli_query($link, "INSERT INTO wavenet.tb_log (`sesname`, `type`, `message`) VALUES ('system', 'device', '$message')");
    }
  } else {
    if ($status === "up" or $status === "unknown") {
      $message = $key['name']." - Down";
      mysqli_query($link, "UPDATE wavenet.tb_devices SET `status`='down', `lastdown`='$now' WHERE `id` = '$id'");
      mysqli_query($link, "INSERT INTO wavenet.tb_log (`sesname`, `type`, `message`) VALUES ('system', 'device', '$message')");
    }
  }
}

// Delete expired session sql.
mysqli_query($link, "DELETE FROM wavenet.tb_loginlog WHERE `stat` = '1' AND `exp_date` < NOW();");

// Delete duplicate active radius session
$radacct = mysqli_query($link, "SELECT username,acctstoptime FROM radius.radacct WHERE `acctstoptime` IS NULL");
$radacct = mysqli_fetch_all($radacct,MYSQLI_ASSOC);

foreach ($radacct as $key) {
  $query = "SELECT username FROM radius.radacct WHERE `username` = '".$key['username']."' AND `acctstoptime` IS NULL";
  $result = mysqli_query($link, $query);
  if (mysqli_num_rows($result) > 1){
    mysqli_query($link, "DELETE FROM radius.radacct WHERE `username` = '".$key['username']."' AND `acctstoptime` IS NULL");
  }
}
