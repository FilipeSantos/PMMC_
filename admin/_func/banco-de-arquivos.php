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

    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : FALSE;
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $arquivo = isset($_POST['arquivo']) ? $_POST['arquivo'] : '';
    $thumb = isset($_POST['imgUploadThumb']) ? $_POST['imgUploadThumb'] : '';
    $formato = isset($_POST['formato']) ? $_POST['formato'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $upload = isset($_POST['upload']) && ($_POST['upload'] === 'true') ? TRUE : FALSE;

    $ba = new BancoArquivo();
    if($id){
        $ba->set_id($id);
    }
    $ba->set_bancoArquivo($titulo, $formato, $thumb, $arquivo, $status, $upload);
    $ba->salvar();
?>