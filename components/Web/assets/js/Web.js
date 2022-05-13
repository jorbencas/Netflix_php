'use strict';
$(document).ready(function () {
    closeLoader();
});
// $(document).ready(function () { 
    // if (page_load === "1") {
    //     $(".content-loader").hide();
        // var date = new Date();
        // let day = date.getDate()
        // let month = date.getMonth()
        // let year = date.getFullYear()
        // let fecha = `${day}/${month}/${year}`;
        // console.log(fecha);
        // let footer = $("footer").css("height").replace("px","");
        // let header = $("header").css("height").replace("px","");
        // let minheight = parseFloat(footer) + parseFloat(header);
        // let screenheight = $(window).height();
        // let height = `calc(${screenheight}vh - ${minheight}px)`;
        // $("main").css("height",height+"px").css("min-height",height);
    /*  document.oncontextmenu = function(event) {
        event.preventDefault();
        alert("JUJUJJUJJUJUJUJU");
        return false;} */
    // }
// });

function getbaseurl() {
    return `http://cosasdeanime.com?r=es/`;
}

function deleteCookie(name) {
    document.cookie = `${name}= ; expires = Thu, 01 Jan 1970 00:00:00 GMT`;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (
        value || ""
    ) + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') 
            c = c.substring(1, c.length);
        
        if (c.indexOf(nameEQ) == 0) 
            return c.substring(nameEQ.length, c.length);
        
    }
    return null;
}

function openalert(color = null, text = null, time = 5000) {
    let timer = time;
    let icon;
    switch (color) {
        case 's': 
            color = 'success';
            icon = "fa-check-circle";
            break;
        case 'd': 
            color = 'danger';
            icon = "fa-exclamation-triangle";
            break;
        case 'w': 
            color = 'warning';
            icon = "fa-exclamation-circle";
            break;
        case 'i':
        default: 
            color = 'info';
            icon = "fa-info-circle";
            break;
    }

    $('body').append("<div class='alert_container'></div>");
    $(".alert_container").append(`
    <div class='alert ${color}'>
        <strong> <i class='fas ${icon}'></i> </strong> 
        <p> ${text} </p>
        <span class='closebtn' onclick='closealert()'>&times;</span>
    </div>`).animate({"right":"0px"},{"width":"195px"});

    timer = setInterval(() => {
        $(".alert_container").on("mousein", () => {
            console.log("in");
            clearInterval(timer);
        });

        $(".alert_container").on("mouseout", () => {
            console.log("out");
            timer = setInterval(() => {
                closealert();
            }, timer);
        });

        closealert();
    }, timer);
}

function soundNotification(){
    if (notification !== null) {
        var audio = new Audio();
        audio.src = notification;
        audio.play();
        console.log("sound");
    }
};

var closealert = function() {
    $(".alert_container")
    .animate({"width":"0px"}, {"right":"-999px"})
    .remove();
}

function loader(){
    $(".content-loader").show();
}

function closeLoader(){
    $('.content-loader').hide(); 
}

function api_ajax(url, special = false, formdata = undefined) {
    var met = formdata ? 'POST' : 'GET';
    let data = formdata ? special === true ? formdata : JSON.stringify(formdata) : null;
    let rand = Math.floor(Math.random() * 100);
    let compLikePages = ['Modals','Grid', 'List', 'Tabla'];
    let peticion = compLikePages.includes(url) ? url : `api&am=${url}&rand=${rand}`;
    let head = {
        "Content-type" : "application/json",
        "charset" : "UTF-8",
        "api_token" : "???123456789Azsxdcfvgnbhknljopimuhytgrfqew127364lpñokmni**/-++89¿juhvtcfdr65es123\\~~xza_qw",
        "X-Requested-With" : "XMLHttpRequest",
    };
    if (getCookie('acces_token') !== null) head["acces_token"] = getCookie('acces_token');
    if (getCookie('admin_token') !== null) head["admin_token"] = getCookie('admin_token');

    return new Promise((resolve, reject) => {
        var resultFetch = fetch(`${getbaseurl()}${peticion}`, {
            method: met, 
            body: data,
            headers: head
        }).then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw "Error en la llamada Ajax";
            }
        }).catch((err) => {
            console.log(err);
            return err;
        });
        resolve(resultFetch);
    });
};


function openmodal(event, data) {
    if (event !== undefined) {
        event.preventDefault();
        if (data !== undefined) {
            loader();
            api_ajax("Modals", false, data).then((resp) => {
                if (resp['css'] !== '' && resp['js'] !== '') {
                    $('head').append(resp['css']);
                    $('body').append(resp['content']).append(resp['js']);
                } else {
                    openalert('d',' Error al cargar el modulo Modals, no existe pages/Modals/Modals.php');
                }
                closeLoader();
            }).catch((error) => {
                console.log(error);
            });
        } else {
            $(".modal_lista").css("display",'inline-block');
            $(".modal_lista").each(function(index,element) {
                let node = element.children[1];
                if (node.textContent.length > 10) {
                    node.textContent = node.textContent.substring(0,9) +"...";
                    //val.expand = val.expand ? true: false;
                } 
            });
        }
    }
}