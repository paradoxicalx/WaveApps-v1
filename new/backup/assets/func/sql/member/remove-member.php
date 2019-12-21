<?php
// Remove multiple member.
if (isset($_GET['r'])) {
  if ($_GET['r'] === "remove") {
    // Cek apakah member dalam status aktif.
    $id = $_POST['id'];
    $count = 0;
    for ($i = 0; $i < count($id); $i++) {
      $status = SqlQuAssoc("SELECT status FROM wavenet.tb_user WHERE id = $id[$i]");
      // echo $status['status'];
      if ($status[0]['status'] == "active") {
        $count++;
      }
    }
    if ($count > 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    } elseif ($count === 0) {
      // Jika tidak ada member aktif. Ganti value table 'deleted' menjadi 1.
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["deleted" => 1], "id", $id[$i]);
        if ($update['status'] == "success") {
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
  if ($_GET['r'] === "remove-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Email") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["email" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Phone") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["phone" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Address") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["address" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Location") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["long" => ""], "id", $id[$i]);
        $update = SqlQuUpdate("wavenet.tb_user", ["lat" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Notes") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_user", ["notes" => ""], "id", $id[$i]);
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
