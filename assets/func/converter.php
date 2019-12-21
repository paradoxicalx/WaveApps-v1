<?php
function netmask2cidr($netmask){
   $bits = 0;
   $netmask = explode(".", $netmask);

   foreach($netmask as $octect)
   $bits += strlen(str_replace("0", "", decbin($octect)));

   return $bits;
}

function formatBytes($size, $precision = 2) {
  if ($size <= 0) {
    return "";
  } else {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
  }
}

function formatBytes10($size, $precision = 2, $formatbyte="Mbps", $calc=1000) {
  if ($formatbyte === "MB") {
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
  } else {
    $suffixes = array('', 'Kbps', 'Mbps', 'Gbps', 'Tbps');
  }
  $bytes = explode("/", $size);
  if ($bytes[0] === "0") { $hasil0 = "0"; } else {
    $base0 = log($bytes[0], $calc);
    $hasil0 = round(pow($calc, $base0 - floor($base0)), $precision) .' '. $suffixes[floor($base0)];
  }
  if ($bytes[1] === "0") { $hasil1 = "0"; } else {
    $base1 = log($bytes[1], $calc);
    $hasil1 = round(pow($calc, $base1 - floor($base1)), $precision) .' '. $suffixes[floor($base1)];
  }
  if ($bytes[1]) {
    return $hasil0."/".$hasil1;
  } else {
    return $hasil0;
  }
}

function convert_seconds($seconds)
 {
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  return $dt1->diff($dt2)->format('%a Day %H:%I:%S');
  }

function secToHR($seconds, $format = '%02d:%02d:%02d') {
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;
  return sprintf($format, $hours, $minutes, $seconds);
}

function rupiah($angka){
	$hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
  	return $hasil_rupiah;
}

function angka($rupiah){
	$rupiah_str = preg_replace("/[^0-9]/", "", $rupiah);
	$rupiah_int = (int) $rupiah_str;
	return $rupiah_int;
 }

 function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
