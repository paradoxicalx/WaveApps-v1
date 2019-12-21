<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['qu'])) {
  if ($_GET['qu'] === "all") {
    $result = [];
    $query = sqlQuAssoc("SELECT r.id, w.name, r.username, g.groupname, r.value, r.dateaddrad, w.id AS memberid
              FROM radius.radcheck AS r, radius.radusergroup AS g, wavenet.tb_user AS w
              WHERE r.userid = w.id AND r.username = g.username AND r.attribute != 'disabled';");
    foreach ($query as $key) {
      $loginid = $key['username'];
      $ipaddress = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Framed-IP-Address' AND `username` = '$loginid'");
      $ipaddress = $ipaddress[0]['value'];
      $rxlimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Recv-Limit' AND `username` = '$loginid'");
      $rxlimit = $rxlimit[0]['value'];
      $txlimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Xmit-Limit' AND `username` = '$loginid'");
      $txlimit = $txlimit[0]['value'];
      $ratelimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Rate-Limit' AND `username` = '$loginid'");
      $ratelimit = $ratelimit[0]['value'];
      $result[] = [ $key['id'],
                    $key['name'],
                    $key['memberid'],
                    $loginid,
                    $key['value'],
                    $key['groupname'],
                    $ipaddress,
                    $key['dateaddrad'],
                    formatBytes($rxlimit),
                    formatBytes($txlimit),
                    $ratelimit
                  ];
    }
  } else {
    $group = $_GET['qu'];
    $result = [];
    $query = sqlQuAssoc("SELECT r.id, w.name, r.username, g.groupname, r.value, r.dateaddrad, w.id AS memberid
              FROM radius.radcheck AS r, radius.radusergroup AS g, wavenet.tb_user AS w
              WHERE r.userid = w.id AND r.username = g.username AND r.attribute != 'disabled' AND w.group = '$group';");
    foreach ($query as $key) {
      $loginid = $key['username'];
      $ipaddress = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Framed-IP-Address' AND `username` = '$loginid'");
      $ipaddress = $ipaddress[0]['value'];
      $rxlimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Recv-Limit' AND `username` = '$loginid'");
      $rxlimit = $rxlimit[0]['value'];
      $txlimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Xmit-Limit' AND `username` = '$loginid'");
      $txlimit = $txlimit[0]['value'];
      $ratelimit = sqlQuAssoc("SELECT value FROM radius.radreply WHERE `attribute` = 'Mikrotik-Rate-Limit' AND `username` = '$loginid'");
      $ratelimit = $ratelimit[0]['value'];
      $result[] = [ $key['id'],
                    $key['name'],
                    $key['memberid'],
                    $loginid,
                    $key['value'],
                    $key['groupname'],
                    $ipaddress,
                    $key['dateaddrad'],
                    formatBytes($rxlimit),
                    formatBytes($txlimit),
                    $ratelimit
                  ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
