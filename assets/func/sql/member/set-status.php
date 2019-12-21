<?php
// Set multiple member status.
if (isset($_GET['s'])) {
  // Aktifkan member
  if ($_GET['s'] === "activated") {
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $update = SqlQuUpdate("wavenet.tb_user", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
        InputLog($_SESSION['name'],"member","Member status change to active [".$update[0]['data'][0]['name']."]");
        $count++;
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count, "data" => $id ];
      echo json_encode($report);
    } else {
      $report = ["status" => "failed"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
    }
  }
  // Nonaktifkan member.
  if ($_GET['s'] === "nonactivated") {
    // Cek apakah member memiliki service yang aktif.
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $useservice = sqlQuAssoc("SELECT * FROM radius.radusergroup WHERE `status` = 'enable' AND `guid` = ".$id[$i]);
      if (count($useservice) > 0) {
        $report = ["status" => "failed"];
        $report[] = ["error" => true, "info" => "in_service", "data" => $id[$i] ];
        echo json_encode($report);
        exit;
      } else {
        // Jika tidak ada service aktif. Update status jadi inactive.
        $count = 0;
        for ($i = 0; $i < count($id); $i++) {
          $update = SqlQuUpdate("wavenet.tb_user", $_POST, "id", $id[$i]);
          if ($update['status'] == "success") {
            InputLog($_SESSION['name'],"member","Member status change to inactive [".$update[0]['data'][0]['name']."]");
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
  }
}
?>
