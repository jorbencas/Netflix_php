'use strict';
function addelement(name) {
  let data = { 
    "action": 'addelementcollection', 
    "name": $(".collections .input_enviar").val() !== '' ? $(" .collections .input_enviar").val() : name,
    "user":$("#user").text(),
    "episode": $(" .collections .input-episode").val()
  };
  api_ajax("Collections", false,data).then((resp) => {
    if (resp['status']['code'] === 200) {
      openalert("s", resp['status']['message']);
      if (name ===  undefined) {
        let elem = `<div class='input-group radio'>
          <input type='radio' id='${resp['data'][0]['id']}' checked  onclick='removeone('${resp['data'][0]['id']}')' value='${resp['data'][0]['id']}'>
          <label for='${resp['data'][0]['id']}' class='label'>${resp['data'][0]['titulo']}</label>
          </div>`;
        $(".collections").append(elem);
      } else {
        $("#"+ resp['data'][0]["id"]).attr("checked",true);
        console.log("#"+ resp['data'][0]["id"]);
      }
      return resp['data'];
    }
  }).catch((error) => {
    console.log(error);
  });
}