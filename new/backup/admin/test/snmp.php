<?php
$a = snmpwalk("10.10.1.29", "public", null); 

foreach ($a as $val) {
    echo "$val\n";
}

?>
