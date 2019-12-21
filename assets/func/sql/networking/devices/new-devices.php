<?php
if (isset($_GET['n']) && $_GET['n'] === "devices") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "catagory", "ip", "area"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data)) {
      $status = false;
      $this_empty[] = ["error" => "Required Field", "col" => $cek_empty_field[$i]];
    }
  }
  if ($status === false) {
    echo json_encode($this_empty);
    exit;
  }
  // Cek apakah ada data yang sama dengan yang telah ada dalam table sql
  // Gunakan fungsi SqlQuDuplicate
  $cek_duplicate_data =
  SqlQuDuplicate("wavenet.tb_devices",
  [
    "name" => $_POST['name'],
    "ip" => $_POST['ip']
  ]);
  if ($cek_duplicate_data['status'] === "duplicate") {
    $status = false;
    echo json_encode($cek_duplicate_data);
    exit;
  }
  // jika API enable cek dan dapatkan data router
  if ($_POST['useapi'] === "true") {
    $API = new routeros_api();
    $API->debug = false;
    if ($API->connect($_POST['ip'] , $_POST['apiname'] , $_POST['apipass'], 8728)) {
      $API->write("/system/resource/print",true);
  		$READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $api_result[] = $ARRAY[0];

      $API->write("/system/identity/print",true);
  		$READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $api_result[] = $ARRAY[0];

      $API->write("/system/clock/print",true);
      $READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $api_result[] = $ARRAY[0];

      $API->write("/interface/print",false);
      $API->write("?default-name=ether1",false);
      $API->write("=.proplist=bytes-in.oid,bytes-out.oid",true);
      $READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $api_result['interface'] = $ARRAY[0];

      $API->write("/system/resource/print",false);
      $API->write("=.proplist=used-memory.oid",true);
      $READ = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $api_result['memory-used'] = $ARRAY[0];

      for ($i=0; $i < $api_result[0]['cpu-count']; $i++) {
        $cpu[$i] = "cpu$i";
      }

      $oid["bytes-in"] = ltrim($api_result['interface']['bytes-in.oid'], '.');
      $oid["bytes-out"] = ltrim($api_result['interface']['bytes-out.oid'], '.');
      $oid["used-memory"] = ltrim($api_result['memory-used']['used-memory.oid'], '.');
      $oid["uptime"] = "0";
      $oid["voltage"] = "0";
      $oid["temperature"] = "0";
      $oid["cpu"] = $cpu;

      $oid_json = json_encode($oid);

    } else {
      $status = false;
      $apifail = ["status" => "wrong_api"];
      $apifail[] = ["error" => "Wrong API username or password !!", "col" => "useapi"];
      echo json_encode($apifail);
      exit;
    }
    $API->disconnect();

    $insert_oid = SqlQuInsert("wavenet.tb_devices_oid",
      [
        "router-name" => $api_result[1]['name'],
        "timezone" => $api_result[2]['time-zone-name'],
        "router-version" => $api_result[0]['version'],
        "total-memory" =>  $api_result[0]['total-memory'],
        "cpu" => $api_result[0]['cpu'],
        "cpu-count" => $api_result[0]['cpu-count'],
        "cpu-frequency" => $api_result[0]['cpu-frequency'],
        "total-hdd" => $api_result[0]['total-hdd-space'],
        "free-hdd" => $api_result[0]['free-hdd-space'],
        "architecture-name" => $api_result[0]['architecture-name'],
        "board-name" => $api_result[0]['board-name'],
        "oid" => $oid_json,
      ]);
    $insert_oid_id = $insert_oid[0]['id'];
  }
  // Buat/masukan data devices baru dengan fungsi SqlQuInsert
  if ($status = true) {
    $insert = SqlQuInsert("wavenet.tb_devices",
      [
        "name" => $_POST['name'],
        "oid" => $insert_oid_id,
        "area" => $_POST['area'],
        "type" => $_POST['type'],
        "model" => $_POST['model'],
        "catagory" => $_POST['catagory'],
        "ip" => ip2long($_POST['ip']),
        "member" => $_POST['member'],
        "useapi" => $_POST['useapi'],
        "apiname" => $_POST['apiname'],
        "apipass" => $_POST['apipass'],
        "autocheck" => $_POST['autocheck']
      ]);
    if ($insert['status'] == "success") {
      InputLog($_SESSION['name'],"networking","New device added [".$_POST['name']."]");
      $insert_id = $insert[0]['id'];
      SqlQuUpdate("wavenet.tb_iplist",
        [
          "useby" => "devices",
          "used" => 1,
          "infid" => $insert_id
        ],
        "ipaddress", ip2long($_POST['ip']));
    }
    echo json_encode($insert);
    exit;
  }
}
?>
