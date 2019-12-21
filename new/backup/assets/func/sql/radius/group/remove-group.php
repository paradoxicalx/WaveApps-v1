<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "group") {
    $id = $_POST['id'];
    $count = 0;
    $log = [];
    for ($i = 0; $i < count($id); $i++) {
      $error = false;
      $groupname = sqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = '$id[$i]'");
      $groupname = $groupname[0]['groupname'];
      // Cek apakah masih ada user terkait
      $used = sqlQuAssoc("SELECT guid FROM radius.radusergroup WHERE `groupname` = '$groupname' AND `username` != '0'");
      $used = $used[0]['guid'];
      if ($used > 0) {
        $report = ["status" => "error"];
        $report[] = ["error" => true, "count" => $count, "log" => "Group used by user" ];
        echo json_encode($report);
        exit;
      }
      // Hapus radusergroup
      $remove = SqlQuRemove("radius.radusergroup", "groupname", $groupname);
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.radusergroup failed";}
      // Hapus radgroupreply
      $remove = SqlQuRemove("radius.radgroupreply", "groupname", $groupname);
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.radgroupreply failed";}
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

  if ($_GET['r'] === "group-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Download Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $groupname = sqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = '$id[$i]'");
        $groupname = $groupname[0]['groupname'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$groupname' AND `attribute` = 'Mikrotik-Recv-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radgroupreply", "id", $radreply);
        if ($remove['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Upload Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $groupname = sqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = '$id[$i]'");
        $groupname = $groupname[0]['groupname'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$groupname' AND `attribute` = 'Mikrotik-Xmit-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radgroupreply", "id", $radreply);
        if ($remove['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Rate Limit") {
      for ($i = 0; $i < count($id); $i++) {
        $groupname = sqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = '$id[$i]'");
        $groupname = $groupname[0]['groupname'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$groupname' AND `attribute` = 'Mikrotik-Rate-Limit'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radgroupreply", "id", $radreply);
        if ($remove['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Profile") {
      for ($i = 0; $i < count($id); $i++) {
        $groupname = sqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = '$id[$i]'");
        $groupname = $groupname[0]['groupname'];
        $radreply = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$groupname' AND `attribute` = 'Mikrotik-Group'");
        $radreply = $radreply[0]['id'];
        $remove = SqlQuRemove("radius.radgroupreply", "id", $radreply);
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
