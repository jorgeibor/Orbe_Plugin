<?php
/* Este fichero se ejecuta cuando clients.js pide información. */

include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
mysql_select_db(DB_NAME, $con);
//Recoge el id de la categoria/tipo de trabajo seleccionado
$idCategory = mysql_real_escape_string($_POST["idCategory"]);

if($idCategory != null || $idCategory != ""){
	//Si idCategory es Todos reenviará Listar Todos a clients.js
	function listarTodos(){
		echo "Listar Todos";
	}
	
	//Si idCategory es Telecom, Seguridad o Orgeby recogerá la info de la categoría seleccionada y lo enviará a clients.js
	function listarFamiliaCateg($familiaCateg){
		$countInfo = 0;
		$datosTodos = array();
		$msg = array();
		$idSubCategoryClients = array();
		$category_client;
		global $con;
		
		/*Categorias y SubCategorias*/
		$subTelecom = array(27,34,38);
		$subSeguridad = array(42);
		$subOrgeby = array(49);
		
		switch($familiaCateg){
			case "Telecom":
				$countSub = 0;
				foreach ($subTelecom as $valor) {
    				$countSub = $countSub + 1;
					if($countSub == 1){
						$sqlSubCategory ="SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}else{
						$sqlSubCategory .=" UNION SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}
				}
				unset($valor);
				$resultadoSubCategory = mysql_query($sqlSubCategory, $con);
				break;
			case "Seguridad":
				$countSub = 0;
				foreach ($subSeguridad as $valor) {
    				$countSub = $countSub + 1;
					if($countSub == 1){
						$sqlSubCategory ="SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}else{
						$sqlSubCategory .=" UNION SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}
				}
				unset($valor);
				$resultadoSubCategory = mysql_query($sqlSubCategory, $con);
				break;
			case "Orgeby":
				$countSub = 0;
				foreach ($subOrgeby as $valor) {
    				$countSub = $countSub + 1;
					if($countSub == 1){
						$sqlSubCategory ="SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}else{
						$sqlSubCategory .=" UNION SELECT `0rb3_term_taxonomy`.`term_id`, Terms.`name` FROM `0rb3_term_taxonomy` INNER JOIN (SELECT `0rb3_terms`.`term_id`,`0rb3_terms`.`name` FROM `0rb3_terms`)Terms ON Terms.`term_id` = `0rb3_term_taxonomy`.`term_id` WHERE `0rb3_term_taxonomy`.`parent`=".$valor;
					}
				}
				unset($valor);
				$resultadoSubCategory = mysql_query($sqlSubCategory, $con);
				break;
		}
				while ($rowSubCategory = mysql_fetch_assoc($resultadoSubCategory)) {
					$idSubCategory = $rowSubCategory["term_id"];
					$sqlIdClient ="SELECT `object_id` FROM `0rb3_term_relationships` WHERE `term_taxonomy_id` = $idSubCategory";
					$resultadoIdClient = mysql_query($sqlIdClient, $con);
					while ($rowIdClient = mysql_fetch_assoc($resultadoIdClient)) {
							$idClient = $rowIdClient["object_id"];
							if(!in_array($idClient, $idSubCategoryClients)){
								array_push($idSubCategoryClients, $idClient);
								$sqlInfoClient = "SELECT `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, `Posts`.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente' AND `ID`=$idClient)Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID`";
								$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
								while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
									$countInfo++; //Suma de número de registros.
									$sqlCategoryClient = "SELECT * FROM `0rb3_term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
									$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
									while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
										$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
									}
									$datos[] = array (
										"n_registro"=>utf8_encode($countInfo),
										"guid"=>utf8_encode($rowInfoClient["guid"]),
										"post_parent"=>utf8_encode("clients.".$rowInfoClient["post_parent"]),
										"post_content"=>utf8_encode($rowInfoClient["post_content"]),
										"term_taxonomy_id"=>utf8_encode($category_client)
									);
									unset($category_client);
								}
							}
							
					}
				}
				unset($idSubCategoryClients);
				if($countInfo == 0){
					$msg[] = array (
						"isNull"=> true,
						"message"=> "No se ha encontrado clientes en las subcategorías de esta familia."
					);
					echo json_encode($msg);
				}else{
					echo json_encode($datos);
				}
	}
	
	//Si idCategory no es Todos, Telecom, Seguridad o Orgeby recogerá la info del tipo de trabajo seleccionado y lo enviará a clients.js
	function listarDefault($id){
		$countInfo = 0;
		$datosTodos = array();
		$msg = array();
		global $con;
		$sqlIdClient ="SELECT `object_id` FROM `0rb3_term_relationships` WHERE `term_taxonomy_id` = $id";
		$resultadoIdClient = mysql_query($sqlIdClient, $con);
		if (mysql_num_rows($resultadoIdClient) == 0) {
			$msg[] = array (
				"isNull"=> true,
				"message"=> "No se ha encontrado clientes con esa categoría."
			);
			echo json_encode($msg);
		}else{
			while ($rowIdClient = mysql_fetch_assoc($resultadoIdClient)) {
				$idClient = $rowIdClient["object_id"];
				$sqlInfoClient = "SELECT `0rb3_posts`.`guid`, `0rb3_posts`.`post_parent`, `Posts`.`post_content` FROM `0rb3_posts` INNER JOIN (SELECT `ID`, `post_content` FROM `0rb3_posts` WHERE `post_type`='cliente' AND `ID`=$idClient)Posts ON `0rb3_posts`.`post_parent` = `Posts`.`ID`";
				$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
				while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
					$countInfo++; //Suma de número de registros.
					$sqlCategoryClient = "SELECT * FROM `0rb3_term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
					$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
					$category_client;
					while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
						$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
					}
					$datosDefault[] = array (
						"n_registro"=>utf8_encode($countInfo),
						"guid"=>utf8_encode($rowInfoClient["guid"]),
						"post_parent"=>utf8_encode("clients.".$rowInfoClient["post_parent"]),
						"post_content"=>utf8_encode($rowInfoClient["post_content"]),
						"term_taxonomy_id"=>utf8_encode($category_client)
					);
					unset($category_client);
				}
			}
			echo json_encode($datosDefault);
		}
	}
	
	//Pregunta si el idCategory coincide con alguna de estas opciones.
	switch($idCategory){
		case "Todos":
			listarTodos();
			break;
		case "Telecom":
			listarFamiliaCateg($idCategory);
			break;
		case "Seguridad":
			listarFamiliaCateg($idCategory);
			break;
		case "Orgeby":
			listarFamiliaCateg($idCategory);
			break;
		//Si no coincide con ninguno de los anteriores eso quiere decir que el botón que ha sido pulsado no es una Categoria si no un tipo de trabajo.
		default:
			listarDefault($idCategory);
			break;
	}
}else{
	$msg = "No se ha recogido la categoria correctamente";
	echo json_encode($msg);
}
mysql_close($con);
?>