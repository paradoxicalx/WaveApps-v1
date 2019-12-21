<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['q'])) {
  session_start();
  $result = [];
  if ($_GET['q'] === "open") {
    $where = "`status` = 'new' OR `status` = 'open'";
  } elseif ($_GET['q'] === "closed") {
    $where = "`status` = 'closed'";
  } elseif ($_GET['q'] === "pending") {
    $where = "`status` = 'pending'";
  } elseif ($_GET['q'] === "trash") {
    $where = "`status` = 'trash'";

  } elseif ($_GET['q'] === "critical") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `priority` = 'critical' ";
  } elseif ($_GET['q'] === "major") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `priority` = 'major' ";
  } elseif ($_GET['q'] === "minor") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `priority` = 'minor' ";

  } elseif ($_GET['q'] === "member") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `topic` = 'member' ";
  } elseif ($_GET['q'] === "networking") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `topic` = 'networking' ";
  } elseif ($_GET['q'] === "billing") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `topic` = 'billing' ";
  } elseif ($_GET['q'] === "other") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `topic` = 'other' ";

  } elseif ($_GET['q'] === "assign") {
    $where = "(`status` = 'new' OR `status` = 'open') AND `assign` = '".$_SESSION['id']."' ";
  } elseif ($_GET['q'] === "member-detail") {
    $where = "`member` = '".$_POST['member']."' AND `status` != 'trash'";
  }

  $query = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket WHERE $where");
  foreach ($query as $key) {
    $member = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE `id` = ".$key['member']);
    $assign = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE `id` = ".$key['assign']);
    $lastreply = sqlQuAssoc("SELECT * FROM wavenet.tb_ticket_reply WHERE `ticketid` = ".$key['id']." ORDER BY id DESC LIMIT 1");
    $lastreplyuser = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE `id` = ".$lastreply[0]['answerer']);
    $device = sqlQuAssoc("SELECT name FROM wavenet.tb_devices WHERE `id` = ".$key['device']);
    if (count($lastreply) > 0) {
      $last = $lastreplyuser[0]['name']." (".time_elapsed_string($lastreply[0]['date']).")";
    } else {
      $last = "";
    }
    if (!empty($key['file'])) {
      $file = "yes";
    } else {
      $file = "no";
    }
    $result[] = [ $key['id'],
                  $key['title'],
                  $last,
                  time_elapsed_string($key['date']),
                  $key['ticket_id'],
                  $key['topic'],
                  $assign[0]['name'],
                  $member[0]['name'],
                  $device[0]['name'],
                  $key['invoice'],
                  $key['priority'],
                  $key['status'],
                  $file,
                ];
  }

  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
