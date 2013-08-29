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
        switch($_POST['acao']){
            case 'aprovar':
                $id = 1;
                break;
            case 'reprovar':
                $id = 0;
        }
        
        if(isset($id)){
            $conn = new DbConnect();
            if($rs = mysql_query("update `boletim` set `status` = $id where id = " . addslashes($_POST['id']) . ";")){
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"erro"}';
            }
        } else {
            echo '{"status":"erro"}';
        }
    } else {
        echo '{"status":"erro"}';
    }
    
    $conn->close();
?>