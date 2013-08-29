<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $referer = explode('?', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
    if($referer[0] == 'http://v2.jogosabertos.dev/admin/login.php' || $referer[0] == 'http://v2.jogosabertos.dev/admin/login.php'){
        if($email = isset($_POST['email']) && !empty($_POST['email']) ? addslashes(strip_tags($_POST['email'])) : FALSE){
            
        } else {
            echo '{"status":"erro","info":"e-mail"}';
        }
    } else {
        echo '{"status":"erro","info":"referer"}';
    }
    
?>