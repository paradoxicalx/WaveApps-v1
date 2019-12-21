<?php
if (isset($_GET['qs']) && $_GET['qs'] == "payment-report") {
  $result = [];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_translog WHERE type = 'sales' OR type = 'wallet'");
  foreach ($query as $key) {
    if ($key['type'] == "wallet") {
      $member = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE id =".$key['transid']);
      $account = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id =".$key['account']);
      $result[] = [
        $key['transid'],
        "Wallet",
        $key['transid'],
        $member[0]['name'],
        $key['transid'],
        $account[0]['name'],
        "",
        "",
        rupiah($key['amount']),
        $key['date']
      ];
    } else {
      $transid = $key['transid'];
      $inv =  sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE `id` = $transid");
      $m = $inv[0]['member'];
      $member = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE id = '$m'");
      $a = $inv[0]['payto'];
      $payto = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id = '$a'");
      $b = $inv[0]['refundfrom'];
      $refundfrom = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id = '$b'");
      if (strpos($key['description'], 'Refund') == true) {
        $type = "Refund";
        $refundfroms = $refundfrom[0]['name'];
      } else {
        $type = "Payment";
        $refundfroms = "";
      }
      $result[] = [
        $inv[0]['id'],
        $type,
        $transid,
        $member[0]['name'],
        $inv[0]['member'],
        $payto[0]['name'],
        $refundfroms,
        ucfirst($inv[0]['paymentmethod']),
        rupiah($inv[0]['total']),
        $key['date']
      ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['qs']) && $_GET['qs'] == "member-report") {
  $result = [];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE deleted = '0'");
  foreach ($query as $key) {
    $transid = $key['id'];
    $sumpaid =  sqlQuAssoc("SELECT SUM(total),count(*) FROM wavenet.tb_invoice WHERE `member` = $transid AND `status` = 'paid' AND deleted  = '0'");
    $sumunpaid =  sqlQuAssoc("SELECT SUM(total),count(*) FROM wavenet.tb_invoice WHERE `member` = $transid AND `status` = 'unpaid' AND deleted  = '0'");
    $result[] = [
            $key['id'],
            $key['name'],
            rupiah($sumunpaid[0]['SUM(total)']),
            $sumunpaid[0]['count(*)'],
            rupiah($sumpaid[0]['SUM(total)']),
            $sumpaid[0]['count(*)'],
            rupiah($sumunpaid[0]['SUM(total)']+$sumpaid[0]['SUM(total)']),
            $sumpaid[0]['count(*)']+$sumunpaid[0]['count(*)'],
            rupiah($key['wallet']),
            $key['group']
          ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
