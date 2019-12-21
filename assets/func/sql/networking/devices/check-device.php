<?php
if (isset($_GET['check-device'])) {
  $now = date("Y-m-d H:i:s");
  $itsup = 0;
  $itsdown = 0;
  $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `deleted` = '0' AND `autocheck` = 'true'");
  foreach ($device as $key) {
    $id = $key['id'];
    $status = $key['status'];
    if ( PingICMP(long2ip($key['ip'])) ) {
      if ($status === "down" or $status === "unknown") {
        $message = $key['name']." - Up";
        SqlQuUpdate("wavenet.tb_devices",
          [
            "status" => "up",
            "lastup" => $now
          ],
          "id", $id);
        InputLog('system',"device","$message");
      }
      $itsup++;
    } else {
      if ($status === "up" or $status === "unknown") {
        $message = $key['name']." - Down";
        SqlQuUpdate("wavenet.tb_devices",
          [
            "status" => "down",
            "lastup" => $now
          ],
          "id", $id);
        InputLog('system',"device","$message");
      }
      $itsdown++;
    }
  }
  $report = ["status" => "success"];
  $report[] = ["up" => $itsup, "down" => $itsdown ];
  echo json_encode($report);
}
?>
