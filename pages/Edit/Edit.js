'use strict';
let searched = window.location.search.split("&");
let page_mode = searched[1] ? 'update': 'insert';
let action = searched[1] ? 'update': 'insert'; 
let animesiglas = action == "update" ? $("#anime .wrap input[name='siglas']").val(): undefined;
let animeid = action == "update" ? $("#anime .wrap input[name='id']").val(): undefined;
let animeidiomas = action == "update" ? $("#anime .wrap input[name='idiomas']:checked").val(): undefined;
let screenwidth = $(window).width();
let diamant_element = screenwidth <= 800 ? ".movil_list .movil_child" : ".list .child";
let diamant = screenwidth <= 800 ? ".movil_list" : ".list";
let baseurl = getbaseurl();

$(document).ready(function() {
  if (screenwidth <= 800) {
    $(".anime").text("Ani");
    $(".episodes").text("Epi");
    $(".openings").text("Op");
    $(".endings").text("Ed");
  }
});

function inputchanges(e) {
  if (e.type == "checkbox" || e.type == "radio") {
    if (e.checked) $(e).attr("checked", true);
    else $(e).attr("checked", false);
  } else $(e).attr("value", e.value);
}

function setvalorations(valoration) {
  let star;
  let node = geteditnode();
  $(`${node} .star-rating span`).each((i,e) => {
    star = (i <= valoration) ? "fas fa-star" : "far fa-star";
    $("#"+e.id + " i").attr("class",star);
  });
  $(`${node} input[name*='valorations']`).attr("value",valoration + 1);
}

//Edit
function setab(evt, tab) {
  if (!evt.currentTarget.className.includes("active")) {
    $(".tabcontent").hide();
    $(".tablinks").removeClass("active");
    if (tab !== 'anime' && 
    $("#" + tab + ` ${diamant_element} .list_element`).length === 0) {
      $("#" + tab + " .form_oculto .wrap input[class*='submit']:last").hide();
      if ($("#" + tab + " > .list_element")[0] !== undefined) {
        $("#" + tab + " > .list_element").each((i, e) => {
          $("#" + tab + ` ${diamant_element} `).append(e);
        });
        $("#" + tab + ` ${diamant}`).css("display", "flex");
        calculatediamantynimg(tab);
        $("#" + tab + " > .wrap").each((i, e) => {
          $("#" + tab + " .forms").append(e);
        });
      } else {
        $("#" + tab + " > .wrap input[class*='submit']:last").hide();
        $("#" + tab + ` ${diamant}`).css("display", "none");
        if (action === 'update') action = "insert";
      }
      $("#" + tab + " .forms .wrap:not(:first-child)").css("height", "0");
    }
    $("#" + tab).show();
    $(evt.currentTarget).addClass("active");
  }
}

//Insert
function setstep() {
  $(".tabcontent").hide();
  $("#" + getstep()).remove();
  $(".steps .content:not([class*='active'])").first().addClass("active");
  let step = getstep();
  $(`#${step}` + ` ${diamant}`).hide();
  $(`#${step}`).show();
  if (step === 'all') {
    $("a.detail").attr("href", `${baseurl}/Anime&id=${animeid}&kind=serie`);
    $("a.edit").attr("href", `${baseurl}/Edit&id=${animeid}`);
  }
}

function getstep() {
  let node = $(".steps .content[class*='active']").last().attr("class").split(" ");
  return node[1];
}

//BOTH
function setabform(event, tab) {
  let elem = tab.split("_");
  let node = geteditnode();
  $(`${node} input[name*='${elem[0]}']`).hide();
  $(`${node} #${elem[0]} .tablink`).removeClass("active");
  $(`${node} input[name*='${tab}']`).show();
  $(event.currentTarget).addClass("active");
}

function addform() {
  $(".tabcontent[id!='anime'] .forms .wrap").css("height", "0");
  let res = getcallapi("delete");
  //last id
  api_ajax(`${res["mod"]}&aq=lastid${res["func"]}`).then((resp) => {
    if (resp['status']['code'] === 200) {
      let node = geteditnode();
      if (action === "update") $(".tabcontent[style*='block'] .form_oculto .wrap input[class*='submit']:last").hide();
      $(".tabcontent[style*='block'] .form_oculto .wrap").clone().appendTo($(".tabcontent[style*='block']"));
      $(node).attr("id", parseInt(resp['data']) + 1);
      $(`${node} .formulario input[name='id']`).attr("value", parseInt(resp['data']) + 1);
      if (action === 'update') action = "insert";
    }
  }).catch((error) => {
    console.log(error);
  });
}

function geteditnode() {
  let tab = $(".tabcontent[style*='block']").attr("id");
  let item = " > .wrap ";
  let expand = $("#" + tab + " .forms > div:not([style*='height: 0px'])").attr("id");
  if (expand !== undefined) {
      item = " #" + expand + " ";
  }
  return "#" + tab + item;
}

