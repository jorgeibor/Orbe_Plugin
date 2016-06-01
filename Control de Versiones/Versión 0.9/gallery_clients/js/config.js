jQuery(function ($) {
	$("#enviarConf").prop( "disabled", true );
	$("#resetColor").prop( "disabled", true );
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "ListarFamilias" },function(infoFamilias){
			//console.log(infoFamilias);
			if(infoFamilias.length > 0){
				var optionSelect = "";
				optionSelect+= "<option id='defaultSelected' disabled='' selected=''>Selecciona una Familia</option>";
				for(var i = 0; i<infoFamilias.length; i++){
					optionSelect += "<option id='"+infoFamilias[i]['id']+"' name='"+i+"'>"+infoFamilias[i]['nombre']+"</option>";
				}
				$("#selectCategoria").html(optionSelect);
				localStorage.setItem('infoFamilia', JSON.stringify(infoFamilias));
			}else{
				var optionSelect = "";
				optionSelect+= "<option id='defaultSelected' disabled='' selected=''>No se ha encontrado familias</option>";
				$("#selectCategoria").html(optionSelect);
			}
		}, "json");
		
	$(document).on('change', '#selectCategoria', function(){
		$("#enviarConf").prop( "disabled", false );
		$("#resetColor").prop( "disabled", false );
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
			if(colorFamilia){
				$('#famcolor').val(colorFamilia);
				$('#hexcolor').val(colorFamilia);
			}else{
				$('#famcolor').val("#757575");
				$('#hexcolor').val("#757575");
			}
			
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
		var confFamiliasColor = JSON.parse(localStorage.getItem("infoFamilia"));
		$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ accion : "GuardarConf" , confFamiliasColor : confFamiliasColor },function(cambios){
			//console.log(cambios);
			if(cambios["isNull"]!=true){
				$("#msgCambios").css("color", "green");
				$("#msgCambios").html(cambios[0]['message']);
				$("#msgCambios").slideDown('slow').delay(3000).slideUp('slow', function(){
					$("#msgCambios").css("color", "#000");
					$("#msgCambios").html("");
				});
				
			}else{
				$("#msgCambios").css("color", "red");
				$("#msgCambios").html(cambios[0]['message']);
				$("#msgCambios").slideDown('slow').delay(3000).slideUp('slow', function(){
					$("#msgCambios").css("color", "#000");
					$("#msgCambios").html("");
				});
			}
		}, "json");
	});
});						