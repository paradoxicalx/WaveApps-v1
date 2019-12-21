<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['q'])) {
  $result = [];
  if ($_GET['q'] === "service") {
    $query = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `deleted` = '0' AND `type` = 'service'");
    foreach ($query as $key) {
      $result[] = [
                    $key['id'],
                    $key['name'],
                    rupiah($key['price']),
                    $key['rgroup'],
                    $key['description'],
                    $key['date']
                  ];
     }
  } else {
    $query = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `deleted` = '0' AND `type` = 'stuff'");
    foreach ($query as $key) {
      $result[] = [
                    $key['id'],
                    $key['name'],
                    rupiah($key['price']),
                    $key['number'],
                    $key['description'],
                    $key['date']
                  ];
      }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
