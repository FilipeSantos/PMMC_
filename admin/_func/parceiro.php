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
    
    $id = isset($_POST['idParceiro']) && is_numeric($_POST['idParceiro']) ? (integer) $_POST['idParceiro'] : FALSE;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $desconto = isset($_POST['desconto']) ? $_POST['desconto'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $site = isset($_POST['site']) ? $_POST['site'] : '';
    $status = isset($_POST['status']) && is_numeric($_POST['status']) ? (integer) $_POST['status'] : 0;
    $tipos = isset($_POST['servico']) ? $_POST['servico'] : '';
    
    $parceiro = new Parceiro();
    if($id){
        $parceiro->set_id($id);
    }
    $parceiro->set_parceiro($nome, $descricao, $desconto, $endereco, $telefone, $email, $site, $status, $tipos);
    $parceiro->salvar();
?>