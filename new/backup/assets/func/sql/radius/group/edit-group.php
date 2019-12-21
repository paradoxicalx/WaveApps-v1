<?php
// Edit data di dalam database
// Identifikasi dengan $_GET['e']
if (isset($_GET['e']) && $_GET['e'] === "group") {
  $report_array = [];
  $status = true;
  // cek apakah input diijikan dalam mode multiple edit.
  if (count($_POST['id']) > 1) {
    $cek_mustempty = ["groupname"];
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
  $cek_empty_field = ["groupname", "profile", "dllimit", "uplimit", "rate", "burst", "threshold", "bursttime"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data) or $data = "") {
      unset($_POST [ $cek_empty_field[$i] ]);
    }
  }
  // Cek apakah ada data yang sama dengan yang telah ada dalam table sql
  // Gunakan fungsi SqlQuDuplicate
  $cek_duplicate_data =
  SqlQuDuplicate("radius.radusergroup",
                [
                  "groupname" => $_POST['groupname']
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
      $oldname = SqlQuAssoc("SELECT groupname FROM radius.radusergroup WHERE `id` = $id[$i]");
      $oldname = $oldname[0]['groupname'];

      // Update Profile
      if (isset($_POST['profile'])) {
        $changeid = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$oldname' AND `attribute` = 'Mikrotik-Group'");
        $changeid = $changeid[0]['id'];
        if (empty($changeid) or $changeid == "") {
          $update = SqlQuInsert("radius.radgroupreply",
                    [
                      "groupname" => $oldname,
                      "attribute" => "Mikrotik-Group",
                      "op" => "=",
                      "value" => $_POST['profile']
                    ]);
          if ($update['status'] !== "success") { $error = true; $log[] = "change profile failed";}
        } else {
          $update = SqlQuUpdate("radius.radgroupreply",
                   [
                     "value" => $_POST['profile']
                   ],
                   "id", $changeid);
          if ($update['status'] !== "success") { $error = true; $log[] = "change profile failed";}
        }
      }
      // Update Dl-Limit
      if (isset($_POST['dllimit'])) {
        $changeid = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$oldname' AND `attribute` = 'Mikrotik-Recv-Limit'");
        $changeid = $changeid[0]['id'];
        if (empty($changeid) or $changeid == "") {
          $update = SqlQuInsert("radius.radgroupreply",
                    [
                      "groupname" => $oldname,
                      "attribute" => "Mikrotik-Recv-Limit",
                      "op" => "=",
                      "value" => $_POST['dllimit']
                    ]);
          if ($update['status'] !== "success") { $error = true; $log[] = "change dllimit failed";}
        } else {
          $update = SqlQuUpdate("radius.radgroupreply",
                   [
                     "value" => $_POST['dllimit']
                   ],
                   "id", $changeid);
          if ($update['status'] !== "success") { $error = true; $log[] = "change dllimit failed";}
        }
      }
      // Update Up-Limit
      if (isset($_POST['uplimit'])) {
        $changeid = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$oldname' AND `attribute` = 'Mikrotik-Xmit-Limit'");
        $changeid = $changeid[0]['id'];
        if (empty($changeid) or $changeid == "") {
          $update = SqlQuInsert("radius.radgroupreply",
                    [
                      "groupname" => $oldname,
                      "attribute" => "Mikrotik-Xmit-Limit",
                      "op" => "=",
                      "value" => $_POST['uplimit']
                    ]);
          if ($update['status'] !== "success") { $error = true; $log[] = "change uplimit failed";}
        } else {
          $update = SqlQuUpdate("radius.radgroupreply",
                   [
                     "value" => $_POST['uplimit']
                   ],
                   "id", $changeid);
          if ($update['status'] !== "success") { $error = true; $log[] = "change uplimit failed";}
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
        $changeid = SqlQuAssoc("SELECT id FROM radius.radgroupreply WHERE `groupname` = '$oldname' AND `attribute` = 'Mikrotik-Rate-Limit'");
        $changeid = $changeid[0]['id'];
        if (empty($changeid) or $changeid == "") {
          $update = SqlQuInsert("radius.radgroupreply",
                    [
                      "groupname" => $oldname,
                      "attribute" => "Mikrotik-Rate-Limit",
                      "op" => "=",
                      "value" => $txrx
                    ]);
          if ($update['status'] !== "success") { $error = true; $log[] = "change rate limit failed";}
        } else {
          $update = SqlQuUpdate("radius.radgroupreply",
                   [
                     "value" => $txrx
                   ],
                   "id", $changeid);
          if ($update['status'] !== "success") { $error = true; $log[] = "change rate limit failed";}
        }
      }
      // Update Name
      if (isset($_POST['groupname'])) {
        $update = SqlQuUpdate("radius.radusergroup", [ "groupname" => $_POST['groupname']], "groupname", $oldname);
        if ($update['status'] == "success") {
           SqlQuUpdate("radius.radgroupreply", [ "groupname" => $_POST['groupname']], "groupname", $oldname);
         } else {
           $error = true; $log[] = "change groupname failed";
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
