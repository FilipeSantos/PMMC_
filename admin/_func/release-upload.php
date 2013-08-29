<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    /*$login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    }
    $login->atualizaSession();
    */

    $upload = new Upload();
    $upload->set_valid_mimeType('ALL');
    $upload->set_tamanho(10240);
    $upload->set_dir_temp('/upload/temp');
    $upload->set_dir_upload('/upload/boletins');
    if($upload->set_arquivo($_FILES['Filedata'])){
        echo $upload->salvar('temp');
    } else {
        echo 'error';
    }
?>