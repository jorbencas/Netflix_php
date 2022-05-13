'use strict';
function removeone(id) {
  let data = { 
    "action": 'removeonecollection', 
    "user":localStorage.getItem("user"),
    "id":$(".input-episode").text(),
    "episode": id 
  };
  api_ajax("Collections", false,data).then((resp) => {
    if (resp['status']['code'] === 200) {
      openalert("s", resp['status']['message']);
      $("#"+ resp['data'][0]["episode_id"]).remove();
      console.log("#"+ resp['data'][0]["episode_id"]);
      return resp['data'];
    }
  }).catch( (error) => {
    console.log(error);
  });
}

function removeall(id) {
  let data = { 
    "action": 'removecollection', 
    "user":localStorage.getItem("user"),
    "id":id
  };
  api_ajax("Collections", false,data).then((resp) => {
    if (resp['status']['code'] === 200) {
      openalert("s", resp['status']['message']);
      location.href = `${getbaseurl()}User`;
      //return resp['data'];
    }
  }).catch((error) => {
    console.log(error);
  });
}