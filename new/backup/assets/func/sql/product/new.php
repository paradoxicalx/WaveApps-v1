<?php
if (isset($_GET['n']) && $_GET['n'] === "product") {
  $status = true;
  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "price", "type"];
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
  // Cek apakah ada data yang sama dengan yang telah ada dalam table sql
  // Gunakan fungsi SqlQuDuplicate
  $cek_duplicate_data =
  SqlQuDuplicate("wavenet.tb_product",
                [
                  "name" => $_POST['name']
                ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  if (!empty($_POST['rgroup'])) {
    $cek_duplicate_data =
    SqlQuDuplicate("wavenet.tb_product",
                  [
                    "rgroup" => $_POST['rgroup']
                  ]);
    if ($cek_duplicate_data['status'] === "duplicate") {
      $status = false;
      echo json_encode($cek_duplicate_data);
      exit;
    }
  }
  if (!empty($_POST['number'])) {
    $cek_duplicate_data =
    SqlQuDuplicate("wavenet.tb_product",
                  [
                    "number" => $_POST['number']
                  ]);
    if ($cek_duplicate_data['status'] === "duplicate") {
      $status = false;
      echo json_encode($cek_duplicate_data);
      exit;
    }
  }
  // Jika Semua pengecekan normal dan status true
  if ($status = true) {
    $insert = SqlQuInsert("wavenet.tb_product", $_POST);
    echo json_encode($insert);
    exit;
  }
}
?>
