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
    
    $titulo = isset($_POST['titulo']) && !empty($_POST['titulo']) ? $_POST['titulo'] : FALSE;
    $descricao = isset($_POST['descricao']) && !empty($_POST['descricao']) ? $_POST['descricao'] : FALSE;
    $texto = isset($_POST['texto']) && !empty($_POST['texto']) ? $_POST['texto'] : FALSE;
    $imgUploadThumb = isset($_POST['imgUploadThumb']) && !empty($_POST['imgUploadThumb']) ? $_POST['imgUploadThumb'] : FALSE;
    $imgUploadImagem1 = isset($_POST['imgUploadImagem1']) && !empty($_POST['imgUploadImagem1']) ? $_POST['imgUploadImagem1'] : FALSE;
    $imgUploadImagem2 = isset($_POST['imgUploadImagem2']) && !empty($_POST['imgUploadImagem2']) ? $_POST['imgUploadImagem2'] : FALSE;
    $dataPub = isset($_POST['dataPub']) && !empty($_POST['dataPub']) ? $_POST['dataPub'] : FALSE;
    $ativo = isset($_POST['ativo']) && is_numeric($_POST['ativo']) ? (integer) $_POST['ativo'] : 0;
    $destaque = isset($_POST['destaque']) && is_numeric($_POST['destaque']) ? $_POST['destaque'] : 0;
    $tags = isset($_POST['tags']) && !empty($_POST['tags']) ? $_POST['tags'] : FALSE;
    $categorias = isset($_POST['categoria']) && !empty($_POST['categoria']) ? $_POST['categoria'] : FALSE;
    $tipoNoticia = isset($_POST['tipoNoticia']) && !empty($_POST['tipoNoticia']) ? $_POST['tipoNoticia'] : FALSE;
    $exibirHome = isset($_POST['exibirHome']) && !empty($_POST['exibirHome']) ? (integer) $_POST['exibirHome'] : 0;
    $idNoticia = isset($_POST['idNoticia']) && is_numeric($_POST['idNoticia']) ? (integer) $_POST['idNoticia'] : FALSE;
    $aprovacao = isset($_POST['aprovacao']) && is_numeric($_POST['aprovacao']) ? (integer) $_POST['aprovacao'] : FALSE;
    
    if($_SESSION['capability'] <= 2){
        if($idNoticia !== FALSE){
            if($ativo === 1){
                $aprovacao = 'NULL';
            } elseif($aprovacao === 1){
                $aprovacao = 1;
            } else {
                $aprovacao = 'NULL';
            }
        } else {
            $aprovacao = 'NULL';
        }
    } else {
        $aprovacao = 1;
    }
    
    $noticia = new Noticia();
    if($idNoticia !== FALSE){
        $noticia->id = $idNoticia;
    }
    
    $noticia->set_noticia($titulo, $descricao, $texto, $imgUploadThumb, $imgUploadImagem1, $imgUploadImagem2, $dataPub, $ativo, $destaque, $tags, $categorias, $tipoNoticia, $exibirHome, $aprovacao);
    $noticia->salvar();
    
    $valida = new Valida();
?>