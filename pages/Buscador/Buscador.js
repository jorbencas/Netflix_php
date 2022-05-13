'use strict';
let action = $("form").attr("action");

function ceckContent(){
    if ($("form input[name='search']").val().length == 0) {
      $("form").attr("action","javascript:void(0);");
    } else {
      setAction();
    }
  }
  
  function setAction(){
    if ($("form").attr("action") !== action) {
      $("form").attr("action",action);
    }
  }
  
  function heandlesearched(event) {
    if (event.value.length > 0) {
      $("input[name='search']").val(event.value);
      setAction();
    } else {
      $("form").attr("action","javascript:void(0);");
    }
  }
    