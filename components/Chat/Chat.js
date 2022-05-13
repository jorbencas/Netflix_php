'use strict';
function closechat() {
  $(".chat_section").hide();
  $('.boton_chat').show();
}

function openchat() {
  $(".chat_section").show();
  $('.boton_chat').hide();
}

function sendmessage() {
  if ($(".toolbar .info_avatar .info_usuario .nombre").text() !== '' && 
  ($(".input_enviar").val() !== undefined || (".input_enviar").val() !== null 
  || (".input_enviar").val() !== '')) {
    let class_side = "";
    let item = "";
    let user = localStorage.getItem('user');
    let data = {
      "user": user, 
      "msg_text": $(".input_enviar").val(),
      "receptor":$(".toolbar .info_avatar .info_usuario .nombre").text(),
      "action":"insertmessage"
    };
    api_ajax("Chat", false,data).then((resp) => {
      if (resp['status']['code'] === 200) {
        resp['data'].forEach(elem => {
          if (elem['emiitter'] === user && elem['receptor'] !== user) {
            class_side = 'mymessague';
          } else {
            class_side = 'message';
          }
          item =+ `<div class="item ${class_side}">${elem['message']}</div>`;
        });
        $(".lista_mensagues p ").hide();
        $(".lista_mensagues").append(item).show();
      }
    }).catch((error) => {
      console.log(error);
    });
  } else {
    openalert("d", "Escribe el mensage para enviar|||");
  }
}

function setcontact() {
  if ($(".list_users").css("display") === 'block') {
    $(".lista_mensagues").show();
    $(".box_text").show();
    $(".toolbar").show();
    $(".list_users").hide();
  } else {
    $(".lista_mensagues").hide();
    $(".box_text").hide();
    $(".toolbar").hide();
    $(".list_users").show();
  }
}

function selectcontact(event) {
  $(".toolbar .info_avatar .info_usuario .estado").text($(event.children[1].children[1]).text());
  $(".toolbar .info_avatar .info_usuario .nombre").text($(event.children[1].children[0]).text());
  $(".toolbar .info_avatar .avatar img").attr("src", $(event.children[0].children[0]).attr("src"));
  let class_side = "";
  let item = "";
  let user = localStorage.getItem('user');
  let chat = reloadchat($(".toolbar .info_avatar .info_usuario .nombre").text());
  if (chat) {
    chat.forEach(elem => {
      if (elem['emiitter'] === user && elem['receptor'] !== user) {
        class_side = 'mymessague';
      } else {
        class_side = 'message';
      }
      item += `
      <div class="item">
        <div class='${class_side}'>
          ${elem['message']}
        </div>
      </div>`;
    });
    
    $(".lista_mensagues").append(item); 
  }
  
  $(".lista_mensagues").show();
  $(".box_text").show();
  $(".toolbar").show();
  $(".list_users").hide();

}

function reloadchat(receptor) {
  let data = {
    "user": localStorage.getItem("user"), 
    "receptor":receptor,
    "action":"listmessages"
  };
  api_ajax("Chat", false,data).then((resp) => {
    if (resp['status']['code'] === 200) return resp['data'];
    else openalert("d", resp['status']['message']);
  }).catch((error) => {
    console.log(error);  
  });
}