$(document).on('ready', function(){
	var nClients = $("#nClientes").val();
	var idCategorys;
	var nameFamily;
	var count = 0;		
	var countC = 0;
	
	<!--Al darle click a un botón de la columna de Tipos de Trabajos, recoge la información sobre la Categoría/Tipo de Trabajo y lo envía a client.php para poder construir la estructura html.-->

	$(document).on('click', '#divCategory span', function(){
		var nameFilter = $(this).text();
		var idCategory = $(this).attr("id").slice(8);
		if(idCategory == "Todos"){
			$(location).attr('href','http://orbe.visualcom.es/clientes/');
		}else{
			$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
				<!--console.log(clientes);-->
				if(clientes[0]['isNull'] == true){
					var output = '<p><h2>'+nameFilter+'</h2></p>';
					output += "<h3>"+clientes[0]['message']+"</h3>";
					$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");
					$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
				}else if(clientes == "Listar Todos"){
					location.reload();
				}else{
					var output = '';
						output += '<p><h2>'+nameFilter+'</h2></p>';
						output += '<div class="divClient"> ';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox">X</span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_content']+"</span>";
								output += "</div>";
							}
						output += "</div>";			
					$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");
					$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
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
								"color": "#FFF"
						});
        				break;
					case "INSTALACIONES ESPECIALES":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#D67D00",
								"color": "#FFF"
						});
        				break;
					case "ELECTRICIDAD":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#FFFF00",
								"color": "#000"
						});
        				break;
					case "SEGURIDAD":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#E30B24",
								"color": "#FFF"
						});
        				break;
					case "ENERGÍA":
       					$( "#category"+categorias_Clientes[count] ).css({
								"background-color": "#009036",
								"color": "#FFF"
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
			"background-color": "#CCCCCC",
			"color": "#808080"
		});    
	});
	<!-- Impedir hacer scroll con el ratón cuando el lightbox esta en pantalla.-->
	var keys = {37: 1, 38: 1, 39: 1, 40: 1};

	function preventDefault(e) {
	  	e = e || window.event;
	  	if (e.preventDefault)
		  	e.preventDefault();
	  	e.returnValue = false;  
	}

	function preventDefaultForScrollKeys(e) {
		if (keys[e.keyCode]) {
			preventDefault(e);
			return false;
		}
	}

	function disableScroll() {
	  if (window.addEventListener) // older FF
		  	window.addEventListener('DOMMouseScroll', preventDefault, false);
	 		window.onwheel = preventDefault; // modern standard
	  		window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
	  		window.ontouchmove  = preventDefault; // mobile
	  		document.onkeydown  = preventDefaultForScrollKeys;
	}

	function enableScroll() {
		if (window.removeEventListener)
			window.removeEventListener('DOMMouseScroll', preventDefault, false);
			window.onmousewheel = document.onmousewheel = null; 
			window.onwheel = null; 
			window.ontouchmove = null;  
			document.onkeydown = null;  
	}
	function showLightbox() {
		$("#over").css("display", "block");
		$("#fade").css("display", "block");
	}
	function hideLightbox() {
		$("#over").css("display", "none");
		$("#fade").css("display", "none");
	}
	$(document).on('click', '.divClientsImage', function(){		
		disableScroll();			   
		showLightbox();
		var imgsrc = $(this).children("img").attr("src");
		var spanval = $(this).children("span").text();
		$("#imageOverFlox").attr("src", imgsrc);
		$(".overboxHeader span.contentOverBox").text(spanval);
	});

	$(document).on('click', '#btnCloseOverBox', function(){	
		enableScroll();						   
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
		if($( "#selectCategoria option:selected" ).text() == "Todos"){
			$(location).attr('href','http://orbe.visualcom.es/clientes/');
		}else{
			$( "#selectCategoria option:selected" ).each(function() {
				var idCategory = $(this).attr("id");
				var nameCategory = $(this).attr("name");
				$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
					if(clientes[0]['isNull'] == true){
						var output = '<p><h2>'+nameCategory+'</h2></p>';
						output += "<h3>"+clientes[0]['message']+"</h3>";
						$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");
						$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
					}else{
						var output = '';
							output += '<p><h2>'+nameCategory+'</h2></p>';
							output += '<div class="divClient"> ';
								output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox">X</span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

								output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
								output += "<span>"+clientes[i]['post_content']+"</span>";
								output += "</div>";
							}
							output += "</div>";			
						$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");										   
						$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
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
					$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");										   
					$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
				}else{
					var output = '';
						output += '<p><h2>'+nameCategory+'</h2></p>';
						output += '<div class="divClient"> ';
							output += '<div id="over" class="overbox">';
								output += '<div id="over" class="overboxHeader">';
									output +='<span id="contentOverBox" class="contentOverBox"></span>';
									output +='<span id="btnCloseOverBox" class="btnCloseOverBox">X</span>';
								output += '</div>';
								output += '<div id="over" class="overboxContent">';
									output += '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								output += '</div>';
							output += '</div>';
							output += '<div id="fade" class="fadebox">&nbsp;</div>';
							var countC = 0;
							for (var i= 0; i < clientes.length; i++){
								countC= countC + 1;
								output += "<div id='"+countC+"' class='divClientsImage'>";

									output += "<img id='"+clientes[i]['post_parent']+"' alt='"+clientes[i]['term_taxonomy_id']+"' class='clientsImage BandW' src='"+clientes[i]['guid']+"'/>";
									output += "<span>"+clientes[i]['post_content']+"</span>";
								output += "</div>";
							}
						output += "</div>";			
					$('.fl-node-5718722ce0122 .fl-module-content .paginacionDiv').html("");
					$('.fl-node-5718722ce0122 .fl-module-content .divClient').html(output);
				}
            }, "json");												   
		});
	});
															   
});						