<?php
if (isset($_GET['qr']) && $_GET['qr'] == "report") {
  $account = $_GET['account'];
  $type = strtolower($_GET['type']);
  $startdate = $_GET['startdate'];
  $enddate = $_GET['enddate'];

  if (!empty($account) or $account != "") {
    $qa = "`account` = '$account' AND ";
  }
  if (!empty($type) or $type != "") {
    $qt = "`type` = '$type' AND ";
  }
  if (!empty($startdate) or $startdate != "") {
    $qd = "`date` BETWEEN '$startdate' AND '$enddate' ";
  }

  $qx = $qa.$qt.$qd;

  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_translog WHERE $qx");
  if (isset($query[0]['id'])) {
    foreach ($query as $key) {
      $account = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id =".$key['account']);
      if (strpos($key['description'], 'Refund') or $key['type'] == "expenses") {
        $status = "minus";
      } elseif ($key['type'] == "transfer") {
        $status = "equals";
      } else {
        $status = "plus";
      }
      $result[] = [
        $key['id'],
        $account[0]['name'],
        ucfirst($key['type']),
        "[".$key['id']."] ".$key['date'],
        $key['description'],
        rupiah($key['amount']),
        rupiah($key['accbal']),
        rupiah($key['allbal']),
        $status
      ];
    }
  } else {
    $result = [];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
