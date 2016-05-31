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
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_family_colors` WHERE `family_name`='".$info['nombre']."'";
		$resultado = mysql_query($consulta, $con);
		$colorFamilia = mysql_result($resultado, 0);
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

function resetConfColor(){
	global $con;
	$idFamiliaSelect = mysql_real_escape_string($_POST["idFamiliaSelect"]);
	if($idFamiliaSelect != null || $idFamiliaSelect != ""){
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_family_colors` WHERE `family_id`='".$idFamiliaSelect."'";
		$resultado = mysql_query($consulta, $con);
		$colorCategory = mysql_result($resultado, 0);
		echo json_encode($colorCategory);
	}
}

function guardarConf(){
	global $con;
	$idFamiliaSelect = mysql_real_escape_string($_POST["idFamiliaSelect"]);
	$nameFamiliaSelect = mysql_real_escape_string($_POST["nameFamiliaSelect"]);
	$colorFamilia = mysql_real_escape_string($_POST["colorFamilia"]);
	$consultaSelect = "SELECT * FROM `".table_prefix."config_family_colors` WHERE `family_id` = '".$idFamiliaSelect."'";
	$result=mysql_query($consulta);
	$rows = mysql_num_rows($result);
	if($rows < 0){
		$consulta = "INSERT INTO `".table_prefix."config_family_colors`(`family_id`, `family_name`, `family_color`) VALUES ('".$idFamiliaSelect."', '".$nameFamiliaSelect."', '".$colorFamilia."')";
		$result=mysql_query($consulta);
		if($result){
			$msg[] = array (
				"isNull"=> false,
				"message"=> "Se ha insertado el color de la familia correctamente"
			);
			echo json_encode($msg);
		}else{
			$msg[] = array (
				"isNull"=> true,
				"message"=> "No se ha ha insertado el color de la familia"
			);
			echo json_encode($msg);
		}
	}else{
		$consulta = "UPDATE `".table_prefix."config_family_colors` SET `family_color`='".$colorFamilia."' WHERE `family_name`='".$nameFamiliaSelect."'";
		$result=mysql_query($consulta);
		if($result){
			$msg[] = array (
				"isNull"=> false,
				"message"=> "Se ha modificado el color de la familia correctamente"
			);
			//"Se ha modificado el color de la familia correctamente"
			echo json_encode($msg);
		}else{
			$msg[] = array (
				"isNull"=> true,
				"message"=> "No se ha modificado el color de la familia"
			);
			echo json_encode($msg);
		}
	}
}

mysql_close($con);
?>