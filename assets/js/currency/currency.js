function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

function convertToRupiah(angka) {
  var rupiah = '';
  var angkarev = angka.toString().split('').reverse().join('');
  for (var i = 0; i < angkarev.length; i++)
    if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
  return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
}

function convertToAngka(rupiah) {
  return parseInt(rupiah.replace(/,.*|[^0-9-]/g, ''), 10);
}

$('.rupiah').inputmask("numeric", {
  prefix: ' Rp. ',
  radixPoint: ",",
  groupSeparator: ".",
  digits: 2,
  autoGroup: true,
  rightAlign: false,
  oncleared: function() {
    self.Value('');
  }
});

function ConvertBytes(fileSizeInBytes) {
  var i = -1;
  var byteUnits = [' kbps', ' Mbps', ' Gbps', ' Tbps', 'Pbps', 'Ebps', 'Zbps', 'Ybps'];
  do {
    fileSizeInBytes = fileSizeInBytes / 1024;
    i++;
  } while (fileSizeInBytes > 1024);
  return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
};

// function bytesToSize(bytes) {
//   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
//   if (bytes == 0) return '0 Byte';
//   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
//   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
// };

function bytesToSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  if (bytes == 0) return '0 Byte';
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  if (i == 0) return bytes + ' ' + sizes[i];
  return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};