<?php require "../../assets/func/sesscek.php"; ?>
<script src="../assets/js/mytable/table1.js"></script>
<script src="../assets/js/menu-content.js"></script>

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
          <button type="button" class="navbar-btn btn btn-info btn-block transition" data-toggle="dropdown">
            <span >Action</span>
          </button>
          <ul class="dropdown-menu role="menu"">
            <li><a href="#" class="selected-edit"><i class="fas fa-edit text-green icoinput"></i> Edit Member </a></li>
            <li><a href="#" class="selected-disable"><i class="fas fa-user-slash text-yellow icoinput"></i> Disable Member </a></li>
            <li><a href="#" class="selected-remove"><i class="fas fa-trash-alt text-red icoinput"></i> Remove Member </a></li>
          </ul>
        </li>
      </ul>

      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li url="test/sql-proc.php?q=true"class="menu-nav active" ><a href="#">True</a></li>
          <li url="test/sql-proc.php?q=false" class="menu-nav"><a href="#">False</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <button class="btn btn-success navbar-btn btn-block btn-new">New Member</button>
        </ul>
      </div>
    </div>
  </nav>
  <div class="row container-data">
    <div class="col-md-12">
      <div id="alert"></div>
      <div class="box box-success">
        <div class="box-header" data-widget="collapse">
          <div class="btn-group">
            <a class="btn btn-default dropdown-toggle fas fa-sort-numeric-down" data-toggle="dropdown"></a>
            <ul class="dropdown-menu" role="menu">
              <li class="table-length" value="10"><a href="#">10 Entries</a></li>
              <li class="table-length" value="20"><a href="#">20 Entries</a></li>
              <li class="table-length" value="50"><a href="#">50 Entries</a></li>
              <li class="table-length" value="100"><a href="#">100 Entries</a></li>
              <li class="divider"></li>
              <li class="table-length" value="-1"><a href="#">Show All</a></li>
            </ul>
          </div>
          <div class="box-tools pull-right btn-table">
          </div>
        </div>
        <div class="box-body">
          <table id="table1" class="table table-bordered table-striped" style="width:100%">
            <thead>
              <tr>
                <th><input type="checkbox" class="selectAll"></th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>IP Address</th>
                <th>Gender</th>
                <th>Lat</th>
                <th>Long</th>
                <th>Wallet</th>
                <th>Status</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div style="width: 100%"><iframe width="100%" height="600" src="https://maps.google.com/maps?width=100%&height=600&hl=en&coord=-7.8043066,110.3283599&q=Jl.%20%20Parangtritis%2C%20Km%2018+(Wavenet%20Media%20Service)&ie=UTF8&t=h&z=14&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a href="https://www.mapsdirections.info/en/journey-planner.htm">Journey Planner UK</a></iframe></div><br />
</section>

<script type="text/javascript">
$('.select2').select2()
  Table1Gen("test/sql-proc.php",
  function ( row, data, index ) {
    if ( data[4] == "Male" ) {
        $('td', row).eq(4).html("<span class='badge bg-blue'>"+data[4]+"</span>");
    } else if ( data[4] == "Female" ) {
      $('td', row).eq(4).html("<span class='badge bg-purple'>"+data[4]+"</span>");
    };
    if ( data[10] == 0) {
      data[10] = "Disabled";
      $('td', row).eq(10).html("<span class='label label-danger'>"+data[10]+"</span>");
    } else {
      data[10] = "Active";
      $('td', row).eq(10).html("<span class='label label-success'>"+data[10]+"</span>");
    };
    $('td', row).eq(0).html("<img class='img-circle' height=20 width=20 src='https://api.adorable.io/avatars/20/"+data[1]+"'>");
  });

  $('.btn-new').on('click', function() {
    var plainArray = table.rows({selected:true}).data().toArray();

    $.post("test/test.php", {
      array: plainArray
     },
      function(data) {
        $('#alert').html(data);
    });

  });

</script>
