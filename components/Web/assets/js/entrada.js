'use strict';
$(window).scroll(() => {
	let screenwidth = $(window).width();
	if (screenwidth > 1000) {
		var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
		var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
		var scrolled = (winScroll / height) * 100;
		document.getElementById("myBar").style.width = scrolled + "%";
    }
});


$('.ir-arriba *').click(function () {
    $('body, html').animate({
        scrollTop: '0px'
    }, 300);
});
$(window).scroll(() => {
    if ($(this).scrollTop() > 0) {
        $('.ir-arriba').slideDown(300);
    } else {
        $('.ir-arriba').slideUp(300);
    }
});  