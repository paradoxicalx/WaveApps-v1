<?php
session_start();

// if (file_exists("../assets/func/sesscek.php")) {
//   $cekfile = "../assets/func/sesscek.php";
// } elseif (file_exists("../../assets/func/sesscek.php")) {
//   $cekfile = "../../assets/func/sesscek.php";
// } else {
//   exit;
// }

$fingerprint = $_POST['fingerprint'];

echo json_encode($_SESSION);

// echo "string";

?>
