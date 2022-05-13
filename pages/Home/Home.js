"use strict";
if (typeof filters !== "undefined") {

//   var timestamp = null;
// var lastMouseX = null;
// var lastMouseY = null;

// document.body.addEventListener("mousemove", function(e) {
//     if (timestamp === null) {
//         timestamp = Date.now();
//         lastMouseX = e.screenX;
//         lastMouseY = e.screenY;
//         return;
//     }

//     var now = Date.now();
//     var dt =  now - timestamp;
//     var dx = e.screenX - lastMouseX;
//     var dy = e.screenY - lastMouseY;
//     var speedX = Math.round(dx / dt * 100);
//     var speedY = Math.round(dy / dt * 100);

//     console.log(speedX);
//     console.log(speedY);
//     timestamp = now;
//     lastMouseX = e.screenX;
//     lastMouseY = e.screenY;
// });

//   var time = 200
// var tracker = setInterval(function(){
//     historicTouchX = touchX;
// }, time);

// document.addEventListener("touchmove", function(){
//     speed = (historicTouchX - touchX) / time;
//     console.log(Math.abs(speed));
// }, false);


  var filt = $.parseJSON(filters);
  let baseurl = getbaseurl();
  let element;
  var current;
  var next;
  //Obtenemos el elemnto que esta disponible para buscar
  filt.forEach((f, i) => {
    if (f.avable == true) {
      element = f;
      current = i;
      next = current + 1;
    }
  });
  // let target = $("#generes_final");
  // let windowHeight = $(window).height();
  $(window).scroll(function () {
    if ( filt.length > 0 && typeof element !== 'undefined' && parseInt(next) > parseInt(current) 
    && element.avable === true /*&& inView(target) && windowHeight > getViewportOffset(target) */
    ) {
      viewloading();
      if ($(".generes .container-loading").length > 1) {
        $(".generes .container-loading").remove();
        viewloading();
      }
      if ( parseInt(next) < filt.length) {
        api_ajax(`Anime&f=generes_${element["filter"]}&as=0_11`).then((resp) => {
          if (resp["status"]["code"] == 200) {
            api_ajax("Grid", false, {
              mod: "Anime",
              elements: resp["data"],
              field: "Anime",
              theme: $("html").attr("theme"),
            }).then((resp) => {
              if (resp["content"] !== "") {
                setTimeout(function(){
                  if (typeof element !== 'undefined' && parseInt(next) < filt.length) {
                    $(".generes .container-loading ").remove();
                    $("#generes_final").remove();
                    $(".generes").append(
                        `<div class='home_slide_banner'><h3>${element.filter}</h3> <a class='link' href='${baseurl}Anime&f=generes_${element.filter}'>Ver mas + </p></div>`
                    ).append(resp["content"]).append("<div id='generes_final'></div>");
                    element.avable = false;
                    element = filt[parseInt(next)];
                    element.avable = true;
                    current = next;
                    next = current + 1;
                  }
                }, 500);
              }
            });
          }
        }).catch((error) => {
          console.log(error);
        });
      } else {
        let user = $("#user").text().length > 0 ? $("#user").text() : null;
        if (user !== null) {
          api_ajax("Buscador", false, { 
            action: "mysearches", 
            user: user 
          }).then((resp) => {
            if (resp["status"]["code"] == 200) {
              api_ajax("Grid", false, {
                mod: "Anime",
                elements: resp["data"],
                field: "Anime",
                theme: $("html").attr("theme"),
              }).then((resp) => {
                if (resp["content"] !== "") {
                  setTimeout(function(){
                    $(".generes .container-loading ").remove();
                    $("#generes_final").remove();
                    $(".generes").append(
                      `<div class='home_slide_banner'><h3>Animes interesanted </h3> </div>`
                    ).append(resp["content"]).append("<div id='generes_final'></div>");
                  }, 500);
                }
              });
            }
          }).catch((error) => {
            console.log(error);
          });
        } else {
          $("#generes_final").remove();
          $(".generes .container-loading ").remove();
        }
      }
    }
  });

  function viewloading() {
    $(".content-loader .container-loading").clone().appendTo($(".generes"));
    $(" .generes .content-loader .container-loading").show();
    $(" .generes .container-loading").css("margin", "auto");
    $(" .generes .container-loading").css("background", "transparent");
  }

  function getViewportOffset(element) {
    var scrollTop = $(window).scrollTop(),
      offset = $(element).offset();
    return offset.top - scrollTop;
  }

  function inView(element, fullHeightInView) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();
    var elemTop = $(element).offset().top;
    var elemBottom;

    if (fullHeightInView) {
      elemBottom = elemTop + $(element).height();
    } else {
      elemBottom = elemTop;
    }

    return (
      elemBottom >= docViewTop &&
      elemTop <= docViewBottom &&
      elemBottom <= docViewBottom &&
      elemTop >= docViewTop
    );
  }
}
