<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "router") {
    $id = $_POST['id'];
    $count = 0;
    $log = [];
    for ($i = 0; $i < count($id); $i++) {
      $error = false;
      $nasname = sqlQuAssoc("SELECT * FROM radius.nas WHERE `id` = ".$id[$i]);
      // Hapus radusergroup
      $remove = SqlQuRemove("radius.nas", "id", "$id[$i]");
      if ($remove['status'] !== "success") {$error = true; $log[] = "Remove radius.nas failed";}
      if ($error === false) {
        InputLog($_SESSION['name'],"radius","Router removed [".$nasname[0]['shortname']."]");
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

  if ($_GET['r'] === "router-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Ports") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("radius.nas", ["ports" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $nasname = sqlQuAssoc("SELECT * FROM radius.nas WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"radius","Port data removed [".$nasname[0]['shortname']."]");
          $count++;
        }
      }
    }
    if ($data == "Server") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("radius.nas", ["server" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $nasname = sqlQuAssoc("SELECT * FROM radius.nas WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"radius","Server data removed [".$nasname[0]['shortname']."]");
          $count++;
        }
      }
    }
    if ($data == "Community") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("radius.nas", ["community" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $nasname = sqlQuAssoc("SELECT * FROM radius.nas WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"radius","Community data removed [".$nasname[0]['shortname']."]");
          $count++;
        }
      }
    }
    if ($data == "Description") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("radius.nas", ["description" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $nasname = sqlQuAssoc("SELECT * FROM radius.nas WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"radius","Description data removed [".$nasname[0]['shortname']."]");
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
