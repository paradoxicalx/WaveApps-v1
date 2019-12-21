<?php
require "../../assets/func/sqlQu.php";

$group = $_GET['q'];

if (empty($group)) {
  $query = "SELECT * FROM wavenet.tb_test";
} else {
  $query = "SELECT * FROM wavenet.tb_test WHERE `status` = $group";
}

$data = sqlQu($query);
$out = json_encode(["data" => $data ]);
echo $out;

?>
