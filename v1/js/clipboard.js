function copytoclipboard($element) {
  var copyText = document.getElementById($element);
  copyText.select();
  document.execCommand("Copy");
  alert("Copied the text: " + copyText.value);
}
