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
    
    $cpf = isset($_POST['cpf']) && !empty($_POST['cpf']) ? addslashes($_POST['cpf']) : FALSE;
    if($cpf){
        $validar = new Valida();
        $validar->campo_unico($cpf, 'usuario', 'cpf');
        if($validar->totalErros){
            echo '{"status":"cadastrado"}';
            exit();
        }
    }
    echo '{"status":"ok"}';
?>