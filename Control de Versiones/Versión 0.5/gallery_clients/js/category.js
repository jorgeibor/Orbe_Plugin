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

	<!-- Cambiar el color de la categoría al pasar por encima. -->
	$("#divCategory #categoryTodos").mouseover(function() {
		$("#divCategory #categoryTodos").css({
			"background-color": "#707173",
			"color": "#FFF"
		});
	});
	$("#divCategory #categoryTelecom").mouseover(function() {
		$("#divCategory #categoryTelecom").css({
			"background-color": "#0574BA",
			"color": "#FFF"
		});
	});
	$("#divCategory #categorySeguridad").mouseover(function() {
		$("#divCategory #categorySeguridad").css({
			"background-color": "#E30B24",
			"color": "#FFF"
		});
	});
	$("#divCategory #categoryOrgeby").mouseover(function() {
		$("#divCategory #categoryOrgeby").css({
			"background-color": "#009036",
			"color": "#FFF"
		});
	});

	<!-- Devuelve el color de la categoría cuando se deja de mantener el ratón encima. -->
	$("#divCategory #categoryTodos, #divCategory #categoryTelecom, #divCategory #categorySeguridad, #divCategory #categoryOrgeby").mouseout(function() {
		$(this).css({
			"background-color": "#CCCCCC",
			"color": "#808080"
		});
	});

	<!-- Cambiar el color del tipo de trabajo al pasar por encima. -->

	$(".divListCategory span").mouseover(function() {
		var nameCategory = $(this).attr("name");
		switch(nameCategory){
			case "TELECOMUNICACIONES":
				$(this).css({
					"background-color": "#0574BA",
					"color": "#FFF"
				});
				break;
			case "INSTALACIONES ESPECIALES":
				$(this).css({
					"background-color": "#D67D00",
					"color": "#FFF"
				});
				break;
			case "ELECTRICIDAD":
       			$(this).css({
						"background-color": "#FFFF00",
						"color": "#000"
				});
        		break;
			case "SEGURIDAD":
       			$(this).css({
						"background-color": "#E30B24",
						"color": "#FFF"
				});
        		break;
			case "ENERGÍA":
       			$(this).css({
						"background-color": "#009036",
						"color": "#FFF"
				});
        		break;
		}
	});

	<!-- Devuelve el color del tipo de trabajo cuando se deja de mantener el ratón encima. -->
	$(".divListCategory span").mouseout(function() {
		$(this).css({
			"background-color": "#CCCCCC",
			"color": "#808080"
		});    
	});

});