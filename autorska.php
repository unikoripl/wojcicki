<?php

/*
Plugin Name: Moja autorska wtyczka
Description: Wtyczka, którą stworzyłem sam
Version 1.0
Author: Jan Wójcicki
*/

function autorska_add_menu(){
    add_menu_page('Strona główna','autorska wtyczka','administrator','autorska','autorska_glowna','dashicons-visibility',12);
}
add_action('admin_menu','autorska_add_menu');

function autorska_glowna(){
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

    <?php
}

