'use strict';
var killSpaceBar = function (evt) {
  var target = evt.target || {},
      isInput = ("INPUT" == target.tagName || "TEXTAREA" == target.tagName || "SELECT" == target.tagName || "EMBED" == target.tagName);
  // if we're an input or not a real target exit
  if (isInput || ! target.tagName) 
      return;
  // if we're a fake input like the comments exit
  if (target && target.getAttribute && target.getAttribute('role') === 'textbox') 
      return;
  // ignore the space and send a 'k' to pause
  if (evt.keyCode === 32) {
      evt.preventDefault();
      var eventObj = document.createEvent("Events");
      eventObj.initEvent("keydown", true, true);
      eventObj.keyCode = 75;
      eventObj.which = 75;
      document.body.dispatchEvent(eventObj);
  }
}
document.addEventListener("keydown", killSpaceBar, false);
let screenwidth = $(window).width();
let element = screenwidth > 800 ? ".controls": ".movil_controls";
const video = document.getElementById("video");
const play = $(element + " #play")[0];
const stop = $(element + " #stop")[0];
const progress = $(element + " #progress")[0];
const timestamp = $(element + " #timestamp")[0];
const timeend = $(element + " #timeend")[0];
const ranges = $(element + " #volume")[0];
const speed = $(element + " #speed")[0];
const setfullvideo = $(element + " #fullscreen")[0];
let vol;
let fullscreen = false;
var moviendo = false;

// $(element + " #vol-icon").on("click", function () {
//   if ($(element + " #vol-icon i").attr("class") == "fas fa-volume-up fa-2x") {
//     vol = video.volume;
//     $(element + " #vol-icon i").attr("class", "fas fa-volume-mute fa-2x");
//     video.volume = 0;
//     ranges.value = 0;
//   } else {
//     $(element + " #vol-icon i").attr("class", "fas fa-volume-up fa-2x");
//     video.volume = vol;
//     ranges.value = vol;
//   }
// });

function getduration(operacion = null, tiempo = null) {
  // Get mins
  let mins = Math.floor(video.currentTime / 60);
  if (mins < 10) {
    mins = "0" + String(mins);
  }

  // Get secs
  let secs = Math.floor(video.currentTime % 60);
  if (operacion !== null && tiempo !== null) {
    if (operacion == "+") secs = secs + tiempo;
    else if (operacion == "-") secs = secs - tiempo;
  }
  if (secs < 10) {
    secs = "0" + String(secs);
  }

  return {"mins":mins,"secs":secs};
}

$(element + " .overlay").on("click", function () {
  updatePlayIcon();
});

// Play and Pause video
function toggleVideoStatus() {
  if (video.paused || (video.canplaythrough && video.loadeddata)) {
    video.play();
  } else {
    video.pause();
  }
}

// Update play/pause icon
function updatePlayIcon() {
  if (video.paused) {
    play.innerHTML = '<i class="fa fa-play fa-2x"></i>';
    $(".overlay").css("height", "100%");
  } else {
    play.innerHTML = '<i class="fa fa-pause fa-2x"></i>';
    $(".overlay").css("height", "0");
  }
}

// Update progress and timestamp
function updateProgress(operacion = null, tiempo = null ) {
  progress.value = (video.currentTime / video.duration) * 100;
  let time = getduration(operacion,tiempo);
  if (time.secs > 5 && window.location.search.includes("EpisodesDetails")) {
    if ($("#user").text().length > 0) {
      let searched = window.location.search.split("&");
      let id_param = searched[1].split("=");
      let id = id_param[1];
      var data = {
        'user':$("#user").text(),
        'action':"sethistory",
        'episode_id':id,
        'time':`${time.mins}:${time.secs}`
      };
      
      api_ajax("History", false,data).then((resp) => {
        console.log(resp);
      }).catch((error) => {
        console.log(error);
      });
    }
  }

  timestamp.innerHTML = `${time.mins}:${time.secs}`;
}

// Set video time to progress
function setVideoProgress() {
  video.currentTime = (progress.value * video.duration) / 100;
}

function setvolumen() {
  if (ranges.value < 0.1 && $(element + " #vol-icon i").attr("class") == "fas fa-volume-up fa-2x") {
    $(element + " #vol-icon i").attr("class", "fas fa-volume-mute fa-2x");
  } else if (ranges.value > 0.1 && $(element + " #vol-icon i").attr("class") == "fas fa-volume-mute fa-2x") {
    $(element + " #vol-icon i").attr("class", "fas fa-volume-up fa-2x");
  }
  video.volume = ranges.value;
}

