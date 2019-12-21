<?php
  require '../config/conf.php';
  $its = "open-page";
  require '../config/syscek.php';
  if ($result['status'] === "failed") {
    header("Location: $weburl");
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $apptitle ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php include "item-top.php"; ?>
</head>
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">
  <?php include "topbar.php"; ?>
  <?php include "sidebar.php"; ?>
  <?php include "control-sidebar.php"; ?>
  <?php include "content.php"; ?>
  <?php include "footer.php"; ?>
  <?php include "modal.php"; ?>
</div>
<?php include "item-bot.php"; ?>
</body>
</html>
