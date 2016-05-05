<?php
/* Este fichero se ejecuta cuando clients.js pide información. */
/* Este fichero completa las opciones de los select */
include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_select_db(DB_NAME, $con);

$actionSelect = mysql_real_escape_string($_POST["actionSelect"]);
$typeSelect = mysql_real_escape_string($_POST["typeSelect"]);
$output = '';
if($actionSelect != null || $actionSelect != ""){
	if($actionSelect == "listar"){
		
		//Si el select utilizado es el de categorias monta las opciones de las categorias.
		if($typeSelect == "category"){
			$listFamily = array('Todos','Telecom','Seguridad','Orgeby');
			$output .= '<option id="defaultSelected" disabled="" selected="">Selecciona una Categoría</option>';	
			foreach($listFamily as $family){
				$output .= '<option id="'.$family.'" name="'.$family.'">'.$family.'</option>';
				$count++;
			}
			echo $output;
		//Si el select utilizado es el de tipos de trabajo monta las opciones de las tipos de trabajo con sus cabeceras de las Sub Categorías.	
		}else if($typeSelect == "tipoTrabajo"){
			$sqlListCategory = "SELECT `0rb3_term_taxonomy`.`term_id`, `0rb3_term_taxonomy`.`parent` FROM `0rb3_term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
			$resultadoListCategory = mysql_query($sqlListCategory, $con);
			$output .= '<option id="defaultSelected" disabled="" selected="">Selecciona un Tipo de Trabajo</option>';	
			while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
				$idFamily = $rowListCategory['term_id'];
				$sqlNameCategory = "SELECT `name` FROM `0rb3_terms` WHERE `term_id`=".$idFamily;
				$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
				while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
					$nameCategory = utf8_encode($rowNameCategory['name']);
					$output .= '<optgroup label="'.$nameCategory.'">';
						$sqlNameCategory = "SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$idFamily;
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