<?php
require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
// Clientes

function listadoClientes() {
	?>
		<style>
			<?php include_once GC_PLUGIN_DIR .'/css/clients.css';?>
		</style>
	<?php
	$url = "http://orbe.visualcom.es/clientes/";
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
	mysql_query("SET NAMES 'utf8'");
	header('Content-Type: text/html; charset=utf-8');
	mysql_select_db(DB_NAME, $con);
	
	//Recoger posibles parametros pasados por GET.
	$filtro = $_GET["filtro"];
	//echo $filtro;
	
	if($filtro != null || $filtro != ""){
		$listSubCategory = array();
		$numResult = 0;
		
		$sqlJobExist = "SELECT `term_id` FROM `".table_prefix."terms` WHERE `name`='".$filtro."'";
		//echo $sqlJobExist;
		$resultJobExist = mysql_query($sqlJobExist, $con);
		$isExist = mysql_result($resultJobExist, 0);
		//echo $isExist;
		if($isExist == NULL || $isExist == ""){
			$msg = "Esa familia/tipo de trabajo no existe en nuestra base de datos.";
		}else{
			//var_dump(mysql_fetch_assoc($resultJobExist));
			$resultJobExist = mysql_query($sqlJobExist, $con);
			while($rowJobExist = mysql_fetch_assoc($resultJobExist)){
				//var_dump($rowJobExist);
				$idJob = $rowJobExist['term_id'];
				$sqlSoloJob = "SELECT `term_taxonomy_id` FROM `".table_prefix."term_taxonomy` WHERE `taxonomy`='Tipo de trabajo'";
				//echo $sqlSoloJob."<br/>";
				$resultSoloJob = mysql_query($sqlSoloJob, $con);
				while ($rowSoloJob = mysql_fetch_assoc($resultSoloJob)) {
					//echo $rowSoloJob['term_taxonomy_id'];
					if($rowSoloJob['term_taxonomy_id'] == $idJob){
						$sqlIsCategory = "SELECT `parent` FROM `".table_prefix."term_taxonomy` WHERE `term_id`=".$idJob;
						$resultIsCategory = mysql_query($sqlIsCategory, $con);
						$isCategory = mysql_result($resultIsCategory, 0);
						//echo $idJob;
						if($isCategory == 0){
							$sqlIdSubCategory = "SELECT `term_id` FROM `".table_prefix."term_taxonomy` WHERE `parent`='".$idJob."' AND `taxonomy` = 'Tipo de trabajo'";
							$resultIdSubCategory = mysql_query($sqlIdSubCategory, $con);
							while ($rowIdSubCategory = mysql_fetch_assoc($resultIdSubCategory)) {
								array_push($listSubCategory, $rowIdSubCategory['term_id']);
							}
							//var_dump($listSubCategory);
							//echo $sqlIdSubCategory."</br>";
							foreach($listSubCategory as $idSub){
								$countSub = $countSub + 1;
								if($countSub == 1){
									$sqlPostSubCategory ="SELECT `".table_prefix."term_relationships`.`object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id`=".$idSub;
								}else{
									$sqlPostSubCategory .=" UNION SELECT `".table_prefix."term_relationships`.`object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id`=".$idSub;
								}
							}
							unset($idSub);
							//echo $sqlPostSubCategory;
							$resultIdSubCategory = mysql_query($sqlPostSubCategory, $con);
							$numResult = mysql_num_rows($resultIdSubCategory);
							if($numResult <= 0){
								$msg = "No se ha realizado ningún tipo de trabajo de esa familia en los clientes de nuestra base de datos.";
							}
							//echo $sqlPostSubCategory."</br>";
						}else{
							$sqlPostSubCategory ="SELECT `".table_prefix."term_relationships`.`object_id` FROM `".table_prefix."term_relationships` WHERE `term_taxonomy_id`=".$idJob;
							//echo $sqlPostSubCategory;
							$resultIdSubCategory = mysql_query($sqlPostSubCategory, $con);
							$numResult = mysql_num_rows($resultIdSubCategory);
							if($numResult <= 0){
								$msg = "No se ha realizado ese tipo de trabajo con ningun cliente de nuestra base de datos.";
							}
						}
					}
				}
			}


			while ($rowPostSubCategory = mysql_fetch_assoc($resultIdSubCategory)) {
				$idCliente = $rowPostSubCategory['object_id'];
				$sqlListClient ="SELECT `Posts`.`ID`, `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente' AND `ID`=".$idCliente.")Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID`";
				$resultadoListClient = mysql_query($sqlListClient, $con);
				$rowListClient = mysql_fetch_assoc($resultadoListClient);
				$sqlCategoryClient = "SELECT * FROM `".table_prefix."term_relationships` WHERE `object_id`='".$rowListClient["ID"]."'";
				$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);

				$category_client;
				while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
					$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
				}
				//var_dump($rowPostSubCategory);
				//echo "</br>";
				if($rowListClient["guid"] != NULL){
					$clientesFiltro[] = array (
						"guid"=>$rowListClient["guid"],
						"post_parent"=>"clients.".$rowListClient["post_parent"],
						"post_title"=>$rowListClient["post_title"],
						"post_content"=>$rowListClient["post_content"],
						"term_taxonomy_id"=>$category_client
					);
				}
				unset($category_client);

			}
		}
		
	}else{
		//Lista todos los clientes existentes en la BD.
		$sqlInfoClient = "SELECT `Posts`.`ID`, `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente')Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID`";
		$resultadoInfoClient = mysql_query($sqlInfoClient, $con);

		/*PAGINACION*/
		$num_total_registros = mysql_num_rows($resultadoInfoClient);
		$CLIENTES_PAGINA = 12;

		$pagina = $_GET["pagina"];
		if (!$pagina) {
			$inicio = 0;
			$pagina = 1;
		}
		else {
			$inicio = ($pagina - 1) * $CLIENTES_PAGINA;
		}
		$total_paginas = ceil($num_total_registros / $CLIENTES_PAGINA);

			$sqlInfoClient = "SELECT `Posts`.`ID`, `".table_prefix."posts`.`guid`, `".table_prefix."posts`.`post_parent`, Posts.`post_title`, Posts.`post_content` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente')Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID` ORDER BY ID ASC LIMIT $inicio, $CLIENTES_PAGINA";
		/************/
			$resultadoInfoClient = mysql_query($sqlInfoClient, $con);

			while ($rowInfoClient = mysql_fetch_assoc($resultadoInfoClient)) {
				//Esta consulta sirve para guardar los tipos de trabajos asignados en cada cliente.
				$sqlCategoryClient = "SELECT * FROM `".table_prefix."term_relationships` WHERE `object_id`='".$rowInfoClient["post_parent"]."'";
				$resultadoCategoryClient = mysql_query($sqlCategoryClient, $con);

				$category_client;
				while ($rowCategoryClient = mysql_fetch_assoc($resultadoCategoryClient)) {
					$category_client .= " ".$rowCategoryClient["term_taxonomy_id"];
				}
				$clientesFiltro[] = array (
					"guid"=>$rowInfoClient["guid"],
					"post_parent"=>"clients.".$rowInfoClient["post_parent"],
					"post_title"=>$rowInfoClient["post_title"],
					"post_content"=>$rowInfoClient["post_content"],
					"term_taxonomy_id"=>$category_client
				);
				unset($category_client);
			}
	}
		/*MONTAR ESTRUCTURA HTML*/
		$output = '';
			$output .= '<div id="divShortcode1" class="divShortcode1" >';
				$output .= '<div id="filterbox" class="filterbox">';
					$output .= '<div id="filterTittle" class="filterboxTittle">';
						$output .='<span id="tittleFilterBox" class="tittleFilterBox">Filtrar por</span>';
						$output .='<span id="iconFilterBox" class="iconFilterBox fa fa-filter"></span>';
					$output .= '</div>';
					$output .= '<div id="filterForm" class="filterboxForm">';
						$output .='<div class="filterSubDiv">';
							$output .= '<span id="formNameFilterBox">';
								$output .= 'Nombre: ';
								$output .= '<input id="nameClient" name="nameClient" type="text">';
							$output .= '</span>';
							$output .= '<span id="formOrderFilterBox">';
								$output .= 'Ordenar:<br>';
								$output .= '<p style="margin-left: 20px; float: left;">Asc<input style="margin-left: 10px;" name="orderClientby" id="orderClientby" class="orderClientby" value="ASC" type="radio" checked="checked"></p>';
								$output .= '<p style="float: left; margin-left: 20px;">Desc<input style="margin-left: 10px;" name="orderClientby" id="orderClientby" class="orderClientby" value="DESC" type="radio"></p>';
							$output .= '</span>';
						$output .= '</div>';	
						$output .='<div class="filterSubDiv filterSubDivJob">';
							$output .='<span id="formFamilyFilterBox" class="formFamilyFilterBox">';
								$output .='Categoria: <select id="selectCategoria"></select>';
							$output .='</span>';
							$output .='<span id="formCategoryFilterBox" class="formCategoryFilterBox">';
								$output .='Tipo de Trabajo: <select id="selectTipoTrabajo"></select>';
							$output .='</span>';
						$output .= '</div>';		
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div id="divClient" class="divClient" style="min-height: 500px;"> ';
					if($filtro != null || $filtro != ""){
						$output .= '<p><h2>'.$filtro.'</h2></p>';
					}else{
						$output .= '<p><h2>Todos</h2></p> ';
					}
					$output .= '<p><h3>'.$msg.'</h3></p>';
					unset($msg);
					$output .= '<div id="over" class="overbox">';
						$output .= '<div id="over" class="overboxHeader">';
							$output .='<span id="contentOverBox" class="contentOverBox"></span>';
							$output .='<span id="btnCloseOverBox" class="btnCloseOverBox fa fa-times-circle-o"></span>';
						$output .= '</div>';
						$output .= '<div id="over" class="overboxContent">';
							$output .= '<div id="overImg" class="overboxImg">';
								$output .= '<img id="imageOverFlox" alt="" class="imageOverFlox" src=""/>';
								//$output .= '<span style="font-size: 20px; float: right; cursor: pointer;" class="fa fa-expand"></span>';
							$output .= '</div>';
							$output .= '<div id="overDesc" class="overboxDesc">';
								$output .= '<h2>Descripción</h2>';
								$output .= '<div id="descClient" class="descClient">';
									$output .= '<span id="descSpan" class="descSpan"></span>';
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div id="overJobs" class="overboxJobs">';
								$output .= '<h2>Tipos de trabajos</h2>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="fade" class="fadebox">&nbsp;</div>';
						$countC = 0;
						foreach ($clientesFiltro as $clientes) {
							$countC= $countC + 1;
							$output .= '<div id="'.$countC.'" class="divClientsImage">';
								$output .= '<img id="'.$clientes['post_parent'].'" alt="'.$clientes['term_taxonomy_id'].'" class="clientsImage BandW" src="'.$clientes['guid'].'"/>';
								$output .= '<div class="divClientsName">';
									$output .= '<span id="nameClient">'.$clientes['post_title'].'</span>';
								$output .= '</div>';
								$output .= '<span id="descClient" style="display:none;">'.$clientes['post_content'].'</span>';
							$output .= '</div>';
						}
					/*VER PAGINACION*/	
					$output .= '<div id="paginacionDiv" class="paginacionDiv"> ';
							if ($total_paginas > 1) {
								$output .= '<ul id="paginacionUl" class="paginacionUl">';
								if ($pagina != 1){
									$output .= '<li><a class="paginacionIzq fa fa-caret-left " href="'.$url.'?pagina='.($pagina-1).'"></a></li>';
								}
								for ($i=1;$i<=$total_paginas;$i++) {
									if ($pagina == $i){
										$output .= '<li class="active" ><a>'.$i.'</a></li>';
									}else{
										$output .= '<li><a href="'.$url.'?pagina='.$i.'">'.$i.'</a></li>';
									}
								}
								if ($pagina != $total_paginas){
									$output .= '<li><a class="paginacionDer fa fa-caret-right " href="'.$url.'?pagina='.($pagina+1).'"></a></li>';
								}
								$output .= '</ul>';
							}
					$output .= '</div>';
					/****************/
				$output .= '</div>';
			$output .= '</div>';
		?>
		<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
		<?php 
		
		//Recogemos todos los clientes existentes en la BD en un array
		
		$sqlArrayClients = "SELECT DISTINCT Posts.`post_title` FROM `".table_prefix."posts` INNER JOIN (SELECT `ID`, `post_title`, `post_content` FROM `".table_prefix."posts` WHERE `post_type`='cliente')Posts ON `".table_prefix."posts`.`post_parent` = `Posts`.`ID`";
		$resultadoArrayClients = mysql_query($sqlArrayClients, $con);
		$arrayClients = array();
		while ($rowArrayClients = mysql_fetch_assoc($resultadoArrayClients)) {
			array_push($arrayClients, $rowArrayClients['post_title']);
		}
		
		?>
		<script>
			var autocompletar = new Array();
			<?php 
			for($p = 0;$p < count($arrayClients); $p++){ ?>
				autocompletar.push('<?php echo $arrayClients[$p]; ?>');
			<?php 
			} 
			?>
			<?php include_once GC_PLUGIN_DIR .'/js/clients.js';?>
		</script>		
		<?php
		return $output;
	mysql_close($con);
}
add_shortcode('listaClientes','listadoClientes');
?>
