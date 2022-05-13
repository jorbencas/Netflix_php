'use strict';
// Este javascript tiene una variable de parametros: parametros.creada desde upload.php

$(document).ready(function () {
    //Para mover las imagenes de orden
    $(getnode() + ".messagesupload").hide();
});

var fileExtension = "";
var hayarchivosubir = false;
var hayarchivoimportar = false;

//función que observa los cambios del campo file y obtiene información
function leerinfo(_this) {

    var files = _this.files;
    var fileSize_total = 0;
    for (var i = 0; i < files.length; i++) {
        //obtenemos un array con los datos del archivo
        var file = files[i];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
        if (!isImage(fileExtension) && !isVideo(fileExtension)) {
            hayarchivosubir = false;
            showMessage("<span class='info'>Nombre de extensión (" + fileExtension + ") no valido</span>");
            break;
        }
        else if (fileName.split(".").length > 2) {
            hayarchivosubir = false;
            showMessage("<span class='info'>Solo se permite un punto (.) en el nombre</span>");
            break;
        } else if (!$(getnode() + " .formulario input[name='siglas']").val()) {
            hayarchivosubir = false;
            showMessage("<span class='info'>Debes de introducir la ssiglas del anime, personage, episodio... antes de poder subrir cualquier fichero multimedia.</span>");
            break;
        }
        else {
            fileSize_total += fileSize;
            hayarchivosubir = true;
            showMessage("<span class='info'> " + files.length + " archivos para subir, peso total: " + Math.round((fileSize_total / 1024 / 1024) * 100) / 100 + " Mbytes.</span>");
        }
    };
   
}

function setanime(valor) {
    $(getnode() + ` .upload .tablinks[valor!="${valor}"]`).removeClass("active");
    $(getnode() + ` .upload .tablinks[valor*="${valor}"]`).addClass("active");
}

function clickcreateform() {
    let siglas = $(getnode() + " .formulario input[name='siglas']").val();
    let anime = $(getnode() + " .formulario input[name='anime']").val();
    let idioma = $(getnode() + " .formulario input[name='idiomas']:checked").val();
    let personage = null;
    let episode = null;
    let openings = null;
    let endings = null
    if (modulo === "profile") {
        let kind = 'profiles';
        if (idioma == undefined) idioma = null;
        if (siglas === undefined) siglas = undefined;
        let profile = localStorage.getItem('profile');
    } else {
        let kind = $(".tabcontent[style*='block']").attr("id");
        if (idioma == undefined) idioma = animeidiomas;
        if (siglas === undefined) siglas = animesiglas;
        let profile = undefined;
    }

    switch (kind) {
        case "anime": anime = $(getnode() + " .formulario input[name='id']").val(); break;
        case "personages": personage = $(getnode() + " .formulario input[name*='id']").val(); break;
        case "episodes": episode = $(getnode() + " .formulario input[name*='id']").val();break;
        case "openings": openings = $(getnode() + " .formulario input[name*='id']").val(); break;
        case "endings": endings = $(getnode() + " .formulario input[name*='id']").val();break;
    }

    if (kind == "anime") {
        let tabs =  `<ul class="tab">
            <div class="tablinks active" valor='banner' onclick="setanime('banner');"><i class="fas fa-images" style="color:var(--color-text);">&nbsp; B</i></div>
            <div class="tablinks" valor='portada' onclick="setanime('portada');"><i class="fas fa-image" style="color:var(--color-text);">&nbsp; P</i></div>
            <div class=" tablinks messagesupload"></div>
        </ul>`;
    } else {
        let tabs = `<div class=" tablinks messagesupload"></div>`;
    }

    var dataform = `
        <div class="formpopupupdate upload">
            <form id="formcreatewrap" class="formulario" enctype="multipart/form-data">
                <div class="barraTop">Subir archivos <span onclick="$('${getnode()} .formpopupupdate').remove();">x</span></div>
                    <div>
                        <div class="buttons" id="uploadimagensubir">
                        <input class="text" onchange="leerinfo(this)" name="file[]" type="file" accept="image/png, .jpeg, .jpg, image/gif, .mp4, .avi, .webm, .mkv, .flv"  multiple/>
                        <div class="button" onclick="clickbuttonupload('subir')"> <i class="fas fa-cloud-upload-alt"></i></div>
                    </div>

                    <div class="buttons" id="uploadimagenimportar">
                        <input class="text" id="uploadimagenimportarurl" name="file2" type="url" accept="image/png, .jpeg, .jpg, image/gif, .mp4, .avi, .webm, .mkv, .flv" />
                        <div class="button" onclick="clickbuttonupload('importar')"><i class="fas fa-file-import"></i></div>
                    </div>
                    ${tabs}
                    <input name='sizeminH' value='300' type='hidden'/>
                    <input name='main' value='0' type='hidden'/>
                    <input name='anime' value='${anime}' type='hidden'/>
                    <input name='personage' value='${personage}' type='hidden'/>
                    <input name='episode' value='${episode}' type='hidden'/>
                    <input name='openings' value='${openings}' type='hidden'/>
                    <input name='endings' value='${endings}' type='hidden'/>
                    <input name='siglas' value='${siglas}' type='hidden'/>
                    <input name='idioma' value='${idioma}' type='hidden'/>
                    <input name='profile' value='${profile}' type='hidden'/>
                    <input name='textform' id='iduploadinputform' value='' type='hidden'/>
                </div>
            </form>
        </div>
        `;
    //añadimos este codigo al final del body. automaticamente, aparecera el popup
    $(getnode() + " .upload").append(dataform);


    if (kind == "anime" && animeid === undefined) {
        let type = $(getnode() + " table tr td:first-child img").attr("type");
        if (type == "banner") {
            setanime('portada');
        } else if (type == "portada") {
            setanime('banner');
        } 
    }
}

