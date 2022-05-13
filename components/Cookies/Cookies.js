'use strict';
function createCokkies(value){
    setCookie('api_token', value, 25);
    closemodal();
}