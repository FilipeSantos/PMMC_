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
    
    if(isset($_POST['acao']) && isset($_POST['id']) && is_numeric($_POST['id'])){
        $id = (integer) $_POST['id'];
        switch($_POST['acao']){
            case 'aprovar':
                $status = 1;
                break;
            case 'reprovar':
                $status = 0;
        }
        
        if(isset($id) && isset($status)){
            $galeria = new Galeria();
            $galeria->set_id($id);
            if($galeria->set_status($status)){
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"erro1"}';
            }
        } else {
            echo '{"status":"erro2"}';
        }
    } else {
        echo '{"status":"erro3"}';
    }
?>