function clickbuttonupload(option) {
    //aqui comprobaremos si es importacion o subida.
    if (option == "subir") {
        //hay archivo seleccionada?
        if (hayarchivosubir) {
            sendajax(option);
        } else {
            showMessage("<span class='messageupload'>Vuelva a seleccionar algún archivo</span>");
        }
    } else if (option == "importar") {
        var namefileimport = $('#uploadimagenimportarurl').val();
        var namefileimport_array = namefileimport.split(".");
        if (namefileimport == "") {
            showMessage("<span class='messageupload'>URL no válida</span>");
        } else if (!isImage(namefileimport_array[namefileimport_array.length - 1]) && !isVideo(namefileimport_array[namefileimport_array.length - 1])) {
            showMessage("<span class='info'>Nombre de extensión (" + namefileimport_array[namefileimport_array.length - 1] + ") no valido</span>");
        } else {
            sendajax(option);
        }
    }
}

function sendajax(option) {
    // message = `<div class='importador'>
    //     <strong class='start'><i class='fas fa-info-circle'></i></strong>
    //     <div id='myProgress' class='start'>
    //         <div class='bar' id='bar'>0%</div>
    //     </div>
    // </div>`;
    // showMessage(message);
    // var width = 0;
    // var timeout = setInterval(frame(width, timeout), 1000);
    //información del formulario
    var formdata = new FormData($(getnode() + " .upload #formcreatewrap")[0]);
    var message = "";
    var content = ""; 
    let kind = "";
    if (modulo !== "profile") {   
        kind = $(".tabcontent[style*='block']").attr("id");
        if (kind == "anime") kind = $(getnode() + " .upload .tablinks.active").attr("valor");
    } else  kind = 'profiles';
    formdata.append('type', kind);
    formdata.append('action', option);
    api_ajax("Upload",true, formdata).then((msg) => {
        if (msg['status']['code'] == 200) {
            $(getnode() + " .sinID").remove();
            for (var i = 0; i < msg['data'].length; i++) {
                $(getnode() + " .formpopupupdate").remove();
                var descargar_fichero = "<a class='descargarfoto' href='" + msg['data'][i]['urldescarga'] + "' download><i class='fas fa-cloud-download-alt'></i></a>";
                if (isImage(msg['data'][i]['extension'])) {
                    if (msg['data'][i]['main'] == '1') {
                        class_principal = "div_principal";
                        var texto_principal = "<p class='principal' id='principal'>Imagen Principal</p>";
                    } else {
                        var class_principal = "";
                        var texto_principal = "<p style='display:none;' class='principal' id='principal' >Imagen Principal</p>";
                    }

                    content = "<tr class='div_img " + class_principal + "' id='idlimedia_" + msg['data'][i]['id'] + "' ><td class='img_div'>" + descargar_fichero + "<img  class='" + class_principal + "'onclick='selecionar_principal()' idmedia='idmedia_" + msg['data'][i]['id'] + "' type='" + msg['data'][i]['type'] +"' kind='" + msg['data'][i]['kind'] + "' id_relative='"+ msg['data'][i]['id_relative'] +"' siglas='"+ msg['data'][i]['siglas'] +"' idioma='" +  msg['data'][i]['idiomas'] + "' name=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + " src=" + msg['data'][i]['urlarchivo'] + " title=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + "/> </td> <td> <p>" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + "</p> <p> tamaño: " + msg['data'][i]['filesize'] + " </p>" + texto_principal + "</td> <td style='text-align:right;'> <div class='buttons'><div class='button' onclick='deletefile(" + msg['data'][i]['id'] + ")'><i class='fa fa-trash' style='font-size:20px;'></i></div></div></td></td></tr>";
                } else if (isVideo(msg['data'][i]['extension'])) {
                    content = "<tr class='div_pdf' id='idlimedia_" + msg['data'][i]['id'] + "' ><td class='img_div'>" + descargar_fichero + "<img  idmedia='idmedia_" + msg['data'][i]['id'] + "' type='" + msg['data'][i]['type'] +" kind='" + kind + "' id_relative='"+ msg['data'][i]['id_relative'] +"' siglas='"+ msg['data'][i]['siglas'] +"' idioma='" +  msg['data'][i]['idiomas'] + "' name=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + " src='" + msg['data'][i]['urlarchivo'] +"' title=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + "/> </td> <td> " + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + " <br> tamaño: " + msg['data'][i]['filesize'] + "</td> <td style='text-align:right;'> <div class='buttons'><div class='button' onclick='deletefile(" + msg['data'][i]['id'] + ")'><i class='fa fa-trash' style='font-size:20px;'></i></div></div></td></td></tr>";
                }
                $(getnode() + " #sortableupload table").append(content);
            };//fin foreach
        } else {
            message = msg['status']['text'];
            showMessage(message);
        }
        hayarchivosubir = false;

    }).catch((error) => {
        //req_fail(jqXHR, textStatus);
        message = "<span class='messageupload'>ERROR! Archivo no valido.</span>";
        showMessage(message);
    });
}


