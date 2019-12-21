<?php
if (isset($_GET['i']) && $_GET['i'] === "device-traffic") {
  $id = $_POST['id'];
  $interface = $_POST['interface'];
  $host = $_POST['host'];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE id = '$id'");
  // Cek API status.
  $API = new routeros_api();
  $API->debug = false;
  if ($API->connect(long2ip($query[0]['ip']) , $query[0]['apiname'] , $query[0]['apipass'], 8728)) {
    $API->write('/interface/monitor-traffic',false);
    $API->write('=interface='.$interface,false);
    $API->write('=once');
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['traffic']['rx'] = $ARRAY[0]['rx-bits-per-second'];
    $result['traffic']['tx'] = $ARRAY[0]['tx-bits-per-second'];
    $API->write('/system/resource/print',true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['traffic']['free-memory'] = $ARRAY[0]['free-memory'];
    $result['traffic']['total-memory'] = $ARRAY[0]['total-memory'];
    $result['traffic']['cpu-load'] = $ARRAY[0]['cpu-load'];
    $result['traffic']['free-hdd-space'] = $ARRAY[0]['free-hdd-space'];
    $result['traffic']['total-hdd-space'] = $ARRAY[0]['total-hdd-space'];
    $API->write('/ping',false);
    $API->write('=address='.$host,false);
    $API->write('=count=1',false);
    $API->write('=interval=1');
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    if ($ARRAY[0]['status']) {
      $result['traffic']['ping-stat'] = "timeout";
    } else {
      $result['traffic']['ping-time'] = str_replace("ms","",$ARRAY[0]['time']);
      $result['traffic']['ping-size'] = $ARRAY[0]['size'];
      $result['traffic']['ping-ttl'] = $ARRAY[0]['ttl'];
    }
  }
  $API->disconnect();
  echo json_encode($result['traffic']);
  exit;
}
?>
