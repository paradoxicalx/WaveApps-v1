<?php
if (isset($_GET['qt']) && $_GET['qt'] == "new-transaction") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["account", "amount", "type"];
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
  // Cek apakah balance pada akun cukup.
  if ($_POST['type'] == "expenses") {
    $account = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id` =".$_POST['account']);
    if ($account[0]['balance'] < $_POST['amount']) {
      $status = false;
      $lack = ["status" => "lack"];
      $lack[] = ["error" => "Lack of funds", "col" => "amount"];
      echo json_encode($lack);
      exit;
    }
  }
  // Proses transaksi
  if ($status = true) {
    $account = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id` =".$_POST['account']);
    if ($_POST['type'] == "expenses") {
      $abal = $account[0]['balance']-$_POST['amount'];
      $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$abal"], "id", $_POST['account']);
    } elseif ($_POST['type'] == "deposit") {
      $abal = $account[0]['balance']+$_POST['amount'];
      $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$abal"], "id", $_POST['account']);
    } else {
      exit;
    }
    if ($updatebalance['status'] == "success") {
      $cash = rupiah($_POST['amount']);
      $type = $_POST['type'];
      $descript = $_POST['description'];
      InputTransLog($_POST['account'], "0", "0", $type, $descript, $_POST['amount']);
      InputLog($_SESSION['name'],"billing-transaction","[$type] $cash transaction was successfully carried out");
      $report = ["status" => "success"];
      $report[] = ["error" => false, "rte" => $type];
      echo json_encode($report);
      exit;
    }
  }
}
?>
