<?php
session_start();
if (!$its or empty($its)) { $its = "open-page"; }
$date = new DateTime();
$sessiontime = $date->getTimestamp();
$timestamp = date('Y-m-d G:i:s');

if (file_exists("config/conf.php")) {
  require 'config/conf.php';
  require 'config/conn.php';
} elseif (file_exists("../config/conf.php")) {
  require '../config/conf.php';
  require '../config/conn.php';
} elseif (file_exists("../../config/conf.php")) {
  require '../../config/conf.php';
  require '../../config/conn.php';
}

if ($its === "load") {
  $fingerprint = $_POST['fingerprint'];
  if (isset($_SESSION['sesid'])) {
    $id = $_SESSION['sesid'];
  } else {
    $id = $_COOKIE['sesid'];
  }
  $cekuser =  mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE sessionid = '$id'");
  $row = mysqli_fetch_all($cekuser,MYSQLI_ASSOC);
  $isi = $row[0];
  if ($isi['validtime'] > $sessiontime) {
    $sestime = $isi['validtime'];
  } else {
    $sestime = $isi['sessiontime']+3600;
  }
  if ($sessiontime < $sestime && $isi['fingerprint'] === $fingerprint) {
    session_regenerate_id();
    $_SESSION['sesid'] = session_id();
    setcookie("sesid", session_id(), time() + (86400 * 1), "/");
    $sessionid = $_SESSION['sesid'];
    $update = "UPDATE wavenet.tb_user SET `sessionid`='$sessionid', `sessiontime`='$sessiontime' WHERE `sessionid` = '$id'";
    if ($link->query($update) === TRUE) {
      for ($i=0; $i <= count($row[0]) ; $i++) {
        $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
      }
      AccBal($isi['id']);
      if (!isset($isi['image']) or $isi['image'] == "" or empty($isi['image'])) {
        $_SESSION['avatar'] = "https://api.adorable.io/avatars/128/".$row['id'];
      } else {
        $_SESSION['avatar'] = "$weburl/image/userimg/".$isi['image'];
      }
      $group = $isi['group'];
      $result = [ "status" => "success", "group" => "$group"];
    } else {
      ClearSession();
      $result = [ "status" => "failed", "error" => "Session Failed"];
    }
  } else {
    ClearSession();
    $result = [ "status" => "failed", "error" => "Session Timeout"];
  }
  echo json_encode($result);
} elseif ($its === "login") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $remember = $_POST['remember'];
  $fingerprint = $_POST['fingerprint'];
  $cekuser = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE username = '$username'");
  if (mysqli_num_rows($cekuser) === 1) {
    $row = mysqli_fetch_all($cekuser,MYSQLI_ASSOC);
    if ( password_verify($password, $row[0]['password']) ) {
      session_regenerate_id();
      $isi = $row[0];
      $validtime = $sessiontime+86400;
      $sessionid = session_id();

      if ($remember === "true") {
        setcookie("sesid", session_id(), time() + (86400 * 1), "/");
        $update = "UPDATE wavenet.tb_user SET
                    `lastlogin`='$timestamp',
                    `sessionid`='$sessionid',
                    `sessiontime`='$sessiontime',
                    `validtime`='$validtime',
                    `fingerprint`= $fingerprint
                  WHERE `id` =".$isi['id'];
      } else {
        setcookie("sesid", session_id(), time() - (86400 * 1), "/");
        unset($_COOKIE['sesid']);
        $update = "UPDATE wavenet.tb_user SET
                    `lastlogin`='$timestamp',
                    `sessionid`='$sessionid',
                    `sessiontime`='$sessiontime',
                    `validtime`='0',
                    `fingerprint`= $fingerprint
                  WHERE `id` =".$isi['id'];
      }
      if ($link->query($update) === TRUE) {
        for ($i=0; $i <= count($isi) ; $i++) {
          $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
        }
        AccBal($isi['id']);
        $_SESSION['sesid'] = $sessionid;
        if (!isset($isi['image']) or $isi['image'] == "" or empty($isi['image'])) {
          $_SESSION['avatar'] = "https://api.adorable.io/avatars/128/".$isi['id'];
        } else {
          $_SESSION['avatar'] = "$weburl/image/userimg/".$isi['image'];
        }
        $group = $isi['group'];
        $result = [ "status" => "success", "group" => "$group"];
      } else {
        ClearSession();
        $result = [ "status" => "failed", "error" => "Failed to connect DB"];
      }
    } else {
      ClearSession();
      $result = [ "status" => "failed", "error" => "Invalid Password !"];
    }
  } else {
    ClearSession();
    $result = [ "status" => "failed", "error" => ""];
  }
  echo json_encode($result);
} elseif ($its === "open-page") {
  $status = true;
  $sesid = $_SESSION['sesid'];
  $cekuser = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE sessionid = '$sesid'");
  $row = mysqli_fetch_all($cekuser,MYSQLI_ASSOC);
  $isi = $row[0];
  if ($isi['validtime'] > ($isi['sessiontime']+3600)) {
    $sestime = $isi['validtime'];
  } else {
    $sestime = $isi['sessiontime']+3600;
  }
  if ($sessiontime < $sestime &&
      $isi['group'] === "admin" &&
      $isi['name'] === $_SESSION['name'] &&
      $isi['username'] === $_SESSION['username']) {
        for ($i=0; $i <= count($isi) ; $i++) {
          $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
        }
        AccBal($isi['id']);
        if ($isi['validtime'] >= $sessiontime) {
          $tick = $isi['validtime'];
        } elseif ($isi['sessiontime']+3600 >= $sessiontime) {
          $tick = $isi['sessiontime']+3600;
        }
        $_SESSION['ticktime'] = $tick-$sessiontime;
  } else {
    $status = false;
  }
  if ($status === false) {
    OpenLoginModal();
    ClearSession();
    $result = [ "status" => "failed", "error" => "Authentication Failure"];
  } else {
    $result = [ "status" => "success", "error" => "Authentication Success"];
  }
  // echo json_encode($result);
} elseif ($its === "get-data") {
  // code...
} elseif ($its === "update-db") {
  // code...
} else {
  exit;
}

