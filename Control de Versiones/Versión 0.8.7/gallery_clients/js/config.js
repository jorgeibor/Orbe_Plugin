jQuery(function ($) {
	$("#enviarConf").prop( "disabled", true );
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "ListarFamilias" },function(infoFamilias){
			//console.log(infoFamilias);
			
			var optionSelect;
			optionSelect+= "<option id='defaultSelected' disabled='' selected=''>Selecciona una Familia</option>";
			for(var i = 0; i<infoFamilias.length; i++){
				optionSelect += "<option id='"+infoFamilias[i]['id']+"' name='"+i+"'>"+infoFamilias[i]['nombre']+"</option>";
			}
			$("#selectCategoria").html(optionSelect);
			localStorage.setItem('infoFamilia', JSON.stringify(infoFamilias));
		}, "json");
		
	$(document).on('change', '#selectCategoria', function(){
		$("#enviarConf").prop( "disabled", false );
		$( "#selectCategoria option:selected" ).each(function() {
			var idFamilia = $( "#selectCategoria option:selected" ).attr("id");
			var storedInfoFamilias = JSON.parse(localStorage.getItem("infoFamilia"));
			$.each(storedInfoFamilias, function(){
				//console.log(this.id);
				if(this.id == idFamilia){
					$('#famcolor').val(this.color);
					$('#hexcolor').val(this.color);
				}
			});
		});
	});
	var famcolor = $('#famcolor').val();
	$('#hexcolor').val(famcolor);	
	$('#famcolor').on('change', function() {
		$('#hexcolor').val(this.value);
		var idFamilia = $( "#selectCategoria option:selected" ).attr("id");
		
		var storedInfoFamilias = JSON.parse(localStorage.getItem("infoFamilia"));
		$.each(storedInfoFamilias, function(){
			//console.log(this.id);
			if(this.id == idFamilia){
				this.color = $('#famcolor').val();
			}
		});
		//console.log(storedInfoFamilias);
		localStorage.setItem('infoFamilia', JSON.stringify(storedInfoFamilias));
	});
	$('#hexcolor').on('change', function() {
		$('#famcolor').val(this.value);
		var idFamilia = $( "#selectCategoria option:selected" ).attr("id");
		
		var storedInfoFamilias = JSON.parse(localStorage.getItem("infoFamilia"));
		$.each(storedInfoFamilias, function(){
			//console.log(this.id);
			if(this.id == idFamilia){
				this.color = $('#hexcolor').val();
			}
		});
		//console.log(storedInfoFamilias);
		localStorage.setItem('infoFamilia', JSON.stringify(storedInfoFamilias));
	});
	
	
	$(document).on('click', '#resetColor', function(){
		var idFamiliaSelect = $( "#selectCategoria option:selected" ).attr("id");
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "resetConfColor" , idFamiliaSelect : idFamiliaSelect },function(colorFamilia){
			//console.log(colorFamilia);
			$('#famcolor').val(colorFamilia);
			$('#hexcolor').val(colorFamilia);
			var storedInfoFamilias = JSON.parse(localStorage.getItem("infoFamilia"));
			$.each(storedInfoFamilias, function(){
				if(this.id == idFamiliaSelect){
					this.color = $('#hexcolor').val();
				}
			});
			localStorage.setItem('infoFamilia', JSON.stringify(storedInfoFamilias));
		}, "json");
	});
	
	$(document).on('click', '#enviarConf', function(){
		var idFamiliaSelect = $( "#selectCategoria option:selected" ).attr("id");
		var nameFamiliaSelect = $( "#selectCategoria option:selected" ).val();
		var colorFamilia = $("#famcolor").val();
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "GuardarConf" , idFamiliaSelect : idFamiliaSelect , nameFamiliaSelect : nameFamiliaSelect , colorFamilia : colorFamilia },function(cambios){
			//console.log(cambios);
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