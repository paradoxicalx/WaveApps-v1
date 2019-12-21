<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "devices") {
    $id = $_POST['id'];
    $count = 0;
    $log = [];
    for ($i = 0; $i < count($id); $i++) {
      $error = false;
      $deviceid = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = '$id[$i]'");
      $ipaddress = $deviceid[0]['ip'];
      $removeip = SqlQuUpdate("wavenet.tb_iplist",
               [
                 "used" => "0",
                 "useby" => "",
                 "infid" => ""
               ],
               "ipaddress", "$ipaddress");
     if ($removeip['status'] !== "success") { $error = true; $log[] = "Remove wavenet.tb_iplist failed";}
     if ($error === false) {
       InputLog($_SESSION['name'],"device", $deviceid[0]['name']." Removed");
       SqlQuRemove("wavenet.tb_devices", "id", $id[$i]);
       SqlQuRemove("wavenet.tb_devices_oid", "id", $deviceid[0]['oid']);
       $count++;
     }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count ];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count, "log" => $log ];
      echo json_encode($report);
      exit;
    }
  }
  if ($_GET['r'] === "remove-data-devices") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Type") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_devices", ["type" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Model") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_devices", ["model" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Member") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_devices", ["member" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "API") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_devices", ["apiname" => ""], "id", $id[$i]);
        $update = SqlQuUpdate("wavenet.tb_devices", ["apipass" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "data" => "$data Removed" ];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    }
  }
}
?>
