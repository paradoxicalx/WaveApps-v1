<?php
  require "../../assets/func/sqlQu.php";

  // $wcmask = long2ip( ~ip2long($_POST['netmask']) );
  // $subnet = long2ip( ip2long($_POST['ipaddress']) & ip2long($_POST['netmask']) );
  // $bcast = long2ip( ip2long($_POST['ipaddress']) | ip2long($wcmask) );
  // $start = ip2long($subnet);
  // $end = ip2long($bcast);
  // $count = $end-$start;
  //
  // // $rangip = array_map('long2ip', range($start, $end));
  //
  // $ipexist = sqlQu("SELECT ipaddress FROM wavenet.tb_iplist");
  //
  // $report[] = [
  //   "subnet" => $start,
  //   "bcast" => $end,
  //   "count" => $count,
  //   "ipexist" => $ipexist
  // ];
  // echo json_encode($report);

  global $link;
  $x = mysqli_query($link, "SELECT SUM(balance) total FROM wavenet.tb_account WHERE deleted = '0'");
  $rx = mysqli_fetch_all($x,MYSQLI_ASSOC);
  $lastbal = $rx[0]['total'];
  print_r($rx);
  echo $lastbal;

?>
