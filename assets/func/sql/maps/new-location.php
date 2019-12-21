<?php
  if (isset($_GET['n'])) {
    $status = true;
    // Cek apakah ada input kosong untuk kolom yang dibutuhkan
    $this_empty = ["status" => "empty"];
    $cek_empty_field = ["name", "long", "lat", "type"];
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
    SqlQuDuplicate("wavenet.tb_maps",
    [
      "name" => $_POST['name']
    ]);
    if ($cek_duplicate_data['status'] === "duplicate") {
      $status = false;
      echo json_encode($cek_duplicate_data);
      exit;
    }
    // Buat/masukan data member baru dengan fungsi SqlQuInsert
    if ($status = true) {
      $insert = SqlQuInsert("wavenet.tb_maps", $_POST);
      InputLog($_SESSION['name'],"maps","New location created [".$_POST['name']."]");
      echo json_encode($insert);
      exit;
    }
  }
?>
