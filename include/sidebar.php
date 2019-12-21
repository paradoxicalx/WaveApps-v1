<!-- <aside class="main-sidebar fixed-d scroller"> -->
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left info">
        <p>
          <i id="statsession" class='fa fa-circle text-green' style="margin-right: 5px"></i>
          <?= strtoupper($_SESSION['username']); ?>
        </p>
        <a><i class="fas fa-coins text-yellow"></i> <a id="s-saldo">Rp. 0</a></a>
      </div>
      <div class="pull-left image">
        <img src="<?= $_SESSION['avatar']; ?>" class="img-circle" alt="User Image">
      </div>
    </div>
    <form action="#" method="get" class="sidebar-form">
      <!-- <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div> -->
    </form>
    <ul class="sidebar-menu" data-widget="tree">
      <li id="clock" class="header text-blue">MAIN NAVIGATION</li>
      <?php include "menu.php"; ?>
    </ul>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">Mikrotik Assistant</li>
      <?php include "menu-mk.php"; ?>
    </ul>
  </section>
</aside>
