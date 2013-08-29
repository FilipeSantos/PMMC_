<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');

    $upload = new Upload();
    $upload->set_valid_mimeType('ALL');
    $upload->set_tamanho(5120);
    $upload->set_dir_temp('/upload/temp');
    $upload->set_dir_upload('/upload/programacao_resultado');
    if($upload->set_arquivo($_FILES['Filedata'])){
        echo $upload->salvar('temp');
    } else {
        echo 'error';
    }
?>