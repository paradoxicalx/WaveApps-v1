<div id="modal-default" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-default"></h4>
      </div>
      <div class="modal-body" id="modal-body-default">
      </div>
      <div class="modal-footer" id="modal-footer-default">
      </div>
    </div>
  </div>
</div>

<div id="modal-map" class="modal fade">
  <div class="modal-dialog modal-lg" style="width: 90vw">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title modal-map"></h4>
      </div>
      <div class="modal-body modal-map" style="height: 80vh;">
      </div>
      <div class="modal-footer modal-map">
      </div>
    </div>
  </div>
</div>

<div id="modal-login" class="modal fade" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header black">
        <button type="button" class="close" data-dismiss="modal"><i class="fas fa-window-close"></i></button>
        <h4 class="modal-title" id="modal-title-login">Session Timeout</h4>
      </div>
      <div class="modal-body" id="modal-body-login">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="login-username">Username</label>
            <div class="col-sm-9">
              <input id="login-username" name="login-username" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="login-password">Password</label>
            <div class="col-sm-9">
              <input id="login-password" name="login-password" type="password" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
              <div class="pretty p-default p-round p-thick">
                  <input type="checkbox" id="login-rememberme"/>
                  <div class="state p-primary-o">
                      <label>Remember Me!</label>
                  </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="login"></label>
            <div class="col-sm-9">
              <input type="button" class="btn btn-success btn-block" id="login" value="Login" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer black" id="modal-footer-login">
        You need login to continue the action !!
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('#modal-default').on('hide.bs.modal', function (e) {
  $(this).find('.modal-content').find(".modal-header,.modal-footer").removeClass("error warning success");
  $(this).find(".modal-dialog").addClass("modal-lg");
  $(this).find('.modal-content').find(".modal-body,.modal-title,.modal-footer").html("");
})
$('#modal-default').on('show.bs.modal', function (event) {
  $(this).find('.modal-content').find(".modal-header,.modal-footer").removeClass("error warning success");
  $(this).find('.modal-content').find(".modal-body").html("");
})
$('#modal-login').on('show.bs.modal', function (event) {
  var windowHeight = $(window).height();
  var boxHeight = $('#modal-login').find('.modal-dialog').height();
  $('#modal-login').find('.modal-dialog').css({'margin-top' : ((windowHeight - boxHeight)/3)});
  $('#modal-title-login').text('Session Timeout').removeClass("text-red text-green");
})
</script>
