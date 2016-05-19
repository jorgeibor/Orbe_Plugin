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

// /var/www/vhosts/visualcom.es/orbe.visualcom.es/wp-content/plugins/gallery_clients/admin/admin_menu.php
require_once GC_PLUGIN_DIR .'/admin/clients.php';
require_once GC_PLUGIN_DIR .'/admin/category.php';

function my_init() {
	if (!is_admin()) {
		wp_deregister_script('jquery'); 
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js', false, '2.2.0', true); 
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'my_init');


?>
