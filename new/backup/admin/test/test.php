
<input type="text" id="ipaddress" value="10.10.1.0">
<input type="text" id="netmask" value="255.255.0.0">
<input type="button" id="testbutton" value="Test" />
<input type="button" id="clearlog" value="Clear" />
<input type="button" id="testbutton2" value="Test 2" />
<hr>
<div id="info1"><span class="progress-text"></span></div><hr>
<div id="info2"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
$('#clearlog').on('click', function() {
  $('#info1').html("");
  $('#info2').html("");
});
// function ip2long(IP) {
//   var i = 0;
//   IP = IP.match( /^([1-9]\d*|0[0-7]*|0x[\da-f]+)(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?$/i );
//   if (!IP) { return false; }
//   IP[0] = 0;
//   for (i = 1; i < 5; i += 1) {
//     IP[0] += !!((IP[i] || '').length);
//     IP[i] = parseInt(IP[i]) || 0;
//   }
//   IP.push(256, 256, 256, 256);
//   IP[4 + IP[0]] *= Math.pow(256, 4 - IP[0]);
//   if (IP[1] >= IP[5] || IP[2] >= IP[6] || IP[3] >= IP[7] || IP[4] >= IP[8]) { return false; }
//   return IP[1] * (IP[0] === 1 || 16777216) + IP[2] * (IP[0] <= 2 || 65536) + IP[3] * (IP[0] <= 3 || 256) + IP[4] * 1;
// }

var subnet = 0;
var bcast = 0;
var count = 0;
var countcek = 0;
var ipexist = 0;
var duplicate = 0;
var iplist;

$('#testbutton').on('click', function() {
  console.log("Button Test Clicked!");
  $('#info1').html("Getting IP/s Data ");
  $.post("test2.php", {
    ipaddress: $("#ipaddress").val(),
    netmask: $("#netmask").val()
  },
  function(data) {
    $('#info2').append("Data Log >> <br>"+data+"<hr>");
    iplist = JSON.parse(data);
    $('#info2').append("Json Log >> <br>"+iplist[0]['ipexist']+"<hr>");
    $('#info1').html("Check IP/s");
    subnet = iplist[0]['subnet'];
    bcast = iplist[0]['bcast'];
    count = iplist[0]['count']+1;
    var countcek = 0;
    ipexist = iplist[0]['ipexist'];
    duplicate = 0;
    CekIPs();
  });
});

function CekIPs() {
  setTimeout(function(){
    if (subnet <= bcast) {
      $.map(ipexist, function(value, key) {
        if (value == subnet) {
          duplicate++
        }
      });
      subnet++;
      countcek++;
      $('#info1').html("Check existing ip "+countcek+"/ "+count+" Find Duplicate: "+duplicate);
      CekIPs();
    }
  }, 1);
}

// function CekIPs() {
//   var z = ipexist.filter(function(val) {
//     return ipexist.indexOf(val) != -1;
//   })
//   $('#info1').text(z);
// }

</script>
