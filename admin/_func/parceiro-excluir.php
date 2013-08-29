<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
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
    
    if(isset($_POST['id']) && is_numeric($_POST['id'])){
        $parceiro = new Parceiro();
        $parceiro->set_id($_POST['id']);
        if($parceiro->excluir()){
            echo '{"status":"ok"}';
        } else {
            echo '{"status":"erro1"}';
        }
    } else {
        echo '{"status":"erro2"}';
    }
?>