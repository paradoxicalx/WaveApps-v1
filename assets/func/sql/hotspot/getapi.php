<?php
if (isset($_GET['getapi'])) {
  $id = $_GET['id'];
  $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = '$id' ");

  $ipRouteros = long2ip($device[0]['ip']);
  $Username= $device[0]['apiname'];
  $Pass= $device[0]['apipass'];
  $port=8728;

  $API = new routeros_api();
  $API->debug = false;
  if ($API->connect($ipRouteros , $Username , $Pass, $port)) {
    $apiconnection = true;

    $API->write("/ip/hotspot/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['server'] = $ARRAY;

    $API->write("/ip/hotspot/profile/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['server_profile'] = $ARRAY;

    $API->write("/ip/hotspot/user/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['user'] = $ARRAY;

    $API->write("/ip/hotspot/user/profile/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['user_profile'] = $ARRAY;

    $API->write("/ip/hotspot/active/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['active'] = $ARRAY;

    $API->write("/ip/hotspot/host/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['host'] = $ARRAY;

    $API->write("/ip/hotspot/cookie/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['cookie'] = $ARRAY;

    $API->write("/ip/hotspot/ip-binding/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['ip_binding'] = $ARRAY;

    $API->write("/ip/hotspot/walled-garden/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['walled_garden'] = $ARRAY;
  }
  $API->disconnect();
  if ($apiconnection) {
    $out = ["status" => "success"];
    $out[] = $result;
    echo json_encode($out);
    exit;
  } else {
    $out = ["status" => "failed"];
    $out[] = ["error" => "failed geting data"];
    echo json_encode($out);
    exit;
  }

}
