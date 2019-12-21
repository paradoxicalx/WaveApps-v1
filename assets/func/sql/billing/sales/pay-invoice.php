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
  // cek jika menggunakan wallet
  if ($_POST['paymentmethod'] == "wallet") {
    $walletis = ["status" => "lack"];
    $id = $_POST['id'];
    $invoice = SqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = '$id[0]'");
    $member = SqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` =".$invoice[0]['member']);
    if ($member[0]['wallet'] < $invoice[0]['total']) {
      $status = false;
      $walletis[] = ["error" => "Lack of wallet funds"];
      echo json_encode($walletis);
      exit;
    }
  }
  // Update data baru dengan fungsi SqlQuUpdate
  if ($status = true) {
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $acc = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id` =".$_POST['payto']);
      $amount = SqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = $id[$i]");
      $acclastbal = $acc[0]['balance']+$amount[0]['total'];
      if ($_POST['paymentmethod'] == "wallet") {
        $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => $acc[0]['balance']], "id", $acc[0]['id']);
        if ($updatebalance['status'] == "success") {
          $member = SqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` =".$invoice[0]['member']);
          $newwallet = $member[0]['wallet']-$amount[0]['total'];
          SqlQuUpdate("wavenet.tb_user", ["wallet" => "$newwallet"], "id", $amount[0]['member']);
          $update = SqlQuUpdate("wavenet.tb_invoice", $_POST, "id", "$id[$i]");
        }
      } else {
        $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$acclastbal"], "id", $acc[0]['id']);
        $update = SqlQuUpdate("wavenet.tb_invoice", $_POST, "id", "$id[$i]");
      }
      InputTransLog($_POST['payto'], "0", $id[$i], "sales", "Payment for invoice number #$id[$i]", $amount[0]['total']);
      InputLog($_SESSION['name'],"billing-sales",$amount[0]['member']." - Successful payment invoice #".$id[$i]);
      $count++;
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