// function frame(width, timeout = null) {
//     if (width >= 100) {
//         clearInterval(timeout);
//     } else { 
//     if (width == 0 || width < 35) { 
//         if ($(getnode() + " .importador strong").hasClass("sucess")) {
//         $(getnode() + " .importador strong").removeClass("sucess").addClass("error");
//         $(getnode() + " .importador strong i").removeClass("fas fa-check-circle").addClass("fas fa-times-circle");
//         $(getnode() + " .importador div").removeClass("sucess").addClass("error");
//         //$("p[class='sucess']").hide();
//         } else {
//         $(getnode() + " .importador strong").removeClass("start").addClass("error");
//         $(getnode() + " .importador strong i").removeClass("fas fa-info-circle").addClass("fas fa-times-circle");
//         $(getnode() + " .importador div").removeClass("start").addClass("error");
//         //$("p[class='start']").hide();
//         }
//         //$("p[class='error']").show();
//     } else if (width >= 35 && width < 65) {
//         $(getnode() + " .importador strong").removeClass("error").addClass("start");
//         $(getnode() + " .importador strong i").removeClass("fas fa-times-circle").addClass("fas fa-info-circle");
//         $(getnode() + " .importador div").removeClass("error").addClass("start");
//         // $("p[class='error']").hide();
//         // $("p[class='start']").show();
//     } else {
//         $(getnode() + " .importador strong").removeClass("start").addClass("sucess");
//         $(getnode() + " .importador strong i").removeClass("fas fa-info-circle").addClass("fas fa-check-circle");
//         $(getnode() + " .importador div").removeClass("start").addClass("sucess");
//         // $("p[class='start']").hide();
//         // $("p[class='sucess']").show();
//     }
//     width++;
//     $(getnode() + " #bar").attr("style",`width:${width}%;`);
//     $(getnode() + " #bar").html( width  + "%");
//     }
// }


function clickselectfoto() {
    var dataform = `
        <div class="formpopupupdate upload" id="modal_upload">
            <form id="formcreatewrap" class="formulario" enctype="multipart/form-data">
                <div class="barraTop">Seleciona una de las imagenes <span onclick="$('${getnode()} .formpopupupdate').remove();">x</span></div>
                    <div class="contenido_principal"><div>
                    <ul class="div_img" id='sortableupload_modal'></ul>
                    <div class="messagesupload"></div>
                    <input name='textform' id='orden_modal' value='' type='hidden'/>

                    <div class="buttons" id="uploadimagenimportar">
                        <div class="button" onclick="$('${getnode()} .formpopupupdate').remove();"><i class="fas fa-save"></i></div>
                    </div>
                </div>
            </form>
        </div>`;

    $(getnode() + " .upload").append(dataform);
    obtenerfotosordenadas();
    $(getnode() + " .formpopupupdate").ready(function () {
        $(getnode() + " #sortableupload_modal").sortable({
            classes: {
                "ui-sortable-active": "papeleraopen",
                "ui-sortable-hover": "papeleradelete"
            },
            containment: getnode() + " #sortableupload_modal",
            scroll: false,
            stop: function (ui, event) {
                var content = [];
                $(getnode() + " .contenido_principal div .div_img img").each(function (index, element) {
                    var elemento = element.attributes.idmedia.nodeValue;
                    var id = elemento.replace("idmedia_", "");
                    content[index] = id + "," + index;
                });
            }
        });
    });
}

