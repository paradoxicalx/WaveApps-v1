<?php
// Daftarkan / Masukan data baru ke database
// Identifikasi dengan $_GET['n']
if (isset($_GET['n']) && $_GET['n'] === "user") {
  $status = true;
  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["member", "username", "password", "group"];
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
  SqlQuDuplicate("radius.radcheck",
                [
                  "username" => $_POST['username']
                ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  // Cek statik ip digunakan atau tidak
  if (!empty($_POST['ipv4'])) {
    $ipstat = SqlQuAssoc("SELECT * FROM wavenet.tb_iplist WHERE `ipaddress` = ".ip2long($_POST['ipv4']));
    $ipstat = $ipstat[0]['used'];
    if ($ipstat > 0) {
      $status = false;
      $report = ["status" => "false"];
      $report[] = ["error" => "IPv4 is already used"];
      echo json_encode($report);
      exit;
    }
  }
  // Jika Semua pengecekan normal dan status true
  if ($status = true) {
    // Buat/masukan data member baru dengan fungsi SqlQuInsert
    $insert = SqlQuInsert("radius.radcheck",
              [
                "userid" => $_POST['member'],
                "username" => $_POST['username'],
                "attribute" => "Cleartext-Password",
                "op" => ":=",
                "value" => $_POST['password']
              ]);
    $insert_id = $insert[0]['id'];
    $insert[] = SqlQuInsert("radius.radusergroup",
              [
                "guid" => $_POST['member'],
                "username" => $_POST['username'],
                "groupname" => $_POST['group']
              ]);
    // Jika kolom ipv4 tidak kosong masukan data ke table rareply dan update data iplist
    if (!empty($_POST['ipv4'])) {
      $addradreply = SqlQuInsert("radius.radreply",
                    [
                      "username" => $_POST['username'],
                      "attribute" => "Framed-IP-Address",
                      "op" => "=",
                      "value" => $_POST['ipv4']
                    ]);
      if ($addradreply['status'] == "success") {
        $insert[] = SqlQuUpdate("wavenet.tb_iplist",
                    [
                      "useby" => "radius",
                      "used" => 1,
                      "infid" => $insert_id
                    ],
                    "ipaddress", ip2long($_POST['ipv4']));
      }
    }
    // Jika Dl-Limit tidak kosong masukan data ke table radreply.
    if (!empty($_POST['dllimit'])) {
      $insert[] = SqlQuInsert("radius.radreply",
                [
                  "username" => $_POST['username'],
                  "attribute" => "Mikrotik-Recv-Limit",
                  "op" => "=",
                  "value" => $_POST['dllimit']
                ]);
    }
    // Jika Up-Limit tidak kosong masukan data ke table radreply.
    if (!empty($_POST['uplimit'])) {
      $insert[] = SqlQuInsert("radius.radreply",
                [
                  "username" => $_POST['username'],
                  "attribute" => "Mikrotik-Xmit-Limit",
                  "op" => "=",
                  "value" => $_POST['uplimit']
                ]);
    }
    // Jika tx/rx rate tidak kosong masukan data ke table radreply.
    if (!empty($_POST['rate'])) {
      if (empty($_POST['burst'])) {
        $_POST['burst'] = "0/0";
      }
      if (empty($_POST['threshold'])) {
        $_POST['threshold'] = "0/0";
      }
      if (empty($_POST['bursttime'])) {
        $_POST['bursttime'] = "0/0";
      }
      $txrx = $_POST['rate']." ".$_POST['burst']." ".$_POST['threshold']." ".$_POST['bursttime'];
      $insert[] = SqlQuInsert("radius.radreply",
                [
                  "username" => $_POST['username'],
                  "attribute" => "Mikrotik-Rate-Limit",
                  "op" => "=",
                  "value" => $txrx
                ]);
    }
    InputLog($_SESSION['name'],"radius","New user added [".$_POST['username']."]");
    echo json_encode($insert);
    exit;
  }
}
?>
