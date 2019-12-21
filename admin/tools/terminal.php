<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<div class="terminal-overlay">
  <div class="terminal_container trans-terminal" id="terminal_container">
    <div class="terminal_row">
      <div class="terminal_column terminal_left">
        <span><i class="fas fa-times-circle text-red pointer" id="close_current"></i></span>
        <span><i class="fas fa-plus-circle text-green pointer new-terminal"></i></span>
        <span><i class="fas fa-minus-circle text-yellow pointer" id="min-terminal"></i></span>
      </div>
      <div class="terminal_column terminal_middle">
        <span id="terminal_title">Open new terminal first!</span>
        <div id="f-input-terminal" class="input-group input-group-sm hide">
          <input id="input-terminal" type="text" class="form-control">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat" id="send-string">Enter</button>
          </span>
        </div>
      </div>
      <div class="pull-right">
        <div class="dropdown">
          <i class="far fa-keyboard text-blue pointer" id="term-input"></i>
          <i class="fas fa-bars pointer dropdown-toggle" data-toggle="dropdown"></i>
          <ul id="terminal-list" class="dropdown-menu pull-right">
            <li class="pointer new-terminal"><a><i class="fas fa-plus-circle text-green"></i> New Terminal</a></li>
            <li id="close-all-terminal" class="pointer"><a><i class="fas fa-times-circle text-red"></i> Close All</a></li>
            <li id="trans-terminal" class="pointer"><a><i class="far fa-eye-slash text-blue"></i> Transparency</a></li>
            <li role="presentation" class="divider"></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="terminal_content" id="terminal-content">
    </div>
  </div>
</div>

<script type="text/javascript">
  var terimcount = 0;
  $('.new-terminal').on('click', function() {
    terimcount = terimcount+1;
    $('#terminal-list').find('li').removeClass("active");
    $('#terminal-content').find('div').removeClass('inaction notaction').addClass('notaction');
    $('#terminal-list')
      .append("<li onclick='removeactv("+terimcount+")' class='termtab active' id='tab"+terimcount+"'><a>Terminal "+terimcount+"</a></li>");
    $('#terminal-content')
      .append(" <div class='tab-pane inaction' id='tab_"+terimcount+"'>"+
              "<iframe style='width:98vw' id='shell"+terimcount+"' class='responsive-iframe' src='<?= $weburl ; ?>/shell?<?= $_SESSION['fingerprint'] ; ?>'></iframe>"+
              "</div>");
    $('#terminal-content').find('#tab_'+terimcount).addClass('inaction');
    $('#terminal_title').text('Terminal '+terimcount);
  });
  $('#close-all-terminal').on('click', function() {
    $('#terminal-list').find("li.termtab").remove();
    $('#terminal-content').find('div').remove();
    $('#terminal_title').text('Open new terminal first!');
  });
  $('#trans-terminal').on('click', function() {
    if( $("#terminal_container").hasClass('trans-terminal')) {
      $('#terminal_container').removeClass('trans-terminal').addClass('solid-terminal');
    } else {
      $('#terminal_container').removeClass('solid-terminal').addClass('trans-terminal');
    }
  });
  $('#term-input').on('click', function() {
    if ($('#f-input-terminal').hasClass('hide')) {
      $('#terminal_title').hide()
      $('#f-input-terminal').removeClass('hide')
    } else {
      $('#terminal_title').show()
      $('#f-input-terminal').addClass('hide')
    }
  });
  function removeactv(tabid) {
    $('#terminal-list').find('li').removeClass("active");
    $('#terminal-list').find('#tab'+tabid).addClass("active");
    $('#terminal-content').find('div').removeClass('inaction notaction').addClass('notaction');
    $('#terminal-content').find('#tab_'+tabid).removeClass('notaction').addClass('inaction');
    $('#terminal_title').text('Terminal '+tabid);
  }
  $('#close_current').on('click', function() {
    $('#terminal-list').find('.active').remove();
    $('#terminal-content').find(".inaction").remove();
    $('#terminal_title').text("");
  });

  $('#send-string').on('click', function() {
    sendCommand();
  });
  $('#input-terminal').keypress(function(e){
    if(e.which == 13){
      endCommand();
    }
  });

  function sendCommand() {
    var url = "<?= $weburl ; ?>/shell?<?= $_SESSION['fingerprint']; ?>";
    var activeterminal = $('#terminal-content').find('.inaction').find('iframe').attr('id');
    var iframe  = document.getElementById(activeterminal);
    var commandstr = $('#input-terminal').val();
    var message = JSON.stringify({
      type : 'input',
      data : commandstr + '\n'
    });
    iframe.contentWindow.postMessage(message, url);
    $('#input-terminal').val('');
  }
</script>
