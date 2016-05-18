<?php
include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_select_db(DB_NAME, $con);
//Recoge el id de la categoria/tipo de trabajo seleccionado
$idCliente = mysql_real_escape_string($_POST["idCliente"]);

if($idCliente != null || $idCliente != ""){
	global $con;
	$sqlJobs ="SELECT `term_taxonomy_id` FROM `0rb3_term_relationships` WHERE `object_id` = ".$idCliente;
	$resultadoJobs = mysql_query($sqlJobs, $con);
	while ($rowJobs = mysql_fetch_assoc($resultadoJobs)) {
		$idJob = $rowJobs['term_taxonomy_id'];
		$sqlnoCategory ="SELECT `parent` FROM `0rb3_term_taxonomy` WHERE `term_taxonomy_id` = ".$idJob;
		$resultadonoCategory = mysql_query($sqlnoCategory, $con);
		
		$noCategory = mysql_result($resultadonoCategory, 0);
		if($noCategory != 0){
			$sqlJob ="SELECT * FROM `0rb3_terms` WHERE `term_id` = ".$idJob;
			$resultadoJob = mysql_query($sqlJob, $con);
			while ($rowJob = mysql_fetch_assoc($resultadoJob)) {
				$infoJob[] = array (
					"term_id"=>utf8_encode($rowJob['term_id']),
					"name"=>utf8_encode($rowJob['name']),
					"slug"=>utf8_encode($rowJob['slug'])
				);
			}
		}
		
	}
	echo json_encode($infoJob);
}else{
	$msg = "No se ha recogido el cliente correctamente";
	echo json_encode($msg);
}
mysql_close($con);
?>