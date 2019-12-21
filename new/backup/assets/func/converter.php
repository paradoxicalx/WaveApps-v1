<?php
function netmask2cidr($netmask){
   $bits = 0;
   $netmask = explode(".", $netmask);

   foreach($netmask as $octect)
   $bits += strlen(str_replace("0", "", decbin($octect)));

   return $bits;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function convert_seconds($seconds)
 {
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  return $dt1->diff($dt2)->format('%a Day %H:%I:%S');
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
?>
