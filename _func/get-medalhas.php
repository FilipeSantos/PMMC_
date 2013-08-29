<?php
        $divisao = isset($_POST['divisao']) && is_numeric($_POST['divisao']) ? (integer) $_POST['divisao'] : FALSE;
        $limit = isset($_POST['limit']) && is_numeric($_POST['limit']) ? (integer) $_POST['limit'] : FALSE;

        if($divisao !== FALSE){
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php');
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Medalha.class.php');
                $cidadesDiv1 = Cidade::get_cidades_medalhas_divisao($divisao, $limit);
                
                $arr = array();
                $arr['status'] = 'ok';
                $arr['cidades'] = $cidadesDiv1;
                echo json_encode($arr);
                exit;
        }
        echo '{"status":"error"}';
        exit;
?>