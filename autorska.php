<?php

/*
Plugin Name: Moja autorska wtyczka Wordpress - Chat
Description: Wtyczka pozwalająca dodawać treści na stronę w postaci chatu.
Version 1.0
Author: Jan Wójcicki
*/

class autorska_czat{
//dwie zmienne przechowujące obiekt i nazwę tabeli
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
    //dodanie pola autorska_action
    if(isset($_POST['autorska_action'])) {
        if($_POST['autorska_action'] == 'add') {
            if($this->add_post($_POST['post_content'])) {
                $notice = '<div class="notice notice-success">Dodano posta o treści: ' . $_POST['post_content'] . '</div>';
            } else {
                $notice = '<div class="notice notice-error">Nie dodano posta o treści: ' . $_POST['post_content'] . '</div>';
            }
            //edycja postów
        } else if($_POST['autorska_action'] == 'edit') {
            if($this->edit_post($_POST['autorska_post_id'],$_POST['post_content'])) {
                $notice = '<div class="notice notice-success">Edytowano wiadomość o treści: ' . $_POST['post_content'] . '</div>';
            } else {
                $notice = '<div class="notice notice-error">Nie udało się zaktualizować wiadomości o treści: ' . $_POST['post_content'] . '</div>';
            }
        }
    }
    if(isset($_POST['autorska_delete'])) {
        //usuwanie postów
        if($this->delete_post($_POST['autorska_post_id'])) {
            $notice = '<div class="notice notice-success">Usunięto wiadomość id: ' . $_POST['autorska_post_id'] . '</div>';
        } else {
            $notice = '<div class="notice notice-error">Nie usunięto wiadomość o id: ' . $_POST['autorska_post_id'] . '</div>';
        }
    }
    //pobieram wiadomość do edycji
$edit = FALSE;
if(isset($_POST['autorska_to_edit'])) {
    $edit = $this->get_autorska_post($_POST['autorska_post_id']);
}
    //utworzenie formularza
    ?>

    <div class="notice notice-success"><p>Dziękuję za zainstalowanie mojej wtyczki! Poniżej znajdziesz dostępne opcje.</p></div>
    <div class="tekstowy">
        <h1>Wybierz co chcesz zrobić a następnie potwierdź przyciskiem ,,Start"</h1>
    </div>
    <h2>Formularz</h2>
    <p>Wybierz opcje:</p>
    <div class="pojemnik_czat">
                <h2><span class="dashicons dashicons-admin-comments"></span>Autorski Czat</h2>
                <?= isset($notice) ? $notice : '';?>
    <form method="POST">
        <?= $edit ? '<input type="hidden" name="autorska_post_id" value="' . $edit->id . '" />' : ''; ?>
        <input type="hidden" name="autorska_action" value="<?= $edit ? 'edit' : 'add'; ?>"/>
        <label for="post_content">Treść posta</label><br>
        <input type="text" name="post_content" value="<?= $edit ? $edit->post_content : ''; ?>"
               placeholder="Treść posta"/>
        <input type="submit" value="<?= $edit ? 'Edytuj' : 'Dodaj'; ?> post" class="button-primary"/>
    </form>    
    <?php
    $all_posts = $this->get_autorska_posts();
    if ($all_posts) {
        echo '<table class="widefat">';
        echo '<thead>
                            <tr>
                                <th>ID</th>
                                <th>Użytkownik</th>
                                <th>Wiadomość</th>
                                <th>Data</th>
                                <td>Opcje</td>
                            </tr>
                        </thead>';
        echo '<tbody>';
        foreach ($all_posts as $p) {
            echo '<tr>';
            echo '<td>' . $p->id . '</td>';
            echo '<td>' . $p->user_id . '</td>';
            echo '<td>' . $p->post_content . '</td>';
            echo '<td>' . $p->create_date . '</td>';
            echo '<td><form method="POST">
                                <input type="hidden" name="autorska_post_id" value="' . $p->id . '" />
                                <input type="submit" name="autorska_to_edit" value="Edytuj" class="button-primary" />
                                <input type="submit" name="autorska_delete" value="Usuń" class="button-primary error" />
                            </form></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    ?>
    </div>
<?php
}
function add_post($post_content) {
    //sprawdzenie, czy nie pusty i czy uzytkownik jest zalogowany
    if(trim($post_content) != '' && is_user_logged_in()){
        $user_id = get_current_user_id();
        $post_content = esc_sql($post_content);
        $this->wpdb->insert( $this->table_name, array('user_id' => $user_id, 'post_content' => $post_content) );
        return TRUE;
    }
    return FALSE;
}
//funkcja zwracająca tablicę wiadomości ograniczona do 50 rekordów
function get_autorska_posts() {
    return $this->wpdb->get_results("SELECT * FROM $this->table_name ORDER BY create_date DESC LIMIT 0,50");
}
//funkcja pobiera wiadomość i zwraca obiekt
function get_autorska_post($id) {
    $id = esc_sql($id);
    $autorska_post = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE id = '" . $id . "'");
    if(isset($autorska_post[0])){
        return $autorska_post[0];
    } else {
        return FALSE;
    }
}
//edycja wiadomości zczytuje id i treść wiadomości
function edit_post($id, $content){
    if(trim($content) != '' && is_user_logged_in()) {
        $id = esc_sql($id);
        $content = esc_sql($content);
        $res = $this->wpdb->update($this->table_name, array('post_content' => $content), array('id' => $id));
        return $res;
    }else {
        return FALSE;
    }
}
//usuwanie wiadomości
function delete_post($id) {
    $id = esc_sql($id);
    if(is_user_logged_in()) {
        return $this->wpdb->delete($this->table_name, array('id' => $id));
    } else {
        return FALSE;
    }
}
}
$autorska_Czat = new autorska_Czat();

add_action( 'widgets_init', 'autorska_register_widget' );

function autorska_register_widget() {
    register_widget( 'autorska_Widget');
}

class autorska_Widget extends WP_Widget {
    function autorska_Widget() {
        // tablica opcji.
        $widget_ops = array(
            'classname' => 'autorska_Widget', //nazwa klasy widgetu
            'description' => 'autorska Czat', //opis widoczny w panelu
        );
        //ładowanie
        parent::__construct( 'autorska_Widget', 'autorska Czat', $widget_ops );
    }
    function form($instance) {
        ?>
        <p>
            Widget wyświetlający czat.
        </p>
        <?php
    }
    function update($new_instance, $old_instance) {
        return $old_instance;
    }

    function widget($args, $instance){
        global $autorska_Czat;
        echo $args['before_widget'];
        if (isset($_POST['post_content'])) {
            $autorska_Czat->add_post($_POST['post_content']);
        }
        if (is_user_logged_in()) {
            echo '<form method="POST">
                <input type="hidden" name="autorska_action" value="add"/>
                <label for="post_content">Treść</label><br>
                <input type="text" name="post_content" value="" placeholder="Treść posta"/>
                <input type="submit" value="Napisz" class="button-primary"/>
            </form>';
        }
        echo '<table>';
        $autorska_posts = $autorska_Czat->get_autorska_posts();
        if ($autorska_posts) {
            foreach ($autorska_posts as $autorska_p) {
                echo '<tr>';
                echo '<td>' . $autorska_p->create_date . ' <br> ' . $autorska_p->post_content . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
        echo $args['after_widget'];
    }

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


