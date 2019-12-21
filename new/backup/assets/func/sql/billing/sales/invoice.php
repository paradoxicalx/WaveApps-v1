<?php
if (isset($_GET['qs']) && $_GET['qs'] == "invoice") {
  $result = [];
  $query =  sqlQuAssoc("SELECT * FROM wavenet.tb_invoice WHERE deleted = '0'");
  foreach ($query as $key) {
    $m = $key['member'];
    $member = sqlQuAssoc("SELECT name FROM wavenet.tb_user WHERE id = '$m'");
    $a = $key['payto'];
    $akun = sqlQuAssoc("SELECT name FROM wavenet.tb_account WHERE id = '$a'");
    $result[] = [
      $key['deleted'],
      $key['status'],
      $key['id'],
      $member[0]['name'],
      rupiah($key['total']),
      $key['identity'],
      $key['date'],
      $key['duedate'],
      $key['datepaid'],
      $akun[0]['name'],
      $key['paymentmethod'],
      $key['notes']
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

if (isset($_GET['qs']) && $_GET['qs'] == "get-member-service") {
  $id = $_POST['member'];
  $query =  sqlQuAssoc("SELECT username,groupname FROM radius.radusergroup WHERE guid = $id");
  $out = json_encode($query);
  echo $out;
  exit;
}

if (isset($_GET['qs']) && $_GET['qs'] == "add-service-used") {
  $result = [];
  $service = $_POST['service'];
  $query =  sqlQuAssoc("SELECT name,price FROM wavenet.tb_product WHERE rgroup = '$service'");
  if ($query) {
    $out = json_encode($query);
  } else {
    $result[] = [
      "name" => "$service",
      "price" => "0"
    ];
    $out = json_encode($result);
  }
  echo $out;
  exit;
}

if (isset($_GET['qs']) && $_GET['qs'] == "multi-member-data") {
  $result = [];
  $mgroup = $_GET['mgroup'];
  $radgroup = $_GET['radgroup'];
  if ($mgroup === "all" and $radgroup === "all") {
    $more = "";
  } elseif ($mgroup !== "all" and $radgroup === "all") {
    $more = "AND w.group = '$mgroup'";
  } elseif ($mgroup === "all" and $radgroup !== "all") {
    $more = "AND g.groupname = '$radgroup'";
  } elseif ($mgroup !== "all" and $radgroup !== "all") {
    $more = "AND w.group = '$mgroup' AND g.groupname = '$radgroup'";
  }
  $query =  sqlQuAssoc("SELECT g.guid, g.groupname, w.name, w.group, g.id
                        FROM radius.radusergroup AS g, wavenet.tb_user AS w
                        WHERE w.deleted = '0' AND g.guid = w.id $more"
                      );

  foreach ($query as $key) {
    $membername = $key['name'];
    $radgroupid = $key['id'];
    $memberid = $key['guid'];
    $radgq = sqlQuAssoc("SELECT username,groupname FROM radius.radusergroup WHERE id = '$radgroupid'");
    $radname = $radgq[0]['username'];
    $service = $radgq[0]['groupname'];

    $prices = sqlQuAssoc("SELECT price FROM wavenet.tb_product WHERE rgroup = '$service'");;
    $price = rupiah($prices[0]['price']);

    $result[] = [
      $memberid,
      $membername,
      $radname,
      $service,
      $price,
      $price,
      $price,
      ""
    ];
  }
  $out = json_encode(["data" => $result ]);
  echo $out;
  exit;
}

?>
