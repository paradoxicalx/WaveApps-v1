<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['qd']
if (isset($_GET['qd'])) {
  $result = [];
  if ($_GET['qd'] === "all") {
    $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE deleted = '0'");
  } else {
    $cata = $_GET['qd'];
    $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE deleted = '0' AND catagory = '$cata'");
  }

  foreach ($query as $key) {
    $lastup = date_format(date_create($key['lastup']),'d/m/y, H:i:s');
    $lastdown = date_format(date_create($key['lastdown']),'d/m/y, H:i:s');
    $dateadd = date_format(date_create($key['dateadd']),'d/m/y, H:i:s');
    if ($key['status'] === "up") {
      $uptime = convert_seconds(strtotime('now')-strtotime($key['lastup']));
    } else {
      $uptime = "-";
    }
    $result[] = [
      $key['id'],
      $key['name'],
      ucfirst($key['type']),
      ucfirst($key['model']),
      long2ip($key['ip']),
      ucfirst($key['status']),
      $uptime,
      ucfirst($key['useapi']),
      $key['apiname'],
      $key['apipass'],
      $lastup,
      $lastdown,
      $dateadd
    ];
  }

  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
