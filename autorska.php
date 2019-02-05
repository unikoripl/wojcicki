<?php

/*
Plugin Name: Moja autorska wtyczka
Description: Wtyczka, którą stworzyłem sam
Version 1.0
Author: Jan Wójcicki
*/

function autorska_add_menu(){
    add_menu_page('Strona główna','autorska wtyczka','administrator','autorska','autorska_glowna','',12);
}
add_action('admin_menu','autorska_add_menu');

function autorska_glowna(){
    echo "Napis";
}