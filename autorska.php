<?php

/*
Plugin Name: Moja autorska wtyczka Wordpress - Chat
Description: Wtyczka pozwalająca dodawać treści na stronę w postaci chatu.
Version 1.0
Author: Jan Wójcicki
*/

class autorska_czat{

function autorska_czat(){
    add_action('admin_menu', array( &$this, 'autorska_add_menu'));
}
function autorska_add_menu(){
    add_menu_page('Strona główna','autorska wtyczka','administrator','autorska', array( &$this, 'autorska_glowna'),'dashicons-visibility',12);
}

function autorska_glowna(){
}
// rejestracja kodu autorska_activation w którym utworzymy tabelę
register_activation_hook(__FILE__, 'autorska_activation');

//stworzenie tabeli bazy danych i aktywacja jej za pomocą query, które pobiera ciąg znaków jako tekst
function autorska_activation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'autorska_posts';

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") != $table_name) {
        $query = "CREATE TABLE " . $table_name . " (
        id int(9) NOT NULL AUTO_INCREMENT,
        user_id MEDIUMINT(6) NOT NULL,
        post_content TEXT NOT NULL,
        create_date TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
        )";

        $wpdb->query($query);
    }
}


