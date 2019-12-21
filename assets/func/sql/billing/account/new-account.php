<?php
  if (isset($_GET['n']) &&  $_GET['n'] == "account") {
    $status = true;
    // Cek apakah ada input kosong untuk kolom yang dibutuhkan
    $this_empty = ["status" => "empty"];
    $cek_empty_field = ["name"];
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
    SqlQuDuplicate("wavenet.tb_account",
    [
      "name" => $_POST['name'],
      "memberlink" => $_POST['memberlink']
    ]);
    if ($cek_duplicate_data['status'] === "duplicate") {
      $status = false;
      echo json_encode($cek_duplicate_data);
      exit;
    }
    // Buat/masukan data member baru dengan fungsi SqlQuInsert
    if ($status = true) {
      $insert = SqlQuInsert("wavenet.tb_account", $_POST);
      if ($insert['status'] === "success") {
        InputTransLog($insert[0]['id'], "0", "0", "newacc", "0", $_POST['balance']);
        InputLog($_SESSION['name'],"billing-account","New account created");
      }
      echo json_encode($insert);
      exit;
    }


  }
?>
