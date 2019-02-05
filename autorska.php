<?php

/*
Plugin Name: Moja autorska wtyczka
Description: Wtyczka, którą stworzyłem sam
Version 1.0
Author: Jan Wójcicki
*/

function autorska_add_menu_(){
    add_menu_page('Strona główna','autorska','administrator','autorska','autorska_glowna','','24');
}
add_action('admin_menu','autorska_addmenu');

function autorska_glowna(){
    echo "Napis"
}