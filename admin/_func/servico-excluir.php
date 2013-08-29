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

    $id = (isset($_POST['categ']) && is_numeric($_POST['categ'])) ? (integer) $_POST['categ'] : FALSE;
    $excluir = Parceiro::delete_servico(array($id));
    if($excluir !== FALSE){
        echo '{"status":"ok"}';
    } else {
        echo '{"status":"error"}';
    }
?>