<?php
/* Este fichero se ejecuta cuando clients.js pide información. */
/* Este fichero completa las opciones de los select */
include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
//mysql_query("SET NAMES 'utf8'");
header('Content-Type: text/html; charset=utf-8');
mysql_select_db(DB_NAME, $con);

$actionSelect = mysql_real_escape_string($_POST["actionSelect"]);
$typeSelect = mysql_real_escape_string($_POST["typeSelect"]);
$output = '';
if($actionSelect != null || $actionSelect != ""){
	if($actionSelect == "listar"){
		
		//Si el select utilizado es el de categorias monta las opciones de las categorias.
		if($typeSelect == "category"){
			$listFamily = array('TODOS');
			$sqlListCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, `".table_prefix."term_taxonomy`.`parent` FROM `".table_prefix."term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
			$resultadoListCategory = mysql_query($sqlListCategory, $con);
			$countC = 0;
			/* Recorre todas las categorias */
			while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
				$idCategory = $rowListCategory['term_id'];
				//Coge el nombre de la categoria que esta recorriendo.
				$sqlNameCategory = "SELECT `name` FROM `".table_prefix."terms` WHERE `term_id`=".$idCategory;
				$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
				while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
					$nameCategory = utf8_encode($rowNameCategory['name']);
					array_push($listFamily, $nameCategory);
				}

			}
			$output = "";
			$output .= '<option id="defaultSelected" disabled="" selected="">Selecciona una Categoría</option>';	
			foreach($listFamily as $family){
				$output .= '<option id="'.$family.'" name="'.$family.'">'.$family.'</option>';
				$count++;
			}
			echo $output;
		//Si el select utilizado es el de tipos de trabajo monta las opciones de las tipos de trabajo con sus cabeceras de las Sub Categorías.	
		}else if($typeSelect == "tipoTrabajo"){
			$sqlListCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, `".table_prefix."term_taxonomy`.`parent` FROM `".table_prefix."term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
			$resultadoListCategory = mysql_query($sqlListCategory, $con);
			$output .= '<option id="defaultSelected" disabled="" selected="">Selecciona un Tipo de Trabajo</option>';	
			while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
				$idFamily = $rowListCategory['term_id'];
				$sqlNameCategory = "SELECT `name` FROM `".table_prefix."terms` WHERE `term_id`=".$idFamily;
				$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
				while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
					$nameCategory = utf8_encode($rowNameCategory['name']);
					$output .= '<optgroup label="'.$nameCategory.'">';
						$sqlNameCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, Terms.`name` FROM `".table_prefix."term_taxonomy` INNER JOIN (SELECT `".table_prefix."terms`.`term_id`,`".table_prefix."terms`.`name` FROM `".table_prefix."terms`)Terms ON Terms.`term_id` = `".table_prefix."term_taxonomy`.`term_id` WHERE `".table_prefix."term_taxonomy`.`parent`=".$idFamily;
						$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
						while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
							$output .= '<option id="'.$rowNameCategory["term_id"].'" name="'.utf8_encode($rowNameCategory['name']).'">'.utf8_encode($rowNameCategory['name']).'</option>';	
						}
					$output .= '</optgroup">';
				}
			}
			echo $output;
		}
	}
}
mysql_close($con);
?>