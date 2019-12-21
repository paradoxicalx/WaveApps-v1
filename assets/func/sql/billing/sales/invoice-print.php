<?php
if (isset($_GET['qs']) && $_GET['qs'] == "invoice-print") {
  $invid = $_POST['invid'];
  $result = [];
  for ($i = 0; $i < count($invid); $i++) {
    $id = $invid[$i];
    $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE id = '$id'");
    $memberid = $query[0]['member'];
    $memberdata = sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE id = '$memberid'");
    $result[] = [
      "invid" => $query[0]['id'],
      "memberid" => $memberid,
      "identity" => $query[0]['identity'],
      "item" => $query[0]['item'],
      "date" => $query[0]['date'],
      "duedate" => $query[0]['duedate'],
      "total" => $query[0]['total'],
      "subtotal" => $query[0]['subtotal'],
      "shipping" => $query[0]['shipping'],
      "tax" => $query[0]['tax'],
      "notes" => $query[0]['notes'],
      "status" => ucfirst($query[0]['status']),
      "name" => $memberdata[0]['name'],
      "email" => $memberdata[0]['email'],
      "phone" => $memberdata[0]['phone'],
      "address" => $memberdata[0]['address']
    ];
  }
  $out = json_encode($result);
  echo $out;
  exit;
}

?>
