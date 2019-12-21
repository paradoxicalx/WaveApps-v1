<script src="../assets/js/jquery/menu-content.js"></script>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= $weburl ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
    <li class="active"></li>
  </ol>
</section>

<section class="content">
  <nav class="navbar navbar-inverse nav-fixed">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="#" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" role="button">
          <i class="fas fa-bars"></i>
        </a>
        <a class="navbar-brand text-blue" href="<?= $weburl ?>"><span class="fas fa-home"></span></a>
      </div>
      <form class="navbar-form navbar-left">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search Table" id="tableSearch">
          <div class="input-group-btn">
            <button class="btn btn-default clearinput" type="button">
              <i class="fas fa-eraser"></i>
            </button>
          </div>
        </div>
      </form>
      <ul class="nav navbar-nav nav-menu">
        <li class="dropdown">
          <button type="button" class="navbar-btn btn btn-info btn-block" data-toggle="dropdown">Action
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu role="menu"">
            <li><a href="#"><i class="fas fa-edit text-green icoinput"></i> Action-1 </a></li>
            <li><a href="#"><i class="fas fa-user-slash text-yellow icoinput"></i> Action-2 </a></li>
            <li><a href="#"><i class="fas fa-trash-alt text-red icoinput"></i> Action-3 </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li var1="url.php" class="menu-nav active" ><a href="#">Menu-1</a></li>
          <li var1="url.php" class="menu-nav"><a href="#">Menu-2</a></li>
          <li var1="url.php" class="menu-nav"><a href="#">Menu-3</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block">New Item</button>
        </ul>
      </div>
    </div>
  </nav>
  <div class="row container-data"></div>
</section>
