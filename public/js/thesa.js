function copytoClipboard($text) {
  // Get the text field
  var copyText = document.getElementById("#" + $text);

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied" + copyText.value);
}

function form_thesa_label($id, $prop) {
  $("#form_thesa_" + $prop).html("' . lang('thesa.loading') . '");
  var url = "/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
  $.ajax({
    type: "POST",
    url: url,
    success: function (rsp) {
      $("#form_thesa_" + $prop).html(rsp);
    },
  });
}

function term_delete($id, $prop) {
  if (confirm("Remove?")) {
    var url = "/admin/ajax_term_delete?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }
}
function form_field_save($form)
  {
    vlr = $("#" + $form).val();

    $vlr = "";
    var url = '/admin/ajax_form_field_save?form=' + $form + "&vlr=" + vlr;
    url = encodeURI(url);
    $("#status_"+$form).load(url);
    togglet($form);
  }
function togglet($form)
  {
    $("#status_" + $form).toggle();
    $("#form_" + $form).toggle();
  }
