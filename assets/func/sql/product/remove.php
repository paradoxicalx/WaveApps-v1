<?php
if (isset($_GET['r'])) {
  if ($_GET['r'] === "remove") {
    $id = $_POST['id'];
    $count = 0;
    for ($i = 0; $i < count($id); $i++) {
      $update = SqlQuUpdate("wavenet.tb_product", ["deleted" => 1], "id", $id[$i]);
      if ($update['status'] == "success") {
        $pdname = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `id` = ".$id[$i]);
        InputLog($_SESSION['name'],"product","Product removed [".$pdname[0]['name']."]");
        $count++;
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    }
  }

  if ($_GET['r'] === "remove-data") {
    $id = $_POST['id'];
    $data = $_POST['data'];
    $count = 0;
    if ($data == "Price") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_product", ["price" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $pdname = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"product","Price data removed [".$pdname[0]['name']."]");
          $count++;
        }
      }
    }
    if ($data == "Radius") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_product", ["rgroup" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $pdname = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"product","Radius data removed [".$pdname[0]['name']."]");
          $count++;
        }
      }
    }
    if ($data == "Number/Code") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_product", ["number" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $pdname = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"product","Number data removed [".$pdname[0]['name']."]");
          $count++;
        }
      }
    }
    if ($data == "Description") {
      for ($i = 0; $i < count($id); $i++) {
        $update = SqlQuUpdate("wavenet.tb_product", ["description" => ""], "id", $id[$i]);
        if ($update['status'] == "success") {
          $pdname = sqlQuAssoc("SELECT * FROM wavenet.tb_product WHERE `id` = ".$id[$i]);
          InputLog($_SESSION['name'],"product","Description data removed [".$pdname[0]['name']."]");
          $count++;
        }
      }
    }
    if ($count === count($id)) {
      $report = ["status" => "success"];
      $report[] = ["error" => false, "data" => "$data Removed" ];
      echo json_encode($report);
      exit;
    } else {
      $report = ["status" => "error"];
      $report[] = ["error" => true, "count" => $count, "data" => $id ];
      echo json_encode($report);
      exit;
    }
  }
}
?>
