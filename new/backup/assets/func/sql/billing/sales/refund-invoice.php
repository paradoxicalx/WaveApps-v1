<?php
if (isset($_GET['qs']) && $_GET['qs'] == "refund-invoice") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["refundfrom"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data)) {
      $status = false;
      $this_empty[] = ["error" => "Required Field", "col" => $cek_empty_field[$i]];
    }
  }
  if ($status === false) {
    echo json_encode($this_empty);
    exit;
  }
  // Cek apakah invoice status paid.
  $this_paid = ["status" => "unpaid"];
  $id = $_POST['id'];
  for ($i = 0; $i < count($id); $i++) {
    $a = sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = '$id[$i]'");
    $stat = $a[0]['status'];
    if ($stat !== "paid") {
      $status = false;
      $this_paid[] = ["error" => "Cannot refund unpaid invoice!"];
    }
  }
  if ($status === false) {
    echo json_encode($this_paid);
    exit;
  }
  // Jika invoice status terbayar. Ganti value table 'status' menjadi refund.
  if ($status = true) {
    $count = 0;
    $acc = $_POST['refundfrom'];
    for ($i = 0; $i < count($id); $i++) {
      $ab = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id`= $acc");
      $acclastbal = $ab[0]['balance'];
      $it = SqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id`=".$id[$i]);
      $invtotal = $it[0]['total'];
      $lastbal = $acclastbal-$invtotal;
      $update = SqlQuUpdate("wavenet.tb_invoice", ["status" => "refund", "refundfrom" => "$acc"], "id", $id[$i]);
      $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$lastbal"], "id", "$acc");
      if ($update['status'] == "success" && $updatebalance['status'] == "success") {
        InputTransLog($acc, "0", $id[$i], "sales", "[Refund] invoice number #$id[$i]", $invtotal);
        InputLog($_SESSION['name'],"billing-sales","Refund invoice payment");
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
