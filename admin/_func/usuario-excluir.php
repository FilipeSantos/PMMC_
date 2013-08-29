<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    } else {
       if($_SESSION['capability'] !== '1'){
          header('Location:/admin/index.php?erro=perfil');
          exit();
       }
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
        $conn = new DbConnect();
        $userId = addslashes($_POST['id']);
        $criId = $_SESSION['idUser'];
        mysql_query("update `arquivo` set idCriador = $criId where idCriador = $userId;");
        mysql_query("update `release` set idCriador = $criId where idCriador = $userId;");
        mysql_query("update `usuario` set idCriador = $criId where idCriador = $userId;");
        if($rs = mysql_query("delete from usuario where id = $userId limit 1;")){
            echo '{"status":"ok"}';
        } else {
            echo '{"status":"erro"}';
        }
    } else {
        echo '{"status":"erro"}';
    }
    
    $conn->close();
?>