<?php
  if (isset($_GET['location'])) {
    $result = [];
    if ($_GET['location'] == "all") {
      $query_member =  sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE deleted = '0'");
      $query_maps =  sqlQuAssoc("SELECT * FROM wavenet.tb_maps WHERE deleted = '0'");
      foreach ($query_member as $key) {
        if ($key['long'] && $key['lat']) {
          $result[] = [
            floatval($key['long']),
            floatval($key['lat']),
            $key['group'],
            $key['name'],
          ];
        }
      }
      foreach ($query_maps as $key) {
        if ($key['long'] && $key['lat']) {
          $result[] = [
            floatval($key['long']),
            floatval($key['lat']),
            $key['type'],
            $key['name'],
          ];
        }
      }
    }
    if ($_GET['location'] == "ap" or $_GET['location'] == "transmitter") {
      $query_maps =  sqlQuAssoc("SELECT * FROM wavenet.tb_maps WHERE deleted = '0' AND `type` = '".$_GET['location']."'");
      foreach ($query_maps as $key) {
        if ($key['long'] && $key['lat']) {
          $result[] = [
            floatval($key['long']),
            floatval($key['lat']),
            $key['type'],
            $key['name'],
          ];
        }
      }
    }
    if ($_GET['location'] == "customer" or $_GET['location'] == "partner" or $_GET['location'] == "admin") {
      $query_member =  sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE deleted = '0' AND `group` = '".$_GET['location']."'");
      foreach ($query_member as $key) {
        if ($key['long'] && $key['lat']) {
          $result[] = [
            floatval($key['long']),
            floatval($key['lat']),
            $key['group'],
            $key['name'],
          ];
        }
      }
    }
    echo json_encode($result);
    exit;
  }

  if (isset($_GET['search'])) {
    $query_member =  sqlQuAssoc("SELECT * FROM wavenet.tb_user WHERE `deleted` = '0' AND `name` LIKE '%".$_GET['search']."%'");
    foreach ($query_member as $key) {
      if ($key['long'] && $key['lat']) {
        $result[] = [
          floatval($key['long']),
          floatval($key['lat']),
          $key['group'],
          $key['name'],
        ];
      }
    }
    echo json_encode($result);
    exit;
  }

  if (isset($_GET['list'])) {
    $query = "SELECT `id`,`name`,`type`,`long`,`lat` FROM wavenet.tb_maps WHERE deleted = '0'";
    $data = sqlQu($query);
    $out = json_encode(["data" => $data ]);
    echo $out;
    exit;
  }
?>
