<?php
  if (file_exists("../config/conn.php")) {
    require '../config/conn.php';
  } elseif (file_exists("../../config/conn.php")) {
    require '../../config/conn.php';
  }
  // Fungsi jalankan query sql
  function sqlQu($query) {
    global $link;
    $result= mysqli_query($link, $query);
    $fetch = mysqli_fetch_all($result);
    return $fetch;
  }
  // Fungsi jalankan query sql dan return ke aaray asssoc
  function sqlQuAssoc($query) {
    global $link;
    $result= mysqli_query($link, $query);
    $fetch = mysqli_fetch_all($result,MYSQLI_ASSOC);
    return $fetch;
  }
  // Fungsi Cek data yang sama dengan yang telah ada dalam tabel sql
  function SqlQuDuplicate($table, $array) {
    global $link;
    $duplicate = false;
    $report = ["status" => "duplicate"];
    for ($i = 0; $i < count($array); $i++) {
      $key = array_keys($array)[$i];
      $query = "SELECT * FROM $table WHERE `$key` = '$array[$key]'";
      $result = mysqli_query($link, $query);
      if (mysqli_num_rows($result) > 0){
        $duplicate = true;
        $report[] = ["error" => "Duplicate Data, Choose Another One", "col" => $key];
      }
    }
    if ($duplicate === true) {
      return $report;
    }
  }
  //Fungsi Insert/masukan data baru pada table sql
  function SqlQuInsert($table, $array) {
    global $link;
    $col;
    for ($i = 0; $i < count($array); $i++) {
      $col .= "`".array_keys($array)[$i]."`";
      if ($i < count($array)-1) {
        $col .= ",";
      }
    }
    $value;
    for ($i = 0; $i < count($array); $i++) {
      $value .= "'".$array[array_keys($array)[$i]]."'";
      if ($i < count($array)-1) {
        $value .= ",";
      }
    }
    $query = "INSERT INTO $table ($col) VALUES ($value)";
    if ($link->query($query) === TRUE) {
      $lastid = mysqli_insert_id($link);
      $data = sqlQuAssoc("SELECT * FROM $table WHERE id = $lastid");
      $report = ["status" => "success"];
      $report[] = ["error" => false, "data" => $data, "id" => $lastid];
      return $report;
    } else {
      $report = ["status" => "failed"];
      $report[] = ["error" => "Failed add new data !!" ];
      return $report;
    }
  }
  // Fungsi Update data pada sql
  function SqlQuUpdate($table, $array, $key, $value) {
    global $link;
    $data;
    for ($i = 0; $i < count($array); $i++) {
      $data .= "`".array_keys($array)[$i]."` = '".$array[array_keys($array)[$i]]."'";
      if ($i < count($array)-1) {
        $data .= ",";
      }
    }
    $query = "UPDATE $table SET $data WHERE `$key` = '$value'";
    if ($link->query($query) === TRUE) {
      $data = sqlQuAssoc("SELECT * FROM $table WHERE $key = $value");
      $report = ["status" => "success"];
      $report[] = ["error" => false, "data" => $data];
      return $report;
    } else {
      $report = ["status" => "failed"];
      $report[] = ["error" => "Failed Update Data !!", "query" => $query];
      return $report;
    }
  }
  // Fungsi Remove data pada sql
  function SqlQuRemove($table, $key, $value) {
    global $link;
    $query = "DELETE FROM $table WHERE `$key`='$value'";
    if ($link->query($query) === TRUE) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "query" => $query];
      return $report;
    } else {
      $report = ["status" => "failed"];
      $report[] = ["error" => "Failed Remove Data !!", "query" => $query];
      return $report;
    }
  }
  // Fungsi menambahkan log dalam table tb_log
  function InputLog($name, $type, $message) {
    global $link;
    $query = "INSERT INTO wavenet.tb_log (`sesname`, `type`, `message`) VALUES ('$name', '$type', '$message')";
    if ($link->query($query) === TRUE) {
      $status = "ok";
    } else {
      $status = "error";
    }
  }
  // Fungsi menambahkan log transaksi ke table translog.
  function InputTransLog($account1, $account2, $transid, $type, $description, $amount) {
    global $link;
    $x = mysqli_query($link, "SELECT SUM(balance) total FROM wavenet.tb_account WHERE deleted = '0'");
    $rx = mysqli_fetch_all($x,MYSQLI_ASSOC);
    $lastbal = $rx[0]['total'];

    $y = mysqli_query($link, "SELECT * FROM wavenet.tb_account WHERE id = '$account1'");
    $ry =  mysqli_fetch_all($y,MYSQLI_ASSOC);
    $acclastbal1 = $ry[0]['balance'];
    $accname1 = $ry[0]['name'];

    $z = mysqli_query($link, "SELECT * FROM wavenet.tb_account WHERE id = '$account2'");
    $rz =  mysqli_fetch_all($z,MYSQLI_ASSOC);
    $acclastbal2 = $rz[0]['balance'];
    $accname2 = $rz[0]['name'];

    $insto = "INSERT INTO wavenet.tb_translog (`account`, `transid` ,`type`, `description`, `amount`, `accbal`, `allbal`) VALUES ";

    if ($type === "newacc") {
      $query = "$insto ('$account1', '0', '$type', '[New Account] $accname1', '$amount', '$acclastbal1', '$lastbal')";
    } elseif ($type === 'wallet') {
      // code
    } elseif ($type === 'sales') {
      $query = "$insto ('$account1', '$transid', '$type', '$description', '$amount', '$acclastbal1', '$lastbal')";
    } elseif ($type === 'deposit') {
      // code
    } elseif ($type === 'transfer') {
      $query1 = "$insto ('$account1', '$transid', '$type', '[send] - $description', '$amount', '$acclastbal1', '$lastbal')";
      $query2 = "$insto ('$account2', '$transid', '$type', '[receive] - $description', '$amount', '$acclastbal2', '$lastbal')";
    }

    if ($type !== 'transfer') {
      if ($link->query($query) === TRUE) {
        $report = ["status" => "success"];
        return $report;
      } else {
        $report = ["status" => "failed"];
        return $report;
      }
    } else {
      if ($link->query($query1) === TRUE && $link->query($query2) === TRUE) {
        $report = ["status" => "success"];
        return $report;
      } else {
        $report = ["status" => "failed"];
        return $report;
      }
    }
  }


?>
