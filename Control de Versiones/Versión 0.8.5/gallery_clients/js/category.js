$(document).ready(function() {
  	var moduleCategory = $('#divCategory');
	var module_offset = moduleCategory.offset();
	<!-- Poner limite al desplazamiento de la columna de Tipos de Trabajo para que no se salga del div Padre -->
		$.fn.scrollBottom = function() { 
			return $(document).height() - this.scrollTop() - this.height(); 
		};
		$(window).on('scroll', function() {
			if($(window).scrollTop() > module_offset.top) {
				moduleCategory.addClass('module-fijo');
			}else{
				moduleCategory.removeClass('module-fijo');
			}
			if($(window).scrollBottom() < module_offset.top) {
				moduleCategory.addClass('module-absolute');
				moduleCategory.removeClass('module-fijo');
			}else{
				moduleCategory.removeClass('module-absolute');
			}
		 });

	<!-- Cambiar el color del tipo de trabajo al pasar por encima. -->

	$(".divListCategory span").mouseover(function() {
		var nameCategory = $(this).attr("name");
		switch(nameCategory){
			case "TELECOMUNICACIONES":
				$(this).css({
					"background-color": "#0574BA",
					"color": "#FFF",
					"font-weight": "bold"
				});
				break;
			case "ESPECIALES Y ELECTRICIDAD":
				$(this).css({
					"background-color": "#0574BA",
					"color": "#FFF",
					"font-weight": "bold"
				});
				break;
			case "SEGURIDAD":
       			$(this).css({
					"background-color": "#E30B24",
					"color": "#FFF",
					"font-weight": "bold"
				});
        		break;
			case "ENERGÍA":
       			$(this).css({
					"background-color": "#009036",
					"color": "#FFF",
					"font-weight": "bold"
				});
        		break;
		}
	});

	<!-- Devuelve el color del tipo de trabajo cuando se deja de mantener el ratón encima. -->
	$(".divListCategory span").mouseout(function() {
		$(this).css({
			"background-color": "#E0E0E0",
			"color": "#000",
			"font-weight": "inherit"
		});    
	});

});