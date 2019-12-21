<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "ipv4") {
    // Cek apakah ip sedang digunakan.
    $id = $_POST['id'];
    $count = 0;
    for ($i = 0; $i < count($id); $i++) {
      $status = SqlQuAssoc("SELECT used FROM wavenet.tb_iplist WHERE master = $id[$i] AND type = 'host' AND used = '1'");
      if (count($status) > 0) {
        $count++;
      }
    }
    if ($count > 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    } elseif ($count === 0) {
      for ($i = 0; $i < count($id); $i++) {
        $ipname = sqlQuAssoc("SELECT * FROM wavenet.tb_ipmaster WHERE `id` = ".$id[$i]);
        $removemaster = SqlQuRemove("wavenet.tb_ipmaster", "id", $id[$i]);
        if ($removemaster['status'] == "success") {
          $removeiplist = SqlQuRemove("wavenet.tb_iplist", "master", $id[$i]);
          InputLog($_SESSION['name'],"networking","IPv4 removed [".$ipname[0]['identity']."]");
          $count++;
        }
      }
      if ($count === count($id)) {
        $report = ["status" => "success"];
        $report[] = ["error" => false, "count" => $count, "data" => $id ];
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
  if ($_GET['r'] === "remove-data-ipv4") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Notes") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_ipmaster", ["notes" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $ipname = sqlQuAssoc("SELECT * FROM wavenet.tb_ipmaster WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"networking","Notes data removed [".$ipname[0]['identity']."]");
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
