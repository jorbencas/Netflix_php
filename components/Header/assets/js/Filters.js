'use strict';
function tooglefilter(id) {
  if ($(`#${id}`).hasClass("collapsed")) {
    var height;
    if (!$(`#${id}-list`).attr("height")) {
      switch (id) {
        case 'g': height = '329px'; break;
        case 'y': height = '188px'; break;
        case 'k': height = '47px'; break;
        case 'i': height = '47px'; break; 
        case 't': height = '47px'; break;
        default: height = '47px'; break;
      }
      $(`#${id}-list`).attr("height",height);
    } else {
      height = $(`#${id}-list`).attr("height");
    }
    $(".filters .list").css('height', 0);
    $(`.expanded  > .link`).text($(`.expanded`).attr('title'));
    $(`#${id}-list`).css("height", height);
    $(`#${id}`).removeClass('collapsed').addClass('expanded');
    $(`#${id} > .link`).text('Cerrar');
  } else {
    $(`#${id}-list`).css("height", 0);
    $(`#${id}`).removeClass('expanded').addClass('collapsed');
    $(`#${id} > .link`).text($(`#${id}`).attr("title"));
  }
}