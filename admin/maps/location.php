<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<link rel="stylesheet" href="../assets/css/openlayers/ol.css" type="text/css">
<script src="../assets/js/openlayers/ol.js"></script>
<script src="../assets/js/openlayers/turf.js"></script>

<section class="content-header">
  <h1>
	  <i class=""></i>
    <span></span>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= $weburl ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
  </ol>
</section>

<section class="content">
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="#" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" role="button">
          <i class="fas fa-bars"></i>
        </a>
        <a class="navbar-brand text-blue" href="<?= $weburl ?>"><span class="fas fa-home"></span></a>
      </div>
      <form class="navbar-form navbar-left">
        <div class="input-group thisshow">
          <input type="text" class="form-control" placeholder="Search Maps" id="mapsearch">
          <div class="input-group-btn">
            <button class="btn btn-default clr-mapsearch" type="button">
              <i class="fas fa-eraser"></i>
            </button>
          </div>
        </div>
        <div class="input-group thishide" style="display: none;">
          <input type="text" class="form-control" placeholder="Search Table" id="tableSearch">
          <div class="input-group-btn">
            <button class="btn btn-default clearinput" type="button">
              <i class="fas fa-eraser"></i>
            </button>
          </div>
        </div>
      </form>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="maps/location-map.php" class="menu-nav info pointer first"><a>Maps</a></li>
          <li url="maps/location-table.php"class="menu-nav danger pointer" ><a>Location</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new thishide" style="display: none;">New Location</button>
        </ul>
      </div>
    </div>
  </nav>
  <div class="row container-data">
  </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
  $(".navbar-nav").find('.menu-nav').click(function() {
    var url = $(this).attr('url');
    $.get(url, function(data) {
      $('.container-data').html(data);
    });
    $('.navbar-nav').find('li').removeClass("active");
    $(this).addClass("active");
    $(".box").removeClass("box-success box-danger box-warning box-info");
    if ($(this).hasClass("info")) {
      $(".box").addClass("box-info");
      $(".thishide").hide()
      $(".thisshow").show()
    } else if ($(this).hasClass("danger")) {
      $(".box").addClass("box-danger");
      $(".thishide").show()
      $(".thisshow").hide()
    }
  });
  $(".first").click();

  $('.sidebar-toggle').on('click', function() {
    if ($('body').hasClass('sidebar-collapse')) {
      $('.nav-fixed').removeClass('m');
    } else {
      $('.nav-fixed').addClass('m');
    }
  });

  $('.clearinput').on('click', function() {
    $('#tableSearch').val('');
    $('#tableSearch').keyup();
  });

})
</script>
