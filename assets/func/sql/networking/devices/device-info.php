<?php
if (isset($_GET['i']) && $_GET['i'] === "info") {
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
  } else {
    $id = $_GET['id'];
  }
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE id = '$id'");
  // Cek API status.
  if ($query[0]['useapi'] === "false") {
    $report = ["status" => "failed"];
    $report[] = ["error" => true, "message" => "API service disabled by admin"];
    echo json_encode($report);
    exit;
  }
  $API = new routeros_api();
  $API->debug = false;
  if ($API->connect(long2ip($query[0]['ip']) , $query[0]['apiname'] , $query[0]['apipass'], 8728)) {
    $apiconnection = true;
    // RouterOS..
    $API->write("/system/identity/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['routeros']['identity'] = $ARRAY[0]['name'];
    $API->write("/system/resource/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['routeros']['board name'] = $ARRAY[0]['board-name'];
    $result['routeros']['uptime'] = strtoupper($ARRAY[0]['uptime']);
    $result['routeros']['free memory'] = formatBytes($ARRAY[0]['free-memory'])." / ".formatBytes($ARRAY[0]['total-memory']);
    $result['routeros']['free hdd'] = formatBytes($ARRAY[0]['free-hdd-space'])." / ".formatBytes($ARRAY[0]['total-hdd-space']);
    $result['routeros']['cpu load'] = $ARRAY[0]['cpu-load']." %";
    $result['routeros']['cpu'] = $ARRAY[0]['cpu'];
    $result['routeros']['cpu frequency'] = $ARRAY[0]['cpu-frequency']." MHz";
    $result['routeros']['version'] = $ARRAY[0]['version'];
    $result['routeros']['build time'] = $ARRAY[0]['build-time'];
    $result['routeros']['architecture'] = $ARRAY[0]['architecture-name'];
    $API->write("/system/clock/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['routeros']['system time'] = $ARRAY[0]['time']." ".$ARRAY[0]['date'];
    $result['routeros']['time zone'] = $ARRAY[0]['time-zone-name']." ".$ARRAY[0]['gmt-offset'];
    $API->write("/system/health/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    if ($ARRAY[0]['voltage']){$result['routeros']['voltage'] = $ARRAY[0]['voltage']." V";}
    if ($ARRAY[0]['temperature']){$result['routeros']['temperature'] = $ARRAY[0]['temperature']." C";}
    $API->write("/system/license/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['routeros']['software id'] = $ARRAY[0]['software-id'];
    $result['routeros']['level'] = $ARRAY[0]['nlevel'];
    // Interface..
    $API->write("/interface/ethernet/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['ethernet'][$ARRAY[$i]['default-name']]['speed'] = $ARRAY[$i]['speed'];
    }
  	$API->write("/interface/print",false);
    $API->write("=.proplist=name,bytes-out.oid,bytes-in.oid",true);
  	$READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);

    for ($i=0; $i < count($ARRAY); $i++) {
      $result['interface-oid'][$ARRAY[$i]['name']]['bytes-in'] = substr($ARRAY[$i]['bytes-in.oid'], strpos($ARRAY[$i]['bytes-in.oid'], ".")+1);
      $result['interface-oid'][$ARRAY[$i]['name']]['bytes-out'] = substr($ARRAY[$i]['bytes-out.oid'], strpos($ARRAY[$i]['bytes-out.oid'], ".")+1);
    }
    $API->write("/interface/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['interface'][$i]['name'] = htmlentities($ARRAY[$i]['name']);
      $result['interface'][$i]['type'] = $ARRAY[$i]['type'];
      if ($result['interface'][$i]['type'] == "ether") {
        $result['interface'][$i]['speed'] = $result['ethernet'][$ARRAY[$i]['default-name']]['speed'];
      } else {
        $result['interface'][$i]['speed'] = "";
      }
      $result['interface'][$i]['running'] = $ARRAY[$i]['running'];
      $result['interface'][$i]["mac address"] = $ARRAY[$i]['mac-address'];
      $result['interface'][$i]['last up'] = $ARRAY[$i]['last-link-up-time'];
      $result['interface'][$i]['rx byte'] = formatBytes($ARRAY[$i]['rx-byte']);
      $result['interface'][$i]['tx byte'] = formatBytes($ARRAY[$i]['tx-byte']);
      $result['interface'][$i]['rx packet'] = $ARRAY[$i]['rx-packet'];
      $result['interface'][$i]['tx packet'] = $ARRAY[$i]['tx-packet'];
      $result['interface'][$i]['mtu'] = $ARRAY[$i]['mtu'];
      $result['interface'][$i]['disabled'] = $ARRAY[$i]['disabled'];
      $result['interface'][$i]['comment'] = $ARRAY[$i]['comment'];
      $result['interface'][$i]['in-oid'] = $result['interface-oid'][$ARRAY[$i]['name']]['bytes-in'];
      $result['interface'][$i]['out-oid'] = $result['interface-oid'][$ARRAY[$i]['name']]['bytes-out'];
    }
    $API->write("/interface/wireless/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['wireless'][$i]['name'] = $ARRAY[$i]['name'];
      $result['wireless'][$i]['mac address'] = $ARRAY[$i]['mac-address'];
      $result['wireless'][$i]['mode'] = $ARRAY[$i]['mode'];
      $result['wireless'][$i]['ssid'] = $ARRAY[$i]['ssid'];
      $result['wireless'][$i]['frequency'] = $ARRAY[$i]['frequency'];
      $result['wireless'][$i]['band'] = $ARRAY[$i]['band'];
      $result['wireless'][$i]['channel width'] = $ARRAY[$i]['channel-width'];
      $result['wireless'][$i]['scan list'] = $ARRAY[$i]['scan-list'];
      $result['wireless'][$i]['wireless protocol'] = $ARRAY[$i]['wireless-protocol'];
      $result['wireless'][$i]['hide ssid'] = $ARRAY[$i]['hide-ssid'];
      $result['wireless'][$i]['type'] = $ARRAY[$i]['interface-type'];
      $result['wireless'][$i]['radio name'] = $ARRAY[$i]['radio-name'];
      $result['wireless'][$i]['frequency mode'] = $ARRAY[$i]['frequency-mode'];
      $result['wireless'][$i]['rate set'] = $ARRAY[$i]['rate-set'];
      $result['wireless'][$i]['tx power mode'] = $ARRAY[$i]['tx-power-mode'];
      $result['wireless'][$i]['default authentication'] = $ARRAY[$i]['default-authentication'];
      $result['wireless'][$i]['default forwarding'] = $ARRAY[$i]['default-forwarding'];
    }
    $API->write("/interface/wireless/registration-table/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['wireless-registration'][$i]['radio name'] = $ARRAY[$i]['radio-name'];
      $result['wireless-registration'][$i]['last ip'] = $ARRAY[$i]['last-ip'];
      $result['wireless-registration'][$i]['comment'] = $ARRAY[$i]['comment'];
      $result['wireless-registration'][$i]['uptime'] = strtoupper($ARRAY[$i]['uptime']);
      $result['wireless-registration'][$i]['signal strength'] = $ARRAY[$i]['signal-strength']." (ch0:".$ARRAY[$i]['signal-strength-ch0'].", ch1:".$ARRAY[$i]['signal-strength-ch1'].")";
      $result['wireless-registration'][$i]['ccq'] = "tx: ".$ARRAY[$i]['tx-ccq'].", rx: ".$ARRAY[$i]['rx-ccq'];
      $result['wireless-registration'][$i]['rx rate'] = $ARRAY[$i]['rx-rate'];
      $result['wireless-registration'][$i]['tx rate'] = $ARRAY[$i]['tx-rate'];
      $bytes = explode(",", $ARRAY[$i]['bytes']);
      $result['wireless-registration'][$i]['bytes'] = formatBytes($bytes[0])." / ".formatBytes($bytes[1]);
      $result['wireless-registration'][$i]['interface'] = $ARRAY[$i]['interface'];
      $result['wireless-registration'][$i]['mac address'] = $ARRAY[$i]['mac-address'];
    }
    $API->write("/ip/address/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['ip-address'][$i]['address'] = $ARRAY[$i]['address'];
      $result['ip-address'][$i]['network'] = $ARRAY[$i]['network'];
      $result['ip-address'][$i]['interface'] = $ARRAY[$i]['interface'];
      $result['ip-address'][$i]['actual-interface'] = $ARRAY[$i]['actual-interface'];
      $result['ip-address'][$i]['disabled'] = $ARRAY[$i]['disabled'];
      $result['ip-address'][$i]['comment'] = $ARRAY[$i]['comment'];
    }
    $API->write("/ip/route/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['ip-route'][$i]['dst address'] = $ARRAY[$i]['dst-address'];
      $result['ip-route'][$i]['gateway'] = $ARRAY[$i]['gateway'];
      $result['ip-route'][$i]['active'] = $ARRAY[$i]['active'];
      $result['ip-route'][$i]['gateway status'] = $ARRAY[$i]['gateway-status'];
      $result['ip-route'][$i]['check'] = $ARRAY[$i]['check-gateway'];
      $result['ip-route'][$i]['distance'] = $ARRAY[$i]['distance'];
      $result['ip-route'][$i]['scope'] = $ARRAY[$i]['scope'];
      $result['ip-route'][$i]['target scope'] = $ARRAY[$i]['target-scope'];
      $result['ip-route'][$i]['routing mark'] = $ARRAY[$i]['routing-mark'];
      $result['ip-route'][$i]['static'] = $ARRAY[$i]['static'];
      $result['ip-route'][$i]['disabled'] = $ARRAY[$i]['disabled'];
    }
    $API->write("/ip/neighbor/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['ip-neighbor'][$i]['interface'] = $ARRAY[$i]['interface'];
      $result['ip-neighbor'][$i]['address'] = $ARRAY[$i]['address'];
      $result['ip-neighbor'][$i]['mac address'] = $ARRAY[$i]['mac-address'];
      $result['ip-neighbor'][$i]['identity'] = $ARRAY[$i]['identity'];
      $result['ip-neighbor'][$i]['platform'] = $ARRAY[$i]['platform'];
      $result['ip-neighbor'][$i]['uptime'] = $ARRAY[$i]['uptime'];
      $result['ip-neighbor'][$i]['board'] = $ARRAY[$i]['board'];
      $result['ip-neighbor'][$i]['interface name'] = $ARRAY[$i]['interface-name'];
    }
    $API->write("/ip/dhcp-server/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['dhcp-server'][$i]['name'] = $ARRAY[$i]['name'];
      $result['dhcp-server'][$i]['interface'] = $ARRAY[$i]['interface'];
      $result['dhcp-server'][$i]['lease time'] = $ARRAY[$i]['lease-time'];
      $result['dhcp-server'][$i]['address pool'] = $ARRAY[$i]['address-pool'];
      $result['dhcp-server'][$i]['disabled'] = $ARRAY[$i]['disabled'];
      $result['dhcp-server'][$i]['lease script'] = $ARRAY[$i]['lease-script'];
    }
    $API->write("/ip/dhcp-server/lease/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['dhcp-lease'][$i]['address'] = $ARRAY[$i]['address'];
      $result['dhcp-lease'][$i]['status'] = $ARRAY[$i]['status'];
      $result['dhcp-lease'][$i]['server'] = $ARRAY[$i]['server'];
      $result['dhcp-lease'][$i]['host name'] = $ARRAY[$i]['host-name'];
      $result['dhcp-lease'][$i]['mac address'] = $ARRAY[$i]['mac-address'];
      $result['dhcp-lease'][$i]['comment'] = $ARRAY[$i]['comment'];
      $result['dhcp-lease'][$i]['expires after'] = $ARRAY[$i]['expires-after'];
      $result['dhcp-lease'][$i]['client id'] = $ARRAY[$i]['client-id'];
      $result['dhcp-lease'][$i]['active address'] = $ARRAY[$i]['active-address'];
      $result['dhcp-lease'][$i]['active mac address'] = $ARRAY[$i]['active-mac-address'];
      $result['dhcp-lease'][$i]['dynamic'] = $ARRAY[$i]['dynamic'];
      $result['dhcp-lease'][$i]['disabled'] = $ARRAY[$i]['disabled'];
    }
    $API->write('/queue/simple/print',true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['queue-simple'][$i]['name'] = $ARRAY[$i]['name'];
      $result['queue-simple'][$i]['target'] = $ARRAY[$i]['target'];
      $result['queue-simple'][$i]['parent'] = $ARRAY[$i]['parent'];
      $result['queue-simple'][$i]['rate'] = formatBytes10($ARRAY[$i]['rate']);
      $result['queue-simple'][$i]['limit-at'] = formatBytes10($ARRAY[$i]['limit-at']);
      $result['queue-simple'][$i]['max-limit'] = formatBytes10($ARRAY[$i]['max-limit']);
      $result['queue-simple'][$i]['burst-limit'] = formatBytes10($ARRAY[$i]['burst-limit']);
      $result['queue-simple'][$i]['burst-threshold'] = formatBytes10($ARRAY[$i]['burst-threshold']);
      $result['queue-simple'][$i]['burst-time'] = $ARRAY[$i]['burst-time'];
      $result['queue-simple'][$i]['comment'] = $ARRAY[$i]['comment'];
      $result['queue-simple'][$i]['packet-marks'] = $ARRAY[$i]['packet-marks'];
      $result['queue-simple'][$i]['priority'] = $ARRAY[$i]['priority'];
      $result['queue-simple'][$i]['bytes'] = formatBytes10($ARRAY[$i]['bytes'],2,"MB",1024);
      $result['queue-simple'][$i]['packets'] = $ARRAY[$i]['packets'];
      $result['queue-simple'][$i]['dynamic'] = $ARRAY[$i]['dynamic'];
      $result['queue-simple'][$i]['disabled'] = $ARRAY[$i]['disabled'];
      $result['queue-simple'][$i]['queue'] = $ARRAY[$i]['queue'];
    }
    $API->write('/queue/tree/print',true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    for ($i=0; $i < count($ARRAY); $i++) {
      $result['queue-tree'][$i]['name'] = $ARRAY[$i]['name'];
      $result['queue-tree'][$i]['parent'] = $ARRAY[$i]['parent'];
      $result['queue-tree'][$i]['packet-mark'] = $ARRAY[$i]['packet-mark'];
      $result['queue-tree'][$i]['limit-at'] = formatBytes10($ARRAY[$i]['limit-at']);
      $result['queue-tree'][$i]['max-limit'] = formatBytes10($ARRAY[$i]['max-limit']);
      $result['queue-tree'][$i]['burst-limit'] = formatBytes10($ARRAY[$i]['burst-limit']);
      $result['queue-tree'][$i]['burst-threshold'] = formatBytes10($ARRAY[$i]['burst-threshold']);
      $result['queue-tree'][$i]['burst-time'] = $ARRAY[$i]['burst-time'];
      $result['queue-tree'][$i]['priority'] = $ARRAY[$i]['priority'];
      $result['queue-tree'][$i]['bytes'] = formatBytes10($ARRAY[$i]['bytes'],2,"MB",1024);
      $result['queue-tree'][$i]['packets'] = $ARRAY[$i]['packets'];
      $result['queue-tree'][$i]['rate'] = $ARRAY[$i]['rate'];
      $result['queue-tree'][$i]['packet-rate'] = $ARRAY[$i]['packet-rate'];
      $result['queue-tree'][$i]['bucket-size'] = $ARRAY[$i]['bucket-size'];
      $result['queue-tree'][$i]['disabled'] = $ARRAY[$i]['disabled'];
    }
    $API->write("/log/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    if ($_GET['limitlog']) {
      $limitlog = $_GET['limitlog'];
    } else {
      $limitlog = count($ARRAY);
    }
    for ($i=$limitlog-1; $i >= 0; $i--) {
      $result['log'][count($ARRAY)-$i]['time'] = $ARRAY[$i]['time'];
      $result['log'][count($ARRAY)-$i]['topics'] = $ARRAY[$i]['topics'];
      $result['log'][count($ARRAY)-$i]['message'] = $ARRAY[$i]['message'];
    }
    $API->write("/snmp/print",true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['snmp']['enabled'] = $ARRAY[0]['enabled'];
  } else {
    $report = ["status" => "failed"];
    $report[] = ["error" => true, "message" => "Failed to connect device"];
    echo json_encode($report);
    exit;
  }
  $API->disconnect();
  if ($apiconnection) {
    $out = ["status" => "success"];
    $out[] = $result;
    echo json_encode($out);
    exit;
  }
}
?>
