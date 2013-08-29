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
    
    if($_SESSION['capability'] <= 2){
        $id = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : FALSE;
        if($id){
            $noticia = new Noticia();
            $noticia->id = $id;
            if($noticia->excluir()){
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"error"}';
            }
        }
    }
?>