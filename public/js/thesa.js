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
  $("#form_thesa_" + $prop).html("Loading...");
  var url = "/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
  $.ajax({
    type: "POST",
    url: url,
    success: function (rsp) {
      $("#form_thesa_" + $prop).html(rsp);
    },
  });
}

function save_ajax_broader($id,$prop)
  {
    var concept = $("#select_"+$prop).val();
    if (concept == null)
      {
        alert("ERRO: Select a concept");
      } else {
        var url = "/admin/ajax_broader_save?id="+$id+"&prop=" + $prop + "&concept=" + concept;
        $("#form_thesa_" + $prop).load(url);
      }

  }

function form_thesa_concept($id, $prop) {
  $("#form_thesa_" + $prop).html("Loading...");
  var url = "/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
  $.ajax({
    type: "POST",
    url: url,
    success: function (rsp) {
      $("#form_thesa_" + $prop).html(rsp);
    },
  });
}

function form_thesa_text($id, $prop) {
  $("#form_thesa_" + $prop).html("Loading...");
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

function text_delete($id,$prop) {
  if (confirm("Remove?")) {
    var url = "/admin/ajax_text_delete?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }
}

function text_edit($id, $prop) {
    var url = "/admin/ajax_text_edit?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
}

function close_text($id,$prop)
  {
    var url = "/admin/ajax_text_list?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }

function save_text($id,$prop)
  {
    var vlr = $("#text_"+$prop).val();
    var reg = $("#text_rg_" + $prop).val();
    var url = "/admin/ajax_form_text_save?id="+$id+"&prop=" + $prop;

    $.ajax({
      type: "POST",
      url: url,
      data: {text: vlr, id: $id, prop: $prop, reg: reg},
      success: function (rsp) {
        $("#form_thesa_" + $prop).html(rsp);
      },
    });
  }
function form_field_save($form,$th)
  {
    vlr = $("#" + $form).val();
    var url = "/admin/ajax_form_field_save?th="+$th+'&form=' + $form + "&vlr=" + vlr;
    url = encodeURI(url);
    $("#status_"+$form).load(url);
    togglet($form);
  }
function togglet($form)
  {
    $("#status_" + $form).toggle();
    $("#form_" + $form).toggle();
  }
