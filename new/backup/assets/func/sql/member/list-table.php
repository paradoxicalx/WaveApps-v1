<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['q'])) {
  if ($_GET['q'] === "all") {
    $query = "SELECT `image`,`id`,`name`,`phone`,`date`,`status`,`username`,`email`,`long`,`lat`,`address`, `notes`
    FROM wavenet.tb_user WHERE deleted = '0'";
  } else {
    $group = $_GET['q'];
    $query = "SELECT `image`,`id`,`name`,`phone`,`date`,`status`,`username`,`email`,`long`,`lat`,`address`, `notes`
    FROM wavenet.tb_user WHERE `group` = '$group' AND deleted = '0'";
  }
  $data = sqlQu($query);
  $out = json_encode(["data" => $data ]);
  echo $out;
  exit;
}
?>
