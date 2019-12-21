<?php
require_once($_SERVER['DOCUMENT_ROOT']."/config/conn.php");
$login_status = $login->login_status();
if($login_status['status'] === false){
	header("location: ../");
	exit();
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
	<?php include "modal.php"; ?>
  <?php include "footer.php"; ?>
	<?php if ($_SESSION['group'] === "admin"): ?>
		<?php include "../admin/tools/terminal.php"; ?>
	<?php endif; ?>
</div>
<?php include "item-bot.php"; ?>
</body>
</html>
