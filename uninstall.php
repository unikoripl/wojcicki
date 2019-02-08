<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ))
    exit();
//usuwam tabelę
global $wpdb;
$table_name = $wpdb->prefix . 'zwpc_posts';
$query ='DROP TABLE '.$table_name;
$wpdb->query($query);