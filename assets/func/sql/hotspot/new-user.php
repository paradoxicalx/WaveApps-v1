<?php
if (isset($_GET['newuser'])) {
  $id = $_GET['id'];
  $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = '$id' ");

  $ipRouteros = long2ip($device[0]['ip']);
  $Username= $device[0]['apiname'];
  $Pass= $device[0]['apipass'];
  $port=8728;

  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "password"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data)) {
      $status = false;
      $this_empty[] = ["error" => "Required Field", "col" => $cek_empty_field[$i]];
    }
  }
  if ($status === false) {
    echo json_encode($this_empty);
    exit;
  }

  // Cek apakah ada input kosong, jika iya hapus dari array $_POST
  // Sehingga didapatkan nya data yang akan di masukan.
  foreach ($_POST as $key => $value) {
    if (empty($value) or $value = "") {
      unset($_POST [ $key ]);
    }
  }

  $API = new routeros_api();
  $API->debug = false;
  if ($API->connect($ipRouteros , $Username , $Pass, $port)) {
    $apiconnection = true;

    $newuser = $API->comm("/ip/hotspot/user/add", $_POST);
  }
  $API->disconnect();
  if ($apiconnection) {
    if (array_key_exists("!trap", $newuser)) {
      $out = ["status" => "failed"];
      $out[] = ["error" => $newuser["!trap"][0]['message'] ];
      echo json_encode($out);
    } else {
      $out = ["status" => "success"];
      $out[] = ["error" => "false", "id" => $newuser];
      echo json_encode($out);
    }
    exit;
  } else {
    $out = ["status" => "failed"];
    $out[] = ["error" => "Unable to connect to device"];
    echo json_encode($out);
    exit;
  }
}

if (isset($_GET['newmultiuser'])) {
  $id = $_GET['id'];
  $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices WHERE `id` = '$id' ");

  $ipRouteros = long2ip($device[0]['ip']);
  $Username= $device[0]['apiname'];
  $Pass= $device[0]['apipass'];
  $port=8728;

  // Cek apakah ada input kosong
  $this_empty = ["status" => "empty"];
  $cek_empty_field = ["name", "password", "number"];
  for ($i = 0; $i < count($cek_empty_field); $i++) {
    $data = $_POST[$cek_empty_field[$i]];
    if (empty($data)) {
      $status = false;
      $this_empty[] = ["error" => "Required Field", "col" => $cek_empty_field[$i]];
    }
  }
  if ($status === false) {
    echo json_encode($this_empty);
    exit;
  }

  // Cek apakah ada input kosong, jika iya hapus dari array $_POST
  // Sehingga didapatkan nya data yang akan di masukan.
  foreach ($_POST as $key => $value) {
    if (empty($value) or $value = "") {
      unset($_POST [ $key ]);
    }
  }

  // Dapatkan variable
  if (isset($_POST['prefix'])) {
    $prefix = $_POST['prefix'];
  } else {
    $prefix = "";
  }
  $length_user = $_POST['name'];
  $length_pass = $_POST['password'];
  $number_user = $_POST['number'];

  // Hapus Variable.
  unset($_POST['name']);
  unset($_POST['password']);
  unset($_POST['prefix']);
  unset($_POST['number']);

  function genUserPass($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  $API = new routeros_api();
  $API->debug = false;
  if ($API->connect($ipRouteros , $Username , $Pass, $port)) {
    $apiconnection = true;
    $user_created = 0;

    $API->write("/ip/hotspot/print",false);
    $API->write('?name='.$_POST['server'], true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['server'] = $ARRAY;

    $API->write("/ip/hotspot/profile/print",false);
    $API->write('?name='.$result['server'][0]['profile'], true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    $result['server_profile'] = $ARRAY;

    for ($i = 0; $i < $number_user; $i++) {
      if ($user_created <= $number_user) {
        $_POST['name'] = $prefix.genUserPass($length_user);
        $_POST['password'] = genUserPass($length_pass);
        $newuser = $API->comm("/ip/hotspot/user/add", $_POST);
        if (array_key_exists("!trap", $newuser)) {
          $number_user + 1;
        } else {
          $user_created++;
          $hasil[] = ["name" => $_POST['name'], "password" => $_POST['password'], "dns" => $result['server_profile'][0]['dns-name'] ];
        }
      }
    }

    if ($number_user > $user_created) {
      $ulang = $number_user - $user_created;

      for ($i = 0; $i < $ulang; $i++) {
        if ($user_created <= $number_user) {
          $_POST['name'] = $prefix.genUserPass($length_user);
          $_POST['password'] = genUserPass($length_pass);
          $newuser = $API->comm("/ip/hotspot/user/add", $_POST);
          if (array_key_exists("!trap", $newuser)) {
            $number_user + 1;
          } else {
            $user_created++;
            $hasil[] = ["name" => $_POST['name'], "password" => $_POST['password'], "dns" => $result['server_profile'][0]['dns-name'] ];
          }
        }
      }
    }

  }
  $API->disconnect();
  if ($apiconnection) {
    $out = ["status" => "success"];
    $out[] = ["error" => "false", "user_created" => $user_created, "user_pass" => $hasil];
    echo json_encode($out);
    exit;
  } else {
    $out = ["status" => "failed"];
    $out[] = ["error" => "Unable to connect to device"];
    echo json_encode($out);
    exit;
  }

}
