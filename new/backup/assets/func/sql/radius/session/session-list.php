<?php
if (isset($_GET['qs'])) {
  $result = [];
  if ($_GET['qs'] === "active") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct WHERE acctstoptime IS NULL");
  } elseif ($_GET['qs'] === "today") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct WHERE acctstarttime >= CURDATE()");
  } elseif ($_GET['qs'] === "all") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct");
  }
  foreach ($query as $key) {
    $upload = $key['acctinputoctets'];
    if ($upload != 0) {
      $upload = formatBytes($upload);
    }
    $download = $key['acctoutputoctets'];
    if ($download != 0) {
      $download = formatBytes($download);
    }
    $result[] = [ $key['id'],
                  $key['username'],
                  $key['nasipaddress'],
                  $key['acctstarttime'],
                  convert_seconds($key['acctsessiontime']),
                  $upload,
                  $download,
                  $key['callingstationid'],
                  $key['framedipaddress']
                ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
