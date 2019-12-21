<?php
if (isset($_GET['c']) && $_GET['c'] === "service") {
  $report_array = [];
  $status = true;
  // Cek apakah ada input kosong, jika iya hapus dari array $_POST
  // Sehingga didapatkan nya data yang akan di edit.
  $cek_empty_field = ["useapi", "apiname","apipass", "autocheck"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data) or $data = "") {
      unset($_POST [ $cek_empty_field[$i] ]);
    }
  }
  //Ganti format Input
  if (isset($_POST['useapi'])) {
    if ($_POST['useapi'] === "enable") {
      $_POST['useapi'] = "true";
      $this_empty = ["status" => "empty"];
      $cek_empty_field = ["apiname", "apipass"];
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
    } else {
      $_POST['useapi'] = "false";
    }
  }
  if (isset($_POST['autocheck'])) {
    if ($_POST['autocheck'] === "enable") {
      $_POST['autocheck'] = "true";
    } else {
      $_POST['autocheck'] = "false";
    }
  }
  // Update data baru dengan fungsi SqlQuUpdate
  if ($status = true) {
    $count = 0;
    $id = $_POST['id'];
    unset($_POST['id']);
    for ($i = 0; $i < count($id); $i++) {
      $devicesid = $id[$i];
      $update = SqlQuUpdate("wavenet.tb_devices", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
        $devname = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = ".$id[$i]);
        InputLog($_SESSION['name'],"networking","Device service changed [".$devname[0]['name']."]");
        if ($_POST['autocheck'] === "false") {
          SqlQuUpdate("wavenet.tb_devices", ["status" => "unknown"], "id", "$id[$i]");
        }
        $oid_id = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE id = '$devicesid'");
        if ($_POST['useapi'] === "false") {
          SqlQuRemove("wavenet.tb_devices_oid", "id", $oid_id[0]['oid']);
          SqlQuUpdate("wavenet.tb_devices", ["oid" => "", "apiname" => "", "apipass" => ""], "id", "$id[$i]");
        } elseif ($_POST['useapi'] === "true") {
          $API = new routeros_api();
          $API->debug = false;
          if ($API->connect(long2ip($oid_id[0]['ip']) , $_POST['apiname'] , $_POST['apipass'], 8728)) {
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
            SqlQuUpdate("wavenet.tb_devices",["useapi" => "false", "apiname" => "", "apipass" => ""], "id", "$id[$i]");
          }
          $API->disconnect();

          $cekoid = SqlQuDuplicate("wavenet.tb_devices_oid",["id" => $oid_id[0]['oid']]);
          if ($cekoid['status'] === "duplicate") {
            $update_oid = SqlQuUpdate("wavenet.tb_devices_oid",
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
            ], "id", $oid_id[0]['oid']);
          } else {
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
                "oid" => "$oid_json",
              ]);
              $insert_oid_id = $insert_oid[0]['id'];
              if ($insert_oid['status'] === "success") {
                SqlQuUpdate("wavenet.tb_devices", ["oid" => "$insert_oid_id"], "id", "$devicesid");
              }
          }

        }
        $count++;
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count, "data" => $id ];
      echo json_encode($report);
    } elseif ($count > 0 && $count < count($id)) {
      $report = ["status" => "warning"];
      $report[] = ["error" => "Can Only Update "+$count+" Data!", "count" => $count, "data" => $id ];
      echo json_encode($report);
    } elseif ($count === 0) {
      $report = ["status" => "failed"];
      $report[] = ["error" => "No Data Has Been Updated", "count" => $count, "data" => $id ];
      echo json_encode($report);
    }
  }
}
?>
