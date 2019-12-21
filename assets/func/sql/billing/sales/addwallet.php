<?php
if (isset($_GET['qs']) && $_GET['qs'] == "addwallet") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["member", "amount", "payto"];
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

  if ($status = true) {
    $member = SqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` =".$_POST['member']);
    $account = SqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE `id` =".$_POST['payto']);
    $mname = $member[0]['name'];
    $mbal = $member[0]['wallet']+$_POST['amount'];
    $abal = $account[0]['balance']+$_POST['amount'];
    $updatebalance = SqlQuUpdate("wavenet.tb_account", ["balance" => "$abal"], "id", $_POST['payto']);
    if ($updatebalance['status'] == "success") {
      $cash = rupiah($_POST['amount']);
      InputTransLog($_POST['payto'], "0", $_POST['member'], "wallet", "Add $cash to $mname wallet", $_POST['amount']);
      InputLog($_SESSION['name'],"billing-wallet","Successful add member wallet");
      $updatemember = SqlQuUpdate("wavenet.tb_user", ["wallet" => "$mbal"], "id", $_POST['member']);
      if ($updatemember['status'] == "success") {
        $report = ["status" => "success"];
        $report[] = ["error" => false];
        echo json_encode($report);
      } else {
        $status = false;
      }
    } else {
      $status = false;
    }
  }
  if ($status = false) {
    $report = ["status" => "failed"];
    $report[] = ["error" => "Add member wallet failed "];
    echo json_encode($report);
  }
}
?>
