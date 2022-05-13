'use strict';
$(document).ready(function () {
	$(".lateralbar .icons, .lateralbar .sidenav").mouseover(function() {
		// if (!$(".lateralbar .sidenav").attr("width")) {
		// 	width = 0;
		// 	$(".lateralbar .sidenav span").each((index, element) => {
		// 		elem_w = parseFloat($(element).width());
		// 		if (elem_w > width) {
		// 			width = elem_w;
		// 		}
		// 	});
		// 	$(".lateralbar .sidenav").attr("width",`${width}px`);
		// } else {
		// 	width = $(".lateralbar .sidenav").attr("width");
		// }
		// $(".lateralbar .sidenav").css("width",`${width}px`);//"250px"
		$(".lateralbar .sidenav").css("width","250px");

	});
	$(".lateralbar").mouseout(function() {
		$(".lateralbar .sidenav").css("width","0px");
	});
});