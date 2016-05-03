<?php
require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
//Tipos de trabajo
/*CONFIGURACION BACKEND WORDPRESS*/
add_action( 'init', 'create_cliente_taxonomies', 0 );
function create_cliente_taxonomies() {
	$labels = array(
	'name' => _x( 'Tipos de trabajo', 'taxonomy general name' ),
	'singular_name' => _x( 'Tipo de trabajo', 'taxonomy singular name' ),
	'search_items' =>  __( 'Buscar por tipo de trabajo' ),
	'all_items' => __( 'Todos los tipos de trabajo' ),
	'parent_item' => __( 'Tipo de trabajo padre' ),
	'parent_item_colon' => __( 'Tipo de trabajo padre:' ),
	'edit_item' => __( 'Editar tipo de trabajo' ),
	'update_item' => __( 'Actualizar tipo de trabajo' ),
	'add_new_item' => __( 'Añadir nuevo tipo de trabajo' ),
	'new_item_name' => __( 'Nombre del nuevo tipo de trabajo' ),
    'not_found' =>  __( 'No se han encontrado tipos de trabajo' )
);
	register_taxonomy( 'Tipo de trabajo', array( 'cliente' ), 
	array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'Tipo-de-trabajo' )
	));
}
/*********************************/
function listadoTrabajos() {
	?>
		<style>
			<?php include_once GC_PLUGIN_DIR .'/css/category.css';?>
			<?php include_once GC_PLUGIN_DIR .'/css/clients.css';?>
		</style>
	<?php
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
	mysql_select_db(DB_NAME, $con);
	//Listar todas las categorias existentes en la BD
	$sqlListCategory = "SELECT `0rb3_term_taxonomy`.`term_id`, `0rb3_term_taxonomy`.`parent` FROM `0rb3_term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
	$resultadoListCategory = mysql_query($sqlListCategory, $con);
		$output = '';
			$output .= '<div id="divCategory" class="divCategory" > ';
				$countC = 0;
				$output .= "<div id='listFamily' class='divListFamily'>";
					$output .= "<span id='categoryTodos' class='categoryFamily' name='Todos'>Todos</span>";
					$output .= "<span id='categoryTelecom' class='categoryFamily' name='Telecom'>Telecomunicaciones</span>";
					$output .= "<span id='categorySeguridad' class='categoryFamily' name='Seguridad'>Seguridad</span>";
					$output .= "<span id='categoryOrgeby' class='categoryFamily' name='Orgeby'>Orgeby</span>";
				$output .= "</div>";
				/* Recorre todas las categorias y recoge todos los tipos de trabajos asignados a cada uno. */
				while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
					$idCategory = $rowListCategory['term_id'];
					//Coge el nombre de la categoria que esta recorriendo.
					$sqlNameCategory = "SELECT `name` FROM `0rb3_terms` WHERE `term_id`=".$idCategory;
					$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
					while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
						$nameCategory = utf8_encode($rowNameCategory['name']);
						$output .= "<div id='listCategory".$nameCategory."' class='divListCategory'>";
							$output .= "<h1 class='h1".$idCategory."'>".$nameCategory."</h1>";
							//Recoge los tipos de trabajo asignado a la categoria que esta recorriendo.
							$sqlNameCategory = "SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$idCategory;
							$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
							while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
								$output .= "<span id='category".$rowNameCategory['term_id']."' name='".$nameCategory."'>".utf8_encode($rowNameCategory['name'])."</span>";
							}
						$output .= "</div>";
					}
					
				}
				/******************************************************************************************/
			$output .= '</div> ';
		?>
		<script>
			<?php include_once GC_PLUGIN_DIR .'/js/category.js';?>
			<?php include_once GC_PLUGIN_DIR .'/js/clients.js';?>
		</script>	
		<?php
		return $output;
}

add_shortcode('listaTrabajos','listadoTrabajos');

?>
