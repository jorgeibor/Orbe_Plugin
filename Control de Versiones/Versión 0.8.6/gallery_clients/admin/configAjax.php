<?php

include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_query("SET NAMES 'utf8'");
header('Content-Type: text/html; charset=utf-8');
mysql_select_db(DB_NAME, $con);

$accion = mysql_real_escape_string($_POST["accion"]);

switch($accion){
	case "ConsultarColor":
		consultarColor();
		break;
	case "GuardarConf":
		guardarConf();
		break;
}

function consultarColor(){
	global $con;
	$nameCategory = mysql_real_escape_string($_POST["nameCategory"]);
	if($nameCategory != null || $nameCategory != ""){
		$consulta = "SELECT `family_color` FROM `".table_prefix."config_family_colors` WHERE `family_name`='".$nameCategory."'";
		$resultado = mysql_query($consulta, $con);
		$colorCategory = mysql_result($resultado, 0);
		echo json_encode($colorCategory);
	}
}

function guardarConf(){
	global $con;
	$nameFamiliaSelect = mysql_real_escape_string($_POST["nameFamiliaSelect"]);
	$colorFamilia = mysql_real_escape_string($_POST["colorFamilia"]);
	
	$consulta = "UPDATE `".table_prefix."config_family_colors` SET `family_color`='".$colorFamilia."' WHERE `family_name`='".$nameFamiliaSelect."'";
	$result=mysql_query($consulta);
	if($result){
		$msg[] = array (
			"isNull"=> false,
			"message"=> "Se ha modificado el color de la familia correctamente"
		);
		echo json_encode($msg);
	}else{
		$msg[] = array (
			"isNull"=> true,
			"message"=> "No se ha modificado el color de la familia"
		);
		echo json_encode($msg);
	}
}

mysql_close($con);
?>