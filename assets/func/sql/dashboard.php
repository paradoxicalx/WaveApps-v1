<?php
if (isset($_GET['getlog'])) {
  if ($_GET['getlog'] === "all") {
    $logs = sqlQuAssoc("SELECT * FROM wavenet.tb_log ORDER BY `id` DESC LIMIT 500");
  } else {
    $logs = sqlQuAssoc("SELECT * FROM wavenet.tb_log WHERE `type`!='login-success' AND `type`!='logout' ORDER BY `id` DESC LIMIT 500");
  }
  foreach ($logs as $key) {
    $result[] = [
      $key['time'],
      $key['type'],
      $key['message'],
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['getcount'])) {
  // Customer Count
  $countcustomer = sqlQuAssoc("SELECT COUNT(*) FROM wavenet.tb_user WHERE `deleted` = '0' AND `group` = 'customer' AND `status` = 'active'");
  $countcustomer = $countcustomer[0]['COUNT(*)'];
  // Open ticket
  $countticket = sqlQuAssoc("SELECT COUNT(*) FROM wavenet.tb_ticket WHERE `status` = 'new' OR `status` = 'open'");
  $countticket = $countticket[0]['COUNT(*)'];
  // Unpaid invoice
  $countinvoice = sqlQuAssoc("SELECT COUNT(*) FROM wavenet.tb_invoice WHERE `deleted` = '0' AND `status` = 'unpaid'");
  $countinvoice = $countinvoice[0]['COUNT(*)'];
  // Device Down
  $countdevice = sqlQuAssoc("SELECT COUNT(*) FROM wavenet.tb_devices WHERE `deleted` = '0' AND `status` = 'down'");
  $countdevice = $countdevice[0]['COUNT(*)'];
  // Notes
  $notes = sqlQuAssoc("SELECT * FROM wavenet.tb_notes WHERE `deleted` = '0'");
  // Online Users

  $result = [
    "countcustomer" => $countcustomer,
    "countticket" => $countticket,
    "countinvoice" => $countinvoice,
    "countdevice" => $countdevice,
    "notes" => $notes,
  ];

  $out = json_encode($result);
  echo $out;
  exit;
}

if (isset($_GET['updateapidata'])) {
  $now = date("Y-m-d H:i:s");

  if (isset($_POST['oid_id'])) {
    $oid_id = $_POST['oid_id'];
    $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `oid` = $oid_id");
  }
  if ($device[0]['ip'] > 0) {
    $iprouter = long2ip($device[0]['ip']);
    $username = $device[0]['apiname'];
    $pass = $device[0]['apipass'];
    $port = 8728;
  }
  if (isset($iprouter)) {
    echo "$now - update data router";
    $API = new routeros_api();
    $API->debug = false;
    if ($API->connect($iprouter , $username , $pass, $port)) {
      $API->write("/system/resource/print",true);
      $READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $apiinfo = $ARRAY;
    } else {
      $apifail = ["status" => "wrong_api"];
      $apifail[] = ["error" => "Wrong API username or password !!", "col" => "useapi"];
      echo json_encode($apifail);
      exit;
    }
    $API->disconnect();

    $API2 = new routeros_api();
    $API2->debug = false;
    if ($API2->connect($iprouter , $username , $pass, $port)) {
      $API2->write("/system/identity/print",true);
      $READ2 = $API2->read(false);
      $ARRAY2 = $API2->parse_response($READ2);
      $apiinfo2 = $ARRAY2;
    }
    $API2->disconnect();

    $API3 = new routeros_api();
    $API3->debug = false;
    if ($API3->connect($iprouter , $username , $pass, $port)) {
      $API3->write("/system/clock/print",true);
      $READ3 = $API3->read(false);
      $ARRAY3 = $API3->parse_response($READ3);
      $apiinfo3 = $ARRAY3;
    }
    $API3->disconnect();

    SqlQuUpdate("wavenet.tb_devices_oid",
    [
      "router-name" => $apiinfo2[0]['name'],
      "router-version" => $apiinfo[0]['version'],
      "total-memory" =>  $apiinfo[0]['total-memory'],
      "cpu" => $apiinfo[0]['cpu'],
      "cpu-count" => $apiinfo[0]['cpu-count'],
      "cpu-frequency" => $apiinfo[0]['cpu-frequency'],
      "total-hdd" => $apiinfo[0]['total-hdd-space'],
      "free-hdd" => $apiinfo[0]['free-hdd-space'],
      "architecture-name" => $apiinfo[0]['architecture-name'],
      "board-name" => $apiinfo[0]['board-name'],
      "timezone" => $apiinfo3[0]['time-zone-name'],
      "last_update" => $now,
    ], "id", "$oid_id");
  }
  exit;
}

if (isset($_GET['changeDevice'])) {
  $id = $_POST['id'];
  $makeoff = SqlQuUpdate("wavenet.tb_devices_oid", [ "onstart" => "0" ], "onstart", "1");
  $makeon = SqlQuUpdate("wavenet.tb_devices_oid", [ "onstart" => "1" ], "id", "$id");
  if ($makeon['status'] === "success") {
    echo json_encode($makeon);
  }
}

if (isset($_GET['edit-devices'])) {
  for ($i=0; $i < $_POST['cpucount']; $i++) {
    $cpu[$i] = $_POST["cpu$i"];
  }
  $oid["bytes-in"] = $_POST['rxoid'];
  $oid["bytes-out"] = $_POST['txoid'];
  $oid["used-memory"] = $_POST['memoryused'];
  $oid["uptime"] = $_POST['uptimeoid'];
  $oid["voltage"] = $_POST['voltageoid'];
  $oid["temperature"] = $_POST['tempoid'];
  $oid["cpu"] = $cpu;
  $oid_json = json_encode($oid);

  $update = SqlQuUpdate("wavenet.tb_devices_oid", [
                            "comunity" => $_POST['community'],
                            "snmp_version" => $_POST['version'],
                            "oid" => $oid_json
                        ], "id", $_POST['id']);

  if ($update['status'] == "success") {
    $report = ["status" => "success"];
    $report[] = ["error" => false, "data" => $update ];
    echo json_encode($report);
    exit;
  } else {
    $report = ["status" => "failed"];
    $report[] = ["error" => "Edit device failed", "data" => $update ];
    echo json_encode($report);
    exit;
  }
}

if (isset($_GET['radiuscount'])) {
  $result = [];
  $query = sqlQuAssoc("SELECT * FROM radius.radusergroup WHERE `username` = 0 AND `guid` = 0");
  foreach ($query as $key) {
    $groupname = $key['groupname'];
    $user = sqlQuAssoc("SELECT COUNT(*) FROM radius.radusergroup WHERE `guid` != '0' AND `groupname` = '$groupname'");
    $user = $user[0]['COUNT(*)'];

    $online = 0;
    $usercek = sqlQuAssoc("SELECT * FROM radius.radusergroup WHERE `groupname` = '$groupname' AND `guid` != 0");
    foreach ($usercek as $key) {
      $name = $key['username'];
      $cek = sqlQuAssoc("SELECT * FROM radius.radacct WHERE `username` = '$name' AND `acctstoptime` IS NULL ");
      if ($cek) {
        $online = $online+1;
      }
    }

    $result[] = [
      "group" => $groupname,
      "user" => $user,
      "online" => $online
    ];
  }

  echo json_encode($result);
}
?>
