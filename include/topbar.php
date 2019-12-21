<header class="main-header">
  <a href="<?= $weburl ?>" class="logo fixed-d">
    <span class="logo-mini"><b><?= $appname3 ?></b><?= $appname4 ?></span>
    <span class="logo-lg"><b><?= $appname1 ?></b><?= $appname2 ?></span>
  </a>
  <nav class="navbar navbar-static-top fixed-d">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <i class="fas fa-bars"></i>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?= $_SESSION['avatar']; ?>" class="user-image" alt="User Image">
            <span class="hidden-xs"><?= $_SESSION['name']; ?></span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="<?= $_SESSION['avatar']; ?>" class="img-circle" alt="User Image">

              <p>
                <?= $_SESSION['email']; ?>
                <small id="publicip"></small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="?page=m-profile&uid=<?= $sesi_id ?>" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a href="<?= $weburl ?>/logout.php" class="btn btn-default btn-flat">Sign out in <b id="ticktime">0</b></a>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fas fa-cogs"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>
