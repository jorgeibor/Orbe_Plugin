jQuery(function ($) {
	$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/filtersMobile.php",{ actionSelect: 'listar', typeSelect : 'category' },function(optionSelect){
		$("#selectCategoria").html(optionSelect);
		$("#selectCategoria option[id='TODOS']").remove();
	});
	
	$(document).on('change', '#selectCategoria', function(){
		$( "#selectCategoria option:selected" ).each(function() {
			var nameCategory = $(this).attr("name");
			$.post("/orbevisualcom/wp-content/plugins/gallery_clients/admin/configAjax.php",{ nameCategory : nameCategory },function(colorCategory){
				console.log(colorCategory);
				$("#famcolor").val(colorCategory)
			}, "json");
		});
	});
});						