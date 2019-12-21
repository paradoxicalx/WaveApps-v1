<?php
$_POST['netmask'] = "255.255.255.0";
$_POST['ipaddress'] = "192.168.1.1";

$wcmask = long2ip( ~ip2long($_POST['netmask']));
$subnet = long2ip( ip2long($_POST['ipaddress']) & ip2long($_POST['netmask']) );
$bcast = long2ip( ip2long($_POST['ipaddress']) | ip2long($wcmask) );
$start = ip2long($subnet);
$end = ip2long($bcast);

$iplist = array_map('long2ip', range($start, $end) );

foreach ($iplist as $value) {
  $iplong = ip2long($value);
  echo "<br>".$iplong;
}

echo long2ip($start);
echo "<br>";
echo long2ip($end);
echo "<br>";
echo ip2long($_POST['netmask']);
?>
