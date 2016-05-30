$(document).ready(function() {
	//Obtener los colores de las familias
	$("div#divCategory h1").each(function(){
		//console.log($(this).html());
		var familyName = $(this).html();
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "ConsultarColor" , nameCategory : familyName },function(colorCategory){
			//console.log("'#h1"+familyName+"' "+colorCategory);
			$("h1[id='h1"+familyName+"']").attr("name", colorCategory);
		}, "json");
	});
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
		var colorFamily = $("h1[id='h1"+nameCategory+"']").attr("name");
		$( this ).css({
			"background-color": colorFamily,
			"color": "#FFF",
			"font-weight": "bold"
		});
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