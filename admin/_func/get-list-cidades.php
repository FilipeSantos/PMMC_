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
    
    $divisao = isset($_POST['divisao']) && is_numeric($_POST['divisao']) ? $_POST['divisao'] : FALSE;
    if($divisao !== FALSE){
        $divisao = (integer) $divisao;
        if($divisao === 1 || $divisao === 2){
            $cidades = Cidade::get_list_cidades(FALSE, $divisao);
            if($cidades === FALSE){
                exit('{"status":"error"}');
            }
            $arrTemp = $cidades;
            $cidades = array();
            $cidades['status'] = 'ok';
            $cidades['cidades'] = $arrTemp;
            exit(json_encode($cidades));
        } else {
            exit('{"status":"error"}');
        }
    } else {
        exit('{"status":"error"}');
    }
?>