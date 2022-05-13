'use strict';$
$("#add").click(function () {
    let cant = $("#cant").val();
    if (cant >= 0) $("#cant").val(parseInt(cant) + 1);
  });
  
  $("#remove").click(function () {
    let cant = $("#cant").val();
    if (cant > 0) $("#cant").val(parseInt(cant) - 1);
  });