<?php
if (isset($_GET['t']) &&  $_GET['t'] == "transfer") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["from", "to", "amount", "description"];
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
  // Cek apakah transfer ke akun sendiri bukan
  if ($_POST['from'] == $_POST['to']) {
    $status = false;
    $selftrans = ["status" => "selftrans"];
    $selftrans[] = ["error" => "Can't transfer to the same account"];
    echo json_encode($selftrans);
    exit;
  }
  // Update data akun dan masukan log transfer
  if ($status = true) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $count = 0;

    $from_curent_bal = sqlQuAssoc("SELECT balance FROM wavenet.tb_account WHERE `id` = '$from'");
    $to_curent_bal = sqlQuAssoc("SELECT balance FROM wavenet.tb_account WHERE `id` = '$to'");

    $fx = $from_curent_bal[0]['balance']-$amount;
    $tx = $to_curent_bal[0]['balance']+$amount;

    $from_update = SqlQuUpdate("wavenet.tb_account", ["balance" => "$fx"], "id", "$from");
    if ($from_update['status'] == "success") {
      $count++;
    }
    $to_update = SqlQuUpdate("wavenet.tb_account", ["balance" => "$tx"], "id", "$to");
    if ($to_update['status'] == "success") {
      $count++;
    }
    if ($count === 2) {
      $insert_log = SqlQuInsert("wavenet.tb_transfer",
      [
        "from" => $from,
        "to" => $to,
        "nominal" => $amount,
        "description" => $description
      ]);
      InputTransLog($from, $to, $insert_log[0]['id'], "transfer", $description, $amount);
      InputLog($_SESSION['name'],"billing-account","Transfer balance successfully");
      $report = ["status" => "success"];
      $report[] = ["error" => false, "data" => "Transfer Success" ];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count];
      echo json_encode($report);
      exit;
    }

  }


}
?>
