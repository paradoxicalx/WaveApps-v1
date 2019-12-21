<?php
if (isset($_GET['n']) && $_GET['n'] === "router") {
  $status = true;
  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["nasname", "shortname", "type", "secret"];
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
  SqlQuDuplicate("radius.nas",
                [
                  "shortname" => $_POST['shortname'],
                  "nasname" => $_POST['nasname']
                ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  if ($status = true) {
    exec("sudo /var/www/apps.wavenet.id/include/reboot-radius.sh");
    $insert = SqlQuInsert("radius.nas", $_POST);
    InputLog($_SESSION['name'],"radius","New router added [".$_POST['shortname']."]");
    echo json_encode($insert);
    exit;
  }

}
?>
