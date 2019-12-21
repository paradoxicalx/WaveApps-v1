<?php
// Dapatkan data dari SQL dan kirimkan sebagai json untuk 'datatables'
// Identifikasi dengan $_GET['q']
if (isset($_GET['qu'])) {
  if ($_GET['qu'] === "all") {
    $result = [];
    $query = sqlQuAssoc("SELECT r.id, w.name, r.username, g.groupname, r.value, g.status, r.dateaddrad, w.id AS memberid
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
      $updown = sqlQuAssoc("SELECT SUM(acctinputoctets) AS upload, SUM(acctoutputoctets) AS download FROM radius.radacct
        WHERE MONTH(acctstarttime) = MONTH(CURRENT_DATE())
        AND YEAR(acctstarttime) = YEAR(CURRENT_DATE())
        AND `username` = '$loginid'
      ");
      $download = $updown[0]['download'];
      $upload = $updown[0]['upload'];
      if ($key['status'] == "enable") {
        $status = "Enable";
      } else {
        $status = "Disable";
      }
      $isonline = SqlQuAssoc("SELECT acctsessionid FROM radius.radacct WHERE `acctstoptime` IS NULL AND `username` = '$loginid'");
      if (count($isonline[0]) > 0){
        $isonline = true;
      } else {
        $isonline = false;
      }
      $result[] = [ $key['id'],
                    $isonline,
                    $loginid,
                    $key['name'],
                    $key['memberid'],
                    $key['groupname'],
                    $ipaddress,
                    formatBytes($download),
                    formatBytes($upload),
                    $key['dateaddrad'],
                    formatBytes($rxlimit),
                    formatBytes($txlimit),
                    $ratelimit,
                    $key['value'],
                    $status,
                  ];
    }
  } else if ($_GET['qu'] === "member") {
    $member = $_POST['member'];
    $result = [];
    $query = sqlQuAssoc("SELECT r.id, w.name, r.username, g.groupname, r.value, g.status, r.dateaddrad, w.id AS memberid
              FROM radius.radcheck AS r, radius.radusergroup AS g, wavenet.tb_user AS w
              WHERE r.userid = w.id AND r.username = g.username AND r.attribute != 'disabled' AND g.guid = '$member';");
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
      $updown = sqlQuAssoc("SELECT SUM(acctinputoctets) AS upload, SUM(acctoutputoctets) AS download FROM radius.radacct
        WHERE MONTH(acctstarttime) = MONTH(CURRENT_DATE())
        AND YEAR(acctstarttime) = YEAR(CURRENT_DATE())
        AND `username` = '$loginid'
      ");
      $download = $updown[0]['download'];
      $upload = $updown[0]['upload'];
      if ($key['status'] == "enable") {
        $status = "Enable";
      } else {
        $status = "Disable";
      }
      $isonline = SqlQuAssoc("SELECT acctsessionid FROM radius.radacct WHERE `acctstoptime` IS NULL AND `username` = '$loginid'");
      if (count($isonline[0]) > 0){
        $isonline = true;
      } else {
        $isonline = false;
      }
      $result[] = [ $key['id'],
                    $key['name'],
                    $key['memberid'],
                    $loginid,
                    $key['value'],
                    $key['groupname'],
                    $ipaddress,
                    $status,
                    $key['dateaddrad'],
                    formatBytes($rxlimit),
                    formatBytes($txlimit),
                    $ratelimit,
                    formatBytes($download),
                    formatBytes($upload),
                    $isonline,
                  ];
    }
  } else {
    $group = $_GET['qu'];
    $result = [];
    $query = sqlQuAssoc("SELECT r.id, w.name, r.username, g.groupname, r.value, g.status, r.dateaddrad, w.id AS memberid
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
      $updown = sqlQuAssoc("SELECT SUM(acctinputoctets) AS upload, SUM(acctoutputoctets) AS download FROM radius.radacct
        WHERE MONTH(acctstarttime) = MONTH(CURRENT_DATE())
        AND YEAR(acctstarttime) = YEAR(CURRENT_DATE())
        AND `username` = '$loginid'
      ");
      $download = $updown[0]['download'];
      $upload = $updown[0]['upload'];
      if ($key['status'] == "enable") {
        $status = "Enable";
      } else {
        $status = "Disable";
      }
      $isonline = SqlQuAssoc("SELECT acctsessionid FROM radius.radacct WHERE `acctstoptime` IS NULL AND `username` = '$loginid'");
      if (count($isonline[0]) > 0){
        $isonline = true;
      } else {
        $isonline = false;
      }
      $result[] = [ $key['id'],
                    $isonline,
                    $loginid,
                    $key['name'],
                    $key['memberid'],
                    $key['groupname'],
                    $ipaddress,
                    formatBytes($download),
                    formatBytes($upload),
                    $key['dateaddrad'],
                    formatBytes($rxlimit),
                    formatBytes($txlimit),
                    $ratelimit,
                    $key['value'],
                    $status,
                  ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
