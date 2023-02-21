function allowDrop(ev) {
  ev.preventDefault();
}
function dragStart(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function dragDrop(ev,id, url,th) {
  $("#broader").html("Saving..." + url);
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));

  $.ajax({
    type: "GET",
    url: url,
    data: { idc: data, ida: id, "check":"Thesa",th:th},
    success: function (rsp) {
      $("#broader").html(rsp);
    },
  });
}
