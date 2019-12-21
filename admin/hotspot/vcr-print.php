<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Print Voucher</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <style>
      @page {
        size: A4 landscape;
        margin: 0;
      }
      @media print {
        html, body {
          width: 297mm;
          height: 210mm;
        }
      }
      body {
        margin: 0;
        padding: 0 0 0 5px;
        /* padding-left: 5px */
      }
      .card {
        position: relative;
        /* border-style: solid; */
        /* border-width: 1px; */
        /* border-top: 6px solid black; */
        width: 300px;
        height: 175px;
        display: inline-block;
        /* margin-top: 10px;
        margin-right: 20px */
        margin: 10px ;
      }
      .card::after {
        content: "";
        background-image: url("https://apps.wavenet.id/image/vcr-background.png");
        opacity: 0.5;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: absolute;
        z-index: -1;
      }
      .card .image {
        display: block;
        width: 100%;
        height: auto;
      }
      .card .card-container {
        background-color: rgba(255, 255, 255, 0.3);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
      }
      .card .card-container .left {
        position: absolute;
        top: 0;
        left: 0;
        width: 50%;
        height: 90%;
      }
      .card .card-container .left-bot {
        background-color: rgba(0, 0, 0, 1);
        color: #fff;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50%;
        height: 10%;
        text-align: center;
      }
      .card .card-container .right {
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        text-align: center;
      }
      .qrcode {
        margin: auto;
        margin-top: 8px;
        margin-bottom: 10px;
        background-color: rgba(255, 255, 255, 1);
        width: 120px;
        height: 120px;
      }
      .qrcode canvas {
        padding-top: 10px;
      }
      .price {
        font-family: "Arial Black", Gadget, sans-serif;
        font-size: 20px;
        color: #fff;
        font-weight: 400;
        text-transform: uppercase;
        text-shadow: 1px 1px #000, -1px 1px #000, 1px -1px #000, -1px -1px #000, 1px 1px 5px #555;
      }
      .logo {
        text-align: center;
        margin-top: 10px;
        width: 100%;
        height: 50px;
      }
      .userpass {
        text-shadow: 1px 1px #fff, -1px 1px #fff, 1px -1px #fff, -1px -1px #fff, 1px 1px 5px #555;
        padding-left: 15px;
        margin: auto;
        margin-top: 10px;
        margin-bottom: 5px;
        background-image: linear-gradient(to left, rgba(0,0,0,0), rgba(255,255,255,1));
        width: 100%;
        height: 40px;
        font-family: Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
      }
      .info small {
        text-shadow: 1px 1px #000, -1px 1px #000, 1px -1px #000, -1px -1px #000, 1px 1px 5px #555;
        font-size: 10px;
        color: #fff;
        font-family: Arial, 'Arial Unicode MS', Helvetica, Sans-Serif;
      }
      .info {
        text-align: center;
        margin: auto;
        margin-bottom: 10px;
      }
      .logo img {
        height: 50px;
      }
    </style>
  </head>

  <body>
    <div id="editor"></div>
    <div class="row-vcr" id="vcr-area"></div>
  </body>
  <script src="../assets/js/jquery/jquery.min.js"></script>
  <script src="../assets/js/jquery/jquery.qrcode.min.js"></script>
  <script src="../assets/js/html2pdf/html2pdf.bundle.min.js"></script>
  <script src="../assets/js/myjs.js"></script>
  <script type="text/javascript">

    var vcrlist = JSON.parse(localStorage.getItem("hs-vcr-print"));
    var userpasslist = vcrlist.user_pass;
    console.log(userpasslist);

    var logo_url = "https://apps.wavenet.id/image/vcr-logo.png";
    var info = "Unlimited kuota. <br>Maksimal pemakaian 4 jam.";
    var corp_name = "www.wavenet.id";
    var price = "IDR 3000";

    for (var i = 0; i < userpasslist.length; i++) {
      $("#vcr-area").append("<div class='card'>"+
        "<div class='card-container'>"+
          "<img src='' id='background"+i+"'>"+
          "<div class='left'>"+
            "<div class='logo'>"+
              "<img src='"+logo_url+"'>"+
            "</div>"+
            "<div class='userpass'>"+
              "<small>Username : <b>"+userpasslist[i].name+"</b></small>"+
              "<br>"+
              "<small>Password : <b>"+userpasslist[i].password+"</b></small>"+
            "</div>"+
            "<div class='info'>"+
              "<small>"+info+"</small>"+
            "</div>"+
          "</div>"+
          "<div class='left-bot'>"+
            corp_name+
          "</div>"+
          "<div class='right'>"+
            "<div class='qrcode' id='qrcode"+i+"'></div>"+
            "<span class='price'>"+price+"</span>"+
          "</div>"+
        "</div>"+
      "</div> ")

      var barcodeurl = "http://"+userpasslist[i].dns+"/?username="+userpasslist[i].name+"&password="+userpasslist[i].password;

      $("#qrcode"+i).qrcode({
        render: 'canvas',
        width: 100,
        height: 100,
        text: barcodeurl
      });
    }

    $( document ).ready(function() {
      var mode = getAllUrlParams().mode;

      if (mode == "print") {
        window.print();
      } else if (mode == "pdf") {
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();

        var date = d.getFullYear() + '-' +
        ((''+month).length<2 ? '0' : '') + month + '-' +
        ((''+day).length<2 ? '0' : '') + day;

        var element = document.getElementById('vcr-area');
        var opt = {
          filename: date+'_hs-voucher.pdf',
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };
        html2pdf().set(opt).from(element).save();
      }
      localStorage.removeItem('hs-vcr-print');
    });


  </script>
</html>
