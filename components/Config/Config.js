'use strict';
function inputchanges(e) {
  if (e.type == "checkbox" || e.type == "radio") {
    let val = e.checked ? true : false;
    $(e).attr("checked", val);
    $(e).attr("value", val);
    if (e.name == 'theme') settheme();
  } else $(e).attr("value", e.value);
}

function saveconfig() {
    let req = {};
    $(".configuration input[name!='undefined'], .configuration  select[name!='undefined']").each((i, e) => {
      let clave = $(e).attr("name");
      let valor = e.value;
      if (clave != undefined) {
        if ((e.type == "checkbox" || e.type == "radio")) {  
          req[clave] = $(e).attr("checked");
        } else if ((e.type !== "checkbox" && e.type !== "radio")) {
          valor = valor !== "" ? valor : null;
          req[clave] = valor;
        }
      }
    });
    req['user'] = localStorage.getItem('user');
    api_ajax("Config",false,req).then((resp) => {
      if (resp['status']['code'] === 200) {
        openalert("s", resp['status']['message']);
      }
    }).catch((error) => {
      console.log(error);
    });
}

function specialelem(params,elem,e, actual) {
  let elems = $(`#${e}`)[0].children;
  $(elems).each((index, element) => {
    if (element.className.includes("active")) {
      $(element).removeClass("active");
    }
  });
  $(actual).addClass("active");
  $(`input[name=${elem}]`).val(params);
}

  function settheme() {
    let input = $(".configuration input[name='theme']").attr("checked");
    let theme = input ? 'dark' : 'light';
    $("head link[href*='themes']").each((i, e) => {
      let dom_element = e.attributes[1];
      if (dom_element.value.includes("dark")) {
        dom_element.value = dom_element.value.replace('dark',theme);
      } else if (dom_element.value.includes("light")) {
        dom_element.value = dom_element.value.replace('light',theme);
      }
    });
  }