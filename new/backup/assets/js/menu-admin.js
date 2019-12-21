$(document).ready(function() {

  $('.menu.dashboard').on('click', function() {
    $.get('dashboard?p=default', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.dashboard');
    });
  })

  $('.menu.member').on('click', function() {
    $.get('member?p=default', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.member');
    });
  })

  $('.menu.rad-user').on('click', function() {
    $.get('radius?p=user', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.rad-user');
    });
  })

  $('.menu.rad-group').on('click', function() {
    $.get('radius?p=group', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.rad-group');
    });
  })

  $('.menu.rad-router').on('click', function() {
    $.get('radius?p=router', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.rad-router');
    });
  })

  $('.menu.rad-session').on('click', function() {
    $.get('radius?p=session', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.rad-session');
    });
  })

  $('.menu.product').on('click', function() {
    $.get('product?p=service', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.product');
    });
  })

  $('.menu.net-ipv4').on('click', function() {
    $.get('networking?p=ipv4', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.net-ipv4');
    });
  })

  $('.menu.net-devices').on('click', function() {
    $.get('networking?p=devices', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.net-devices');
    });
  })

  $('.menu.net-snmp').on('click', function() {
    $.get('networking?p=snmp', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.net-snmp');
    });
  })

  $('.menu.bill-account').on('click', function() {
    $.get('billing?p=account', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.bill-account');
    });
  })

  $('.menu.bill-sales').on('click', function() {
    $.get('billing?p=sales', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.bill-sales');
    });
  })




  $('.menu.test').on('click', function() {
    $.get('test?p=default', function(data) {
      $('.content-container').html(data);
      copyDataMenu('.menu.test');
    });
  })

});

function copyDataMenu(id) {
  var menu =  $(id).closest('a').html();
  $('.menu').closest("li").removeClass("active");
  $(id).closest("li").addClass("active");
  $('.content-header').find('h1').html(menu);

  if ($(id).closest(".treeview").length) {
    upmenu = "<li>"+$(id).closest(".treeview").find('a').html()+"</li>";
  } else {
    upmenu = "";
  }
  var dashboard = "<a><i class='fas fa-tachometer-alt'></i>Dashboard</a>";
  var breadcrumb = "<li>"+dashboard+"</li>"+upmenu+"<li>"+menu+"</li>";
  $('.breadcrumb').html(breadcrumb);
  $('.breadcrumb').find(".pull-right-container").remove();
}
