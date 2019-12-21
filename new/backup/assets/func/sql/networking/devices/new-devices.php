<?php
if (isset($_GET['n']) && $_GET['n'] === "devices") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "catagory", "ip"];
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
  SqlQuDuplicate("wavenet.tb_devices",
  [
    "name" => $_POST['name'],
    "ip" => $_POST['ip']
  ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  // Buat/masukan data devices baru dengan fungsi SqlQuInsert
  if ($status = true) {
    $insert = SqlQuInsert("wavenet.tb_devices",
      [
        "name" => $_POST['name'],
        "type" => $_POST['type'],
        "model" => $_POST['model'],
        "catagory" => $_POST['catagory'],
        "ip" => ip2long($_POST['ip']),
        "member" => $_POST['member'],
        "useapi" => $_POST['useapi'],
        "apiname" => $_POST['apiname'],
        "apipass" => $_POST['apipass'],
        "autocheck" => $_POST['autocheck']
      ]);
    if ($insert['status'] == "success") {
      $insert_id = $insert[0]['id'];
      SqlQuUpdate("wavenet.tb_iplist",
        [
          "useby" => "devices",
          "used" => 1,
          "infid" => $insert_id
        ],
        "ipaddress", ip2long($_POST['ip']));
    }
    echo json_encode($insert);
    exit;
  }
}
?>
