<?php
if (isset($_GET['reply-edit'])) {
  session_start();
  $replydata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket_reply WHERE `id` = ".$_POST['id']);
  $ticketdata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = ".$replydata[0]['ticketid']);
  // Cek pemilik pesan.
  // jika pesan sudah dihapus sebelumnya.
  if ($replydata[0]['answerer'] != $_SESSION['id'] or $replydata[0]['deleted'] === "1") {
    $report = ["status" => "not-allowed"];
    $report[] = ["error" => "Not Allowed"];
    echo json_encode($report);
    exit;
  }
  // Jika hapus pesan.
  if (isset($_POST['remove-reply'])) {
    $remove = SqlQuUpdate("wavenet.tb_ticket_reply", [
      "content" => addslashes("<span class='reply-removed'><i class='fas fa-info-circle'></i> Message Deleted</span>"),
      "file" => "",
      "ro_capture" => "",
      "deleted" => "1"
    ], "id", $_POST['id']);
    if ($remove['status'] === "success") {
      $report = ["status" => "success"];
      $report[] = ["error" => "Message Deleted"];
      echo json_encode($report);
      exit;
    }
  }
  // Jika hapus Attachment
  if (isset($_POST['rem-attach'])) {
    $remove = SqlQuUpdate("wavenet.tb_ticket_reply", [
      "file" => "",
      "edited" => "1"
    ], "id", $_POST['id']);
    if ($remove['status'] != "success") {
      $report = ["status" => "failed"];
      $report[] = ["error" => "Failed to remove Attachment"];
      echo json_encode($report);
      exit;
    }
  }
  // Jika hapus rekaman perangkat.
  if (isset($_POST['rem-device'])) {
    $device_file_name = "";
    $remove = SqlQuUpdate("wavenet.tb_ticket_reply", [
      "ro_capture" => "",
      "edited" => "1"
    ], "id", $_POST['id']);
    if ($remove['status'] != "success") {
      $report = ["status" => "failed"];
      $report[] = ["error" => "Failed to remove Captured Devices"];
      echo json_encode($report);
      exit;
    }
  } else {
    $device_file_name = $replydata[0]['ro_capture'];
  }
  // Cek device info.
  $error_device = ["status" => "err_device"];
  if ($_POST['e-att-device'] > 0) {
    $url = 'http://127.0.0.1/admin/networking/sql-proc.php?i=info&limitlog=50&id='.$_POST['e-att-device'];
    $device_json = file_get_contents($url);
    $decojson = json_decode($device_json, true);
    if ($decojson['status'] === "failed") {
      $error_device[] = ["error" => "Can't load devices information", "col" => "err_device"];
      $status = false;
      echo json_encode($error_device);
      exit;
    } else {
      $now = time();
      $device_file_name = urlencode($now."_".$ticketdata[0]['id']."_".$decojson[0]['routeros']['identity'].".cdi");
      $device_file = fopen("file/$device_file_name", "w");
      fwrite($device_file, $device_json);
    }
  }
  // Simpan pesan.
  if (isset($_POST['set-hide'])) {
    $hide = "1";
  } else {
    $hide = "0";
  }
  if ($_FILES['file-new']['name']) {
    $havefile = true;
    $error_file = ["status" => "err_file"];
    if ($_FILES['file-new']["size"] > 10000000) {
      $error_file[] = ["error" => "File to large", "col" => "file"];
      $status = false;
      echo json_encode($error_file);
      exit;
    }
  } else {
    $havefile = false;
  }
  $save = SqlQuUpdate("wavenet.tb_ticket_reply", [
    "content" => $_POST['reply-edit'],
    "hide" => $hide,
    "ro_capture" => $device_file_name,
    "edited" => "1"
  ], "id", $_POST['id']);
  if ($save['status'] === "success") {
    if ($havefile === true) {
      $filename = $ticketdata[0]['ticket_id']."_".$_POST['id']."_".urlencode($_FILES['file-new']['name']);
      $dirUpload = "file/";
      $upload = move_uploaded_file($_FILES['file-new']["tmp_name"], $dirUpload.$filename);
      if ($upload) {
        SqlQuUpdate("wavenet.tb_ticket_reply",["file" => $filename],"id", $_POST['id']);
        $save[] = ["filename" => $filename];
      }
    }
    echo json_encode($save);
    exit;
  }

  $report = ["status" => "failed"];
  $report[] = ["error" => "Failed to change message!"];
  echo json_encode($report);
  exit;
}
?>
