'use strict';
$(document).ready(function () {
    updateOnlineStatus("load");
    document.body.addEventListener("offline", function () {
        updateOnlineStatus("offline")
    }, false);
    document.body.addEventListener("online", function () {
        updateOnlineStatus("online")
    }, false);
});

function updateOnlineStatus(msg) {
    var condition = navigator.onLine ? "online" : "offline";
    if (condition == 'offline') {
        window.location = `${getbaseurl()}Errors`;
    }
}

function toogleLangs() {
	if ($(".header").css('height') == '0px') {
		$(".header").css("height", "45px");
	} else {
		$(".header").css("height", "0px");	
	}
}

/* 
var holding = false;
var track = document.getElementById('track');
var progress_music = document.getElementById('progress');
var play_music = document.getElementById('play');
var next = document.getElementById('next');
var prev = document.getElementById('prev');
var title = document.getElementById('title');
var artist = document.getElementById('artist');
var art = document.getElementById('art');
var current_track = 0;
var song, audio, duration;
var playing = false;

var songs = $.parseJSON(songs);

window.addEventListener('load', init(), false);

function init() {    
    song = songs[current_track];
    audio = new Audio();
    audio.src = song.url;
    title.textContent = song.title;
    artist.textContent = song.artist;
    art.src = song.art;
}

audio.addEventListener('timeupdate', updateTrack, false);
audio.addEventListener('loadedmetadata', function () {
    duration = this.duration;
}, false);
window.onmousemove = function (e) {
    e.preventDefault();
    if (holding) seekTrack(e);
}
window.onmouseup = function (e) {
    holding = false;
    //console.log(holding);
}
track.onmousedown = function (e) {
    holding = true;
    seekTrack(e);
    //console.log(holding);
}
play_music.onclick = function () {
    playing ? audio.pause() : audio.play();
}
audio.addEventListener("pause", function () {
    play_music.innerHTML = '<i class="fa fa-play"></i>';
    playing = false;
}, false);

audio.addEventListener("playing", function () {
    play_music.innerHTML = '<i class="fa fa-pause"></i>';
    playing = true;
}, false);
next.addEventListener("click", nextTrack, false);
prev.addEventListener("click", prevTrack, false);


function updateTrack() {
    curtime = audio.currentTime;
    percent = Math.round((curtime * 100) / duration);
    progress_music.style.width = percent + '%';
    handler.style.left = percent + '%';
}

function seekTrack(e) {
    event = e || window.event;
    var x = e.pageX - player.offsetLeft - track.offsetLeft;
    percent = Math.round((x * 100) / track.offsetWidth);
    if (percent > 100) percent = 100;
    if (percent < 0) percent = 0;
    progress_music.style.width = percent + '%';
    handler.style.left = percent + '%';
    audio.play();
    audio.currentTime = (percent * duration) / 100
}
function nextTrack() {
    current_track++;
    current_track = current_track % (songs.length);
    song = songs[current_track];
    audio.src = song.url;
    audio.onloadeddata = function() {
      updateInfo();
    }
}

function prevTrack() {
    current_track--;
    current_track = (current_track == -1 ? (songs.length - 1) : current_track);
    song = songs[current_track];
    audio.src = song.url;
    audio.onloadeddata = function() {
      updateInfo();
    }
}

function updateInfo() {
    title.textContent = song.title;
    artist.textContent = song.artist;
    art.src = song.art;
    art.onload = function() {
        audio.play();
    }
} */