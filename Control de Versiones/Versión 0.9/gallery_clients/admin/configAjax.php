<?php

include_once "../../../../wp-config.php";
$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_query("SET NAMES 'utf8'");
header('Content-Type: text/html; charset=utf-8');
mysql_select_db(DB_NAME, $con);

$accion = mysql_real_escape_string($_POST["accion"]);

switch($accion){
	case "ListarFamilias":
		listarFamilias();
		break;
	case "ConsultarColor":
		consultarColor();
		break;	
	case "resetConfColor":
		resetConfColor();
		break;
	case "GuardarConf":
		guardarConf();
		break;
}

function listarFamilias(){
	global $con;
	$sqlListCategory = "SELECT `".table_prefix."term_taxonomy`.`term_id`, `".table_prefix."term_taxonomy`.`parent` FROM `".table_prefix."term_taxonomy` WHERE `taxonomy`='Tipo de trabajo' AND `parent`=0 order by `parent`";
	$resultadoListCategory = mysql_query($sqlListCategory, $con);
	/* Recorre todas las categorias */
	while ($rowListCategory = mysql_fetch_assoc($resultadoListCategory)) {
		$idCategory = $rowListCategory['term_id'];
		//Coge el nombre de la categoria que esta recorriendo.
		$sqlNameCategory = "SELECT `name` FROM `".table_prefix."terms` WHERE `term_id`=".$idCategory;
		$resultadoNameCategory = mysql_query($sqlNameCategory, $con);
		while ($rowNameCategory = mysql_fetch_assoc($resultadoNameCategory)) {
			$nameCategory = $rowNameCategory['name'];
			$listFamily[] = array (
				"id"=>$idCategory,
				"nombre"=>$nameCategory
			);
		}

	}
	
	foreach($listFamily as $family => $info){
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_plugin` WHERE `family_name`='".$info['nombre']."'";
		$resultado = mysql_query($consulta, $con);
		$colorFamilia = mysql_result($resultado, 0, 'family_color');
		if($colorFamilia == null || $colorFamilia == ""){
			$colorFamilia = "#757575";
		}
		$listaFamilias[] = array (
			"id"=>$info['id'],
			"nombre"=>$info['nombre'],
			"color"=>$colorFamilia
		);
	}
	
	echo json_encode($listaFamilias);
	
	
}

function consultarColor(){
	global $con;
	$idFamiliaSelect = mysql_real_escape_string($_POST["familyId"]);
	if($idFamiliaSelect != null || $idFamiliaSelect != ""){
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_plugin` WHERE `family_id`='".$idFamiliaSelect."'";
		$resultado = mysql_query($consulta, $con);
		$colorCategory = mysql_result($resultado, 0);
		echo json_encode($colorCategory);
	}
}

function resetConfColor(){
	global $con;
	$idFamiliaSelect = mysql_real_escape_string($_POST["idFamiliaSelect"]);
	if($idFamiliaSelect != null || $idFamiliaSelect != ""){
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_plugin` WHERE `family_id`='".$idFamiliaSelect."'";
		$resultado = mysql_query($consulta, $con);
		$colorCategory = mysql_result($resultado, 0);
		echo json_encode($colorCategory);
	}
}

function guardarConf(){
	global $con;
	$confFamiliasColor = $_REQUEST['confFamiliasColor'];
	$countErrorInsert = 0;
	$countErrorUpdate = 0;
	
	foreach ( $confFamiliasColor as $clave => $valor ){
		$consultaConfig = "SELECT * FROM `".table_prefix."config_plugin` WHERE `family_id` = '".$valor['id']."'";
		$resultConfig=mysql_query($consultaConfig);
		$rows = mysql_num_rows($resultConfig);
		if($rows == 0){
			$consultaInsert = "INSERT INTO `".table_prefix."config_plugin`(`family_id`, `family_name`, `family_color`) VALUES ('".$valor['id']."', '".$valor['nombre']."', '".$valor['color']."')";
			$resultInsert=mysql_query($consultaInsert);
			if(!$resultInsert){
				$countErrorInsert = $countErrorInsert + 1;
			}
		}else{
			$consultaUpdate = "UPDATE `".table_prefix."config_plugin` SET `family_color`='".$valor['color']."' WHERE `family_id`='".$valor['id']."'";
			$resultUpdate=mysql_query($consultaUpdate);
			if(!$resultUpdate){
				$countErrorUpdate = $countErrorUpdate + 1;
			}
		}
	}
	
	if($countErrorInsert > 0 || $countErrorUpdate > 0){
		$msg[] = array (
			"isNull"=> true,
			"message"=> "Ha habido un problema al guardar los cambios de la configuración",
			"countErr" => $countErrorInsert." ".$countErrorUpdate
		);
	}else{
		$msg[] = array (
			"isNull"=> false,
			"message"=> "Los cambios se han guardado correctamente",
			"countErr" => $countErrorInsert." ".$countErrorUpdate
		);
	}
	echo json_encode($msg);
	
}

mysql_close($con);
?>