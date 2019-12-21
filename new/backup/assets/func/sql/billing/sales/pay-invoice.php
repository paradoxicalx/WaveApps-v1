<?php
if (isset($_GET['qs']) && $_GET['qs'] == "pay-invoice") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["paymentmethod", "payto", "datepaid"];
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
  // Cek apakah invoice sudah lunas atau belum.
  $ispaid = ["status" => "paidoff"];
  $id = $_POST['id'];
  for ($i = 0; $i < count($id); $i++) {
    $cekpaid = SqlQuAssoc("SELECT status FROM wavenet.tb_invoice WHERE `id` = $id[$i]");
    if ($cekpaid[0]['status'] !== "unpaid") {
      $status = false;
      $ispaid[] = ["error" => "Found one or more invoices already paid off"];
    }
  }
  if ($status === false) {
    echo json_encode($ispaid);
    exit;
  }
  // Update data baru dengan fungsi SqlQuUpdate
  if ($status = true) {
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $update = SqlQuUpdate("wavenet.tb_invoice", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
        $acc = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id` =".$_POST['payto']);
        $amount = SqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = $id[$i]");
        $acclastbal = $acc[0]['balance']+$amount[0]['total'];
        $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$acclastbal"], "id", $acc[0]['id']);
        if ($updatebalance['status'] == "success") {
          InputTransLog($_POST['payto'], "0", $id[$i], "sales", "Payment for invoice number #$id[$i] is successful", $amount[0]['total']);
          InputLog($_SESSION['name'],"billing-sales","Successful invoice payment");
          $count++;
        }
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count, "data" => $id ];
      echo json_encode($report);
    } elseif ($count > 0 && $count < count($id)) {
      $report = ["status" => "warning"];
      $report[] = ["error" => "Can Only Update "+$count+" Data!", "count" => $count, "data" => $id ];
      echo json_encode($report);
    } elseif ($count === 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => "No Data Has Been Updated", "count" => $count, "data" => $id ];
      echo json_encode($report);
    }
  }


}
?>
