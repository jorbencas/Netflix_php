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
    
  function handlesearch(event) {
    let active = 'Anime';
    let leng = event.value.length;
    if (leng < 3) {
      $("form").attr("action","javascript:void(0);");
      $(".search_contenent .lista_resultados").html(`<div class='wrapper'>
      <p>El termino de busqueda debede contener al menos 3 caracteres</p>
      </div>`);
    } else if (leng > 3 ) {
      console.log("Hola Mundo " + leng);
      setAction();
      let timeout;
      $(".search_contenent input[name='search']").val(event.value);
      let limite = $(".search_contenent input[name='limit']").val();
      let data = {
        action: $(".search_contenent input[name='action']").val(),
        search: $(".search_contenent input[name='search']").val(), 
        kind: $(".search_contenent input[name='kind']").val(),
        user: $(".search_contenent input[name='user']").val(),
        limit: limite
      };
      console.log(data);
      clearTimeout(timeout);
      timeout = setTimeout(() => { 
        api_ajax(`Filters`,false,data).then((resp) => {
          if (resp['status']['code'] === 200) {
            if (resp['data'].length > 0) {
              let results = '<div class="animes">';
              resp['data'].forEach(element => {
                let tittle = element["titulo"];
                if (tittle.length > 27) {
                  tittle = tittle.substring(0,27) +"...";
                }
                let text = element["sinopsis"];
                if (text.length > 49) {
                  text = text.substring(0,49) +"...";
                }
              
                results += ` 
                <a href='${getbaseurl()}Anime&id=${element["id"]}' class='animes_element' id='${element["id"]}'>
                  <div class='img'>
                      <img  src='${element["src"]}' >
                  </div>
                  <div class='info'>
                      <h3> ${tittle} </h3>
                      <p> ${text} </p>
                  </div>
                </a>`;
              });
              results += '</div>';
      
              if ((resp['data'].length - 1) > limite) {
                results += `<div class='more_button'>
                <input type='submit' class='search_icon' value='Ver mas +' /> 
                </div>`;
              }
              $(".search_contenent .lista_resultados").html(results);
            } else {
              $(".search_contenent .lista_resultados").html(`
              <div class='wrapper'>
                <h1>No se han encontrado ${active} que coincidan con el termino:  ${data.search} </h1>
              </div>`);
            }
          } else {
            $(".search_contenent .lista_resultados").html(`
            <div class='wrapper'>
              <h1>${resp['status']['message']}</h1>
            </div>`);
          }
        }).catch((error) => {
          $(".search_contenent .lista_resultados").html(`
          <div class='wrapper'>
            <h1>${error}</h1>
          </div>`);
        });
        $(".search_contenent .lista_resultados").show();
      }, 500);
    }
  };