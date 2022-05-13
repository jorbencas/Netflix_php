'use strict';
$(document).ready(function() {
	// Objeto del Banner
	var banner = {
		padre: $('#banner'),
		numeroanimes: $('#banner').children('.anime').length,
		posicion: 1
	};

	banner.padre.children('.anime').first().addClass("active");
	banner.padre.children('.anime').first().css({'left': 0 });

	// Funcion para calcular el alto que tendran los contenedores padre
	var altoBanner = function() {
		var alto = banner.padre.children('.anime').outerHeight();
		banner.padre.css({
			'height': alto + 'px'
		});
	}

	// Establecemos que el #contenedor tenga el 100% del alto de la pagina. 
	// Para despues centrarlo verticalente con flexbox.
	var altoContenedor = function() {
		var altoVentana = $(window).height();
		if (altoVentana <= $('.contenedor').outerHeight() + 200) {
			$('#contenedor').css({'height': ''});
		} else {
			$('#contenedor').css({'height': altoVentana + 'px'});
		}
	}

	// Ejecutamos las funciones para calcular los altos.
	altoBanner();
	altoContenedor();

	// Si cambiamos el tamaño de la pantalla se
	// ejecuta esta funcion para saber el nuevo
	// tamaño del elemento padre
	$(window).resize(function() {
		altoBanner();
		altoContenedor();
	});

	if (banner.numeroanimes > 1) {
		setTimeout(() => { $('#banner-next').click(); }, 4000);

		$('#banner-next').on('click', function(e) {
			e.preventDefault();
			//console.log("AAAAAA");
			if (banner.posicion < banner.numeroanimes) {
				banner.padre.children().not('.active').css({ 'left': '100%'});
				$('#banner .active').removeClass('active').next().addClass('active').animate({ 'left': 0});
				$('#banner .active').prev().animate({ 'left': '-100%' });
				banner.posicion = banner.posicion + 1;
			} else {
				$('#banner .active').animate({ 'left': '-100%'});
				banner.padre.children().not('.active').css({ 'left': '100%'});
				$('#banner .active').removeClass('active');
				banner.padre.children().first().addClass('active').animate({ 'left': 0});
				banner.posicion = 1;
			}
			setTimeout(() => { $('#banner-next').click(); }, 5000);
		});

		$('#banner-prev').on('click', function(e) {
			e.preventDefault();
			if (banner.posicion > 1) {
				banner.padre.children().not('.active').css({ 'left': '-100%' });
				$('#banner .active').animate({ 'left': '100%' });
				$('#banner .active').removeClass('active').prev().addClass('active').animate({ 'left': 0 });
				banner.posicion = banner.posicion - 1;
			} else {
				banner.padre.children().not('.active').css({'left': '-100%' });
				$('#banner .active').animate({'left': '100%' });
				$('#banner .active').removeClass('active');
				banner.padre.children().last().addClass('active').animate({ 'left': 0 });
				banner.posicion = banner.numeroanimes;
			}
		});
	}
});