function getnode() {
    let tab = $(".tabcontent[style*='block']").attr("id");
    let item = " > .wrap ";
    let expand = $("#" + tab + " .forms > div:not([style*='height: 0px'])").attr("id");
    if (expand !== undefined) {
        item = " #" + expand + " ";
    }
    return "#" + tab + item;
}

function deletefile(_this) {
    var element = $("#idlimedia_" + _this + " td > img");
    let datos = element[0].attributes;
    let parametros = {};
    parametros['iddelete'] = datos.idmedia.nodeValue.replace("idmedia_", "");
    parametros['namedelete'] = datos.name.nodeValue;
    parametros['id_relative'] = datos.id_relative.nodeValue; 
    parametros['kind'] = datos.kind.nodeValue;
    parametros['type'] = datos.type.nodeValue;
    parametros['siglas'] = datos.siglas.nodeValue;
    parametros["idioma"] = datos.idioma.nodeValue;
    parametros['profile'] = localStorage.getItem('profile') !== undefined 
    || localStorage.getItem('profile') !== null ? localStorage.getItem('profile') : null;

    parametros['action'] = "delete";
    //información del formulario
    var message = "";
    api_ajax(`Upload`,false,parametros).then((msg) => {
        if (msg['status']["code"] == 200) {
            //eliminamos de la lista input form
            $(getnode() + " #idlimedia_" + msg['data']['id']).remove();
            message = "<span class='messageupload'>archivo eliminado</span>";
            showMessage(message);

            if (msg['data']['main'] == '1') {
                //reasignamos la clase div_principal, al igual que el texto, a la nueva imagen
                $(getnode() + " #sortableupload table .div_img").removeClass("div_principal");
                $(getnode() + " #sortableupload table #idlimedia_" + msg['data']['idimg']).addClass("div_principal");
                $(getnode() + " #sortableupload table .div_principal #principal").show();
            }
        } else {
            message = msg['status']['text'];
            showMessage(message);
        }
    }).catch((error) => {
        //req_fail(jqXHR, textStatus);
        message = "<span class='messageupload'>ERROR! URL no valida.</span>";
        showMessage(message);
    });

}

function obtenerfotosordenadas() {
    let parametros = {};
    let kind = $(".tabcontent[style*='block']").attr("id");
    let siglas = $(getnode() + " .formulario input[name='siglas']").val();
    let idioma = $(getnode() + " .formulario input[name='idiomas']:checked").val();
    if (idioma == undefined) idioma = animeidiomas;
    if (siglas === undefined) siglas = animesiglas;
    parametros["siglas"] = siglas;
    parametros["idioma"] = idioma;
    parametros['profile'] = localStorage.getItem('profile') !== undefined 
    || localStorage.getItem('profile') !== null ? localStorage.getItem('profile') : null;
    parametros['type'] = kind;
    parametros['id_relative'] = $(getnode() + " .formulario input[name='id']").val(); 
    switch (kind) {
        case "anime": parametros['kind'] = "anime";break;
        case "personages": parametros['kind'] = "personage";break;
        case "episodes": parametros['kind'] = "episode";break;
        case "openings": parametros['kind'] = "opening";break;
        case "endings": parametros['kind'] = "ending";break;
    }
    parametros['action'] = "getmediaby";
    contenido = "";
    api_ajax('Upload',false,parametros).then((msg) => {
        if (msg['status']["code"] == 200) {
            for (var i = 0; i < msg['data'].length; i++) {
                var class_principal = "";
                if (msg['data'][i]['main'] == '1') {
                    class_principal = "div_principal";
                    contenido = "<img  class='" + class_principal + " principal_disabled ' onclick='selecionar_principal()' idmedia='idmedia_" + msg['data'][i]['id'] + "' type='" + msg['data'][i]['type'] +"' kind='" + msg['data'][i]['kind'] + "' id_relative='"+ msg['data'][i]['id_relative'] +"' siglas='"+ msg['data'][i]['siglas'] +"' idioma='" +  msg['data'][i]['idiomas'] + "' name=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + " src=" + msg['data'][i]['urlarchivo'] + " title='" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + "' />";
                    $(getnode() + " .contenido_principal div ul ").append("<li class='img_div ui-sortable-handle' >" + contenido + "</li>");
                } else {
                    contenido = "<img  class='" + class_principal + " principal_avable ' onclick='selecionar_principal()' idmedia='idmedia_" + msg['data'][i]['id'] + "' type='" + msg['data'][i]['type'] +"' kind='" + msg['data'][i]['kind'] + "' id_relative='"+ msg['data'][i]['id_relative'] +"' siglas='"+ msg['data'][i]['siglas'] +"' idioma='" +  msg['data'][i]['idiomas'] + "' name=" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + " src=" + msg['data'][i]['urlarchivo'] + " title='" + msg['data'][i]['name'] + '.' + msg['data'][i]['extension'] + "' />";
                    $(getnode() + " .contenido_principal div ul ").append("<li class='img_div ui-sortable-handle' >" + contenido + "</li>");
                }
            }//fin for
        }
        else {
            message = msg['status']['text'];
            showMessage(message);
        }
    }).catch( (error) => {
        //req_fail(jqXHR, textStatus);
        message = "<span class='messageupload'>ERROR! URL no valida.</span>";
        showMessage(message);
    });

}

