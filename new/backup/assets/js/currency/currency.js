function isNumberKey(evt) {
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}

function convertToRupiah(angka) {
  var rupiah = '';
  var angkarev = angka.toString().split('').reverse().join('');
  for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
  return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
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
  oncleared: function () { self.Value(''); }
});
