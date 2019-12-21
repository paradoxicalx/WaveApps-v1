<?php
if (isset($_GET['qlist'])) {
  if ($_GET['qlist'] === "all") {
    $result = [];
    $id = $_POST['ipid'];

    for ($i = 0; $i < count($id); $i++) {
      $query = sqlQuAssoc("SELECT * FROM wavenet.tb_iplist WHERE master = '$id[$i]'");
      foreach ($query as $key) {
        if (strtoupper($key['useby']) === "RADIUS") {
          $infid = $key['infid'];
          $getid = sqlQuAssoc("SELECT userid FROM radius.radcheck WHERE id = '$infid'");
          $info = "Member ID: ".$getid[0]['userid'];
        } elseif (strtoupper($key['useby']) === "DEVICES") {
          $infid = $key['infid'];
          $getid = sqlQuAssoc("SELECT name FROM wavenet.tb_devices WHERE id = '$infid'");
          $info = $getid[0]['name'];
        } else {
          $info = "";
        }

        $result[] = [
          $key['ipaddress'],
          long2ip($key['ipaddress']),
          strtoupper($key['type']),
          strtoupper($key['useby']),
          $info
        ];
      }
    }
  }
  $out = json_encode(["data" => $result]);
  echo $out;
  exit;
}
?>
