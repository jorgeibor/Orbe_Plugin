$(document).on('ready', function(){
	var nClients = $("#nClientes").val();
	var idCategorys;
	var nameFamily;
	var count = 0;		
	var countC = 0;
	
	<!--Al darle click a una categoría de la columna de Tipos de Trabajos, recoge la información sobre la Categoría/Grupo y lo envía a client.php para poder construir la estructura html. -->

	$(document).on('click', '#divCategory h1', function(){
		var nameFilter = $(this).text();
		var idCategory = $(this).attr("class").slice(2);
		<!--console.log(idCategory);-->
			$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
				console.log(clientes);
				if(clientes[0]['isNull'] == true){
					var output = '<p><h2>'+nameFilter+'</h2></p>';
					output += "<h3>"+clientes[0]['message']+"</h3>";
					$('#divShortcode1 #divClient').html("");
					$('#divShortcode1 #divClient').html(output);
					$('#divShortcode1 #divClient').css("height", "500px");
				}else if(clientes == "Listar Todos"){
					$('#divShortcode1 #divClient').css("height", "auto");
					location.reload();
				}else{
					$('#divShortcode1 #divClient').css("height", "auto");
					var output = '';
						output += '<div id="filterbox" class="filterbox">';
							output += '<div id="filterTittle" class="filterboxTittle">';
								output +='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
								output +='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
							output += '</div>';
							output += '<div id="filterForm" class="filterboxForm">';
								output +='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
									output +='Categoria: <select id="selectCategoria"></select>';
								output +='</span>';
								output +='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
									output +='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
								output +='</span>';
							output += '</div>';
						output += '</div>';
						output += '<div id="divClient" class="divClient"> ';
							output += '<p><h2>'+nameFilter+'</h2></p>';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<div id="overImg" class="overboxImg">';
										output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
									output += '</div>';
									output += '<div id="overDesc" class="overboxDesc">';
										output += '<h2>Descripción</h2>';
										output += '<div id="descClient" class="descClient">';
											output += '<span id="descSpan" class="descSpan"></span>';
										output += '</div>';
									output += '</div>';
									output += '<div id="overJobs" class="overboxJobs">';
										output += '<h2>Tipos de trabajos</h2>';
									output += '</div>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_title']+"</span>";
									output += "<span id='descClient' style='display:none;'>"+clientes[i]['post_content']+"</span>";							   
								output += "</div>";
							}
						output += "</div>";			
					$('#divShortcode1').html("");
					$('#divShortcode1').html(output);
				}
			}, "json");
	});

	<!--Al darle click a un botón de la columna de Tipos de Trabajos, recoge la información sobre la SubCategoría/Tipo de Trabajo y lo envía a client.php para poder construir la estructura html.-->

	$(document).on('click', '#divCategory span', function(){
		var nameFilter = $(this).text();
		var idCategory = $(this).attr("id").slice(8);
		<!--console.log(idCategory);-->
		if(idCategory == "Todos"){
			$(location).attr('href','http://orbe.visualcom.es/clientes/');
		}else{
			$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
				if(clientes[0]['isNull'] == true){
					var output = '<p><h2>'+nameFilter+'</h2></p>';
					output += "<h3>"+clientes[0]['message']+"</h3>";
					$('#divShortcode1 #divClient').html("");
					$('#divShortcode1 #divClient').html(output);
					$('#divShortcode1 #divClient').css("height", "500px");
				}else if(clientes == "Listar Todos"){
					$('#divShortcode1 #divClient').css("height", "auto");
					location.reload();
				}else{
					$('#divShortcode1 #divClient').css("height", "auto");
					var output = '';
						output += '<div id="filterbox" class="filterbox">';
							output += '<div id="filterTittle" class="filterboxTittle">';
								output +='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
								output +='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
							output += '</div>';
							output += '<div id="filterForm" class="filterboxForm">';
								output +='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
									output +='Categoria: <select id="selectCategoria"></select>';
								output +='</span>';
								output +='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
									output +='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
								output +='</span>';
							output += '</div>';
						output += '</div>';
						output += '<div id="divClient" class="divClient"> ';
							output += '<p><h2>'+nameFilter+'</h2></p>';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<div id="overImg" class="overboxImg">';
										output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
									output += '</div>';
									output += '<div id="overDesc" class="overboxDesc">';
										output += '<h2>Descripción</h2>';
										output += '<div id="descClient" class="descClient">';
											output += '<span id="descSpan" class="descSpan"></span>';
										output += '</div>';
									output += '</div>';
									output += '<div id="overJobs" class="overboxJobs">';
										output += '<h2>Tipos de trabajos</h2>';
									output += '</div>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_title']+"</span>";
									output += "<span id='descClient' style='display:none;'>"+clientes[i]['post_content']+"</span>";						   
								output += "</div>";
							}
						output += "</div>";	
					$('#divShortcode1').html("");
					$('#divShortcode1').html(output);
				}
			}, "json");
		}
	});
	
	<!-- Al pasar el ratón por encima de un cliente, el usuario puede visualizar los tipos de trabajos que se le han asignado por medio de cambios del color de fondo de los botones. -->														   
															   
	$(document).on('mouseover', 'div.divClientsImage img', function() {
		idCategorys = $(this).attr("alt");
		var categorias_Clientes = idCategorys.split(' ').splice(1);
			$.each(categorias_Clientes, function( ){
				nameFamily = $( "#category"+categorias_Clientes[count] ).attr("name");
				switch(nameFamily){
					case "TELECOMUNICACIONES":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#0574BA",
								"color": "#FFF",
								"font-weight": "bold"
						});
        				break;
					case "ESPECIALES Y ELECTRICIDAD":
						$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#0574BA",
								"color": "#FFF",
								"font-weight": "bold"
						});
					break;
					case "SEGURIDAD":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#E30B24",
								"color": "#FFF",
								"font-weight": "bold"
						});
        				break;
					case "ENERGÍA":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#009036",
								"color": "#FFF",
								"font-weight": "bold"
								
						});
        				break;
				}
				count = count + 1;
			});
			count = 0;							   
	});
	<!-- Devuelve el color de los tipos de trabajos -->
	$(document).on('mouseout', 'div.divClientsImage img', function() {
		$( "div#divCategory span" ).css({
			"background-color": "#E0E0E0",
			"color": "#000",
			"font-weight": "inherit"
		});    
	});
	<!-- Lightbox -->
	function showLightbox() {
		$("#over").css("display", "block");
		$("#fade").css("display", "block");
	}
	function hideLightbox() {
		$("#over").css("display", "none");
		$("#fade").css("display", "none");
	}
	$(document).on('click', '.divClientsImage', function(){				   
		showLightbox();
		var imgsrc = $(this).children("img").attr("src");
		var idCliente = $(this).children("img").attr("id").slice(8);
		<!--console.log(idCliente);-->
		var spanval = $(this).children("span:first").text();
		var descval = $(this).children("span#descClient").text();
		<!--console.log(descval);-->
		$("#imageOverFlox").attr("src", imgsrc);
		$(".overboxHeader span.contentOverBox").text(spanval);
		$("span#descSpan").text(descval);
		<!--<span id="category28" name="TELECOMUNICACIONES" class="jobCliente">Cableado y Fibra</span>-->
		$.post("/wp-content/plugins/gallery_clients/admin/lightboxDetails.php",{ idCliente : idCliente },function(tiposTrabajos){
			<!--console.log(tiposTrabajos);-->
			var output = '';
			output += '<h2>Tipos de trabajos</h2>';
			output += '<div id="listJobsCliente" class="listJobsCliente">';
			for (var i= 0; i < tiposTrabajos.length; i++){
				output += '<span style="background-color: rgb(224, 224, 224); color: rgb(0, 0, 0); font-weight: inherit; padding: 5px; margin: 10px; display: -moz-box; width: 200px;" id="job'+tiposTrabajos[i]['term_id']+'" name="'+tiposTrabajos[i]['name']+'" class="jobCliente">'+tiposTrabajos[i]['name']+'</span>';	
			}
			output += '</div>';									
			$('#overJobs').html("");
			$('#overJobs').html(output);										
		}, "json");
	});

	$(document).on('mouseover', '.jobCliente', function() {	   
		$(this).css({
				"background-color": "#757575",
				"color": "#FFF",
				"cursor": "pointer"
		});
	});
													
	$(document).on('mouseout', '.jobCliente', function() {	   
		$(this).css({
			"background-color": "#E0E0E0",
			"color": "#000"
		});
	});	
													
	$(document).on('click', '.jobCliente', function() {	   
		hideLightbox();
		var idJob = $(this).attr("id").slice(3);
		var nameCategory = $(this).text();
		console.log(idJob);
		$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idJob },function(clientes){							
				console.log(clientes);
				if(clientes[0]['isNull'] == true){
					var output = '<p><h2>'+nameCategory+'</h2></p>';
					output += "<h3>"+clientes[0]['message']+"</h3>";
					$('#divShortcode1 #divClient').html("");
					$('#divShortcode1 #divClient').html(output);
					$('#divShortcode1 #divClient').css("height", "500px");
				}else{
					$('#divShortcode1 #divClient').css("height", "auto");
					var output = '';
						output += '<div id="filterbox" class="filterbox">';
							output += '<div id="filterTittle" class="filterboxTittle">';
								output +='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
								output +='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
							output += '</div>';
							output += '<div id="filterForm" class="filterboxForm">';
								output +='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
								output +='Categoria: <select id="selectCategoria"></select>';
								output +='</span>';
								output +='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
								output +='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
								output +='</span>';
							output += '</div>';
						output += '</div>';
						output += '<div id="divClient" class="divClient"> ';
						output += '<p><h2>'+nameCategory+'</h2></p>';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<div id="overImg" class="overboxImg">';
										output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
									output += '</div>';
									output += '<div id="overDesc" class="overboxDesc">';
										output += '<h2>Descripción</h2>';
										output += '<div id="descClient" class="descClient">';
											output += '<span id="descSpan" class="descSpan"></span>';
										output += '</div>';
									output += '</div>';
									output += '<div id="overJobs" class="overboxJobs">';
										output += '<h2>Tipos de trabajos</h2>';
									output += '</div>';
								output += '</div>';
							output += '</div>';
						output += '<div id="fade" class="fadebox">&nbsp;</div>';
						var countC = 0;
						for (var i= 0; i < clientes.length; i++){
							countC= countC + 1;
							output += "<div id='"+countC+"' class='divClientsImage'>";

							output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
							output += "<span>"+clientes[i]['post_title']+"</span>";
							output += "<span id='descClient' style='display:none;'>"+clientes[i]['post_content']+"</span>";							   
							output += "</div>";
						}
						output += "</div>";		
						$('#divShortcode1').html("");
						$('#divShortcode1').html(output);
			}
		}, "json");
	});	

	$(document).on('click', '#btnCloseOverBox', function(){							   
		hideLightbox();
	});	

	<!--Filtro adaptado para dispositivos móviles-->

	<!--Al darle click al icono del filtro carga la información de las categorias y los tipos de trabajo en los select. Muestra el div completo del formulario.-->
	$(document).on('click', '#iconFilterBox', function(){
		$('#filterForm').toggle( "fast" );
		var displayForm = $('#filterForm').css('display');
		if(displayForm == 'block'){
			$.post("/wp-content/plugins/gallery_clients/admin/filtersMobile.php",{ actionSelect: 'listar', typeSelect : 'category' },function(optionSelect){
				$("#selectCategoria").html(optionSelect);
			});
			$.post("/wp-content/plugins/gallery_clients/admin/filtersMobile.php",{ actionSelect: 'listar', typeSelect : 'tipoTrabajo' },function(optionSelect){
				$("#selectTipoTrabajo").html(optionSelect);
			});
		}
		
	});
															   
	<!--Lista los clientes a partir de la categoría seleccionada.-->
	$(document).on('change', '#selectCategoria', function(){
		$("#selectTipoTrabajo").prop('selectedIndex',0);
		if($( "#selectCategoria option:selected" ).text() == "TODOS"){
			$(location).attr('href','http://orbe.visualcom.es/clientes/');
		}else{
			$( "#selectCategoria option:selected" ).each(function() {
				var idCategory = $(this).attr("id");
				console.log(idCategory);
				var nameCategory = $(this).attr("name");
				$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
					if(clientes[0]['isNull'] == true){
						var output = '<p><h2>'+nameCategory+'</h2></p>';
						output += "<h3>"+clientes[0]['message']+"</h3>";
						$('#divShortcode1 #divClient').html("");
						$('#divShortcode1 #divClient').html(output);
						$('#divShortcode1 #divClient').css("height", "500px");
					}else{
						$('#divShortcode1 #divClient').css("height", "auto");
					var output = '';
						output += '<div id="filterbox" class="filterbox">';
							output += '<div id="filterTittle" class="filterboxTittle">';
								output +='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
								output +='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
							output += '</div>';
							output += '<div id="filterForm" class="filterboxForm">';
								output +='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
									output +='Categoria: <select id="selectCategoria"></select>';
								output +='</span>';
								output +='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
									output +='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
								output +='</span>';
							output += '</div>';
						output += '</div>';
						output += '<div id="divClient" class="divClient"> ';
							output += '<p><h2>'+nameCategory+'</h2></p>';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<div id="overImg" class="overboxImg">';
										output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
									output += '</div>';
									output += '<div id="overDesc" class="overboxDesc">';
										output += '<h2>Descripción</h2>';
										output += '<div id="descClient" class="descClient">';
											output += '<span id="descSpan" class="descSpan"></span>';
										output += '</div>';
									output += '</div>';
									output += '<div id="overJobs" class="overboxJobs">';
										output += '<h2>Tipos de trabajos</h2>';
									output += '</div>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_title']+"</span>";
									output += "<span id='descClient' style='display:none;'>"+clientes[i]['post_content']+"</span>";							   
								output += "</div>";
							}
						output += "</div>";		
						$('#divShortcode1').html("");
						$('#divShortcode1').html(output);
					}
				}, "json");
			});
		}
	});
	
	<!--Lista los clientes a partir del tipo de trabajo seleccionada.-->

	$(document).on('change', '#selectTipoTrabajo', function(){
		$("#selectCategoria").prop('selectedIndex',0);
		$( "#selectTipoTrabajo option:selected" ).each(function() {
			var idCategory = $(this).attr("id");
			var nameCategory = $(this).attr("name");										   
            $.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
				if(clientes[0]['isNull'] == true){
					var output = '<p><h2>'+nameCategory+'</h2></p>';
					output += "<h3>"+clientes[0]['message']+"</h3>";
					$('#divShortcode1 #divClient').html("");
					$('#divShortcode1 #divClient').html(output);
					$('#divShortcode1 #divClient').css("height", "500px");
				}else{
					$('#divShortcode1 #divClient').css("height", "auto");
					var output = '';
						output += '<div id="filterbox" class="filterbox">';
							output += '<div id="filterTittle" class="filterboxTittle">';
								output +='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
								output +='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
							output += '</div>';
							output += '<div id="filterForm" class="filterboxForm">';
								output +='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
									output +='Categoria: <select id="selectCategoria"></select>';
								output +='</span>';
								output +='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
									output +='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
								output +='</span>';
							output += '</div>';
						output += '</div>';
						output += '<div id="divClient" class="divClient"> ';
							output += '<p><h2>'+nameCategory+'</h2></p>';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<div id="overImg" class="overboxImg">';
										output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
									output += '</div>';
									output += '<div id="overDesc" class="overboxDesc">';
										output += '<h2>Descripción</h2>';
										output += '<div id="descClient" class="descClient">';
											output += '<span id="descSpan" class="descSpan"></span>';
										output += '</div>';
									output += '</div>';
									output += '<div id="overJobs" class="overboxJobs">';
										output += '<h2>Tipos de trabajos</h2>';
									output += '</div>';
								output += '</div>';
							output += '</div>';
							<!-- 
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox">X</span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								output += '</div>';
							output += '</div>'; 
							-->
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_title']+"</span>";
									output += "<span id='descClient' style='display:none;'>"+clientes[i]['post_content']+"</span>";							   
								output += "</div>";
							}
						output += "</div>";			
					$('#divShortcode1').html("");
					$('#divShortcode1').html(output);
				}
            }, "json");												   
		});
	});
});						