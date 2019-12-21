<?php
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://mikrotik.id/produk.php?kategori=43");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  </body>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    var xx = $('.padding-atas-bawah-30').toArray();
    var yy = xx[0];
    var tt = $(yy).text();
    console.log(tt);
  </script>
</html>
