<?php
if (isset($_GET['select-invoice'])) {
  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `member` = ".$_POST['id']);
  echo json_encode($query);
  exit;
}

if (isset($_GET['n']) && $_GET['n'] == "new-ticket") {
  session_start();
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["topic", "title", "priority", "content"];
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
  // Cek file/Attachment.
  if ($_FILES['file']['name']) {
    $havefile = true;
    $error_file = ["status" => "err_file"];
    if ($_FILES["file"]["size"] > 10000000) {
      $error_file[] = ["error" => "File to large", "col" => "file"];
      $status = false;
      echo json_encode($error_file);
      exit;
    }
  } else {
    $havefile = false;
  }

  if ($status = true) {
    $insert = SqlQuInsert("wavenet.tb_ticket",
      [
        "creator" => $_SESSION['id'],
        "topic" => $_POST['topic'],
        "device" => $_POST['device'],
        "invoice" => $_POST['invoice'],
        "assign" => $_POST['assign'],
        "member" => $_POST['member'],
        "title" => $_POST['title'],
        "content" => $_POST['content'],
        "priority" => $_POST['priority'],
      ]);
    if ($insert['status'] == "success") {
      $ticketid = "T".date("Ym").$insert[0]['id'];
      SqlQuUpdate("wavenet.tb_ticket",["ticket_id" => $ticketid],"id", $insert[0]['id']);
      InputLog($_SESSION['name'],"ticket","New ticket created [Ticket Number : $ticketid]");

      if ($havefile = true) {
        $filename = $ticketid."_".urlencode($_FILES['file']['name']);
        $dirUpload = "file/";
        $upload = move_uploaded_file($_FILES["file"]["tmp_name"], $dirUpload.$filename);
        if ($upload) {
          SqlQuUpdate("wavenet.tb_ticket",["file" => $filename],"id", $insert[0]['id']);
        }
      }

      echo json_encode($insert);
      exit;
    }
  }


}
?>
