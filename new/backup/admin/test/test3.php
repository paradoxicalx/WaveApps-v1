<div id="info"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
$startMemory = memory_get_usage();
$array = range(1, 1048574);

// $ip1 = ip2long("10.0.0.0");
// $ip2 = ip2long("10.255.255.255");
// $arr = range($ip1, $ip2);
// print_r($array);

echo memory_get_usage() - $startMemory, ' bytes';
?>

<?php foreach ($array as $value) : ?>
  <script type="text/javascript">
    $('#info').html("<?= $value ; ?>");
  </script>
<?php endforeach ?>
