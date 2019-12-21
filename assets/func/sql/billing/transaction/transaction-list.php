<?php
if (isset($_GET['qt']) && $_GET['qt'] == "transaction-list") {
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_translog WHERE type = 'expenses' OR type = 'deposit'");
  foreach ($query as $key) {
    $account = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id =".$key['account']);
    $result[] = [
      $key['id'],
      $account[0]['name'],
      $key['type'],
      rupiah($key['amount']),
      $key['date'],
      $key['description']
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
