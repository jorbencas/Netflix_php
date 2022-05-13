'use strict';
$(document).ready(function () { // document.oncontextmenu = function() {return false;}
  $(".api_list .list").attr("init",$(".api_list .list").html());
});

function getapicals(params, event) {
  if ($(event).hasClass("active")) {
    $(event).removeClass("active");
    $(".api_list .list").html($(".api_list .list").attr("init"));
  } else if ($(event).hasClass("disabled")) {
    $(event).removeClass("disabled");
    $(".api_list .list").html($(".api_list .list").attr("init"));
  } else {
    let data = {
      action: params,
      func: params+"api"
    };
    api_ajax(`Apidoc`, false, data).then((resp) => {
      if (resp["status"]["code"] === 200) {
        $(event).addClass("active");
          html = "";
          resp['data'].forEach(element => {
              html += `<div class='api_element'>
              <div class='method ${element.method}'>${element.method}</div>
              <div class='url'>${element.url}</div>
              <div class='text'>${element.text}</div>
              </div>`;
          });
        $(".api_list .list").html(html);
      } else {
        $(event).addClass("disabled");
      }
    }).catch( (error) => {
      $(event).addClass("disabled");
    });
  }
}
