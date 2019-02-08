<?php

/*
Plugin Name: Moja autorska wtyczka
Description: Wtyczka, którą stworzyłem sam
Version 1.0
Author: Jan Wójcicki
*/

class autorska_czat{

private $wpdb;
private $table_name;
    
function autorska_czat(){
    global $wpdb;
    $this->wpdb = $wpdb;
    $this->table_name = $wpdb->prefix . 'autorska_posts';
    add_action('admin_menu', array( &$this, 'autorska_add_menu'));
}
function autorska_add_menu(){
    add_menu_page('Strona główna','autorska wtyczka','administrator','autorska', array( &$this, 'autorska_glowna'),'dashicons-visibility',12);
}

function autorska_glowna(){

if(isset($_POST['autorska_action'])) {
    if($_POST['autorska_action'] == 'add') {
        if($this->add_post($_POST['post_content'])) {
            $notice = '<div class="notice notice-success">Dodano posta o treści: ' . $_POST['post_content'] . '</div>';
        } else {
            $notice = '<div class="notice notice-error">Nie dodano posta o treści: ' . $_POST['post_content'] . '</div>';
        }
    }
}
?>

<div class="notice notice-success"><p>Dziękuję za zainstalowanie mojej wtyczki! Poniżej znajdziesz dostępne opcje.</p></div>
<div class="tekstowy">
    <h1>Wybierz co chcesz zrobić a następnie potwierdź przyciskiem ,,Start"</h1>
</div>
<form method="POST">
<h2>Formularz</h2>
<p>Wybierz opcje:</p>
<table class="formularz">
    <tbody>
    <tr>
        <th scope="row">
            <label for="pierwsze_pole">Opcja 1</label>
        </th>
        <td>
            <input type="text" id="pierwsze_pole" name="pierwsze_pole" value=""
                   placeholder="Napisz coś"/><br>
            <span class="description">Wpisz tutaj swój tekst</span>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="drugie_pole">Opcja 2</label>
        </th>
        <td>
            <select id="drugie_pole" name="drugie_pole">
                <option value="opcja1">jeden</option>
                <option value="opcja2">dwa</option>
                <option value="opcja3">trzy</option>
            </select><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" class="button-primary" value="Start"/>
        </td>
    </tr>
    </tbody>
</table>
</form>


<div class="pojemnik_czat">
            <h2><span class="dashicons dashicons-admin-comments"></span>Autorski Czat</h2>
            <?= isset($notice) ? $notice : '';?>
            <form method="POST">
                <input type="hidden" name="autorska_action" value="add" />
                <label for="post_content">Napisz treść swojego posta</label><br>
                <input type="text" name="post_content" value="" placeholder="Napisz coś..."/>
                <input type="submit" value="Dodaj post" class="button-primary"/>
            </form>
        </div>
    
    <?php
}
}
$autorska_czat = new autorska_czat();

register_activation_hook(__FILE__, 'autorska_activation');

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