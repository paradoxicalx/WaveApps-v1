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
      $update = SqlQuUpdate("wavenet.tb_devices", $_POST, "id", "$id[$i]");
      if ($update['status'] == "success") {
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
