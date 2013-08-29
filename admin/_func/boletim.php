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
    
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $arquivo = isset($_POST['arquivo']) ? $_POST['arquivo'] : '';
    $data = isset($_POST['data']) ? $_POST['data'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $idBoletim = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : FALSE;
    $upload = isset($_POST['upload']) && ($_POST['upload'] === 'true') ? TRUE : FALSE;
    
    $boletim = new Boletim();
    if($idBoletim){
        $boletim->id = $idBoletim;
    }
    $boletim->set_boletim($titulo, $arquivo, $data, $status, $upload);
    $boletim->salvar();
?>