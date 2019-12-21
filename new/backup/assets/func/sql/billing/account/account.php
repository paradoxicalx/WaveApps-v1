<?php
if (isset($_GET['q']) && $_GET['q'] == "account") {
  $result = [];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_account WHERE deleted = '0'");
  foreach ($query as $key) {
    $result[] = [
      $key['id'],
      $key['name'],
      rupiah($key['balance']),
      $key['number'],
      $key['phone'],
      $key['bankurl'],
      $key['description']
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['q']) && $_GET['q'] == "transfer") {
  $result = [];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_transfer");
  foreach ($query as $key) {
    $f = $key['from'];
    $t = $key['to'];
    $from = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id = '$f'");
    $to = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id = '$t'");
    $result[] = [
      $key['id'],
      $from[0]['name'],
      $to[0]['name'],
      rupiah($key['nominal']),
      $key['description'],
      $key['date']
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
