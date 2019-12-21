<?php
if (isset($_GET['qs']) && $_GET['qs'] == "edit-invoice") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["member", "identity", "date", "duedate", "item", "total", "subtotal"];
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

  if ($status == true) {
    $id = $_POST['id'];
    unset($_POST['id']);
    $update = SqlQuUpdate("wavenet.tb_invoice", $_POST, "id", $id[0]);
    if ($update['status'] == "success") {
      InputLog($_SESSION['name'],"billing-sales","Invoice number #$id[0] has changed");
      $report = ["status" => "success"];
      $report[] = ["error" => false ];
      echo json_encode($report);
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => "Edit invoice failed" ];
      echo json_encode($report);
    }
  }
}
?>
