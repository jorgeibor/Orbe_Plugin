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
    global $wpdb;
	

	
}

