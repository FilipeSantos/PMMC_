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
    
    $modalidade = isset($_POST['modalidade']) && is_numeric($_POST['modalidade']) ? (integer) $_POST['modalidade'] : FALSE;
    if($modalidade !== FALSE){
        $arr = array();
        $modObj = new Modalidade();
        $divisao = $modObj->get_divisoes_modalidade($modalidade);
        $local = $modObj->get_locais_modalidade($modalidade);
        $sexo = $modObj->get_sexo_modalidade($modalidade);
        $categoria = $modObj->get_categoria_modalidade($modalidade);
        $prova = $modObj->get_prova_modalidade($modalidade);
        $cidade = $modObj->get_cidade_modalidade($modalidade);

        if($divisao === FALSE || $sexo === FALSE || $categoria === FALSE){
            exit('{"status":"error"}');
        }
        
        $arr['status'] = 'ok';
        $arr['divisao'] = $divisao;
        $arr['local'] = $local;
        $arr['sexo'] = $sexo;
        $arr['categoria'] = $categoria;
        if($prova !== FALSE && is_array($prova)){
            $arr['prova'] = $prova;
        }
        $arr['cidade'] = $cidade;
        exit(json_encode($arr));
    }
?>