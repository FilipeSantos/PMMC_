<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       exit();
    }
    $login->atualizaSession();
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    if($referer === NULL){
        die();
    }
    if(strpos($referer, BASE_URL) !== 0){
        die();
    }
    
    $email = isset($_POST['email']) && !empty($_POST['email']) ? addslashes($_POST['email']) : FALSE;
    if($email){
        $validar = new Valida();
        $validar->campo_unico($email, 'usuario', 'email');
        if($validar->totalErros){
            echo '{"status":"cadastrado"}';
            exit();
        }
    }
    echo '{"status":"ok"}';
?>