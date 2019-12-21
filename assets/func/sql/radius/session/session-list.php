<?php
if (isset($_GET['qs'])) {
  $result = [];
  if ($_GET['qs'] === "active") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct WHERE `acctstoptime` IS NULL LIMIT 1000");
  } elseif ($_GET['qs'] === "today") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct WHERE `acctstarttime` >= CURDATE() LIMIT 1000");
  } elseif ($_GET['qs'] === "all") {
    $query = sqlQuAssoc("SELECT * FROM radius.radacct LIMIT 1000");
  } elseif ($_GET['qs'] === "member") {
    $where = "";
    $id = $_POST['id'];
    $username = sqlQuAssoc("SELECT * FROM radius.radusergroup WHERE `guid` = '$id'");
    foreach ($username as $key) {
      $where .= " OR  `username` = '".$key['username']."'";
    }
    $query = sqlQuAssoc("SELECT * FROM radius.radacct WHERE  `username` = '' $where LIMIT 100");
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
    $result[] = [ $key['radacctid'],
                  $key['username'],
                  $key['nasipaddress'],
                  $key['acctstarttime'],
                  convert_seconds($key['acctsessiontime']),
                  $upload,
                  $download,
                  $key['callingstationid'],
                  $key['framedipaddress'],
                  $key['acctstoptime']
                ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['dc'])) {
  $count = 0;
  $id = $_POST['id'];
  unset($_POST['id']);
  for ($i = 0; $i < count($id); $i++) {
    $sessdata = SqlQuAssoc("SELECT * FROM radius.radacct WHERE `radacctid` = $id[$i]");
    $username = $sessdata[0]['username'];
    $router = $sessdata[0]['nasipaddress'];
    $termi = exec("echo 'User-Name=$username' | radclient -x $router:".$_POST['port']." disconnect '".$_POST['secret']."'");
    if (strpos($termi, 'User-Name') == false) {
      $count++;
    }
  }
  if ($count == count($id)) {
    $report = ["status" => "success"];
    $report[] = ["error" => true, "count" => $count];
    echo json_encode($report);
    exit;
  } else {
    $report = ["status" => "error"];
    $report[] = ["error" => true, "count" => $count];
    echo json_encode($report);
    exit;
  }
}
?>
