'use strict';
$(document).ready(function () {
  $('.modal_background').css('opacity', '0.6').show();
  $('.modal_content').css('opacity', '1').show();
  $("body").css("overflow", "hidden");
});

function closemodal() {
  if($("#modalsAssets")){
    $.parseJSON(modalsAssets).forEach(element => {
      $("head link[href*='components/"+element+"'], body script[src*='components/"+element+"']").remove();
    });
    $("#modalsAssets").remove();
  }
  $('.modal_background').remove();
  $('.modal_content').remove();
  $("body").css("overflow", "auto");
}