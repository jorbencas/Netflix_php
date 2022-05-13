'use strict';
function add() {
  let data = { 
    "action": 'insertcomment', 
    "comment": $(".estado .input_enviar").val(),
    "user":localStorage.getItem("user"),
    "episode":$(".input-episode").val() !== "" ? $(".input-episode").val() : null,
    "anime":$(".input-anime").val() !== "" ? $(".input-anime").val() : null,
    "manga":$(".input-manga").val() !== "" ? $(".input-manga").val() : null,
  };
  api_ajax("Comments", false,data).then((resp) => {
    if (resp['status']['code'] === 200) {
      let elements = '';
      resp['data'].forEach(element => {
        elements += `<li class="comentario">
          <div class="info_avatar">
              <div class="avatar">
                  <img src="${element['avatar']}" alt="${element['user']}">
              </div>
          </div>
          <div class="line">
            <p class="line_comment">${element['comment']}</p>
              <div class="date">
                <p class="">${element['fecha']}</p>
                <p class="">${element['hora']}</p>
              </div>
          </div>
      </li>`});

      if ($(".commentarios .comentario").length == 0) {
        $(".wrapper").remove();
        let node = '.episode-page';
        if (modulo === 'User' ) node = '#comments';
        else if (modulo === 'Anime') node = 'main';

        $(node).append("<div class='commentarios'></div>");
      } else {
        $(".commentarios .comentario").remove();
      }
      $(".commentarios").append(elements);
      openalert("s", resp['status']['message']);
      return resp['data'];
    }
  }).catch( (error) => {
    console.log(error);
  });
}

function remove(id) {
  let data = {
    "action": 'deleteOnecomment', 
    "id": id,
    'user':localStorage.getItem("user")
  };
  api_ajax("Comments", false, data).then((resp) => {
    if (resp['status']['code'] === 200) {
      if (resp['data'].length > 0) {
        $(".commentarios .comentario").remove();
        let elements = '';
        resp['data'].forEach(element => {
          elements += `<li class="comentario">
            <div class="info_avatar">
                <div class="avatar">
                    <img src="${element['avatar']}" alt="${element['user']}">
                </div>
            </div>
            <div class="line">
              <p class="line_comment">${element['comment']}</p>
                <div class="date">
                  <p class="">${element['fecha']}</p>
                  <p class="">${element['hora']}</p>
                </div>
            </div>
            <div class="info_avatar" onclick="remove(${element['id']})">
              <i class='fa fa-trash' style='font-size:20px;'></i>
          </div>
        </li>`});
        $(".commentarios").append(elements);
        openalert("s", resp['status']['message']);
      } else {
        $(".commentarios").remove();
        $("#comments").append(`
        <div class='wrapper'>
          <div class='mensajes'>
            <div class='notification info $timeout'>
              ${resp['status']['message']}
            </div>
          </div>
        </div>`);
        $(".wrapper").show();
      }
      return resp['data'];
    }
  }).catch((error) => {
      console.log(error);
  });
}
