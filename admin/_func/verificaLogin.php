<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       echo 'FALSE';
       exit();
    }
    echo 'TRUE';
    $login->atualizaSession();
?>