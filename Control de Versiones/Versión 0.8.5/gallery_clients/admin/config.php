<?php
/*add_submenu_page( 'cliente', 'Gallery Clients', 'Configuración', 
'manage_options', 'edit.php?post_type=cliente', NULL );
*/
add_action('admin_menu' , 'add_options_plugin'); 
function add_options_plugin(){
    add_submenu_page(
        'edit.php?post_type=cliente',
        'Configuración',
        'Configuración',
        'manage_options',
        'gallery_clients_config_plugin',
        'gallery_clients_config_plugin' 
    );
}

function gallery_clients_config_plugin(){
    require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("No se conecto: " . mysql_error());
	//mysql_query("SET NAMES 'utf8'");
	header('Content-Type: text/html; charset=utf-8');
	mysql_select_db(DB_NAME, $con);
	
	
	?>
	<style>
		<?php include_once GC_PLUGIN_DIR .'/css/gallery_clients_config.css';?>
	</style>
	<style>
		<?php include_once GC_PLUGIN_DIR .'/css/font-awesome.css';?>
	</style>
	<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
			<div id="gclients-header" class="clearfix">
                <div id="gclients-logo">Gallery Clientes</div>
                <h2>Configuración</h2>
            </div>
            <div class="container-fluid">
                <div id="gm-message"></div>
                <div class="panel panel-default">
                    <div class="panel-config">
                    <span>Familias: </span><select id="selectCategoria"></select><input type="color" style="border: none;padding: 0;" id="famcolor" name="famcolor" value="#FFF">
                    </div>
                </div>
    		</div>	
	<script>
		<?php
		include_once GC_PLUGIN_DIR .'/js/config.js';
		?> 
	</script>
	<?php
}

