<?php
require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
// Clientes
add_action( 'init', 'my_custom_init' );
/*CONFIGURACION BACKEND WORDPRESS*/
function my_custom_init() {
	$labels = array(
	'name' => _x( 'Clientes', 'post type general name' ),
        'singular_name' => _x( 'Cliente', 'post type singular name' ),
        'add_new' => _x( 'Añadir nuevo cliente', 'cliente' ),
        'add_new_item' => __( 'Añadir nuevo cliente' ),
        'edit_item' => __( 'Editar cliente' ),
        'new_item' => __( 'Nuevo cliente' ),
        'view_item' => __( 'Ver cliente' ),
        'search_items' => __( 'Buscar cliente' ),
        'not_found' =>  __( 'No se han encontrado clientes' ),
        'not_found_in_trash' => __( 'No se han encontrado clientes en la papelera' ),
        'parent_item_colon' => ''
    );

    $args = array( 'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title', 'editor', 'thumbnail' ), 
		'menu_icon' => 'dashicons-groups',
		'rewrite' => true,
		'exclude_from_search'=>true
    );
 
    register_post_type( 'cliente', $args );
}

function listadoClientes() {
	?>
		<style>
			<?php include_once GC_PLUGIN_DIR .'/css/clients.css';?>
		</style>
	<?php
	$url = "http://orbe.visualcom.es/clientes/";
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
	mysql_select_db(DB_NAME, $con);
	
	//Lista todos los clientes existentes en la BD.
	$sqlInfoClient = "SELECT `Posts`.`ID`, `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, `Posts`.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente')Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID`";
	$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
	
	/*PAGINACION*/
	$num_total_registros = mysql_num_rows($resultadoInfoClient);
	$TAMANO_PAGINA = 12;

	$pagina = $_GET["pagina"];
	if (!$pagina) {
		$inicio = 0;
		$pagina = 1;
	}
	else {
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);
	
	$sqlInfoClient = "SELECT `Posts`.`ID`, `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, `Posts`.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente')Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID` ORDER BY ID ASC LIMIT $inicio, $TAMANO_PAGINA";
	/************/
		$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
	
		while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
			//Esta consulta sirve para guardar los tipos de trabajos asignados en cada cliente.
			$sqlCategoryClient = "SELECT * FROM `0rb3_term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
			$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
			
			$category_client;
			while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
				$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
			}
			$datosTodos[] = array (
            	"guid"=>utf8_encode($rowInfoClient["guid"]),
				"post_parent"=>utf8_encode("clients.".$rowInfoClient["post_parent"]),
				"post_content"=>utf8_encode($rowInfoClient["post_content"]),
				"term_taxonomy_id"=>utf8_encode($category_client)
        	);
			unset($category_client);
		}
		/*MONTAR ESTRUCTURA HTML*/
		$output = '';
				$output .= '<div id="filterbox" class="filterbox">';
					$output .= '<div id="filterTittle" class="filterboxTittle">';
						$output .='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
						$output .='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
					$output .= '</div>';
					$output .= '<div id="filterForm" class="filterboxForm">';
						$output .='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
							$output .='Categoria: <select id="selectCategoria"></select>';
						$output .='</span>';
						$output .='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
							$output .='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
						$output .='</span>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="divClient"> ';
					$output .= '<p><h2>Todos</h2></p> ';
					$output .= '<div id="over" class="overbox">';
						$output .= '<div id="over" class="overboxHeader">';
							$output .='<span id="contentOverBox" class="contentOverBox"></span>';
							$output .='<span id="btnCloseOverBox" class="btnCloseOverBox">X</span>';
						$output .= '</div>';
						$output .= '<div id="over" class="overboxContent">';
							$output .= '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="fade" class="fadebox">&nbsp;</div>';
						$countC = 0;
						foreach ($datosTodos as $todos) {
							$countC= $countC + 1;
							$output .= '<div id="'.$countC.'" class="divClientsImage">';
								$output .= '<img id="'.$todos['post_parent'].'" alt="'.$todos['term_taxonomy_id'].'" class="clientsImage BandW" src="'.$todos['guid'].'"/>';
								$output .= '<span>'.$todos['post_content'].'</span>';
							$output .= '</div>';
						}
					/*VER PAGINACION*/	
					$output .= '<div id="paginacionDiv" class="paginacionDiv"> ';
							if ($total_paginas > 1) {
								$output .= '<ul id="paginacionUl" class="paginacionUl">';
								if ($pagina != 1){
									$output .= '<li><a class="paginacionIzq fa fa-caret-left " href="'.$url.'?pagina='.($pagina-1).'"></a></li>';
								}
								for ($i=1;$i<=$total_paginas;$i++) {
									if ($pagina == $i){
										$output .= '<li class="active" ><a>'.$i.'</a></li>';
									}else{
										$output .= '<li><a href="'.$url.'?pagina='.$i.'">'.$i.'</a></li>';
									}
								}
								if ($pagina != $total_paginas){
									$output .= '<li><a class="paginacionDer fa fa-caret-right " href="'.$url.'?pagina='.($pagina+1).'"></a></li>';
								}
								$output .= '</ul>';
							}
					$output .= '</div>';
					/****************/
				$output .= '</div>';
		?>
		<script>
			<?php include_once GC_PLUGIN_DIR .'/js/clients.js';?>
		</script>	
		<?php
		return $output;
	mysql_close($con);
}
add_shortcode('listaClientes','listadoClientes');
?>
