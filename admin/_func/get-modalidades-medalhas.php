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
    
    $cidade = isset($_POST['cidade']) && is_numeric($_POST['cidade']) ? (integer) $_POST['cidade'] : FALSE;
    if($cidade !== FALSE){
        $modalidades = new Modalidade();
        $modalidades = $modalidades->get_modalidades($cidade);
        if($modalidades === FALSE){
            exit('{"status":"error"}');
        }
        $medalha = new Medalha();
        foreach($modalidades as $i=>$modalidade){
            $item = $medalha->get_medalhas($cidade, $modalidade->id);
            if($item === FALSE){
                $modalidades[$i]->medalha_ouro = '0';
                $modalidades[$i]->medalha_prata = '0';
                $modalidades[$i]->medalha_bronze = '0';
                $modalidades[$i]->medalha_total = '0';
                continue;
            }
            $modalidades[$i]->medalha_ouro = (string) $item->medalha_ouro;
            $modalidades[$i]->medalha_prata = (string) $item->medalha_prata;
            $modalidades[$i]->medalha_bronze = (string) $item->medalha_bronze;
            $modalidades[$i]->medalha_total = (string) $item->medalha_total;
        }        
        $arrTemp = $modalidades;
        $modalidades = array();
        $modalidades['status'] = 'ok';
        $modalidades['modalidades'] = $arrTemp;        
        exit(json_encode($modalidades));
    } else {
        exit('{"status":"error"}');
    }
?>