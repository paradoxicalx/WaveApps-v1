<?php
if (isset($_GET['qr'])) {
  if ($_GET['qr'] === "all") {
    $result = [];
    $query = sqlQuAssoc("SELECT * FROM radius.nas");
    foreach ($query as $key) {
      $result[] = [
                    $key['id'],
                    $key['shortname'],
                    $key['nasname'],
                    $key['type'],
                    $key['ports'],
                    $key['secret'],
                    $key['server'],
                    $key['community'],
                    $key['description']
                  ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
