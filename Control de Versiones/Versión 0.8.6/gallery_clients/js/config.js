jQuery(function ($) {
	$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/filtersMobile.php",{ actionSelect: 'listar', typeSelect : 'category' },function(optionSelect){
		$("#selectCategoria").html(optionSelect);
		$("#selectCategoria option[id='TODOS']").remove();
	});
	
	$(document).on('change', '#selectCategoria', function(){
		$( "#selectCategoria option:selected" ).each(function() {
			var nameCategory = $(this).attr("name");
			$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "ConsultarColor" , nameCategory : nameCategory },function(colorCategory){
				//console.log(colorCategory);
				$("#famcolor").val(colorCategory);
			}, "json");
		});
	});
	
	$(document).on('click', '#enviarConf', function(){
		var nameFamiliaSelect = $( "#selectCategoria option:selected" ).attr("name");
		var colorFamilia = $("#famcolor").val();
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "GuardarConf" , nameFamiliaSelect : nameFamiliaSelect , colorFamilia : colorFamilia },function(cambios){
			console.log(cambios);
			if(cambios["isNull"]!=true){
				$("#msgCambios").css("color", "green");
				$("#msgCambios").html(cambios[0]['message']);
			}else{
				$("#msgCambios").css("color", "red");
				$("#msgCambios").html(cambios[0]['message']);
			}
		}, "json");
	});
});						