<?php
if (isset($_GET['qs']) && $_GET['qs'] == "remove-invoice") {
  $status = true;
  // Cek apakah invoice sudah terbayar atau belum.
  $this_paid = ["status" => "paid"];
  $id = $_POST['id'];
  for ($i = 0; $i < count($id); $i++) {
    $a = sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = '$id[$i]'");
    $stat = $a[0]['status'];
    if ($stat === "paid") {
      $status = false;
      $this_paid[] = ["error" => "Cannot remove paid invoice!!"];
    }
  }
  if ($status === false) {
    echo json_encode($this_paid);
    exit;
  }
  // Jika invoice belum dibayar. Ganti value table 'deleted' menjadi 1.
  if ($status = true) {
    $count = 0;
    for ($i = 0; $i < count($id); $i++) {
      $update = SqlQuUpdate("wavenet.tb_invoice", ["deleted" => 1], "id", $id[$i]);
      if ($update['status'] == "success") {
        InputLog($_SESSION['name'],"billing-sales","Invoice #".$id[$i]." removed");
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

?>
