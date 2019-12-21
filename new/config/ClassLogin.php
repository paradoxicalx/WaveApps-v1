<?php
Class Login{

	public function login_status(){
    global $link;
		session_start();
    $now = date("Y-m-d H:i:s");
    $result = ["status" => false];

    if (isset($_COOKIE['_php-id'])) {
      $token = $_COOKIE['_php-id'];
      $query = "SELECT * FROM wavenet.tb_loginlog WHERE `token` = '$token' AND `exp_date` > '$now' AND `stat` = '1'";
      $sellog = mysqli_query($link, $query);
      if (mysqli_num_rows($sellog) === 1) {
        $row = mysqli_fetch_all($sellog,MYSQLI_ASSOC)[0];
        $ip = long2ip($row['ipaddress']);
        $expireddb = $row['exp_date'];
        $fingerprint = $row['fingerprint'];
        $username = $row['username'];
        $string = RandomString(10);

        if ($ip == $_SERVER['REMOTE_ADDR'] && $row['useragent'] == $_SERVER['HTTP_USER_AGENT'] && $row['stat'] == '1') {
          $memberdata = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE username = '$username'");
          $rowmember = mysqli_fetch_all($memberdata,MYSQLI_ASSOC)[0];
          $userid = $rowmember['id'];
          $group = $rowmember['group'];

          for ($i=0; $i <= count($rowmember) ; $i++) {
            $_SESSION[array_keys($rowmember)[$i]] = $rowmember[array_keys($rowmember)[$i]];
          }

          $_SESSION['accbal'] = mysqli_fetch_all(mysqli_query($link, "SELECT balance FROM wavenet.tb_account WHERE memberlink = '$userid'"),MYSQLI_ASSOC)[0]['balance'];

          $newtoken = sha1($ip.$expireddb.$fingerprint.$string.microtime());
          $updatetoken = "UPDATE wavenet.tb_loginlog SET `token`='$newtoken' WHERE `token` = '$token'";
          if ($link->query($updatetoken) === TRUE) {
            setcookie("_php-id", $newtoken, intval(strtotime($expireddb)), "/");
            $_SESSION['token'] = $newtoken;
            $result = ["status" => true, "group" => $group];
          }
        }
      }
    } elseif (isset($_SESSION['token'])) {
			$token = $_SESSION['token'];
			$query = "SELECT * FROM wavenet.tb_loginlog WHERE `token` = '$token' AND `exp_date` > '$now' AND `stat` = '1'";
      $sellog = mysqli_query($link, $query);
			if (mysqli_num_rows($sellog) === 1) {
				$row = mysqli_fetch_all($sellog,MYSQLI_ASSOC)[0];
        $ip = long2ip($row['ipaddress']);
        $expireddb = $row['exp_date'];
        $fingerprint = $row['fingerprint'];
        $username = $row['username'];
        $string = RandomString(10);

				if ($ip == $_SERVER['REMOTE_ADDR'] && $row['useragent'] == $_SERVER['HTTP_USER_AGENT'] && $row['stat'] == '1') {
					$memberdata = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE username = '$username'");
          $rowmember = mysqli_fetch_all($memberdata,MYSQLI_ASSOC)[0];
          $userid = $rowmember['id'];
          $group = $rowmember['group'];

          for ($i=0; $i <= count($rowmember) ; $i++) {
            $_SESSION[array_keys($rowmember)[$i]] = $rowmember[array_keys($rowmember)[$i]];
          }

					$_SESSION['accbal'] = mysqli_fetch_all(mysqli_query($link, "SELECT balance FROM wavenet.tb_account WHERE memberlink = '$userid'"),MYSQLI_ASSOC)[0]['balance'];

					$newtoken = sha1($ip.$expireddb.$fingerprint.$string.microtime());
					$expireddb = date("Y-m-d H:i:s",strtotime("+15 minutes"));
          $updatetoken = "UPDATE wavenet.tb_loginlog SET `token`='$newtoken', `exp_date`='$expireddb' WHERE `token` = '$token'";
          if ($link->query($updatetoken) === TRUE) {
						$_SESSION['token'] = $newtoken;
            $result = ["status" => true, "group" => $group];
					}
				}
			}
    }
		if ($result['status'] === false) {
			$this->logout();
		}
    return $result;
	}


	Public function mark_fail_login($username, $fingerprint){
    global $link;
    $date = date("Y-m-d H:i:s");
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$useragent = $_SERVER['HTTP_USER_AGENT'];

    $save = "INSERT INTO wavenet.tb_loginlog (`username`, `date`, `ipaddress`, `useragent`, `fingerprint`,`stat`)
                    VALUES ('$username','$date','$ip','$useragent', '$fingerprint','0')";
    if ($link->query($save) === TRUE) {
      $result = true;
    } else {
      $result = false;
    }
		return $result;
	}


	public function check_max_login($fingerprint, $limit=5){
    global $link;
    $ip = ip2long($_SERVER['REMOTE_ADDR']);
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $select = mysqli_query($link, "SELECT * FROM wavenet.tb_loginlog
              WHERE `ipaddress` = '$ip' AND `fingerprint` = '$fingerprint' AND `useragent` = '$useragent'");
		$row = mysqli_fetch_all($select,MYSQLI_ASSOC)[0];
		$date = $row['date'];
		$allowdate = date("Y-m-d H:i:s",strtotime("+1 day"));
    if (mysqli_num_rows($select) > $limit && $date < $allowdate) {
      $result = true;
    } else {
      $result = false;
    }
    return $result;
	}


	public function login_success($username, $expired, $fingerprint){
    global $link;
    session_start();
    $date = date("Y-m-d H:i:s");
    if($expired <> 0){
			$expireddb = date("Y-m-d H:i:s",strtotime($expired));
		}
		else{
			$expireddb = date("Y-m-d H:i:s",strtotime("+15 minutes"));
		}
    $ip = ip2long($_SERVER['REMOTE_ADDR']);
		$useragent = $_SERVER['HTTP_USER_AGENT'];
    $string = RandomString(10);
    $token = sha1($ip.$expireddb.$fingerprint.$string.microtime());
    $remove = "DELETE FROM wavenet.tb_loginlog WHERE `username`='$username' AND `fingerprint` = '$fingerprint'";
    if ($link->query($remove) === TRUE) {
      $save = "INSERT INTO wavenet.tb_loginlog (`username`, `date`, `exp_date`, `token`, `ipaddress`, `useragent`, `fingerprint`,`stat`)
                      VALUES ('$username','$date','$expireddb','$token','$ip','$useragent', '$fingerprint','1')";
      if ($link->query($save) === TRUE) {
        $result = true;
      } else {
        $result = false;
      }
    } else {
      $result = false;
    }
    if ($result === true) {
  		if($expired <> 0){
  			$expr = intval(strtotime($expired));
        setcookie("_php-id", $token, $expr, "/");
  		} else {
        setcookie("_php-id", "", -3600, "/");
      }
      $memberdata = mysqli_query($link, "SELECT * FROM wavenet.tb_user WHERE username = '$username'");
      $row = mysqli_fetch_all($memberdata,MYSQLI_ASSOC)[0];
      $userid = $row['id'];
      for ($i=0; $i <= count($row) ; $i++) {
        $_SESSION[array_keys($row)[$i]] = $row[array_keys($row)[$i]];
      }
      $_SESSION['token'] = $token;
      $_SESSION['accbal'] = mysqli_fetch_all(mysqli_query($link, "SELECT balance FROM wavenet.tb_account WHERE memberlink = '$userid'"),MYSQLI_ASSOC)[0]['balance'];
    }
    return $result;
	}

	public function logout(){
		session_start();
		global $link;
		$status = false;
		if (isset($_COOKIE['_php-id'])) {
			$token = $_COOKIE['_php-id'];
		} elseif (isset($_SESSION['token'])) {
			$token = $_SESSION['token'];
		}
		if (isset($token) or !empty($token) or $token <> 0) {
			$now = date("Y-m-d H:i:s");
			$updatetime = "UPDATE wavenet.tb_loginlog SET `exp_date`='$now' WHERE `token` = '$token'";
			if ($link->query($updatetime) === TRUE) {
				$status = true;
			}
		}
    $_SESSION = [];
    unset($_SESSION);
    session_unset();
    session_destroy();
    setcookie("_php-id", '', time() - (86400), "/");
    unset($_COOKIE['_php-id']);
		return true;
	}

	public function login_redir(){
    $login_status = $this->login_status();
    if($login_status['status'] === true){
      login_success();
    } else {
			login_failed();
    	exit;
    }
	}

}

function RandomString($n) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
  }
  return $randomString;
}
?>