function ClearSession() {
  $_SESSION = [];
  unset($_SESSION);
  session_unset();
  session_destroy();
  setcookie("sesid", '', time() - (86400 * 1), "/");
  unset($_COOKIE['sesid']);
}

function AccBal($id) {
  global $link;
  $accbal = mysqli_query($link, "SELECT balance FROM wavenet.tb_account WHERE `memberlink` = $id");
  if (mysqli_num_rows($accbal) === 1) {
    $rowbal = mysqli_fetch_all($accbal,MYSQLI_ASSOC);
    $balance = $rowbal[0]['balance'];
    $_SESSION['accbal'] = $balance;
  } else {
    unset($_SESSION['accbal']);
  }
}

function OpenLoginModal() {

}

?>

<?php if ($its === "open-page" && $status === true): ?>
  <script type="text/javascript">
    $(document).ready(function(){
      if (<?= $_SESSION['accbal'] ?> > 0) {
        $('#s-saldo').text(convertToRupiah(<?= $_SESSION['accbal'] ?>));
      } else if (<?= $_SESSION['wallet'] ?>) {
        $('#s-saldo').text(convertToRupiah(<?= $_SESSION['wallet'] ?>));
      } else {
        $('#s-saldo').text(convertToRupiah(0));
      }
      if (<?= $_SESSION['ticktime'] ?>) {
        $('#ticktime').text(<?= $_SESSION['ticktime'] ?>);
      } else {
        $('#ticktime').text("0");
      }
      if (publicip) {
        $('#publicip').text("Your IP : "+publicip);
      }
    });
  </script>
<?php endif; ?>



<?php unset($its); ?>
