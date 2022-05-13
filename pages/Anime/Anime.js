'use strict';
$(document).ready(function() {
  let screenwidth = $(window).width();
  if (screenwidth <= 800) {
    $("#episodes p").text("Epi");
    $("#personages p").text("Pers");
    $("#openings p").text("Op");
    $("#endings p").text("Ed");
  }
});

function more_less(event) {
  if ($(event).hasClass("mas")) {
    $("."+$(event).attr("class") + " i").removeClass("fa-plus").addClass("fa-minus");
    $(event).removeClass("mas").addClass("menos");
    $("#sinopsis").text($("#sinopsis").text().substring(0,149) +"...");
  } else {
    $("."+$(event).attr("class") + " i").removeClass("fa-minus").addClass("fa-plus");
    $(event).removeClass("menos").addClass("mas");
    $("#sinopsis").text($("#sinopsis").attr("text"));
  }
}

function setab(evt, tab) {
  $(".tabcontent").hide();
  $(".tablinks").addClass("active");
  $("#" + tab).show();
  $(evt.currentTarget).removeClass("active");
}

function setabdetail(evt, tab, permited) {
  $("main > .tabcontent").hide();
  $(".tablinks").removeClass("active");
  $("#" + tab).show();
  if (permited) {
    $(".tablink").css("visibility","visible");//mostramos los botones de grid y tabla ya que no es el modulo de personage
    let active = $(".tablink.active").attr("id");
    let disabled = active == 'grid' ? 'tabla': 'grid';
    $("#" + tab + disabled).hide();
    $("#" + tab + active).show();
  } else {
    $(".tablink").css("visibility","hidden");//Ocultamos los botones de grid y tabla ya que no es el modulo de personage
  }
  $(evt.currentTarget).addClass("active");
}

function setabcontent(evt,disabled) {
  let active = $(".tablink.active").attr("id");
  if (disabled !== active) {
    $(".tablink").removeClass("active");
    let tab = $(".tablinks.active").attr("id");
    
    let tab_active = tab + active;//Episodestable (st)
    let remove = tab_active.includes("st") ? tab_active.replace("st", "t") : tab_active.replace("sg", "g");
    $("#" + remove).hide();

    let tab_disabled = tab + disabled;//Episodesgrid (sg)
    let show = tab_disabled.includes("st") ? tab_disabled.replace("st", "t") : tab_disabled.replace("sg", "g");
    $("#" + show).show();
    $(evt.currentTarget).addClass("active");
  }
}

function setvalorations(valoration, anime) {
  $("#" + anime + " .star-rating span").each((i,e) => {
    let star = i <= valoration ? "fas fa-star" : "far fa-star";
    $("#"+e.id + " i").attr("class",star);
  });
  $("#" + anime + " input[name*='valorations']").attr("value",valoration + 1);
}

function setfavorite(fav, id, elem) {
  let action = fav === "far fa-heart" ? 'addfav': 'removefav';
  let data = {
    "action": action,
    "id":id,
    "user":localStorage.getItem("user")
  };
  api_ajax("Anime", false,data).then( (resp) => {
    if (resp['status']['code'] === 200) {
      $(elem).attr("onclick",$(elem).attr("onclick").replace(fav,resp['data']));
      $(".favorite i").removeClass(`${fav}`).addClass(`${resp['data']}`);
    }
  }).catch((error) => {
    console.log(error);
  });
}