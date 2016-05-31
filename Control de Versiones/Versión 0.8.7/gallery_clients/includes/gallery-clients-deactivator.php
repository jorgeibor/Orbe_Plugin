<?php
function delete_tables_db(){
	global $wpdb;

	$table_name = $wpdb->prefix . "config_family_colors";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);
	
	$table_name = $wpdb->prefix . "config_plugin";
	$sql = "DROP TABLE $table_name";
	$wpdb->query($sql);

}

?>
