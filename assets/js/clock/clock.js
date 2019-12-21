var xmlHttp;
function srvTime(){
    try {
        //FF, Opera, Safari, Chrome
        xmlHttp = new XMLHttpRequest();
    }
    catch (err1) {
        //IE
        try {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (err2) {
            try {
                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (eerr3) {
                //AJAX not supported, use CPU time.
                alert("AJAX not supported");
            }
        }
    }
    xmlHttp.open('HEAD',window.location.href.toString(),false);
    xmlHttp.setRequestHeader("Content-Type", "text/html");
    xmlHttp.send('');
    return xmlHttp.getResponseHeader("Date");
}

function startTime() {
  var st = srvTime();
  var today = new Date(st);
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  var y = today.getFullYear();
  var months = ["Jan", "Feb", "Mar", "Apr", "Mai", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];
  var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
  var d = days[today.getDay()];
  var b = months[today.getMonth()];
  var t = today.getDate();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('clock').innerHTML =
  d + ", " + t + " " + b + " " + y + " | " +h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

startTime();
