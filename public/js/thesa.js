
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
  var url = $path+"/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
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
        var url = $path+"/admin/ajax_broader_save?id="+$id+"&prop=" + $prop + "&concept=" + concept;
        $("#form_thesa_" + $prop).load(url);
      }

  }

function form_thesa_concept($id, $prop) {
  $("#form_thesa_" + $prop).html("Loading...");
  var url = $path+"/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
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
  var url = $path+"/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
  $.ajax({
    type: "POST",
    url: url,
    success: function (rsp) {
      $("#form_thesa_" + $prop).html(rsp);
    },
  });
}

function form_thesa_reference($id, $prop) {
  $("#form_thesa_" + $prop).html("Loading...");
  var url = $path + "/admin/ajax_form/?id=" + $id + "&prop=" + $prop;
  $.ajax({
    type: "POST",
    url: url,
    success: function (rsp) {
      $("#form_thesa_" + $prop).html(rsp);
    },
  });
}

function thesa_reference_set($id,$ref,item)
  {
    var cited = $("#cited").val();
    var reference = $("#reference").val();
    var $prop = "#form_thesa_reference";

    var block = document.getElementById("ref_"+$ref);
    if ($(item).is(":checked")) {
          var url = $path + "/admin/ajax_concept_reference?id=" + $id + "&ref=" + $ref + "&set=1";
          $($prop).load(url);
    } else {
          var url = $path + "/admin/ajax_concept_reference?id=" + $id + "&ref=" + $ref + "&set=0";
          $($prop).load(url);
    }
  }

function form_thesa_refecence_save($id,$prop,$term)
  {
    var cited = $("#cited").val();
    var reference = $("#reference").val();
    var url = $path+"/admin/ajax_form_reference_save?id="+$id+"&prop=" + $prop+"&term="+$term;

    $.ajax({
      type: "POST",
      url: url,
      data: { cited: cited, id: $id, prop: $prop, reference: reference },
      success: function (rsp) {
        $("#form_thesa_" + $prop).html(rsp);
      },
    });
  }

function form_thesa_refecence_cancel($id,$prop,$term)
  {
    var url = $path + "/admin/ajax_reference_list?id=" + $id + "&prop=" + $prop + "&term=" + $term;
    $("#form_thesa_" + $prop).load(url);
  }

function term_delete($id, $prop) {
  if (confirm("Remove?")) {
    var url = $path+"/admin/ajax_term_delete?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }
}

function text_delete($id,$prop) {
  if (confirm("Remove?")) {
    var url = $path+"/admin/ajax_text_delete?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }
}

function text_edit($id, $prop) {
    var url = $path+"/admin/ajax_text_edit?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
}

function close_text($id,$prop)
  {
    var url = $path+"/admin/ajax_text_list?id=" + $id + "&prop=" + $prop;
    $("#form_thesa_" + $prop).load(url);
  }

function save_text($id,$prop)
  {
    var vlr = $("#text_"+$prop).val();
    var reg = $("#text_rg_" + $prop).val();
    var url = $path+"/admin/ajax_form_text_save?id="+$id+"&prop=" + $prop;

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
    var url = $path+"/admin/ajax_form_field_save?th="+$th+'&form=' + $form + "&vlr=" + vlr;
    url = encodeURI(url);
    $("#status_"+$form).load(url);
    togglet($form);
  }
function togglet($form)
  {
    $("#status_" + $form).toggle();
    $("#form_" + $form).toggle();
  }

 function newwin(url, xx, yy) {
   NewWindow = window.open(
     url,
     "newwin2",
     "scrollbars=yes,resizable=no,width=" +
       xx +
       ",height=" +
       yy +
       ",top=10,left=10"
   );
   NewWindow.focus();
   void 0;
 }

 function winclose() {
   close();
 }

 function wclose() {
   window.opener.location.reload();
   close();
 }