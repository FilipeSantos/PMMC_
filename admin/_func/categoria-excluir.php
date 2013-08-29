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
    
    $conn = new DbConnect();

    $categ = (isset($_POST['categ']) && is_numeric($_POST['categ'])) ? $_POST['categ'] : FALSE;
    if($rs = mysql_query("delete from noticia_categoria where idCategoria = $categ;")){
        if($rs = mysql_query("delete from categoria where id = $categ;")){
            echo '{"status":"ok"}';
        } else {
            echo '{"status":"erro"}';
        }
    } else {
        echo '{"status":"erro"}';
    }
    
    $conn->close();
?>