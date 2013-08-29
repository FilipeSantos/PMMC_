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
    $noticia = isset($_POST['linkNoticia']) ? $_POST['linkNoticia'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $idRelease = isset($_POST['id']) && is_numeric($_POST['id']) ? $_POST['id'] : FALSE;
    $upload = isset($_POST['upload']) && ($_POST['upload'] === 'true') ? TRUE : FALSE;
    $token = isset($_POST['newRelease']) && !empty($_POST['newRelease']) ? $_POST['newRelease'] : FALSE;
    
    $release = new Release();
    if($idRelease){
        $release->id = $idRelease;
    }
    $release->set_release($titulo, $arquivo, $data, $noticia, $status, $upload);
    $release->salvar();
    if($token){
        $_SESSION['idRelease'] = $token;
    }
?>