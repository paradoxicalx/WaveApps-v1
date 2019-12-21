<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="nav-tabs-custom terminal-overlay">
  <ul class="nav nav-tabs no-border" style="height:40px;">
    <li style="margin-left: 10px">
      <div class="dropdown">
        <button class="btn btn-success dropdown-toggle"data-toggle="dropdown"><i class="fas fa-terminal"></i></button>
        <button id="term-input" class="btn btn-default"><i class="fas fa-pencil-alt"></i></button>
        <ul id="terminal-list" class="dropdown-menu">
          <li id="new-terminal" class="pointer"><a><i class="fas fa-plus-circle text-green"></i> New Terminal</a></li>
          <li id="close-all-terminal" class="pointer"><a><i class="fas fa-times-circle text-red"></i> Close All</a></li>
          <li role="presentation" class="divider"></li>
        </ul>
      </div>
    </li>
    <li class="pull-right">
      <button id="min-terminal" class="btn btn-warning"><i class="fas fa-compress"></i></button>
    </li>
    <li class="pull-right">
      <button id="trans-terminal" class="btn btn-info"><i class="far fa-eye-slash"></i></button>
    </li>
  </ul>
  <div id="terminal-content" class="tab-content trans-terminal">
  </div>
</div>

<script type="text/javascript">
  var terimcount = 0;
  $('#new-terminal').on('click', function() {
    terimcount = terimcount+1;
    $('#terminal-list').find('li').removeClass("active");
    $('#terminal-content').find('div').removeClass('inaction notaction').addClass('notaction');
    $('#terminal-list')
      .append("<li onclick='removeactv("+terimcount+")' class='termtab active' id='tab"+terimcount+"'><a>Window "+terimcount+"</a></li>");
    $('#terminal-content')
      .append(" <div class='tab-pane inaction' id='tab_"+terimcount+"'>"+
              "<iframe style='width:98vw' id='shell"+terimcount+"' class='responsive-iframe' src='<?= $weburl ; ?>:6361/?<?= $_SESSION['fingerprint'] ; ?>'></iframe>"+
              "</div>");
    $('#terminal-content').find('#tab_'+terimcount).addClass('inaction');
  });
  $('#close-all-terminal').on('click', function() {
    $('#terminal-list').find("li.termtab").remove();
    $('#terminal-content').find('div').remove();
  });
  $('#trans-terminal').on('click', function() {
    if( $("#terminal-content").hasClass('trans-terminal')) {
      $('#terminal-content').removeClass('trans-terminal').addClass('solid-terminal');
    } else {
      $('#terminal-content').removeClass('solid-terminal').addClass('trans-terminal');
    }
  });
  $('#term-input').on('click', function() {
    $('#modal-default').modal('show');
    $('#modal-title-default').text("Terminal Input");
    $('#modal-body-default').load("tools/terminal-input.php");
    $('#modal-default').css("z-index", "10002");
  });
  function removeactv(tabid) {
    $('#terminal-list').find('li').removeClass("active");
    $('#terminal-list').find('#tab'+tabid).addClass("active");
    $('#terminal-content').find('div').removeClass('inaction notaction').addClass('notaction');
    $('#terminal-content').find('#tab_'+tabid).removeClass('notaction').addClass('inaction');
  }
</script>
