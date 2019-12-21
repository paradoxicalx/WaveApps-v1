<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['q'])) {
  if ($_GET['q'] === "all") {
    $query = "SELECT `image`,`id`,`name`,`status`,`group`,`phone`,`date`,`username`,`email`,`long`,`lat`,`address`, `notes`
    FROM wavenet.tb_user WHERE deleted = '0'";
  } else {
    $group = $_GET['q'];
    $query = "SELECT `image`,`id`,`name`,`status`,`group`,`phone`,`date`,`username`,`email`,`long`,`lat`,`address`, `notes`
    FROM wavenet.tb_user WHERE `group` = '$group' AND deleted = '0'";
  }
  $data = sqlQu($query);
  $out = json_encode(["data" => $data ]);
  echo $out;
  exit;
}

if (isset($_GET['log'])) {
  $id = $_GET['id'];
  $member = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = '$id'");
  $username = $member[0]['username'];
  $name = $member[0]['name'];
  $logs = sqlQuAssoc("SELECT * FROM wavenet.tb_log WHERE `type` != 'device' AND `type` != 'networking' AND `type` != 'billing-sales' AND
    (`message` LIKE '%$id%' OR `message` LIKE '%$username%' OR `message` LIKE '%$name%')");
  foreach ($logs as $key) {
    $result[] = [
      $key['time'],
      $key['type'],
      $key['message'],
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['log-device'])) {
  $id = $_POST['id'];
  $name = "";
  $devicename = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `member` = '$id'");
  foreach ($devicename as $key) {
    $name .= " OR `message` LIKE '%".$key['name']."%'";
  }
  $logs = sqlQuAssoc("SELECT * FROM wavenet.tb_log WHERE (`type` = 'device' OR `type` = 'networking') AND (`message` LIKE '%$id%' $name)");
  foreach ($logs as $key) {
    $result[] = [
      $key['time'],
      $key['type'],
      $key['message'],
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
