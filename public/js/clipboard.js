function copytoclipboard($element) {
  var copyText = document.getElementById($element);
  copyText.select();
  document.execCommand("copy");

  alert("Copied to Clipboard: " + copyText.value);
}

function text2clipboard($element) {
  var range = document.createRange();
  range.selectNode(document.getElementById("cpl"));
  window.getSelection().removeAllRanges(); // clear current selection
  window.getSelection().addRange(range); // to select text
  alert("Copied to Clipboard: "+range);
  document.execCommand("copy");
  window.getSelection().removeAllRanges(); // to deselect
}