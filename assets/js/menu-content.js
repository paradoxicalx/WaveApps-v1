$(document).ready(function() {

  $(".navbar-nav").find('.menu-nav').click(function() {
    var url = $(this).attr('url');
    ChangeAjaxUrl(url);
    table.rows().deselect();
    $('.navbar-nav').find('li').removeClass("active");
    $(this).addClass("active");
    $(".box").removeClass("box-success box-danger box-warning box-info");
    if ($(this).hasClass("info")) {
      $(".box").addClass("box-info");
    } else if ($(this).hasClass("success")) {
      $(".box").addClass("box-success");
    } else if ($(this).hasClass("danger")) {
      $(".box").addClass("box-danger");
    } if ($(this).hasClass("warning")) {
      $(".box").addClass("box-warning");
    }
  });

  var menu = $('.nav-fixed');
  var origOffsetY = menu.offset().top;
  function scroll() {
      if ($(window).scrollTop() >= origOffsetY) {
          $(menu).addClass('navbar-fixed-top');
      } else {
          $(menu).removeClass('navbar-fixed-top');
      }
  }
  document.onscroll = scroll;

  $('.clearinput').on('click', function() {
    $('#tableSearch').val('');
    $('#tableSearch').keyup();
  });

  $('.sidebar-toggle').on('click', function() {
    if ($('body').hasClass('sidebar-collapse')) {
      $('.nav-fixed').removeClass('m');
    } else {
      $('.nav-fixed').addClass('m');
    }
  });

});
