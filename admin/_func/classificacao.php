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
    
    $cidade = isset($_POST['cidade']) && is_numeric($_POST['cidade']) ? (integer) $_POST['cidade'] : FALSE;
    $pontos = isset($_POST['pontos']) && is_array($_POST['pontos']) ? $_POST['pontos'] : FALSE;
    
    $valida = FALSE;
    $classificacao = new Classificacao();
    $valida = $classificacao->set_save_classificacao($cidade, $pontos);
    $valida = $classificacao->salvar();
    if($valida === TRUE){
        exit('{"status":"ok"}');
    } else {
        exit('{"status":"error"}');
    }
    
?>