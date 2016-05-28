<?php

include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_query("SET NAMES 'utf8'");
header('Content-Type: text/html; charset=utf-8');
mysql_select_db(DB_NAME, $con);

$nameCategory = mysql_real_escape_string($_POST["nameCategory"]);

if($nameCategory != null || $nameCategory != ""){
	$consulta = "SELECT `family_color` FROM `".table_prefix."config_family_colors` WHERE `family_name`='".$nameCategory."'";
	$resultado = mysql_query($consulta, $con);
	$colorCategory = mysql_result($resultado, 0);
	echo json_encode($colorCategory);
}
mysql_close($con);
?>