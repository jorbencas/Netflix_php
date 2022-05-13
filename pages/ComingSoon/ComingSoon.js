'use strict';
// Set the date we're counting down to
var countDownDate = new Date("Jan 5, 2022 00:00:00").getTime();
var segundos = 1000;
var minutos = segundos * 60;
var hora = minutos * 60;
var dia = hora * 24;

// Update the count down every 1 second
var x = setInterval(function() {
  // Get today's date and time
  var now = new Date().getTime();
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    $("#demo").html("EXPIRED");
  } else {
    // Time calculations for days, hours, minutes and seconds
    let d = Math.floor(distance / (dia));
    let h = Math.floor((distance % (dia)) / (hora));
    let m = Math.floor((distance % (hora)) / (minutos));
    let s = Math.floor((distance % (minutos)) / (segundos));
    
    renderElement(d, 'days');
    renderElement(h, 'hours');
    renderElement(m, 'minutes');
    renderElement(s, 'seconds');
  }
}, 1000);

function renderElement(element, node){
  var length = (Math.log(element) / Math.LN10 + 1); // for integers
  if ( length < 2 ) {
    element = "0" +  element;
  }
  $("#"+node).text(element);
}