<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
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
    $tipo = isset($_POST['tipo']) && is_numeric($_POST['tipo']) ? $_POST['tipo'] : FALSE;
    $categorias = isset($_POST['categoria']) ? $_POST['categoria'] : FALSE;
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    
    if($tipo == '1'){
        $titulo = isset($_POST['tituloFoto']) ? $_POST['tituloFoto'] : FALSE;
        $descricao = isset($_POST['descricaoFoto']) ? $_POST['descricaoFoto'] : '';
        $data = isset($_POST['dataFoto']) ? $_POST['dataFoto'] : FALSE;
        $status = isset($_POST['statusFoto']) ? $_POST['statusFoto'] : 0;
        $midia = isset($_POST['imgUploadThumb']) ? $_POST['imgUploadThumb'] : FALSE;
        $thumb = isset($_POST['imgUploadThumb']) ? $_POST['imgUploadThumb'] : FALSE;
    } elseif($tipo == '2'){
        $titulo = isset($_POST['tituloVideo']) ? $_POST['tituloVideo'] : FALSE;
        $descricao = isset($_POST['descricaoVideo']) ? $_POST['descricaoVideo'] : '';
        $data = isset($_POST['dataVideo']) ? $_POST['dataVideo'] : FALSE;
        $status = isset($_POST['statusVideo']) ? $_POST['statusVideo'] : 0;
        $midia = isset($_POST['idVideo']) ? $_POST['idVideo'] : FALSE;
        $thumb = isset($_POST['inputThumbVideo']) ? $_POST['inputThumbVideo'] : FALSE;
    }
    
    $galeria = new Galeria();
    if($id){
        $galeria->set_id($id);
    }
    
    $galeria->set_galeria($tipo, $titulo, $data, $descricao, $midia, $thumb, $status, $categorias, $tags);
    $galeria->salvar();
?>