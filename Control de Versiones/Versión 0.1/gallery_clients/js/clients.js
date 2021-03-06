$(document).on('ready', function(){
	var nClients = $("#nClientes").val();
	var idCategorys;
	var nameFamily;
	var count = 0;						   
	var idClients = [];
	var countC = 0;
	$(document).on('each', 'div.divClient div.divClientsImage img', function( value ){
		countC = countC + 1;
		if (idClients || countC){
			idClients.push($(this).attr("id").split('clients.'));
		}		  
	});
	
	$(document).on('click', '#divCategory span', function(){
		var nameFilter = $(this).text();
		var idCategory = $(this).attr("id").slice(8);
		$.post("/wp-content/plugins/gallery_clients/admin/filters.php",{ idCategory : idCategory },function(clientes){
			<!--console.log(clientes);-->
			if(clientes[0]['isNull'] == true){
				var output = '<p><h2>'+nameFilter+'</h2></p>';
				output += "<h3>"+clientes[0]['message']+"</h3>";
				$('.fl-node-5718722ce0122 .fl-module-content .fl-rich-text').html(output);
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

				$('.fl-node-5718722ce0122 .fl-module-content .fl-rich-text').html(output);
			}
		}, "json");
	});
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
	$(document).on('mouseout', 'div.divClientsImage img', function() {
		$( "div#divCategory span" ).css({
			"background-color": "#CCCCCC",
			"color": "#808080"
		});    
	});

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
		var spanval = $(this).children("span").text();
		$("#imageOverFlox").attr("src", imgsrc);
		$(".overboxHeader span.contentOverBox").text(spanval);
	});

	$(document).on('click', '#btnCloseOverBox', function(){							   
		hideLightbox();
	});												   
});						