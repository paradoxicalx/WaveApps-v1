<?php
if (isset($_GET['qip'])) {
  if ($_GET['qip'] === "all") {
    $result = [];
    $query = sqlQuAssoc("SELECT * FROM wavenet.tb_ipmaster");
    foreach ($query as $key) {
      $id = $key['id'];
      $subnet = $key['subnet'];
      $broadcast = $key['broadcast'];
      $netmask = $key['netmask'];
      $cidr = netmask2cidr(long2ip($netmask));

      $countip = count(sqlQuAssoc("SELECT * FROM wavenet.tb_iplist WHERE type = 'host' AND master = $id"));
      $countuse = count(sqlQuAssoc("SELECT * FROM wavenet.tb_iplist WHERE type = 'host' AND master = $id AND used = '1'"));
      $percent = $countuse/$countip*100;

      $ip = long2ip($subnet)."/".$cidr;

      if ($percent == 100) {
        $clrprs = "danger";
      } elseif ($percent >= 50) {
        $clrprs = "warning";
      } elseif ($percent < 50) {
        $clrprs = "success";
      }

      $graph = "$ip<span class='pull-right label label-$clrprs'>$countuse/$countip</span>
                <div class='progress progress-xxs'>
                  <div class='progress-bar progress-bar-$clrprs progress-bar-striped' style='width: $percent%'></div>
                </div>";

      $result[] = [
                    $id, $ip, $key['identity'], $key['usage'], $key['notes'], $graph
                  ];
    }
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}
?>
