'use strict';
function click_admin() {
  $(".start").each(function(i, e) {
    let width = 0;
    let id = $(e).attr("id");
    let timeout = setInterval( width = frame(id, width), 1000);
    let data = {
      action: 'getdata',
      table : id
    }
    
    api_ajax(`Admin`, false, data).then((resp) => {
      if (resp["status"]["code"] === 200) {
        let input = $(".switch input[name='theme']").is(":checked");
        let action = input ? 'backup' : 'recover';
        timeout = setInterval( width = frame(id, width), 1000);
        if (action == "backup") {
          console.log(action);
          //backup(resp['data'], action, this);
        } else if (action == "recover") {
          console.log(action);
          //recover(resp['data'], action, this);
        } 
        clearInterval(timeout);
        timeout = setInterval(width = frame(id, width), 10);
      }
    });
  });
}

function recover(params,action,event) {
  params.attr.forEach((e, i) => {
    let data = {
      action: action,
      tabla: params.tabla,
      src: e.src
    };
    
    api_ajax(`Admin`, false, data).then(resp => {
      if (resp["status"]["code"] === 200) {
        if (i === (params.attr.length - 1)) $(event).addClass("active");
      } else {
        $(event).addClass("disabled");
      }
    }).catch( (error) => {
      $(event).addClass("disabled");
      console.log(error);
    });
  });
}

function backup(params,action, event) {
  let data = {
    action: action,
    tabla: params.tabla
  }; 
  api_ajax(`Admin`, false, data).then(resp => {
    if (resp["status"]["code"] === 200) {
      $(event).addClass("active");
    } else {
      $(event).addClass("disabled");
    }
  }).catch( (error) => {
    $(event).addClass("disabled");
    console.log(error);
  });
}

function inputchanges(e) {
  if (e.type == "checkbox" || e.type == "radio") {
    let val = e.checked ? true : false;
    $(e).attr("checked", val);
  }
}

function frame(id, width) {
  if (width >= 100) {
    clearInterval(timeout);
  } else {
    if (width == 0 || width < 35) {
      if ($(`#${id}`).hasClass("sucess")) {
        $(`#${id}`).removeClass("sucess").addClass("error");
      } else {
        $(`#${id}`).removeClass("start").addClass("error");
      }
    } else if (width >= 35 && width < 65) {
      $(`#${id}`).removeClass("error").addClass("start");
    } else {
      $(`#${id}`).removeClass("start").addClass("sucess");
    }
    width++;
    $( `#${id}`+" .bar").css("width",width + "%");
    $( `#${id}`+" .percent").html(width + "%");
  }
  return width;
} 