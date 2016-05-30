<?php
require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
//Tipos de trabajo

function listadoTrabajos() {
	?>
		<style>
			<?php include_once GC_PLUGIN_DIR .'/css/category.css';?>
			<?php include_once GC_PLUGIN_DIR .'/css/clients.css';?>
		</style>
	<?php
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
	//mysql_query("SET NAMES 'utf8'");
	header('Content-Type: text/html; charset=utf-8');
	mysql_select_db(DB_NAME, $con);
	//Listar todas las categorias existentes en la BD
	$sqlListCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, `".table_prefix."term_taxonomy`.`parent` FROM `".table_prefix."term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
	$resultadoListCategory = mysql_query($sqlListCategory, $con);
		$output = '';
				$output .= '<div id="divCategory" class="divCategory" > ';
					$countC = 0;
					/* Recorre todas las categorias y recoge todos los tipos de trabajos asignados a cada uno. */
					while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
						$idCategory = $rowListCategory['term_id'];
						//Coge el nombre de la categoria que esta recorriendo.
						$sqlNameCategory = "SELECT `name` FROM `".table_prefix."terms` WHERE `term_id`=".$idCategory;
						$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
						while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
							$nameCategory = $rowNameCategory['name'];
							$output .= "<div id='listCategory".$nameCategory."' class='divListCategory'>";
								$output .= "<h1 id='h1".$nameCategory."' name='' class='h1".$nameCategory."'>".$nameCategory."</h1>";
								//Recoge los tipos de trabajo asignado a la categoria que esta recorriendo.
								$sqlNameCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, Terms.`name` FROM `".table_prefix."term_taxonomy` INNER JOIN (SELECT `".table_prefix."terms`.`term_id`,`".table_prefix."terms`.`name` FROM `".table_prefix."terms`)Terms ON Terms.`term_id` = `".table_prefix."term_taxonomy`.`term_id` WHERE `".table_prefix."term_taxonomy`.`parent`=".$idCategory;
								$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
								while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
									$output .= "<span id='category".$rowNameCategory['term_id']."' name='".$nameCategory."'>".$rowNameCategory['name']."</span>";
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