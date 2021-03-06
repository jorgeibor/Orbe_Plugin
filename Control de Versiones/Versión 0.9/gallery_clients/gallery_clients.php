<?php
/** 
	Plugin Name: 	Gallery Clients
	Description: 	Plugin para gestionar y mostrar la lista de clientes de Orbe
	Version:		0.4
	Author:			Visualcom Solutions
	Author URL:		http://www.visualcom.es/
**/

define( 'GC_VERSION', '0.4' );

define( 'GC_REQUIRED_WP_VERSION', '0.4' );

define( 'GC_PLUGIN', __FILE__ );

define( 'GC_PLUGIN_BASENAME', plugin_basename( GC_PLUGIN ) );

define( 'GC_PLUGIN_NAME', trim( dirname( GC_PLUGIN_BASENAME ), '/' ) );

define( 'GC_PLUGIN_DIR', untrailingslashit( dirname( GC_PLUGIN ) ) );

define( 'GC_PLUGIN_MODULES_DIR', GC_PLUGIN_DIR . '/modules' );

require_once GC_PLUGIN_DIR .'/../../../wp-config.php';

if($table_prefix != null){
	define('table_prefix', $table_prefix);
}else{
	define('table_prefix', 'wp_');
}
// /var/www/vhosts/visualcom.es/orbe.visualcom.es/wp-content/plugins/gallery_clients/admin/admin_menu.php

register_activation_hook( __FILE__, 'plugingallery_install');
require_once GC_PLUGIN_DIR .'/includes/gallery-clients-activator.php';
function plugingallery_install(){
	create_tables_db();
}

require_once GC_PLUGIN_DIR .'/includes/gallery-clients-deactivator.php';
register_deactivation_hook( __FILE__, 'plugingallery_uninstall');
function plugingallery_uninstall(){
	delete_tables_db();
}

function my_init() {
	if (!is_admin()) {
		wp_deregister_script('jquery'); 
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js', false, '2.2.0', true); 
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'my_init');


?>
