<?php
// Edit data di dalam database
// Identifikasi dengan $_GET['e']
if (isset($_GET['e']) && $_GET['e'] === "user") {
  $report_array = [];
  $status = true;
  // cek apakah input diijikan dalam mode multiple edit.
  if (count($_POST['id']) > 1) {
    $cek_mustempty = ["ipv4"];
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
  $cek_empty_field = ["password", "group", "ipv4", "dllimit", "uplimit", "rate", "burst", "threshold", "bursttime"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data) or $data = "") {
      unset($_POST [ $cek_empty_field[$i] ]);
    }
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
  // Update / Tambahkan data baru
  if ($status = true) {
    $log = [];
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $error = false;

      $oldname = SqlQuAssoc("SELECT username FROM radius.radcheck WHERE `id` = $id[$i]");
      $oldname = $oldname[0]['username'];
      // Update Password
      if (isset($_POST['password'])) {
        $update = SqlQuUpdate("radius.radcheck", [ "value" => $_POST['password']], "id", "$id[$i]");
        if ($update['status'] !== "success") { $error = true; $log[] = "change password failed";}
      }
      // Update Group
      if (isset($_POST['group'])) {
        $update = SqlQuUpdate("radius.radusergroup", [ "groupname" => $_POST['group']], "username", $oldname);
        if ($update['status'] !== "success") { $error = true; $log[] = "change group failed";}
      }
      // Update / Tambah IPv4
      if (isset($_POST['ipv4'])) {
        $oldip = SqlQuAssoc("SELECT ipaddress FROM wavenet.tb_iplist WHERE `infid` = $id[$i]");
        $oldip = $oldip[0]['ipaddress'];

        if (empty($oldip) or $oldip == "") {
          $insert = SqlQuInsert("radius.radreply",
                    [
                      "username" => $oldname,
                      "attribute" => "Framed-IP-Address",
                      "op" => "=",
                      "value" => $_POST['ipv4']
                    ]);
          if ($insert['status'] === "success") {
            $update = SqlQuUpdate("wavenet.tb_iplist",
            [
              "useby" => "radius",
              "used" => 1,
              "infid" => "$id[$i]"
            ],
            "ipaddress", ip2long($_POST['ipv4']));
          }
          if ($update['status'] !== "success") { $error = true; $log[] = "add new ip failed";}
        } else {
          $update = SqlQuUpdate("radius.radreply",
                   [
                     "value" => $_POST['ipv4']
                   ],
                   "value", long2ip($oldip));
          if ($update['status'] !== "success") { $error = true; $log[] = "update ip radreply failed";}
          if ($update['status'] == "success") {
            $update = SqlQuUpdate("wavenet.tb_iplist",
                     [
                       "used" => "0",
                       "useby" => "",
                       "infid" => ""
                     ],
                     "infid", "$id[$i]");
            if ($update['status'] !== "success") { $error = true; $log[] = "remove iplist failed";}
            $update = SqlQuUpdate("wavenet.tb_iplist",
                     [
                       "useby" => "radius",
                       "used" => 1,
                       "infid" => "$id[$i]"
                     ],
                     "ipaddress", ip2long($_POST['ipv4']));
            if ($update['status'] !== "success") { $error = true; $log[] = "update iplist failed";}
          }
        }
      }
      // Update / Tambah Download Limit
      if (isset($_POST['dllimit'])) {
        $cekready = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$oldname' AND `attribute` = 'Mikrotik-Recv-Limit'");
        $ready = $cekready[0]['id'];
        if (empty($ready) or $ready == "") {
          SqlQuInsert("radius.radreply",
                    [
                      "username" => $oldname,
                      "attribute" => "Mikrotik-Recv-Limit",
                      "op" => "=",
                      "value" => $_POST['dllimit']
                    ]);
        } else {
          $update = SqlQuUpdate("radius.radreply",
                   [
                     "value" => $_POST['dllimit']
                   ],
                   "id", $ready);
          if ($update['status'] !== "success") { $error = true; $log[] = "change dl-limit failed";}
        }
      }
      // Update / Tambah Upload Limit
      if (isset($_POST['uplimit'])) {
        $cekready = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$oldname' AND `attribute` = 'Mikrotik-Xmit-Limit'");
        $ready = $cekready[0]['id'];
        if (empty($ready) or $ready == "") {
          SqlQuInsert("radius.radreply",
                    [
                      "username" => $oldname,
                      "attribute" => "Mikrotik-Xmit-Limit",
                      "op" => "=",
                      "value" => $_POST['uplimit']
                    ]);
        } else {
          $update = SqlQuUpdate("radius.radreply",
                   [
                     "value" => $_POST['uplimit']
                   ],
                   "id", $ready);
          if ($update['status'] !== "success") { $error = true; $log[] = "change up-limit failed";}
        }
      }
      // Update / Tambah Tx Rx Rate
      if (isset($_POST['rate'])) {
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
        $cekready = SqlQuAssoc("SELECT id FROM radius.radreply WHERE `username` = '$oldname' AND `attribute` = 'Mikrotik-Rate-Limit'");
        $ready = $cekready[0]['id'];
        if (empty($ready) or $ready == "") {
          SqlQuInsert("radius.radreply",
                    [
                      "username" => $oldname,
                      "attribute" => "Mikrotik-Rate-Limit",
                      "op" => "=",
                      "value" => $txrx
                    ]);
        } else {
          $update = SqlQuUpdate("radius.radreply",
                   [
                     "value" => $txrx
                   ],
                   "id", $ready);
          if ($update['status'] !== "success") { $error = true; $log[] = "update rate limit failed";}
        }
      }
      // Cek apakah ada error atau tidak. Jika tidak tambah count
      if ($error === false) {
        $count++;
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count, $log ];
      echo json_encode($report);
    } elseif ($count > 0 && $count < count($id)) {
      $report = ["status" => "warning"];
      $report[] = ["error" => true, "count" => $count, $log ];
      echo json_encode($report);
    } elseif ($count === 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => true, "count" => $count, $log ];
      echo json_encode($report);
    }
  }
}
?>
