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
    $medalhasOuro = isset($_POST['ouro']) && is_array($_POST['ouro']) ? $_POST['ouro'] : FALSE;
    $medalhasPrata = isset($_POST['prata']) && is_array($_POST['prata']) ? $_POST['prata'] : FALSE;
    $medalhasBronze = isset($_POST['bronze']) && is_array($_POST['bronze']) ? $_POST['bronze'] : FALSE;
    
    $valida = FALSE;
    $medalha = new Medalha();
    $valida = $medalha->set_save_medalhas($cidade, $medalhasOuro, $medalhasPrata, $medalhasBronze);
    $valida = $medalha->salvar();
    if($valida === TRUE){
        exit('{"status":"ok"}');
    } else {
        exit('{"status":"error"}');
    }
    
?>