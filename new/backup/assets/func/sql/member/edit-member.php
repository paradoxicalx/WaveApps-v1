<?php
// Edit multiple/single member.
if (isset($_GET['e'])) {
  $report_array = [];
  $status = true;

  // cek apakah input diijikan dalam mode multiple edit.
  if (count($_POST['id']) > 1) {
    $cek_mustempty = ["name", "username", "email", "phone"];
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
  $cek_empty_field = ["name", "username", "password", "email", "phone", "lat", "long", "address", "group"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data) or $data = "") {
      unset($_POST [ $cek_empty_field[$i] ]);
    }
  }
  // Validasi format data yang di masukan
  // Email
  if (isset($_POST['email']) && !empty($_POST['email'])) {
    if ($validemail = notValidEmail("email", $_POST['email'])) {
      echo json_encode($validemail);
      $status = false;
      exit;
    }
  }
  // Password, akan di hash.
  if (isset($_POST['password']) && !empty($_POST['password'])) {
    $validpass = ValidPassword("password", $_POST['password']);
    if ($validpass['status'] ===  "valid") {
      $_POST['password'] = $validpass[0]['data'];
    } else {
      echo json_encode($validpass);
      $status = false;
      exit;
    }
  }
  // Cek apakah ada data yang sama dengan yang telah ada dalam table sql
  // Gunakan fungsi SqlQuDuplicate
  $cek_duplicate_data =
  SqlQuDuplicate("wavenet.tb_user",
  [
    "username" => $_POST['username']
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
      $update = SqlQuUpdate("wavenet.tb_user", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
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
