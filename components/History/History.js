'use strict';
function removeone(id) {
    let data = {"action":"deletelement", "id":id};
    api_ajax(`History`,false,data).then((resp) => {
        if (resp['status']['code'] === 200) {
            $(`#${id}`).remove();
        }
    }).catch((error) => {
        console.log(error);
    });
}