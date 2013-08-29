<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    if($referer === NULL){
        die();
    }
    if(strpos($referer, BASE_URL) !== 0){
        die();
    }
    
    $login = new Login();
    if(!$login->verificaLogin()){
        $login->set_user($_POST['inputUsuario']);
        $login->set_pass($_POST['inputSenha']);
        $login->logar();
    } else {
        echo '{"status":"ok"}';
    }
?>