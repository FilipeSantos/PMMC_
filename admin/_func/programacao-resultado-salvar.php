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

    if(isset($_POST)){
        $progObj = new Programacao();
        $progObj->set_id(isset($_SESSION['idProgramacao']) ? $_SESSION['idProgramacao'] : FALSE);
        $valida = $progObj->set_resultado_programacao(isset($_POST) ? $_POST : FALSE, isset($_SESSION['tipoResultado']) ? $_SESSION['tipoResultado'] : FALSE);

        if($valida === TRUE){
            if($progObj->salvar_resultado_programacao()){
                exit('{"status":"ok"}');
            } else {
                exit('{"status":"error"}');
            }
        }
    }
?>