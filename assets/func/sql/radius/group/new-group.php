<?php
if (isset($_GET['n']) && $_GET['n'] === "group") {
  $status = true;
  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["groupname"];
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
  SqlQuDuplicate("radius.radusergroup",
                [
                  "groupname" => $_POST['groupname']
                ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  // Jika Semua pengecekan normal dan status true
  if ($status = true) {
    $insert = [];
    $insert = SqlQuInsert("radius.radusergroup",
              [
                "username" => "0",
                "priority" => "0",
                "guid" => "0",
                "groupname" => $_POST['groupname']
              ]);
    // Jika Profile tidak kosong masukan data ke table radgroupreply.
    if (!empty($_POST['profile'])) {
      $insert[] = SqlQuInsert("radius.radgroupreply",
                [
                  "groupname" => $_POST['groupname'],
                  "attribute" => "Mikrotik-Group",
                  "op" => "=",
                  "value" => $_POST['profile']
                ]);
    }
    // Jika Dl-Limit tidak kosong masukan data ke table radgroupreply.
    if (!empty($_POST['dllimit'])) {
      $insert[] = SqlQuInsert("radius.radgroupreply",
                [
                  "groupname" => $_POST['groupname'],
                  "attribute" => "Mikrotik-Recv-Limit",
                  "op" => "=",
                  "value" => $_POST['dllimit']
                ]);
    }
    // Jika Up-Limit tidak kosong masukan data ke table radgroupreply.
    if (!empty($_POST['uplimit'])) {
      $insert[] = SqlQuInsert("radius.radgroupreply",
                [
                  "groupname" => $_POST['groupname'],
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
      $insert[] = SqlQuInsert("radius.radgroupreply",
                [
                  "groupname" => $_POST['groupname'],
                  "attribute" => "Mikrotik-Rate-Limit",
                  "op" => "=",
                  "value" => $txrx
                ]);
    }
    // Jika max session tidak kosong
    if (!empty($_POST['maxsess'])) {
      $insert[] = SqlQuInsert("radius.radgroupcheck",
                [
                  "groupname" => $_POST['groupname'],
                  "attribute" => "Simultaneous-Use",
                  "op" => ":=",
                  "value" => $_POST['maxsess']
                ]);
    }
    InputLog($_SESSION['name'],"radius","New group created [".$_POST['groupname']."]");
    echo json_encode($insert);
    exit;
  }

}
?>
