<?php
function notValidEmail($col, $data) {
  if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
    $report = ["status" => "invalid"];
    $report[] = ["error" => "Invalid Email Address", "col" => $col, "data" => "$data"];
    return $report;
  }
}

function ValidPassword($col, $password) {
  if(strlen($password) < 7){
    $report = ["status" => "invalid"];
    $report[] = ["error" => "Password Requires 8 Character", "col" => $col, "data" => "$password"];
    return $report;
  } else {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $report = ["status" => "valid"];
    $report[] = ["error" => false, "col" => $col, "data" => "$password"];
    return $report;
  }
}


?>
