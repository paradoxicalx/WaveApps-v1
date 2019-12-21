<?php
require "../../assets/func/sqlQu.php";

$query =  sqlQuAssoc("SELECT * FROM radius.radcheck");
$a = ['wave1', 'wave2', 'wave3'];
foreach ($query as $key) {
  $guid = $key['userid'];
  $username = $key['username'];
  $priority = "1";
  $groupname = $a[mt_rand(0, count($a) - 1)];
  $insert = SqlQuInsert("radius.radusergroup", [
    "guid" => $guid,
    "username" => $username,
    "groupname" => $groupname,
    "priority" => $priority
  ]);
  print_r($groupname."<br>");
}

?>
