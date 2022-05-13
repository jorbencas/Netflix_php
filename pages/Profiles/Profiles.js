'use strict';
function setprofile(id) {
    $(".admin_element .img").css("border-color","var(--main-grey)");
    $(`#${id} .img`).css("border-color","var(--color-text)");
    $("#acces input[name=id_profile]").attr("value",id);
}