function selecionar_principal() {
    let parametros = {};
    $(getnode() + " .contenido_principal div .div_img img").click(function (event) {
        var idmedia = event.target.attributes.idmedia.nodeValue;
        if ($(getnode() + " .contenido_principal div .div_img img").hasClass("principal_avable")) {
            parametros['idimg'] = idmedia.replace("idmedia_", "");
            parametros["kind"] = event.target.attributes.kind.nodeValue;
            parametros["type"] = event.target.attributes.type.nodeValue;
            parametros['id_relative'] = event.target.attributes.id_relative.nodeValue; 
            parametros["action"] = "selecionar";
            parametros['profile'] = localStorage.getItem('profile') !== undefined 
            || localStorage.getItem('profile') !== null ? localStorage.getItem('profile') : null;
            api_ajax('Upload',false,parametros).then((msg) => {
                if (msg['status']["code"] == 200) {
                    $(getnode() + " .contenido_principal div .div_img img").removeClass("div_principal");
                    $(getnode() + " .contenido_principal div img[idmedia= 'idmedia_" + msg['data']['idimg'] + "']").addClass("div_principal");
                    message = "<span class='messageupload'>Se ha camviado la imagen con exito. </span>";
                    showMessage(message);
                    $(getnode() + " .contenido_principal div .div_img img").removeClass("principal_disabled").addClass("principal_avable");
                    $(getnode() + " .contenido_principal div .div_img img[idmedia= 'idmedia_" + msg['data']['idimg'] + "']").removeClass("principal_avable").addClass("principal_disabled");

                    //Eliminamos todas las clases del elemento principal
                    $(getnode() + " #sortableupload table .div_pdf").show();
                    $(getnode() + " #sortableupload table .div_img").removeClass("div_principal");
                    $(getnode() + " #sortableupload table .div_img img").removeClass("div_principal");

                    //Aplicamos el css para el elemento principal
                    $(getnode() + " #sortableupload table .div_img .principal").hide();
                    $(getnode() + " #sortableupload table #idlimedia_" + msg['data']['idimg']).addClass("div_principal");
                    $(getnode() + " #sortableupload table .div_img img[idmedia='idmedia_" + msg['data']['idimg'] + "']").addClass("div_principal");
                    $(getnode() + " #sortableupload table .div_principal .principal").show();
                }
                else {
                    message = msg['status']['text'];
                    showMessage(message);
                }
            }).catch( (error) => {
                //req_fail(jqXHR, textStatus);
                message = "<span class='messageupload'>ERROR! URL no valida.</span>";
                showMessage(message);
            });//fin ajax
        }
    });
}

//como la utilizamos demasiadas veces, creamos una función para
//evitar repetición de código
function showMessage(message) {
    $(getnode() + " .messagesupload").html("").show();
    $(getnode() + " .messagesupload").html(message);
}

//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido
function isImage(extension) {
    extension = extension.toLowerCase()
    switch (extension) {
        case 'jpg':case 'gif':case 'png':case 'jpeg':
            return true;
            break;
        default:
            return false;
            break;
    }
}

function isVideo(extension) {
    extension = extension.toLowerCase()
    switch (extension) {
        case 'mp4': case 'avi': case 'webm': case 'mkv': case 'flv':
            return true;
            break;
        default:
            return false;
            break;
    }
}
