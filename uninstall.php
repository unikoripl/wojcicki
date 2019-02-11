<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ))
    exit();
//kod odpowiedzialny za usuwanie tabeli
global $wpdb;
$table_name = $wpdb->prefix . 'autorska_posts';
$query ='DROP TABLE '.$table_name;
$wpdb->query($query);