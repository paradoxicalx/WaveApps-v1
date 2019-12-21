<?php
if (isset($_GET['r'])) {
  session_start();
  if ($_GET['r'] === "change-assign") {
    $changeassign = SqlQuUpdate("wavenet.tb_ticket", ["assign" => $_POST['assign']], "id", $_POST['id']);
    $ticketdata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = ".$_POST['id']);
    $assigndata = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$ticketdata[0]['assign']);
    $assigndata2 = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$_POST['assign']);
    $ticketid = $ticketdata[0]['ticket_id'];
    $ticketass = $assigndata[0]['name'];
    $newassgn = $assigndata2[0]['name'];
    if ($changeassign['status'] == "success") {
      InputLog($_SESSION['name'],"ticket","Ticket [$ticketid] assignment to $ticketass");
      SqlQuInsert("wavenet.tb_ticket_reply", [
        "ticketid" => $_POST['id'],
        "answerer" => $_SESSION['id'],
        "content" => "Ticket assigned to <b>$ticketass</b>"
      ]);
      $report = ["status" => "success"];
      $report[] = ["assgname" => $newassgn];
      echo json_encode($report);
    }
  }

  if ($_GET['r'] === "change-status") {
    $changestatus = SqlQuUpdate("wavenet.tb_ticket", ["status" => $_POST['status']], "id", $_POST['id']);
    $ticketdata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = ".$_POST['id']);
    $ticketid = $ticketdata[0]['ticket_id'];
    $status = $_POST['status'];
    if ($_POST['status'] === "open") {$label = "label-info";}
    if ($_POST['status'] === "pending") {$label = "label-warning";}
    if ($_POST['status'] === "closed") {$label = "label-success";}
    if ($_POST['status'] === "trash") {$label = "label-default";}
    if ($changestatus['status'] == "success") {
      InputLog($_SESSION['name'],"ticket","Ticket [$ticketid] move to folder ".$_POST['status']);
      SqlQuInsert("wavenet.tb_ticket_reply", [
        "ticketid" => $_POST['id'],
        "answerer" => $_SESSION['id'],
        "content" => addslashes("Ticket move to folder <span class='label $label'>$status</span>")
      ]);
      $report = ["status" => "success"];
      $report[] = ["status" => $_POST['status']];
      echo json_encode($report);
    }
  }

  if ($_GET['r'] === "change-priority") {
    $changepriority = SqlQuUpdate("wavenet.tb_ticket", ["priority" => $_POST['priority']], "id", $_POST['id']);
    $ticketdata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = ".$_POST['id']);
    $ticketid = $ticketdata[0]['ticket_id'];
    if ($_POST['priority'] === "critical") {$label = "label-danger";}
    if ($_POST['priority'] === "major") {$label = "label-warning";}
    if ($_POST['priority'] === "minor") {$label = "label-info";}
    if ($changepriority['status'] == "success") {
      InputLog($_SESSION['name'],"ticket","Change priority ticket [$ticketid] to ".$_POST['priority']);
      SqlQuInsert("wavenet.tb_ticket_reply", [
        "ticketid" => $_POST['id'],
        "answerer" => $_SESSION['id'],
        "content" => addslashes("Change priority to <span class='label $label'>".$_POST['priority']."</span>")
      ]);
      $report = ["status" => "success"];
      $report[] = ["status" => $_POST['priority']];
      echo json_encode($report);
    }
  }

  if ($_GET['r'] === "reply-ticket") {
    $status = true;
    // Cek apakah ada input kosong untuk kolom yang dibutuhkan
    $this_empty = ["status" => "empty"];
    $cek_empty_field = ["reply-text"];
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
    // Cek file.
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
    // Cek device info.
    $error_device = ["status" => "err_device"];
    $device_file_name = "";
    if ($_POST['att-device'] > 0) {
      $url = 'http://127.0.0.1/admin/networking/sql-proc.php?i=info&limitlog=50&id='.$_POST['att-device'];
      $device_json = file_get_contents($url);
      $decojson = json_decode($device_json, true);
      if ($decojson['status'] === "failed") {
        $error_device[] = ["error" => "Can't load devices information", "col" => "err_device"];
        $status = false;
        echo json_encode($error_device);
        exit;
      } else {
        $now = time();
        $device_file_name = urlencode($now."_".$_POST['id']."_".$decojson[0]['routeros']['identity'].".cdi");
        $device_file = fopen("file/$device_file_name", "w");
        fwrite($device_file, $device_json);
      }
    }

    if ($status === true) {
      $ticketdata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE `id` = ".$_POST['id']);
      $ticketid = $ticketdata[0]['ticket_id'];
      if (isset($_POST['set-hide'])) {
        $hide = "1";
      } else {
        $hide = "0";
      }
      $insert = SqlQuInsert("wavenet.tb_ticket_reply", [
        "ticketid" => $_POST['id'],
        "answerer" => $_SESSION['id'],
        "content" => $_POST['reply-text'],
        "hide" => $hide,
        "ro_capture" => $device_file_name
      ]);
      if ($insert['status'] == "success") {
        InputLog($_SESSION['name'],"ticket", $_SESSION['name']." reply ticket [$ticketid]");
        if ($havefile === true) {
          $filename = $ticketid."_".$insert[0]['id']."_".urlencode($_FILES['file']['name']);
          $dirUpload = "file/";
          $upload = move_uploaded_file($_FILES["file"]["tmp_name"], $dirUpload.$filename);
          if ($upload) {
            SqlQuUpdate("wavenet.tb_ticket_reply",["file" => $filename],"id", $insert[0]['id']);
            $insert[] = ["filename" => $filename];
          }
        }
        if (isset($_POST['set-closed'])) {
          $close = SqlQuUpdate("wavenet.tb_ticket", ["status" => "closed"], "id", $_POST['id']);
          if ($close['status'] == "success") {
            SqlQuInsert("wavenet.tb_ticket_reply", [
              "ticketid" => $_POST['id'],
              "answerer" => $_SESSION['id'],
              "content" => addslashes("Ticket move to folder <span class='label label-success'>closed</span>")
            ]);
          } 
        }
        if ($ticketdata[0]['status'] === "pending") {
          $changestatus = SqlQuUpdate("wavenet.tb_ticket", ["status" => "open"], "id", $_POST['id']);
          if ($changestatus['status'] == "success") {
            InputLog($_SESSION['name'],"ticket","Ticket [$ticketid] move to folder open");
            SqlQuInsert("wavenet.tb_ticket_reply", [
              "ticketid" => $_POST['id'],
              "answerer" => $_SESSION['id'],
              "content" => addslashes("Ticket move to folder <span class='label label-info'>open</span>")
            ]);
          }
        }
        if ($ticketdata[0]['status'] === "new") {
          SqlQuUpdate("wavenet.tb_ticket", ["status" => "open"], "id", $_POST['id']);
        }
        echo json_encode($insert);
        exit;
      }
    }
  }
}

if (isset($_GET['read-reply'])) {
  session_start();
  $whoim = $_SESSION['id'];
  $result =[];
  $replydata = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket_reply WHERE `ticketid` = ".$_POST['id']);
  foreach ($replydata as $key) {
    $memberdata = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `id` = ".$key['answerer']);
    if ($whoim === $key['answerer'] && $key['deleted'] === "0") {
      $option = "enable";
    } else {
      $option = "disable";
    }
    if (!isset($memberdata[0]['image']) or $memberdata[0]['image'] == "" or empty($memberdata[0]['image'])) {
       $image = "https://api.adorable.io/avatars/128/".$memberdata[0]['id'];
     } else {
       $image = "$weburl/image/userimg/".$memberdata[0]['image'];
     }
    $result[] = [ "id" => $key['id'],
                  "image" => $image,
                  "name" => $memberdata[0]['name'],
                  "content" => $key['content'],
                  "file" => $key['file'],
                  "date" => $key['date'],
                  "hide" => $key['hide'],
                  "edited" => $key['edited'],
                  "option" => $option,
                  "ro_capture" => $key['ro_capture']
                ];
  }
  echo json_encode($result);
}
?>
