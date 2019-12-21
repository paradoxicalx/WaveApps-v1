<?php
if (isset($_GET['e']) && $_GET['e'] == "account") {
  $report_array = [];
  $status = true;
  // cek apakah input diijikan dalam mode multiple edit.
  if (count($_POST['id']) > 1) {
    $cek_mustempty = ["name"];
    for ($i = 0; $i < count($cek_mustempty); $i++) {
      $data = $_POST[$cek_mustempty[$i]];
      if (!empty($data)) {
        $report = ["status" => "error"];
        $report[] = ["error" => true];
        echo json_encode($report);
        $status = false;
        exit;
      }
    }
  }
  // Cek apakah ada input kosong, jika iya hapus dari array $_POST
  // Sehingga didapatkan nya data yang akan di edit.
  $cek_empty_field = ["name", "number", "phone", "bankurl", "description"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data) or $data = "") {
      unset($_POST [ $cek_empty_field[$i] ]);
    }
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
    $status = false;
    exit;
  }
  // Update data baru dengan fungsi SqlQuUpdate
  if ($status = true) {
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $update = SqlQuUpdate("wavenet.tb_account", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
        InputLog($_SESSION['name'],"billing-account","Account number #$id[$i] changed");
        $count++;
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
