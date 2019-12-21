<?php
// echo $_SERVER['DOCUMENT_ROOT'];

 exec("ping -c 1 " . $ip . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
  $result = substr($ping_time[0], strpos($ping_time[0], "=")+1);
print_r($result);