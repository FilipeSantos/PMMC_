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
        $progObj->set_data(isset($_POST['dataPub']) ? $_POST['dataPub'] : FALSE);
        $progObj->set_descricao(isset($_POST['descricao']) ? $_POST['descricao'] : FALSE);
        $progObj->set_modalidade(isset($_POST['modalidade']) ? $_POST['modalidade'] : FALSE);
        $progObj->set_divisao(isset($_POST['divisao']) ? $_POST['divisao'] : FALSE);
        $progObj->set_local(isset($_POST['local']) ? $_POST['local'] : FALSE);
        $progObj->set_sexo(isset($_POST['sexo']) ? $_POST['sexo'] : FALSE);
        $progObj->set_categoria(isset($_POST['categoria']) ? $_POST['categoria'] : FALSE);
        $progObj->set_prova(isset($_POST['prova']) ? $_POST['prova'] : FALSE);
        $progObj->set_cidade(isset($_POST['cidade']) ? $_POST['cidade'] : FALSE);
        $progObj->set_status(isset($_POST['status']) ? $_POST['status'] : FALSE);

        if($progObj->salvar_programacao()){
            exit('{"status":"ok"}');
        } else {
            exit('{"status":"error"}');
        }
        
    }
?>