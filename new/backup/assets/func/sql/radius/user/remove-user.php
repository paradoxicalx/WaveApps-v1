<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "user") {
    $id = $_POST['id'];
    $count = 0;
    $log = [];
    for ($i = 0; $i < count($id); $i++) {
      $error = false;
      $username = sqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = '$id[$i]'");
      $username = $username[0]['username'];
      $ipaddress = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `username` = '$username'");
      $ipaddress = ip2long($ipaddress[0]['value']);
      $remove = SqlQuRemove("radius.radcheck", "id", $id[$i]);
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.radcheck failed";}
      $remove = SqlQuRemove("radius.radusergroup", "username", $username);
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.radusergroup failed";}
      $remove = SqlQuRemove("radius.radreply", "username", $username);
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.radreply failed";}
      $remove = SqlQuUpdate("wavenet.tb_iplist",
               [
                 "used" => "0",
                 "useby" => "",
                 "infid" => ""
               ],
               "ipaddress", "$ipaddress");
      if ($remove['status'] !== "success") { $error = true; $log[] = "Remove iplist failed";}
      if ($error === false) {
        $count++;
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count, "log" => $log ];
      echo json_encode($report);
      exit;
    }
  }
  if ($_GET['r'] === "user-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Ipv4") {
      for ($i = 0; $i < count($id); $i++) {
        $username = sqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = '$id[$i]'");
        $username = $username[0]['username'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$username' AND `attribute` = 'Framed-IP-Address'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radreply", "id", $radreply);
        if ($remove['status'] === "success") {
          $update = SqlQuUpdate("wavenet.tb_iplist",
          [
            "used" => "0",
            "useby" => "",
            "infid" => ""
          ],
          "infid", "$id[$i]");
        }
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Download Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $username = sqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = '$id[$i]'");
        $username = $username[0]['username'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$username' AND `attribute` = 'Mikrotik-Recv-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radreply", "id", $radreply);
        if ($remove['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Upload Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $username = sqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = '$id[$i]'");
        $username = $username[0]['username'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$username' AND `attribute` = 'Mikrotik-Xmit-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radreply", "id", $radreply);
        if ($remove['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Rate Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $username = sqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = '$id[$i]'");
        $username = $username[0]['username'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$username' AND `attribute` = 'Mikrotik-Rate-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radreply", "id", $radreply);
        if ($remove['status'] == "success") {
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