function setvelocity() {
  video.playbackRate = speed.value;
}

function setfullscreen() {
  if (fullscreen) {
    fullscreen = false;
    $("#fullscreen i").attr("class", "fas fa-expand fa-2x");
    $(".element_video").append(video);
    $(".element_video").append($(".overlay"));
    $(".element_video").append($(element));
    $(element).show();
    $(".videoplayer").remove();
    $("body").css("overflow", "auto");
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  } else {
    fullscreen = true;
    $("body").append(`<div class='videoplayer'></div>`);
    $(element + " #fullscreen i").attr("class", "fas fa-compress fa-2x");
    $(".videoplayer").append(video);
    $(".videoplayer").append($(".overlay"));
    $(".videoplayer").append($(element));
    $("body").css("overflow", "hidden");
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(
        Element.ALLOW_KEYBOARD_INPUT
      );
    }

    setTimeout(() => {
      $(".controls").hide();
    }, 3000);
  }
}

function resetprogress() {
  video.currentTime = 0;
}
// Event Listeners
video.addEventListener("click", toggleVideoStatus);
video.addEventListener("pause", updatePlayIcon);
video.addEventListener("play", updatePlayIcon);
video.addEventListener("timeupdate", updateProgress);
ranges.addEventListener("change", setvolumen);
play.addEventListener("click", toggleVideoStatus);
stop.addEventListener("click", resetprogress);
progress.addEventListener("change", setVideoProgress);
speed.addEventListener("change", setvelocity);
setfullvideo.addEventListener("click", setfullscreen);

$(document).keydown(function (event) {
  if (event.keyCode == 32 && !$(".input_enviar").is(":focus")) {
    //play pause video space button
    toggleVideoStatus();
    updatePlayIcon();
  } else if (event.keyCode == 70  && !$(".input_enviar").is(":focus") && !fullscreen) {
    // fullscreen f button
    setfullscreen();
  } else if (event.keyCode == 77 && !$(".input_enviar").is(":focus") ) {
    //mute m button
    if ($("#vol-icon i").attr("class") == "fas fa-volume-up fa-2x") {
      vol = video.volume;
      $("#vol-icon i").attr("class", "fas fa-volume-mute fa-2x");
      video.volume = 0;
      ranges.value = 0;
    }
  } else if (event.keyCode == 86  && !$(".input_enviar").is(":focus") ) {
    //mute v button
    if ($("#vol-icon i").attr("class") !== "fas fa-volume-up fa-2x") {
      $("#vol-icon i").attr("class", "fas fa-volume-up fa-2x");
      video.volume = vol;
      ranges.value = vol;
    }
  } else if (event.keyCode == 74  && !$(".input_enviar").is(":focus") ) {
    //next 10 segundos j button
    // updateProgress("+",10);
    // toggleVideoStatus();
    // setVideoProgress();
    // debugger;
  } else if (event.keyCode == 75  && !$(".input_enviar").is(":focus") ) {
    //prev 10 segundos k button
    // updateProgress("-",10);
    // toggleVideoStatus();
    // setVideoProgress();
    // debugger;
  } else if (event.keyCode == 123  && !$(".input_enviar").is(":focus")) {
    // Prevent F12
    return false;
  } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
    // Prevent Ctrl+Shift+I
    return false;
  } else if (event.keyCode == 27 && fullscreen) {
    //salir fullscreen esc button
    setfullscreen();
  }
});

$(document).bind(
  "fullscreenchange webkitfullscreenchange mozfullscreenchange msfullscreenchange",
  function (e) {
    var fullscreenElement =
      document.fullscreenElement ||
      document.webkitFullscreenElement ||
      document.mozFullscreenElement ||
      document.msFullscreenElement;

    if (!fullscreenElement && fullscreen) {
      setfullscreen();
    }
  }
);

document.onmousemove = function () {
  if (fullscreen == true) {
    moviendo = true;
  }
};
setInterval(function () {
  if (fullscreen == true) {
    if (!moviendo) {
      $(".controls").hide();
    } else {
      moviendo = false;
      $(".controls").show();
    }
  }
}, 1000); // Cada segundo, pon el valor que quieras.
