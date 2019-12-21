<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "account") {
    // Cek apakah akun masih memiliki saldo
    $id = $_POST['id'];
    $count = 0;
    for ($i = 0; $i < count($id); $i++) {
      $balance = SqlQuAssoc("SELECT balance FROM wavenet.tb_account WHERE id = $id[$i]");
      // echo $status['status'];
      if ($balance[0]['balance'] > 0) {
        $count++;
      }
    }
    if ($count > 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    } elseif ($count === 0) {
      // Jika tidak akun balance = 0. Ganti value table 'deleted' menjadi 1.
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_account", ["deleted" => 1], "id", $id[$i]);
        if ($update['status'] == "success") {
          $accname = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE id = $id[$i]");
          InputLog($_SESSION['name'],"billing-account", $accname[0]['name']." Removed");
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

  if ($_GET['r'] === "account-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Bank Account") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_account", ["number" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Phone") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_account", ["phone" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Bank URL") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_account", ["bankurl" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $count++;
        }
      }
    }
    if ($data == "Description") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_account", ["description" => ""], "id", $id[$i]);
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
