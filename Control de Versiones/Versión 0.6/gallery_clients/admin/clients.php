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
        'public' => false,
        'publicly_queryable' => false,
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
	
	//Recoger posibles parametros pasados por GET.
	$filtro = $_GET["filtro"];
	if($filtro != null || $filtro != ""){
		$sqlIdFiltro = "SELECT `term_id` FROM `0rb3_terms` WHERE `name`='".$filtro."'";
		$resultIdFiltro = mysql_query($sqlIdFiltro, $con);
		$idFiltro = mysql_result($resultIdFiltro, 0);
		
		/*
			<a href="http://orbe.visualcom.es/clientes?filtro=Cableado y Fibra"><span style="color: #ffffff; font-size: 12px;">Cableado y Fibra</span></a>
			<a href="http://orbe.visualcom.es/clientes?filtro=Electrónica de Red"><span style="color: #ffffff; font-size: 12px;"> Electrónica de Red</span></a>
			<a href="http://orbe.visualcom.es/clientes?filtro=Soluciones Inalámbricas"><span style="color: #ffffff; font-size: 12px;"> Soluciones Inalámbricas</span></a>
			<a href="http://orbe.visualcom.es/clientes?filtro=Seguridad en Redes"><span style="color: #ffffff; font-size: 12px;"> Seguridad en Redes</span></a>
			<a href="http://orbe.visualcom.es/clientes?filtro=Comunicaciones Unificadas"><span style="color: #ffffff; font-size: 12px;"> Comunicaciones Unificadas</span></a>
			<a href="http://orbe.visualcom.es/clientes?filtro=Data Center"><span style="color: #ffffff; font-size: 12px;"> Data Center</span></a>
		*/
		
		/*
		$sqlPostSubCategory ="SELECT `0rb3_term_relationships`.`object_id` FROM `0rb3_term_relationships` WHERE `term_taxonomy_id`=".$idFiltro;
		$resultadoPostSubCategory = mysql_query($sqlPostSubCategory, $con);
		while ($rowPostSubCategory = mysql_fetch_assoc($resultadoPostSubCategory)) {
			$idCliente = $rowPostSubCategory['object_id'];
			$sqlListClient ="SELECT `Posts`.`ID`, `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente' AND `ID`=".$idCliente.")Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID`";
			//$sqlListClient ="SELECT `0rb3_posts`.* FROM `0rb3_posts` WHERE `ID`=".$idCliente;
			$resultadoListClient = mysql_query($sqlListClient, $con);
			while ($rowListClient = mysql_fetch_assoc($resultadoListClient)) {
				$sqlCategoryClient = "SELECT * FROM `0rb3_term_relationships` WHERE `object_id`='".$rowListClient["post_parent"]."'";
				$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);

				$category_client;
				while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
					$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
				}
				$clientesFiltro[] = array (
					"guid"=>utf8_encode($rowListClient["guid"]),
					"post_parent"=>utf8_encode("clients.".$rowListClient["ID"]),
					"post_title"=>utf8_encode($rowListClient["post_title"]),
					"post_content"=>utf8_encode($rowListClient["post_content"]),
					"term_taxonomy_id"=>utf8_encode($category_client)
				);
				unset($category_client);
			}
		}
		*/	
		
		//var_dump($clientesFiltro);
	}else{
		//Lista todos los clientes existentes en la BD.
		$sqlInfoClient = "SELECT `Posts`.`ID`, `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente')Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID`";
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

			$sqlInfoClient = "SELECT `Posts`.`ID`, `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente')Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID` ORDER BY ID ASC LIMIT $inicio, $TAMANO_PAGINA";
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
				$clientesFiltro[] = array (
					"guid"=>utf8_encode($rowInfoClient["guid"]),
					"post_parent"=>utf8_encode("clients.".$rowInfoClient["post_parent"]),
					"post_title"=>utf8_encode($rowInfoClient["post_title"]),
					"post_content"=>utf8_encode($rowInfoClient["post_content"]),
					"term_taxonomy_id"=>utf8_encode($category_client)
				);
				unset($category_client);
			}
	}
		/*MONTAR ESTRUCTURA HTML*/
		$output = '';
			$output .= '<div id="divShortcode1" class="divShortcode1" >';
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
				$output .= '<div id="divClient" class="divClient"> ';
					if($filtro != null || $filtro != ""){
						$output .= '<p><h2>'.$filtro.'</h2></p>';
					}else{
						$output .= '<p><h2>Todos</h2></p> ';
					}
					$output .= '<div id="over" class="overbox">';
						$output .= '<div id="over" class="overboxHeader">';
							$output .='<span id="contentOverBox" class="contentOverBox"></span>';
							$output .='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
						$output .= '</div>';
						$output .= '<div id="over" class="overboxContent">';
							$output .= '<div id="overImg" class="overboxImg">';
								$output .= '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								//$output .= '<span style="font-size: 20px; float: right; cursor: pointer;" class="fa fa-expand"></span>';
							$output .= '</div>';
							$output .= '<div id="overDesc" class="overboxDesc">';
								$output .= '<h2>Descripción</h2>';
								$output .= '<div id="descClient" class="descClient">';
									$output .= '<span id="descSpan" class="descSpan"></span>';
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div id="overJobs" class="overboxJobs">';
								$output .= '<h2>Tipos de trabajos</h2>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="fade" class="fadebox">&nbsp;</div>';
						$countC = 0;
						foreach ($clientesFiltro as $clientes) {
							$countC= $countC + 1;
							$output .= '<div id="'.$countC.'" class="divClientsImage">';
								$output .= '<img id="'.$clientes['post_parent'].'" alt="'.$clientes['term_taxonomy_id'].'" class="clientsImage BandW" src="'.$clientes['guid'].'"/>';
								$output .= '<span>'.$clientes['post_title'].'</span>';
								$output .= '<span id="descClient" style="display:none;">'.$clientes['post_content'].'</span>';
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
