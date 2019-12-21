<?php
// Buat member baru dengan melalui pengecekan
// Identifikasi dengan $_GET['n']
if (isset($_GET['n'])) {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "username", "password", "status", "group"];
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
  // Validasi format data yang di masukan
  // Email
  if (!empty($_POST['email'])) {
    if ($validemail = notValidEmail("email", $_POST['email'])) {
      echo json_encode($validemail);
      exit;
    }
  }
  // Password, akan di hash.
  $validpass = ValidPassword("password", $_POST['password']);
  if ($validpass['status'] ===  "valid") {
    $_POST['password'] = $validpass[0]['data'];
  } else {
    echo json_encode($validpass);
    exit;
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
    exit;
  }

  // Buat/masukan data member baru dengan fungsi SqlQuInsert
  if ($status = true) {
    $insert = SqlQuInsert("wavenet.tb_user", $_POST);
    echo json_encode($insert);
    exit;
  }
}
?>
