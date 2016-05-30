<?php
require_once GC_PLUGIN_DIR .'/../../../wp-config.php';
// Clientes
add_action( 'init', 'my_custom_init' );
/*CONFIGURACION BACKEND WORDPRESS*/
function my_custom_init() {
	$labels = array(
	'name' => _x( 'Clientes', 'post type general name' ),
        'singular_name' => _x( 'Cliente', 'post type singular name' ),
        'add_new' => _x( 'Añadir nuevo cliente', 'cliente' ),
        'add_new_item' => __( 'Añadir nuevo cliente' ),
        'edit_item' => __( 'Editar cliente' ),
        'new_item' => __( 'Nuevo cliente' ),
        'view_item' => __( 'Ver cliente' ),
        'search_items' => __( 'Buscar cliente' ),
        'not_found' =>  __( 'No se han encontrado clientes' ),
        'not_found_in_trash' => __( 'No se han encontrado clientes en la papelera' ),
        'parent_item_colon' => ''
    );

    $args = array( 'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
		'show_in_menu' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title', 'editor', 'thumbnail' ), 
		'menu_icon' => 'dashicons-groups',
		'rewrite' => true,
		'exclude_from_search'=>true
    );
 
    register_post_type( 'cliente', $args );
}

add_action( 'init', 'create_cliente_taxonomies', 0 );
/*CONFIGURACION BACKEND WORDPRESS*/
function create_cliente_taxonomies() {
	$labels = array(
	'name' => _x( 'Tipos de trabajo', 'taxonomy general name' ),
	'singular_name' => _x( 'Tipo de trabajo', 'taxonomy singular name' ),
	'search_items' =>  __( 'Buscar por tipo de trabajo' ),
	'all_items' => __( 'Todos los tipos de trabajo' ),
	'parent_item' => __( 'Tipo de trabajo padre' ),
	'parent_item_colon' => __( 'Tipo de trabajo padre:' ),
	'edit_item' => __( 'Editar tipo de trabajo' ),
	'update_item' => __( 'Actualizar tipo de trabajo' ),
	'add_new_item' => __( 'Añadir nuevo tipo de trabajo' ),
	'new_item_name' => __( 'Nombre del nuevo tipo de trabajo' ),
    'not_found' =>  __( 'No se han encontrado tipos de trabajo' )
);
	register_taxonomy( 'Tipo de trabajo', array( 'cliente' ), 
	array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'Tipo-de-trabajo' )
	));
}
/*********************************/

global $wpdb;

$gal_clie_conf = $wpdb->prefix . 'config_plugin';

if($wpdb->get_var("show tables like '".$gal_clie_conf."'") != $gal_clie_conf) {
	$sql = "CREATE TABLE {$gal_clie_conf} (
		conf_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		family BIGINT(20) UNSIGNED NOT NULL,
		clients_per_page BIGINT(5) NOT NULL,
		PRIMARY KEY (conf_id)
	) ENGINE=InnoDB; {$charset_collate}";
	$wpdb->query($sql);
}
echo $sql;

$gal_clie_conf_fam_col = $wpdb->prefix . 'config_family_colors';

if($wpdb->get_var("show tables like '".$gal_clie_conf_fam_col."'") != $gal_clie_conf_fam_col) {
	$sql = "CREATE TABLE {$gal_clie_conf_fam_col} (
		family_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		family_name VARCHAR(200) NOT NULL DEFAULT '',
		family_color VARCHAR(50) NOT NULL DEFAULT '',
		PRIMARY KEY (family_id),
		KEY family_name (family_name)
	) ENGINE=InnoDB; {$charset_collate}";
	$wpdb->query($sql);
}


require_once GC_PLUGIN_DIR .'/admin/clients.php';
require_once GC_PLUGIN_DIR .'/admin/category.php';
require_once GC_PLUGIN_DIR .'/admin/config.php';
?>
