<?php
   session_start();
   $_SESSION = [];
   unset($_SESSION);
   session_unset();
   session_destroy();

   setcookie('sesid', '', time()-3600);
   setcookie("sesid", '', time() - (86400 * 1), "/");
   unset($_COOKIE['sesid']);

   header("Location: ../");
   exit;
?>
