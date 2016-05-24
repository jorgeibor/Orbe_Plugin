<?php
/* Este fichero se ejecuta cuando clients.js pide información. */

include_once "../../../../wp-config.php";

$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
header('Content-Type: text/html; charset=utf-8');
mysql_select_db(DB_NAME, $con);
mysql_query("SET NAMES 'utf8'");
//Recoge el id de la categoria/tipo de trabajo seleccionado
$idCategory = mysql_real_escape_string($_POST["idCategory"]);
$nameClient = mysql_real_escape_string($_POST["nameClient"]);
$orderBy = mysql_real_escape_string($_POST["orderBy"]);

if($idCategory != null || $idCategory != ""){
	//Si idCategory es Todos reenviará Listar Todos a clients.js
	function listarTodos(){
		echo "Listar Todos";
	}
	
	function listarByName($name, $order){
		global $con;
		// ORDER BY `".table_prefix."posts`.`post_parent` ".$order
		$sqlInfoClient = "SELECT `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, `Posts`.`post_title`, `Posts`.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente' AND `post_title`='".$name."')Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID` ";
		$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
		while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
			$sqlCategoryClient = "SELECT * FROM `".table_prefix."term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
			$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
			$category_client;
			while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
				$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
			}			
			$datosCliente[] = array (
				"guid"=>$rowInfoClient["guid"],
				"post_parent"=>"clients.".$rowInfoClient["post_parent"],
				"post_title"=>$rowInfoClient["post_title"],
				"post_content"=>$rowInfoClient["post_content"],
				"term_taxonomy_id"=>$category_client
			);
		}
		foreach ($datosCliente as $clave => $fila) {
			$postTitle[$clave] = $fila['post_title'];
		}
		if($order == "asc"){
			array_multisort($postTitle, SORT_ASC, $datosCliente);
		}else if($order == "desc"){
			array_multisort($postTitle, SORT_DESC, $datosCliente);
		}
		echo json_encode($datosCliente);
	}
	
	function listarByJob($id, $orderby){
		/* Listar clientes por el tipo de trabajo seleccionado */
		if(is_numeric($id)) {
			$countInfo = 0;
			$datosTodos = array();
			$msg = array();
			global $con;
			$sqlIdClient ="SELECT `object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id` = $id";
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
					$sqlInfoClient = "SELECT `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, `Posts`.`post_title`, `Posts`.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente' AND `ID`=$idClient)Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID`";
					$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
					while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
						$countInfo++; //Suma de número de registros.
						$sqlCategoryClient = "SELECT * FROM `".table_prefix."term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
						$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
						$category_client;
						while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
							$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
						}
						$datosDefault[] = array (
							"n_registro"=>$countInfo,
							"guid"=>$rowInfoClient["guid"],
							"post_parent"=>"clients.".$rowInfoClient["post_parent"],
							"post_title"=>$rowInfoClient["post_title"],
							"post_content"=>$rowInfoClient["post_content"],
							"term_taxonomy_id"=>$category_client
						);
						unset($category_client);
					}
				}
				//Ordenamos el contenido del array por el campo de post_title que corresponde al nombre del cliente
				foreach ($datosDefault as $key => $row) {
					$aux[$key] = $row['post_title'];
				}
				//Lista de nombres a ordenar, El tipo de orden ASC o DESC y el array de los clientes.
				array_multisort($aux, $orderby, $datosDefault);
				if($orderby == "asc"){
					array_multisort($aux, SORT_ASC, $datosDefault);
				}else if($orderby == "desc"){
					array_multisort($aux, SORT_DESC, $datosDefault);
				}
				echo json_encode($datosDefault);
			}
		/* Listar clientes por la familia seleccionada */
    	} else {
        	$countInfo = 0;
			$msg = array();
			$idSubCategory = array();
			$idSubCategoryClients = array();
			$category_client;
			global $con;
			
			
			$sqlIdCategory = "SELECT `term_id` FROM `".table_prefix."terms` WHERE `name`='".$id."'";
			$resultIdCategory = mysql_query($sqlIdCategory, $con);
			while ($rowIdCategory = mysql_fetch_assoc($resultIdCategory)) {
				$idCategory = $rowIdCategory['term_id'];
				$sqlIdSubCategory = "SELECT `term_id` FROM `".table_prefix."term_taxonomy` WHERE `parent`='".$idCategory."' AND `taxonomy` = 'Tipo de trabajo'";
				$resultIdSubCategory = mysql_query($sqlIdSubCategory, $con);
				while ($rowIdSubCategory = mysql_fetch_assoc($resultIdSubCategory)) {
					array_push($idSubCategory, $rowIdSubCategory['term_id']);
				}
			}
			foreach($idSubCategory as $valor){
				$countSub = $countSub + 1;
				if($countSub == 1){
					$sqlPostSubCategory ="SELECT `".table_prefix."term_relationships`.`object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id`=".$valor;
				}else{
					$sqlPostSubCategory .=" UNION SELECT `".table_prefix."term_relationships`.`object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id`=".$valor;
				}
			}
			unset($valor);
			$resultadoPostSubCategory = mysql_query($sqlPostSubCategory, $con);

			while ($rowPostSubCategory = mysql_fetch_assoc($resultadoPostSubCategory)) {
				$idClient = $rowPostSubCategory["object_id"];
				if(!in_array($idClient, $idSubCategoryClients)){
					array_push($idSubCategoryClients, $idClient);
					$sqlInfoClient = "SELECT `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, `Posts`.`post_title`, `Posts`.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente' AND `ID`=$idClient)Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID`";
					$resultadoInfoClient = mysql_query($sqlInfoClient, $con);
					while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
						$countInfo++; //Suma de número de registros.
						$sqlCategoryClient = "SELECT * FROM `".table_prefix."term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
						$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);
						while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
							$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
						}
						$datos[] = array (
							"n_registro"=>$countInfo,
							"guid"=>$rowInfoClient["guid"],
							"post_parent"=>"clients.".$rowInfoClient["post_parent"],
							"post_title"=>$rowInfoClient["post_title"],
							"post_content"=>$rowInfoClient["post_content"],
							"term_taxonomy_id"=>$category_client
						);
						unset($category_client);
					}
				}
							
			}
			//Ordenamos el contenido del array por el campo de post_title que corresponde al nombre del cliente
			foreach ($datos as $clave => $fila) {
				$postTitle[$clave] = $fila['post_title'];
			}
			if($orderby == "asc"){
				array_multisort($postTitle, SORT_ASC, $datos);
			}else if($orderby == "desc"){
				array_multisort($postTitle, SORT_DESC, $datos);
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
		
	}
	
	//Pregunta si el idCategory coincide con alguna de estas opciones.
	switch($idCategory){
		case "Todos":
			listarTodos();
			break;
		case "Nombre":	
			listarByName($nameClient, $orderBy);
			break;
		default:
			listarByJob($idCategory, $orderBy);
			break;
	}
}else{
	$msg = "No se ha recogido la categoria correctamente";
	echo json_encode($msg);
}
mysql_close($con);
?>