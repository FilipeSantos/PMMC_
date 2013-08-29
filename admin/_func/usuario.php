<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:login.php');
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
    $nome = isset($_POST['nome']) && !empty($_POST['nome']) ? $_POST['nome'] : FALSE;
    $senha = isset($_POST['senha']) && !empty($_POST['senha']) ? $_POST['senha'] : FALSE;
    $novaSenha = isset($_POST['senhaNova']) && !empty($_POST['senhaNova']) ? $_POST['senhaNova'] : FALSE;
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : FALSE;
    $idPerfil = isset($_POST['capability']) && !empty($_POST['capability']) ? $_POST['capability'] : FALSE;
    $cpf = isset($_POST['cpf']) && !empty($_POST['cpf']) ? $_POST['cpf'] : FALSE;
    $telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? $_POST['telefone'] : FALSE;
    $celular = isset($_POST['celular']) && !empty($_POST['celular']) ? $_POST['celular'] : FALSE;
    $empresa = isset($_POST['empresa']) && !empty($_POST['empresa']) ? $_POST['empresa'] : FALSE;
    $status = isset($_POST['status']) && is_numeric($_POST['status']) ? $_POST['status'] : 0;
    
    $userLog = new Usuario();
    if($id && ($_SESSION['capability'] === '1')){
        $userLog->set_user_id($id);
    } elseif($_SESSION['capability'] > 1){
        $userLog->set_user_id($_SESSION['idUser']);
    }
    
    $userLog->valida_usuario($nome, $senha, $novaSenha, $email, $idPerfil, $cpf, $telefone, $celular, $empresa, $status);
    $userLog->salvar();
?>