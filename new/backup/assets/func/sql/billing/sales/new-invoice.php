<?php
if (isset($_GET['qs']) && $_GET['qs'] == "create-single-invoice") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["member", "identity", "date", "duedate", "item", "total", "subtotal"];
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
  // Buat/masukan data member baru dengan fungsi SqlQuInsert
  if ($status = true) {
    $date = $_POST['date'];
    $payterm = $_POST['duedate'];
    $duedate = date('Y-m-d', strtotime($date. " + $payterm days"));
    $insert = SqlQuInsert("wavenet.tb_invoice", [
      "identity" => $_POST['identity'],
      "member" => $_POST['member'],
      "item" => $_POST['item'],
      "date" => $_POST['date'],
      "duedate" => $duedate,
      "total" => $_POST['total'],
      "subtotal" => $_POST['subtotal'],
      "shipping" => $_POST['shipping'],
      "tax" => $_POST['tax'],
      "notes" => $_POST['notes']
    ]);
    if ($insert['status'] === "success") {
      $member = $_POST['member'];
      $insertid = $insert[0]['id'];
      InputLog($_SESSION['name'],"billing-sales","New invoice [#$insertid] for members [$member] successfully created");
    }
    echo json_encode($insert);
    exit;
  }
}

if (isset($_GET['qs']) && $_GET['qs'] == "create-multi-invoice") {
  $status = true;
  // Cek apakah ada input kosong untuk kolom yang dibutuhkan
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["identity", "date", "duedate"];
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
  // Buat/masukan data member baru dengan fungsi SqlQuInsert
  if ($status = true) {
    $count = 0;
    $payterm = $_POST['duedate'];
    $duedate = date('Y-m-d', strtotime($date. " + $payterm days"));
    $discount = $_POST['discount'];
    $qty = $_POST['qty'];
    $datatable = $_POST['dataallinv'];

    foreach ($datatable as $key) {
      $userid = $key[0];
      $name = $key[1];
      $service = $key[3];
      $price = angka($key[4]);
      $subtotal = angka($key[5]);
      $total = angka($key[6]);
      $item = [];
      $item[] = [
        "name" => "$service",
        "price" => "$price",
        "qty" => "$qty",
        "discount" => "$discount",
        "totalprice" => "$subtotal",
      ];
      $insert = SqlQuInsert("wavenet.tb_invoice", [
        "identity" => $_POST['identity'],
        "member" => $userid,
        "item" => json_encode($item),
        "date" => $_POST['date'],
        "duedate" => $duedate,
        "total" => $total,
        "subtotal" => $subtotal,
        "shipping" => $_POST['shipping'],
        "tax" => $_POST['tax'],
        "notes" => $_POST['notes']
      ]);
      if ($insert['status'] === "success") {
        $sesname = $_SESSION['name'];
        $insertid = $insert[0]['id'];
        InputLog("$sesname","billing-sales","New invoice [#$insertid] for members [$userid] successfully created");
        $count++;
      }
    }
    if ($count === count($datatable)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "success" => $count, "total" => count($datatable) ];
      echo json_encode($report);
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => "failed input data", "success" => $count, "total" => count($datatable) ];
      echo json_encode($report);
    }
  }


}
?>
