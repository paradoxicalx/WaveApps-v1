<?php
  session_start();
  $thison = $_POST['thison'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $remember = $_POST['remember'];
  $fingerprint = $_POST['fingerprint'];
  $date = new DateTime();
  $currenttime = $date->getTimestamp();
  $maxlogintime = 3600;
  require 'conn.php';
  require 'conf.php';

  if ($thison === "load" && isset($_SESSION['uniqid'])) {
    $id = $_SESSION['uniqid'];
    $result =  mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE `sessionid` = '$id'");
    $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $isi = $row[0];
    $maxtime = $isi['sessiontime'] + $maxlogintime;
    if ($currenttime < $maxtime) {
      $logedin = true;
    } else {
      unset($_SESSION);
    }
  }

  if ($thison === "load" && isset($_COOKIE['sesid'])) {
     $id = $_COOKIE['sesid'];

     $result =  mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE sessionid = '$id'");
     $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
     $isi = $row[0];

     if ($currenttime < $isi['validtime'] && $isi['fingerprint'] === $fingerprint) {

       for ($i=0; $i <= count($row[0]) ; $i++) {
         $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
       }
       unset($_SESSION["password"]);

       if (!isset($isi['image']) or $isi['image'] == "" or empty($isi['image'])) {
         $_SESSION['avatar'] = "https://api.adorable.io/avatars/128/".$row['id'];
       } else {
         $_SESSION['avatar'] = "$weburl/image/userimg/".$isi['image'];
       }

       $oldsesid = $_COOKIE['sesid'];
       session_regenerate_id();
       $_SESSION['uniqid'] = session_id();
       setcookie("sesid", session_id(), time() + (86400 * 1), "/");

       $date = new DateTime();
       $sessiontime = $date->getTimestamp();
       $sessionid = $_SESSION['uniqid'];
       $update = "UPDATE wavenet.tb_user SET `sessionid`='$sessionid', `sessiontime`='$sessiontime' WHERE `sessionid` = '$oldsesid'";
       if ($link->query($update) === TRUE) {
         $logedin = true;
       } else {
         $logedin = false;
       }
     } else {
       $logedin = false;
     }
   }

  if ( $thison === "login" ) {
    $result = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
      $isi = $row[0];
      if ( password_verify($password, $row[0]['password']) ) {

        for ($i=0; $i <= count($row[0]) ; $i++) {
          $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
        }
        $_SESSION["fingerprint"] = $fingerprint;
        unset($_SESSION["password"]);

        if (!isset($isi['image']) or $isi['image'] == "" or empty($isi['image'])) {
          $_SESSION['avatar'] = "https://api.adorable.io/avatars/128/".$isi['id'];
        } else {
          $_SESSION['avatar'] = "$weburl/image/userimg/".$isi['image'];
        }

        session_regenerate_id();
        $_SESSION['uniqid'] = session_id();
        $timestamp = date('Y-m-d G:i:s');
        $date = new DateTime();
        $sessiontime = $date->getTimestamp();
        $validtime = $sessiontime+86400;
        $sessionid = $_SESSION['uniqid'];
        if ($remember === "true") {
          setcookie("sesid", session_id(), time() + (86400 * 1), "/");
          $update = "UPDATE wavenet.tb_user SET
                      `lastlogin`='$timestamp',
                      `sessionid`='$sessionid',
                      `sessiontime`='$sessiontime',
                      `validtime`='$validtime',
                      `fingerprint`= $fingerprint
                    WHERE `id` =".$_SESSION['id'];
        } else {
          setcookie("sesid", session_id(), time() - (86400 * 1), "/");
          unset($_COOKIE['sesid']);
          $update = "UPDATE wavenet.tb_user SET
                      `lastlogin`='$timestamp',
                      `sessionid`='$sessionid',
                      `sessiontime`='$sessiontime',
                      `validtime`='0',
                      `fingerprint`= $fingerprint
                    WHERE `id` =".$_SESSION['id'];
        }

        if ($link->query($update) === TRUE) {
          $logedin = true;
        } else {
          $logedin = false;
        }
      } else {
        $logedin = false;
      }
    }
  }

  if ($logedin === true) {
    echo $_SESSION['group']." login";
    exit;
  } elseif ($logedin === false){
    echo "error login";
    setcookie("sesid", session_id(), time() - (86400 * 1), "/");
    unset($_COOKIE['sesid']);
    unset($_SESSION);
    exit;
  } else {
    echo "ready login";
  }

  unset($thison);
  unset($_SESSION['destroyed']);

?>
