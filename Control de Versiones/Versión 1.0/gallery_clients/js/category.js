$(document).ready(function() {
	//Obtener los colores de las familias
	$("div#divCategory h1").each(function(){
		//console.log($(this).html());
		var familyId = $(this).attr("id");
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "ConsultarColor" , familyId : familyId },function(colorCategory){
			console.log(colorCategory);
			if(colorCategory != "false"){
				$("h1[id='"+familyId+"']").attr("name", colorCategory);
			}else{
				$("h1[id='"+familyId+"']").attr("name", "#757575");
			}
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
		var colorFamily = $("h1[class='h1"+nameCategory+"']").attr("name");
		//console.log(colorFamily);
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