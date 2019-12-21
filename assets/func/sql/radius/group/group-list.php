<?php
if (isset($_GET['qg'])) {
  if ($_GET['qg'] === "all") {
    $result = [];
    $query = sqlQuAssoc("SELECT * FROM radius.radusergroup WHERE `username` = 0 AND `guid` = 0");
    foreach ($query as $key) {
      $groupname = $key['groupname'];
      $profile = sqlQuAssoc("SELECT value FROM radius.radgroupreply WHERE `attribute` = 'Mikrotik-Group' AND `groupname` = '$groupname'");
      $profile = $profile[0]['value'];
      $dllimit = sqlQuAssoc("SELECT value FROM radius.radgroupreply WHERE `attribute` = 'Mikrotik-Recv-Limit' AND `groupname` = '$groupname'");
      $dllimit = $dllimit[0]['value'];
      $uplimit = sqlQuAssoc("SELECT value FROM radius.radgroupreply WHERE `attribute` = 'Mikrotik-Xmit-Limit' AND `groupname` = '$groupname'");
      $uplimit = $uplimit[0]['value'];
      $ratelimit = sqlQuAssoc("SELECT value FROM radius.radgroupreply WHERE `attribute` = 'Mikrotik-Rate-Limit' AND `groupname` = '$groupname'");
      $ratelimit = $ratelimit[0]['value'];
      $maxsess = sqlQuAssoc("SELECT value FROM radius.radgroupcheck WHERE `attribute` = 'Simultaneous-Use' AND `groupname` = '$groupname'");
      $maxsess = $maxsess[0]['value'];
      $user = sqlQuAssoc("SELECT COUNT(*) FROM radius.radusergroup WHERE `guid` != '0' AND `groupname` = '$groupname'");
      $user = $user[0]['COUNT(*)'];
      $result[] = [
                    $key['id'],
                    $key['groupname'],
                    $user,
                    $profile,
                    $maxsess,
                    $dllimit,
                    $uplimit,
                    $ratelimit
                  ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
