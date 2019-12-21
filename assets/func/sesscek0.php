<?php
  session_start();

  $thison ="cek-seasson";
  $date = new DateTime();
  $currenttime = $date->getTimestamp();

  if (file_exists("../config/conn.php")) {
    require '../config/conn.php';
    $dologin = "../config/dologin.php";
  } elseif (file_exists("../../config/conn.php")) {
    require '../../config/conn.php';
    $dologin = "../../config/dologin.php";
  } else {
    exit;
  }

  if (isset($_SESSION['uniqid']) or isset($_COOKIE['sesid'])) {
    if (isset($_SESSION['uniqid'])) {
      $id = $_SESSION['uniqid'];
    } elseif (isset($_COOKIE['sesid'])) {
      $id = $_COOKIE['sesid'];
    }
    $result =  mysqli_query($link, "SELECT * FROM tb_user WHERE sessionid = '$id'");
    $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
    $isi = $row[0];
    $maxtime = $isi['sessiontime']+3600;
    if ($currenttime < $maxtime && $isi['group'] === "admin") {
      $logedin = true;
      for ($i=0; $i <= count($row[0]) ; $i++) {
        $_SESSION[array_keys($isi)[$i]] = $isi[array_keys($isi)[$i]];
      }
      unset($_SESSION["password"]);
      if (!isset($isi['image']) or $isi['image'] == "" or empty($isi['image'])) {
        $_SESSION['avatar'] = "https://api.adorable.io/avatars/128/".$row['id'];
      } else {
        $_SESSION['avatar'] = "$weburl/image/userimg/".$isi['image'];
      }
    } elseif ($currenttime < $isi['validtime'] && $isi['group'] === "admin") {
      $logedin = true;
    } else {
      $_SESSION = [];
      unset($_SESSION);
      session_unset();
      session_destroy();
      setcookie('sesid', '', time()-3600);
      unset($_COOKIE['sesid']);
      header("Location: ".$id.".php");
      $logedin = false;
    }
  } else {
    $logedin = false;
  }
  unset($_SESSION['destroyed']);

?>


<?php if ($logedin === false) : ?>
  <script type="text/javascript">
    $('#modal-login').modal('show');
    $('#login').on('click', function() {
      gologin ("login");
    });
    function gologin (thison){
      $.post("<?= $dologin ; ?>", {
        thison: "login",
        login: $("#login").val(),
        username: $("#login-username").val(),
        password: $("#login-password").val(),
        fingerprint: fingerprint,
        remember: document.getElementById("login-rememberme").checked
      },
      function(data) {
        if (data === "admin login") {
          $('#modal-login').find('.modal-title').text("Login Success!").removeClass('text-red').addClass('text-green');
          $('#modal-login').modal('hide');
        } else {
          $('#modal-login').find('.modal-title').text("Username or Password incorrect!").removeClass('text-green').addClass('text-red');
        }
      });
    }
  </script>
<?php
  session_start();
  $_SESSION = [];
  unset($_SESSION);
  session_unset();
  session_destroy();
  setcookie('sesid', '', time()-3600);
  unset($_COOKIE['sesid']);
  exit;
  endif
?>

<?php
  if ($logedin === true) {
    $id = $_SESSION['uniqid'];
    $update = "UPDATE wavenet.tb_user SET `sessiontime`='$currenttime' WHERE `sessionid` = '$id'";
    $accbal = mysqli_query($link, "SELECT balance FROM wavenet.tb_account WHERE `memberlink` =".$_SESSION['id']);
    if ($link->query($update) === TRUE) {
      $logedin = true;
    }
    if (mysqli_num_rows($accbal) === 1) {
      $row = mysqli_fetch_all($accbal,MYSQLI_ASSOC);
      $balance = $row[0]['balance'];
      $_SESSION['accbal'] = $balance;
    } else {
      unset($_SESSION['accbal']);
    }
    if ($isi['validtime'] >= $currenttime) {
      $tick = $isi['validtime'];
    } elseif ($isi['sessiontime']+3600 >= $currenttime) {
      $tick = $isi['sessiontime']+3600;
    }
    $_SESSION['ticktime'] = $tick-$currenttime;
    // echo "string";
  }
?>
