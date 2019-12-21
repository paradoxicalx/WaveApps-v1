<?php
require_once($_SERVER['DOCUMENT_ROOT']."/assets/func/sqlQu.php");
$login->login_redir();
?>
<textarea id="input-terminal" rows="5" style="width:100%"></textarea>
<button id="send-string" class="btn btn-block btn-info">Enter</button>

<script type="text/javascript">
  $('#send-string').on('click', function() {
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
  });
</script>
