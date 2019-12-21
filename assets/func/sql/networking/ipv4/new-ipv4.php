<?php
if (isset($_GET['n']) && $_GET['n'] === "ipv4") {
  $status = true;
  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["identity", "ipaddress", "netmask", "usage"];
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
  SqlQuDuplicate("wavenet.tb_ipmaster",
  [
    "identity" => $_POST['identity']
  ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }

  // Kirim data untuk di cek.
  $wcmask = long2ip( ~ip2long($_POST['netmask']));
  $subnet = long2ip( ip2long($_POST['ipaddress']) & ip2long($_POST['netmask']) );
  $bcast = long2ip( ip2long($_POST['ipaddress']) | ip2long($wcmask) );
  $start = ip2long($subnet);
  $end = ip2long($bcast);
  $count = $end-$start;
  $ipexist = sqlQu("SELECT ipaddress FROM wavenet.tb_iplist");
  $report = ["status" => "success"];
  $report[] = [
    "subnet" => $start,
    "bcast" => $end,
    "count" => $count,
    "ipexist" => $ipexist
  ];
  echo json_encode($report);
}

if (isset($_GET['n']) && $_GET['n'] === "ipv4-add") {
  $wcmask = long2ip( ~ip2long($_POST['netmask']));
  $subnet = long2ip( ip2long($_POST['ipaddress']) & ip2long($_POST['netmask']) );
  $bcast = long2ip( ip2long($_POST['ipaddress']) | ip2long($wcmask) );
  $start = ip2long($subnet);
  $end = ip2long($bcast);

  $addmaster = SqlQuInsert("wavenet.tb_ipmaster",
  [
    "identity" => $_POST['identity'],
    "usage" => $_POST['usage'],
    "notes" => $_POST['notes'],
    "usage" => $_POST['usage'],
    "subnet" => ip2long($subnet),
    "broadcast" => ip2long($bcast),
    "netmask" => ip2long($_POST['netmask'])
  ]);
  if ($addmaster['status'] === "success") {
    $masterid = $addmaster[0]['data'][0]['id'];
    $iplist = array_map('long2ip', range($start, $end) );
    foreach ($iplist as $value) {
      $iplong = ip2long($value);
      if ($iplong === ip2long($subnet)) {
        $insertip = SqlQuInsert("wavenet.tb_iplist",
        [
          "ipaddress" => $iplong,
          "master" => $masterid,
          "type" => "Subnet",
          "used" => "1",
          "useby" => "Subnet"
        ]);
      }
      elseif ($iplong === ip2long($bcast)) {
        $insertip = SqlQuInsert("wavenet.tb_iplist",
        [
          "ipaddress" => $iplong,
          "master" => $masterid,
          "type" => "Broadcast",
          "used" => "1",
          "useby" => "Broadcast"
        ]);
      }
      else {
        $insertip = SqlQuInsert("wavenet.tb_iplist",
        [
          "ipaddress" => $iplong,
          "master" => $masterid,
          "type" => "Host"
        ]);
      }
    }
    InputLog($_SESSION['name'],"networking","New IPv4 list added [".$_POST['identity']."]");
    echo json_encode($insertip);
    exit;
  }

}
?>