function handledata() {
  let data = {};
  let tab = page_mode === 'insert' ? getstep() : $(".tabcontent[style*='block']").attr("id");
  let res = getcallapi(null, tab);
  let mod = res["mod"];
  let height = res['height'];
  let generes;
  let node = geteditnode();
  $(node + " .formulario input[name!='undefined']").each((i, e) => {
    let clave = $(e).attr("name");
    if (clave != undefined) {
      let valor = e.value;
      if ((e.type == "checkbox" || e.type == "radio") && e.checked === true) {
        if (clave == 'idiomas' && tab == 'anime') {
          animeidiomas = valor;
        } else if (clave == "generes") {
          if (generes !== undefined) valor = generes + "," + valor;
          else generes = valor;
        }
      } else if ((e.type !== "checkbox" && e.type !== "radio") || e.name == "favorites") {
        if (clave == 'siglas') animesiglas = valor;
        else if (tab == 'anime' && clave == 'id') animeid = valor;
        else if (clave == 'id') $(node).attr('id', valor);
        else if (clave == 'nombre') $(node).attr('tittle', valor);
        else if (clave == 'titulo_'+$("html").attr("lang")) valor = valor.replace("'","`");
        else if (clave == 'sinopsis_'+$("html").attr("lang")) valor = valor.replace("'","`");
        else if (clave == 'nombre') valor = valor.replace("'","`");
        else if (clave == 'descripcion') valor = valor.replace("'","`");
        
        valor = valor !== "" ? valor : null;
      }
      data[clave] = valor;
    }
  });
  let values = `validate${mod}`(data);
  if (values['valid'] === true) {
    if (mod !== 'Anime') {
      let node = $(node + " tr img").attr("name").split(".");
      let num = node[0];
      if (num.startsWith("0")) num = num.replace("0","");
      $(node + " input[name*='num']").attr("value", num);
      data['num'] = num;
      $(node).attr('tittle', num);
    }
    api_ajax(mod, false, data).then((resp) => {
      if (resp['status']['code'] === 200) {
        openalert("s", resp['status']['message']);
        if (mod !== "Anime") {
          if (action == 'insert') {
            let id = $(node).attr('id');
            $(`${node} input.submit:first`).show();
            if (page_mode === 'insert') {
              $(`${node} input.submit[value*='Insertar']`).hide();
            } else {
              $(`${node} input.submit[value*='Insertar']`).attr("value", "Actualizar");
              $(`${node} .input-group`).prepend(`<input type='button' class='submit' onclick="remove(${id})" value='Eliminar'>`);
            }

            $(`.tabcontent[style*='block'] ${diamant_element}`).append(`
            <div class='list_element' elem='${id}'> 
              <div class="img" style='background: url("${$(`${node} table tr:first-child img`).attr('src')}"); background-size: cover;' ></div>
            <div class="info">${$(node).attr('tittle')}</div>  </div>`);

            $(".tabcontent[style*='block'] .forms").append($(node));
            $(`.tabcontent[style*='block'] ${diamant}`).css("display", "flex");
            calculatediamantynimg();
          } else if (action == "update") {
            $(`.tabcontent[style*='block'] ${diamant_element} .list_element:last-child`).attr("onclick", `expand(event.currentTarget, ${height})`);
          }
        } else if (page_mode == 'insert') setstep();
      }
    }).catch((error) => {
      console.log(error);
    });
  } else {
    openalert("d", values['message']);
  }
}

function calculatediamantynimg(tab = null) {
  let left;
  let list_elem;
  if (tab) {
    if (screenwidth > 800) {
      left = $(`#${tab} > ${diamant_element} .list_element`).length > 50 ? -340 : -100;
    } else {
      left = $(`#${tab} > ${diamant_element} .list_element`).length > 50 ? -10 : 0;
    }
    list_elem = `#${tab} ${diamant_element} .list_element`;
  } else {
    if (screenwidth > 800) {
      left = $(`.tabcontent[style*='block'] > ${diamant_element} .list_element`).length > 50 ? -340 : -100;
    } else {
      left = $(`.tabcontent[style*='block'] > ${diamant_element} .list_element`).length > 50 ? -10 : 0;
    }
    list_elem = `.tabcontent[style*='block'] ${diamant_element} .list_element`;
  }
  $(list_elem).each((i, e) => {
    if (i > 0) {
      if (screenwidth > 800) {
        left = parseInt(left) + 25;
      } else {
        left = parseInt(left) + 16;
      }
    }
    $(e).css("left",`${left}%`);
  });
}

function expand(element, height) {
  let list = element.attributes.elem.nodeValue;
  if (parseInt($(`.tabcontent[style*='block'] > .forms > div[id='${list}']`).css("height")) < height) {
    $(".tabcontent[style*='block'] .forms .wrap").css("height", "0");
    $(`.tabcontent[style*='block'] > .forms > div[id='${list}']`).css("height", height + "px");
  }
}

function remove(id) {
  let res = getcallapi("delete");
  api_ajax(res['mod'], false,{"action": `deleteOne${res['func']}`, "id": id} ).then((resp) => {
    if (resp['status']['code'] === 200) {
      if (res['mod'] === "Anime") {
        window.location = `${baseurl}/Edit`;
      } else {
        if ($(`.tabcontent[style*='block'] ${diamant_element} .list_element`).length > 0) {
          $(`.tabcontent[style*='block'] ${diamant_element} .list_element[elem='${id}']`).remove();
          $(`.tabcontent[style*='block'] .forms .list_element[elem='${id}']`).remove();
          $(`.tabcontent[style*='block'] .forms .list_element:last`).show();
        } else {
          $(`.tabcontent[style*='block'] ${diamant}`).hide();
          addform();
        }
      }
      return resp['data'];
    }
  }).catch((error) => {
    console.log(error);
  });
}

function getcallapi(kind = null, tab = null) {
  if (tab == null) {
    tab = $(".tabcontent[style*='block']").attr("id");
  }
  switch (tab) {
    case "anime":
      mod = "Anime";
      func = "anime";
      height = 0;
      break;
    case "episodes":
      mod = "Episodes";
      func = "episode";
      height = 901;
      break;
    case "openings":
      mod = "Openings";
      func = "opening";
      height = 701;
      break;
    case "endings":
      mod = "Endings";
      func = "ending";
      height = 701;
      break;
    default: mod = "all"; break;
  }
  let res = {};
  if (mod !== 'all') {
    res.mod = mod;
    if (kind === 'delete') res.func = func; 
    else res.height = height;
  }
  return res;
}